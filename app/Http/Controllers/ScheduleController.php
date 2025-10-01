<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\Interval;
use App\Models\WorkOrder;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

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

        // Ensure user has access to this vessel
        if (! auth()->user()->hasSystemAccessToVessel($vessel)) {
            abort(403, 'Access denied to this vessel');
        }

        // Loop Work Orders, Pass Used Frequencies into Array for Dynamic Display
        $rawFrequencies = WorkOrder::with('equipmentInterval')
            ->whereHas('equipmentInterval.equipment', fn ($q) => $q->where('vessel_id', $vessel->id))
            ->get()
            ->map(fn ($wo) => mb_strtolower($wo->equipmentInterval?->frequency))
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
            ->filter(fn ($f) => isset($order[$f]))
            ->sortBy(fn ($f) => $order[$f])
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
            case in_array($frequency, ['annual', '2-yearly', '3-yearly', '5-yearly', '6-yearly', '10-yearly', '12-yearly']):
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
            ->whereHas('equipmentInterval', fn ($q) => $q->where('frequency', $frequency))
            ->whereHas('equipmentInterval.equipment', fn ($q) => $q->where('vessel_id', $vessel->id))
            ->where(function (Builder $q) use ($start, $end) {
                $q->where(function (Builder $sub) use ($start, $end) {
                    $sub->whereNotIn('status', ['completed', 'deferred'])
                        ->where(function (Builder $inner) use ($start, $end) {
                            $inner->whereBetween('due_date', [$start, $end])
                                ->orWhereNull('due_date');
                        });
                })
                    ->orWhere(function (Builder $sub) use ($start, $end) {
                        $sub->whereIn('status', ['completed', 'deferred'])
                            ->whereBetween('due_date', [$start, $end]);
                    });
            });

        // If Toggled: Filter by Assignee = Current User
        if ($request->boolean('assigned')) {
            $query->where('assigned_to', auth()->id());
        }

        // Full Query
        $allWorkOrders = $query->get();

        // Separate into Arrays for Active & Resolved
        $activeWorkOrders = $allWorkOrders->filter(fn ($wo) => ! in_array($wo->status, ['completed', 'deferred']));
        $resolvedWorkOrders = $allWorkOrders->filter(fn ($wo) => in_array($wo->status, ['completed', 'deferred']));

        // Default Grouping by Date
        $group = $request->input('group', 'date');
        $groups = [];

        // Group by Category
        if ($group === 'category') {
            // 1) group & sort active by category
            $activeByCat = $activeWorkOrders
                ->sortBy([
                    fn ($a, $b) => strcmp($a->equipmentInterval->equipment->category->name ?? '', $b->equipmentInterval->equipment->category->name ?? ''),
                    fn ($a, $b) => $a->due_date <=> $b->due_date,
                ])
                ->groupBy(fn ($wo) => $wo->equipmentInterval->equipment->category->name ?? 'Uncategorized');

            // 2) group & sort resolved by category
            $resolvedByCat = $resolvedWorkOrders
                ->sortBy([
                    fn ($a, $b) => strcmp($a->equipmentInterval->equipment->category->name ?? '', $b->equipmentInterval->equipment->category->name ?? ''),
                    fn ($a, $b) => $a->completed_at <=> $b->completed_at,
                ])
                ->groupBy(fn ($wo) => $wo->equipmentInterval->equipment->category->name ?? 'Uncategorized');

            // 3) union all category keys (active ∪ resolved)
            $allCats = $activeByCat->keys()->merge($resolvedByCat->keys())->unique();

            // 4) build $groups[category] = ['active'=>..., 'resolved'=>...]
            foreach ($allCats as $cat) {
                $groups[$cat] = [
                    'active' => $activeByCat->get($cat, collect()),
                    'resolved' => $resolvedByCat->get($cat, collect()),
                ];
            }
        }

        // Group by Location
        elseif ($group === 'location') {
            // 1) sort & group active and resolved exactly as before
            $act = $activeWorkOrders
                ->sortBy([
                    fn ($a, $b) => strcmp($a->equipmentInterval->equipment->location->deck->name, $b->equipmentInterval->equipment->location->deck->name),
                    fn ($a, $b) => ($a->equipmentInterval->equipment->location->display_order ?? 0) <=> ($b->equipmentInterval->equipment->location->display_order ?? 0),
                    fn ($a, $b) => strcmp($a->equipmentInterval->equipment->location->name, $b->equipmentInterval->equipment->location->name),
                    fn ($a, $b) => $a->due_date <=> $b->due_date,
                ])
                ->groupBy(fn ($wo) => $wo->equipmentInterval->equipment->location->deck->name)
                ->map(fn ($deckGroup) => $deckGroup->groupBy(fn ($wo) => $wo->equipmentInterval->equipment->location->name)
                );

            $res = $resolvedWorkOrders
                ->sortBy([
                    fn ($a, $b) => strcmp($a->equipmentInterval->equipment->location->deck->name, $b->equipmentInterval->equipment->location->deck->name),
                    fn ($a, $b) => ($a->equipmentInterval->equipment->location->display_order ?? 0) <=> ($b->equipmentInterval->equipment->location->display_order ?? 0),
                    fn ($a, $b) => strcmp($a->equipmentInterval->equipment->location->name, $b->equipmentInterval->equipment->location->name),
                    fn ($a, $b) => $a->completed_at <=> $b->completed_at,
                ])
                ->groupBy(fn ($wo) => $wo->equipmentInterval->equipment->location->deck->name)
                ->map(fn ($deckGroup) => $deckGroup->groupBy(fn ($wo) => $wo->equipmentInterval->equipment->location->name)
                );

            // 2) union all deck keys
            $allDecks = $act->keys()->merge($res->keys())->unique();

            foreach ($allDecks as $deck) {
                // pull out per‐deck location groups (or empty collection)
                $activeLocs = $act->get($deck, collect());
                $resolvedLocs = $res->get($deck, collect());

                // union all location keys within this deck
                $allLocs = $activeLocs->keys()->merge($resolvedLocs->keys())->unique();

                // initialize the deck
                $groups[$deck] = [];

                foreach ($allLocs as $locationName) {
                    $groups[$deck][$locationName] = [
                        'active' => $activeLocs->get($locationName, collect()),
                        'resolved' => $resolvedLocs->get($locationName, collect()),
                    ];
                }
            }
        }

        // Pass Vessel Crew into View for Assignee Drop-Down
        $availableUsers = $vessel->users()->orderBy('first_name')->get();

        // Calculate formatted range for display
        $formattedRange = match ($frequency) {
            'daily' => $date->format('F j, Y'),
            'weekly', 'bi-weekly' => $date->copy()->startOfWeek()->format('M j').' – '.$date->copy()->endOfWeek()->format('M j, Y'),
            'monthly', 'quarterly', 'bi-annually' => $date->format('F Y'),
            default => $date->format('Y'),
        };

        return view('v2.pages.maintenance.schedule', compact(
            'frequency',
            'date',
            'visibleFrequencies',
            'group',
            'groups',
            'activeWorkOrders',
            'resolvedWorkOrders',
            'availableUsers',
            'formattedRange'
        ));
    }
}
