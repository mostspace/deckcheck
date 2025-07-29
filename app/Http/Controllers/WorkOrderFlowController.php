<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\WorkOrder;
use App\Models\Vessel;
use Illuminate\Support\Collection;
use Illuminate\Support\Carbon;

class WorkOrderFlowController extends Controller
{

    /**
     * Start the work order completion flow.
     * Accepts a list of work order IDs (ordered) and displays the first one.
     */
    public function start(Request $request)
    {
        $workOrderIds = $request->input('ids', []);
        $currentId = $request->input('current'); // Optional: allow starting somewhere other than first

        if (empty($workOrderIds)) {
            return abort(400, 'No work orders selected.');
        }

        $vessel = currentVessel();
        $workOrders = WorkOrder::whereIn('id', $workOrderIds)
            ->with([
                'equipmentInterval.equipment.location.deck',
                'completedBy',
                'tasks.completedBy',
            ])
            ->get()
            ->filter(fn($wo) => $wo->equipmentInterval->equipment->vessel_id === $vessel->id)
            ->sortBy(fn($wo) => array_search($wo->id, $workOrderIds))
            ->values();

        $currentWorkOrder = $workOrders->firstWhere('id', $currentId) ?? $workOrders->first();
        $currentIndex = $workOrders->search(fn($wo) => $wo->id === $currentWorkOrder->id);

        $dateRangeLabel = $request->input('dateRangeLabel');
        $groupName = $request->input('groupName');

        return view('maintenance.schedule.flow.modal', [
            'workOrders' => $workOrders,
            'currentWorkOrder' => $currentWorkOrder,
            'currentIndex' => $currentIndex,
            'groupName' => $groupName,
            'frequency' => ucfirst($request->input('frequency', 'Scheduled')),
            'dateRangeLabel' => $dateRangeLabel,
        ]);
    }

    /**
     * Return a specific work order in the flow by ID (used for Next/Prev AJAX).
     */
    public function load(Request $request, WorkOrder $workOrder)
    {
        $this->authorizeFlow($workOrder);

        $workOrder->load([
            'equipmentInterval.equipment.location.deck',
            'completedBy',
            'tasks.completedBy',
        ]);

        $availableUsers = $workOrder->equipmentInterval->equipment->vessel
            ->users()
            ->orderBy('first_name')
            ->get();

        return view('maintenance.schedule.flow.partials.work-order', compact('workOrder', 'availableUsers'));
    }

    /**
     * Authorize that this work order belongs to the current vessel.
     */
    private function authorizeFlow(WorkOrder $workOrder)
    {
        if ($workOrder->equipmentInterval->equipment->vessel_id !== currentVessel()?->id) {
            abort(403);
        }
    }



}