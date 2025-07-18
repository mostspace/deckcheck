<?php

namespace App\Http\Controllers;

use App\Models\WorkOrder;
use Illuminate\Http\Request;
use App\Models\Equipment;
use App\Models\Deficiency;
use App\Models\Vessel;
use App\Models\EquipmentInterval;


class WorkOrderController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        
    }


    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    // Single Work Order Detail (from EquipmentInterval View)
    public function show(WorkOrder $workOrder)
    {
        $this->authorizeWorkOrder($workOrder);
        
        $workOrder->load([
            'equipmentInterval.equipment.location.deck',
            'completedBy',
            'tasks.completedBy',
        ]);

        $availableUsers = $workOrder->equipmentInterval->equipment->vessel
            ->users()
            ->orderBy('name')
            ->get();

        return view('inventory.equipment.intervals.work-orders.show', compact('workOrder', 'availableUsers'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(WorkOrder $workOrder)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, WorkOrder $workOrder)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(WorkOrder $workOrder)
    {
        //
    }

    // Assign Work Order to Crew Member
    public function assign(Request $request, WorkOrder $workOrder)
    {
        $this->authorizeWorkOrder($workOrder);

        $request->validate([
            'assigned_to' => ['nullable', 'exists:users,id'],
        ]);

        $userId = $request->input('assigned_to');
        $vesselUserIds = $workOrder->equipmentInterval->equipment->vessel->users->pluck('id');

        if ($userId && ! $vesselUserIds->contains($userId)) {
            return response()->json(['error' => 'Invalid assignee selected.'], 422);
        }

        $workOrder->update([
            'assigned_to' => $userId,
        ]);

        return response()->json([
            'success' => true,
            'user' => [
                'first_name' => $workOrder->assignee?->first_name,
                'last_name' => $workOrder->assignee?->last_name,
                'avatar_url' => $workOrder->assignee?->avatar_url ?? '/images/default-avatar.png',
            ],
        ]);
    }

    // Open Scheduled Work Order
    public function open(WorkOrder $workOrder)
    {
        $this->authorizeWorkOrder($workOrder);

        if ($workOrder->status !== 'scheduled') {
            return response()->json(['error' => 'Invalid status transition.'], 400);
        }

        $workOrder->status = 'open';
        $workOrder->save();

        return response()->json(['success' => true]);
    }


    // Complete Work Order & Generate Deficiency if Needed
    public function complete(Request $request, WorkOrder $workOrder)
    {
        $this->authorizeWorkOrder($workOrder);

        // Ensure all tasks are either completed or flagged
        $allTasksResolved = $workOrder->tasks->every(fn ($t) => in_array($t->status, ['completed', 'flagged']));

        if (! $allTasksResolved) {
            return back()->with('error', 'Not all tasks are resolved.');
        }

        // Identify flagged tasks
        $flaggedTasks = $workOrder->tasks->where('status', 'flagged');
        $isFlagged = $flaggedTasks->isNotEmpty();

        // Require notes if flagged tasks exist
        $notes = $request->input('notes');
        if ($isFlagged && empty($notes)) {
            return back()->with('error', 'Please include notes when flagging tasks to complete this work order.');
        }

        // Determine new status and timestamps
        $newStatus = 'completed';
        $now       = now();

        // Build update payload
        $updateData = [
            'status'       => $newStatus,
            'completed_at' => $now,
            'notes'        => $notes,
        ];

        // If this is the very first work order (due_date still null), stamp it now
        if (is_null($workOrder->due_date)) {
            $updateData['due_date'] = $now;
        }

        // Perform the update
        $workOrder->update($updateData);

        $interval = $workOrder->equipmentInterval;

        $interval->update([
            'next_due_date'  => now()
            
        ]);

        // Create a deficiency if any tasks were flagged
        if ($isFlagged) {

            Deficiency::create([
                'equipment_id'   => $workOrder->equipmentInterval->equipment_id,
                'work_order_id'  => $workOrder->id,
                'opened_by'      => auth()->id(),
                'subject'        => 'Observations During ' . ucfirst($interval->frequency) . ' ' . $interval->description . ' #' . $workOrder->id,
                'description'    => $notes,
                'priority'       => 'medium',
                'status'         => 'open',
            ]);
        }

        // Handle first interval completion
        $firstWorkOrder = $workOrder->equipmentInterval->workOrders->sortBy('id')->first();

        if ($firstWorkOrder && $firstWorkOrder->id === $workOrder->id) {
            app(\App\Services\WorkOrderGenerationService::class)
                ->handleFirstCompletion($workOrder->equipmentInterval);
        }

        // If Request is From Schedule Flow, Grab Response and Intercept Redirect
        if ($request->expectsJson()) {
            return response()->json([
                'status' => 'success',
                'message' => 'Work order marked as completed.',
                'work_order_id' => $workOrder->id,
            ]);
        }

        return redirect()->route('work-orders.show', $workOrder)
            ->with('success', 'Work order marked as completed.');
    }

    private function authorizeWorkOrder(WorkOrder $workOrder)
    {
        if (
            $workOrder->equipmentInterval->equipment->vessel_id !== currentVessel()?->id
        ) {
            abort(403);
        }
    }


}
