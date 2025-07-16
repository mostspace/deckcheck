<?php

namespace App\Http\Controllers;


use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Database\Eloquent\Builder;
use App\Models\WorkOrder;
use App\Models\Equipment;
use App\Models\EquipmentInterval;
use App\Models\Interval;


class ScheduleController extends Controller
{
    // Work Order Schedule Index
    public function index(Request $request)
    {
        // Active Vessel Auth
        $vessel = currentVessel();
        if (! $vessel) {
            abort(404, 'No active vessel selected.');
        }

        // Loop Work Orders, Pass Used Frequencies into Array for Dynamic Display
        $rawFrequencies = WorkOrder::with('equipmentInterval')
            ->whereHas('equipmentInterval.equipment', fn($q) => $q->where('vessel_id', $vessel->id))
            ->get()
            ->map(fn($wo) => strtolower($wo->equipmentInterval?->frequency))
            ->filter()
            ->unique()
            ->values()
            ->toArray();

        // Set Frequency Tab Display Order
        $order = [
            'daily' => 1, 'weekly' => 2, 'bi-weekly' => 3, 'monthly' => 4,
            'quarterly' => 5, 'bi-annually' => 6, 'annual' => 7,
            '2-yearly' => 8, '3-yearly' => 9, '5-yearly' => 10,
            '6-yearly' => 11, '10-yearly' => 12, '12-yearly' => 13,
        ];

        // Update Array with Display Order
        $visibleFrequencies = collect($rawFrequencies)
            ->filter(fn($f) => isset($order[$f]))
            ->sortBy(fn($f) => $order[$f])
            ->values()
            ->toArray();

        // Set Date Anchors for Each Respective Frequency
        $frequency = $request->input('frequency', $visibleFrequencies[0] ?? 'daily');
        $date = Carbon::parse($request->input('date', now()));

        // Calculate Interval Date Range for Each Respective Frequency
        switch ($frequency) {
            case 'daily':
                $start = $date->copy()->startOfDay();
                $end = $date->copy()->endOfDay();
                break;
            case 'weekly':
            case 'bi-weekly':
                $start = $date->copy()->startOfWeek();
                $end = $frequency === 'bi-weekly'
                    ? $start->copy()->addWeeks(2)->subSecond()
                    : $start->copy()->endOfWeek();
                break;
            case 'monthly':
                $start = $date->copy()->startOfMonth();
                $end = $date->copy()->endOfMonth();
                break;
            case 'quarterly':
                $start = $date->copy()->startOfQuarter();
                $end = $date->copy()->endOfQuarter();
                break;
            case 'bi-annually':
                $month = $date->month <= 6 ? 1 : 7;
                $start = Carbon::create($date->year, $month, 1)->startOfDay();
                $end = $start->copy()->addMonths(6)->subSecond();
                break;
            case in_array($frequency, ['annual','2-yearly','3-yearly','5-yearly','6-yearly','10-yearly','12-yearly']):
                $start = $date->copy()->startOfYear();
                $end = $date->copy()->endOfYear();
                break;
            default:
                $start = $date->copy()->startOfDay();
                $end = $date->copy()->endOfDay();
                break;
        }

        // Query Work Orders
        // By Due_Date for Open, Scheduled, In-Progress, Flagged, Overdue
        // By Completed_at for Completed, Deferred
        $query = WorkOrder::with(['equipmentInterval.equipment.location.deck', 'assignee'])
            ->whereHas('equipmentInterval', fn($q) => $q->where('frequency', $frequency))
            ->whereHas('equipmentInterval.equipment', fn($q) => $q->where('vessel_id', $vessel->id))
            ->where(function (Builder $q) use ($start, $end) {
                $q->where(function (Builder $sub) use ($start, $end) {
                    $sub->whereNotIn('status', ['completed', 'deferred'])
                        ->where(function (Builder $inner) use ($start, $end) {
                            $inner->whereBetween('due_date', [$start, $end])
                                ->orWhereNull('due_date'); // <-- this line is new
                        });
                })
                ->orWhere(function (Builder $sub) use ($start, $end) {
                    $sub->whereIn('status', ['completed', 'deferred'])
                        ->whereBetween('completed_at', [$start, $end]);
                });
            });

        // If Toggled: Filter by Assignee = Current User
        if ($request->boolean('assigned')) {
            $query->where('assigned_to', auth()->id());
        }

        // Default Grouping by Date
        $group = $request->input('group', 'date');
        
        // Full Query
        $allWorkOrders = $query->get();

        // Separate into Arrays for Active & Resolved
        $activeWorkOrders = $allWorkOrders->filter(fn($wo) => !in_array($wo->status, ['completed', 'deferred']));
        $resolvedWorkOrders = $allWorkOrders->filter(fn($wo) => in_array($wo->status, ['completed', 'deferred']));

        // Group by Category or Location when Toggled
        switch ($group) {
            case 'category':
                $activeWorkOrders = $activeWorkOrders->sortBy([
                    fn($a, $b) => strcmp($a->equipmentInterval->equipment->category->name ?? '', $b->equipmentInterval->equipment->category->name ?? ''),
                    fn($a, $b) => $a->due_date <=> $b->due_date,
                ])->groupBy(fn($wo) => $wo->equipmentInterval->equipment->category->name ?? 'Uncategorized');

                $resolvedWorkOrders = $resolvedWorkOrders->sortBy([
                    fn($a, $b) => strcmp($a->equipmentInterval->equipment->category->name ?? '', $b->equipmentInterval->equipment->category->name ?? ''),
                    fn($a, $b) => $a->completed_at <=> $b->completed_at,
                ])->groupBy(fn($wo) => $wo->equipmentInterval->equipment->category->name ?? 'Uncategorized');

                break;

            case 'location':
                $activeWorkOrders = $activeWorkOrders->sortBy([
                    fn($a, $b) => strcmp($a->equipmentInterval->equipment->location->deck->name ?? '', $b->equipmentInterval->equipment->location->deck->name ?? ''),
                    fn($a, $b) => strcmp($a->equipmentInterval->equipment->location->name ?? '', $b->equipmentInterval->equipment->location->name ?? ''),
                    fn($a, $b) => $a->due_date <=> $b->due_date,
                ])->groupBy(fn($wo) => $wo->equipmentInterval->equipment->location->deck->name ?? 'Unknown Deck');

                $resolvedWorkOrders = $resolvedWorkOrders->sortBy([
                    fn($a, $b) => strcmp($a->equipmentInterval->equipment->location->deck->name ?? '', $b->equipmentInterval->equipment->location->deck->name ?? ''),
                    fn($a, $b) => strcmp($a->equipmentInterval->equipment->location->name ?? '', $b->equipmentInterval->equipment->location->name ?? ''),
                    fn($a, $b) => $a->completed_at <=> $b->completed_at,
                ])->groupBy(fn($wo) => $wo->equipmentInterval->equipment->location->deck->name ?? 'Unknown Deck');

                break;

            case 'date':
            default:
                $activeWorkOrders = $activeWorkOrders->sortBy(fn($wo) => $wo->due_date);
                $resolvedWorkOrders = $resolvedWorkOrders->sortBy(fn($wo) => $wo->completed_at);
                break;
        }

        // Pass Vessel Crew into View for Assignee Drop-Down
        $availableUsers = $vessel->users()->orderBy('first_name')->get();

        return view('maintenance.schedule.index', compact(
            'activeWorkOrders', 
            'resolvedWorkOrders', 
            'frequency', 
            'date', 
            'visibleFrequencies', 
            'availableUsers', 
            'group'
        ));
    }

