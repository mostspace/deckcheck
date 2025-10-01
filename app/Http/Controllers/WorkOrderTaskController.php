<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\WorkOrderTask;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class WorkOrderTaskController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
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

    /**
     * Display the specified resource.
     */
    public function show(WorkOrderTask $workOrderTask)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(WorkOrderTask $workOrderTask)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, WorkOrderTask $workOrderTask)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(WorkOrderTask $workOrderTask)
    {
        //
    }

    // Update Task Status
    public function updateStatus(Request $request, WorkOrderTask $task)
    {
        // Ensure user has access to the vessel this task belongs to
        if (! auth()->user()->hasSystemAccessToVessel($task->workOrder->equipmentInterval->equipment->vessel)) {
            abort(403, 'Access denied to this vessel');
        }

        $validated = $request->validate([
            'status' => ['required', Rule::in(['completed', 'flagged'])],
        ]);

        $isResolved = in_array($validated['status'], ['completed', 'flagged']);

        $task->update([
            'status' => $validated['status'],
            'is_flagged' => $validated['status'] === 'flagged',
            'completed_at' => $isResolved ? now() : null,
            'completed_by' => $isResolved ? auth()->id() : null,
        ]);

        $workOrder = $task->workOrder()->with(['tasks', 'equipmentInterval.workOrders'])->first();

        if (! $workOrder || ! $workOrder->equipmentInterval) {
            return response()->json(['error' => 'Invalid work order data'], 400);
        }

        $tasks = $workOrder->tasks;

        $anyFlagged = $tasks->contains(fn ($t) => $t->status === 'flagged');
        $allResolved = $tasks->every(fn ($t) => in_array($t->status, ['completed', 'flagged']));

        $workOrder->update([
            'status' => $anyFlagged ? 'flagged' : 'in_progress',
        ]);

        return response()->json(['success' => true]);
    }
}
