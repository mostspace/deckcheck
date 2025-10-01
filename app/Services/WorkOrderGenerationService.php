<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\EquipmentInterval;
use App\Models\Task;
use App\Models\WorkOrder;
use App\Models\WorkOrderTask;
use Carbon\CarbonInterval;
use Illuminate\Support\Carbon;

class WorkOrderGenerationService
{
    // Create the initial (first) work order when an equipment interval is created.
    public function createInitialWorkOrder(EquipmentInterval $interval): WorkOrder
    {
        $workOrder = WorkOrder::create([
            'equipment_interval_id' => $interval->id,
            'due_date' => null,
            'status' => 'Open',
            'notes' => null,
        ]);

        $this->createWorkOrderTasks($workOrder);

        return $workOrder;
    }

    // Handle the first completion event and schedule future work orders.
    public function handleFirstCompletion(EquipmentInterval $interval): void
    {
        $now = Carbon::now();

        // Set timestamps on interval
        $interval->first_completed_at = $now;
        $interval->last_completed_at = $now;
        $interval->next_due_date = $now->copy()->add($interval->frequency_interval);
        $interval->save();

        // Generate 3 future work orders
        $this->generateFutureWorkOrders($interval, $interval->first_completed_at, 2);
    }

    /**
     * Generate scheduled work orders starting from a base date.
     */
    protected function generateFutureWorkOrders(EquipmentInterval $interval, Carbon $startDate, int $count = 2): void
    {
        $nextDueDate = null;

        for ($i = 1; $i <= $count; $i++) {
            $step = CarbonInterval::make($interval->frequency_interval)->multiply($i);
            $dueDate = $startDate->copy()->add($step);

            $workOrder = WorkOrder::create([
                'equipment_interval_id' => $interval->id,
                'due_date' => $dueDate,
                'status' => $i === 1 ? 'open' : 'scheduled',
                'notes' => null,
            ]);

            $this->createWorkOrderTasks($workOrder);

            if ($i === 1) {
                $nextDueDate = $dueDate;
            }
        }

        $interval->next_due_date = $nextDueDate;
        $interval->save();
    }

    // Create work order tasks from interval template tasks.
    protected function createWorkOrderTasks(WorkOrder $workOrder): void
    {
        $equipment = $workOrder->equipmentInterval?->equipment;
        $interval = $workOrder->equipmentInterval;
        $template = $interval?->interval;

        if (! $template || ! $equipment) {
            return;
        }

        $tasks = $template->tasks;

        foreach ($tasks as $task) {
            switch ($task->applicable_to) {
                case 'All Equipment':
                    // Always applies — no filtering needed
                    break;

                case 'Specific Equipment':
                    $equipmentIds = $task->equipment()->pluck('equipment.id')->toArray();

                    if (! in_array($equipment->id, $equipmentIds)) {
                        continue 2; // skip to next task
                    }
                    break;

                case 'Conditional':
                    $conditions = json_decode($task->applicability_conditions ?? '[]', true);

                    $match = true;
                    foreach ($conditions as $condition) {
                        $key = $condition['key'] ?? null;
                        $value = $condition['value'] ?? null;

                        if (! $key || $value === null) {
                            $match = false;
                            break;
                        }

                        // Static keys
                        if (in_array($key, ['manufacturer', 'model', 'deck_id', 'location_id'])) {
                            if ((string) $equipment->$key !== (string) $value) {
                                $match = false;
                                break;
                            }
                        }
                        // Dynamic (user-defined) keys
                        elseif (array_key_exists($key, $equipment->attributes_json ?? [])) {
                            if ((string) $equipment->attributes_json[$key] !== (string) $value) {
                                $match = false;
                                break;
                            }
                        } else {
                            // Invalid key — does not exist
                            $match = false;
                            break;
                        }
                    }

                    if (! $match) {
                        continue 2; // skip this task
                    }

                    break;
            }

            WorkOrderTask::create([
                'work_order_id' => $workOrder->id,
                'name' => $task->description,
                'instructions' => $task->instructions,
                'sequence_position' => $task->display_order ?? 0,
            ]);
        }
    }

    public function propagateNewTask(Task $task): void
    {
        // 1. grab all EquipmentInterval IDs for this template
        $intervalId = $task->interval_id;
        $eiIds = EquipmentInterval::where('interval_id', $intervalId)
            ->pluck('id');

        // 2. fetch all "pending" WorkOrders
        $workOrders = WorkOrder::whereIn('equipment_interval_id', $eiIds)
            ->whereNotIn('status', ['completed', 'deferred'])
            ->get();

        // 3. for each one, override its template tasks to just [$task] then call createWorkOrderTasks()
        foreach ($workOrders as $wo) {
            $template = $wo->equipmentInterval->interval;
            // temporarily replace the loaded tasks collection
            $template->setRelation('tasks', collect([$task]));

            $this->createWorkOrderTasks($wo);

            // (no need to restore—this is per-instance)
        }

    }

    // Delete Work Order Tasks and Re-Populate to Propagate Template-Level Changes
    public function refreshTasksForInterval(int $intervalId): void
    {
        // 1) Grab all pending work orders for this interval template
        $pending = WorkOrder::whereHas('equipmentInterval', function ($q) use ($intervalId) {
            $q->where('interval_id', $intervalId);
        })
            ->whereNotIn('status', ['completed', 'deferred', 'in-progress'])
            ->get();

        if ($pending->isEmpty()) {
            return;
        }

        // 2) Delete all their tasks in one go
        $woIds = $pending->pluck('id')->all();
        WorkOrderTask::whereIn('work_order_id', $woIds)->delete();

        // 3) Re-populate each order from the template
        foreach ($pending as $wo) {
            $this->createWorkOrderTasks($wo);
        }
    }

    /* VERSION CONTROL

    // Create WorkOrderTask V2 [Backup]
    // Addition of Specific Equipment Filtering

    protected function createWorkOrderTasks(WorkOrder $workOrder): void
    {
        $equipment = $workOrder->equipmentInterval?->equipment;
        $interval = $workOrder->equipmentInterval;
        $template = $interval?->interval;

        if (! $template || ! $equipment) return;

        $tasks = $template->tasks;

        foreach ($tasks as $task) {
            switch ($task->applicable_to) {
                case 'All Equipment':
                    break;

                case 'Specific Equipment':
                    $equipmentIds = $task->equipment()->pluck('equipment.id')->toArray();

                    if (! in_array($equipment->id, $equipmentIds)) {
                        continue 2;
                    }
                    break;

                case 'Conditional':
                    // Placeholder for future condition-matching
                    continue 2;
            }

            WorkOrderTask::create([
                'work_order_id'      => $workOrder->id,
                'name'               => $task->description,
                'instructions'       => $task->instructions,
                'sequence_position'  => $task->display_order ?? 0,
            ]);
        }
    }



    // Create WorkOrderTask V1 [Backup]
    // MVP

    protected function createWorkOrderTasks(WorkOrder $workOrder): void
    {
        $interval = $workOrder->equipmentInterval;
        $template = $interval->interval;

        if (! $template) return;

        $tasks = $template->tasks;

        foreach ($tasks as $task) {
            WorkOrderTask::create([
                'work_order_id'      => $workOrder->id,
                'name'               => $task->description,
                'instructions'       => $task->instructions,
                'sequence_position'  => $task->display_order ?? 0,
            ]);
        }
    } */
}