    /*
    public function flow(Request $request, WorkOrder $workOrder)
    {
        $vessel = currentVessel();

        if (! $vessel || $workOrder->equipment?->vessel_id !== $vessel->id) {
            abort(404, 'Work order not found for current vessel.');
        }

        $frequency = $request->input('frequency', 'daily');
        $date = Carbon::parse($request->input('date', now()));

        [$start, $end] = $this->getDateRange($frequency, $date);

        $workOrders = WorkOrder::whereHas('equipment', function ($query) use ($vessel) {
                $query->where('vessel_id', $vessel->id);
            })
            ->whereBetween('due_date', [$start, $end])
            ->orderBy('due_date')
            ->pluck('id')
            ->toArray();

        $currentIndex = array_search($workOrder->id, $workOrders);
        $prevId = $workOrders[$currentIndex - 1] ?? null;
        $nextId = $workOrders[$currentIndex + 1] ?? null;

        $workOrder->load(['equipment', 'equipmentInterval', 'equipmentInterval.interval', 'tasks']);

        return view('maintenance.schedule.flow', [
            'workOrder' => $workOrder,
            'frequency' => $frequency,
            'date' => $date,
            'prevId' => $prevId,
            'nextId' => $nextId,
        ]);
    }

    private function getDateRange(string $frequency, Carbon $date): array
    {
        $intervalMap = [
            'daily'        => '1 day',
            'weekly'       => '1 week',
            'bi-weekly'    => '2 weeks',
            'monthly'      => '1 month',
            'quarterly'    => '3 months',
            'bi-annually'  => '6 months',
            'annual'       => '1 year',
            '2-yearly'     => '2 years',
            '3-yearly'     => '3 years',
            '5-yearly'     => '5 years',
            '6-yearly'     => '6 years',
            '10-yearly'    => '10 years',
            '12-yearly'    => '12 years',
        ];

        $duration = $intervalMap[$frequency] ?? '1 day';

        try {
            $start = $date->copy()->startOfDay();
            $end = $date->copy()->add($duration)->subSecond();
        } catch (\Exception $e) {
            // Fallback to 1-day slice if something went wrong
            $start = $date->copy()->startOfDay();
            $end = $date->copy()->endOfDay();
        }

        return [$start, $end];
    }
        */

}
