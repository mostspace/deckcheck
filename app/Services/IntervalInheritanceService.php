<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\Equipment;
use App\Models\EquipmentInterval;
use App\Models\Interval;
use App\Models\WorkOrder;

class IntervalInheritanceService
{
    // Generate All EquipmentIntervals on New Equipment Creation
    public function handle(Equipment $equipment): void
    {
        $intervals = Interval::with('tasks')
            ->where('category_id', $equipment->category_id)
            ->get();

        foreach ($intervals as $interval) {
            $this->createEquipmentInterval($equipment, $interval);
        }
    }

    public function handleNewInterval(Interval $interval): void
    {
        Equipment::where('category_id', $interval->category_id)
            ->cursor()
            ->each(function (Equipment $equipment) use ($interval) {
                $this->createEquipmentInterval($equipment, $interval);
            });
    }

    // Create Equipment Interval
    protected function createEquipmentInterval(Equipment $equipment, Interval $interval): EquipmentInterval
    {
        $ei = EquipmentInterval::create([
            'equipment_id' => $equipment->id,
            'interval_id' => $interval->id,
            'description' => $interval->description,
            'facilitator' => $interval->facilitator,
            'frequency' => $interval->interval,
            'frequency_interval' => $this->mapToFrequencyString($interval->interval),
            'is_active' => true,
            'first_completed_at' => null,
            'last_completed_at' => null,
            'next_due_date' => null,
        ]);

        // your existing initial work order logic
        app(WorkOrderGenerationService::class)
            ->createInitialWorkOrder($ei);

        return $ei;
    }

    // Parse Frequency for Carbon
    protected function mapToFrequencyString(string $interval): string
    {
        return match (mb_strtolower($interval)) {
            'daily' => '1 day',
            'weekly' => '1 week',
            'bi-weekly' => '2 weeks',
            'monthly' => '1 month',
            'quarterly' => '3 months',
            'bi-annually' => '6 months',
            'annual' => '1 year',
            '2-yearly' => '2 years',
            '3-yearly' => '3 years',
            '5-yearly' => '5 years',
            '6-yearly' => '6 years',
            '10-yearly' => '10 years',
            '12-yearly' => '12 years',
            default => throw new \InvalidArgumentException("Unrecognized interval: {$interval}"),
        };
    }

    // Handle Interval Deletion
    public function deactivateInterval(Interval $interval): int
    {
        $eqIntervals = EquipmentInterval::where('interval_id', $interval->id)->get();
        $count = $eqIntervals->count();
        $ids = $eqIntervals->pluck('id')->all();

        // Deactivate them
        EquipmentInterval::whereIn('id', $ids)
            ->update(['is_active' => false]);

        // Delete all non-completed, non-deferred work orders
        WorkOrder::whereIn('equipment_interval_id', $ids)
            ->whereNotIn('status', ['completed', 'deferred'])
            ->delete();

        return $count;
    }
}
