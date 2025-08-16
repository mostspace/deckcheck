<?php

namespace App\Http\Controllers;

use App\Models\Deficiency;
use Illuminate\Http\Request;
use App\Models\Equipment;
use App\Models\WorkOrder;
use App\Models\User;
use App\Models\DeficiencyUpdate;

class DeficiencyController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $vessel = currentVessel();

        $deficiencies = Deficiency::with(['equipment', 'workOrder'])
            ->whereHas('equipment', fn ($q) => $q->where('vessel_id', $vessel->id))
            ->latest()
            ->get();

        return view('maintenance.deficiencies.index', compact('deficiencies'));
    }


    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        {
            $vessel = currentVessel();

            return view('deficiencies.create', [
                'equipmentList' => $vessel->equipment,
                'workOrders' => $vessel->workOrders ?? collect(),
            ]);
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'equipment_id'  => 'required|exists:equipment,id',
            'work_order_id' => 'nullable|exists:work_orders,id',
            'subject'       => 'required|string|max:255',
            'description'   => 'nullable|string',
            'priority'      => 'required|in:low,medium,high',
        ]);

        $validated['opened_by'] = auth()->id();
        $validated['status'] = 'open';

        Deficiency::create($validated);

        return redirect()->route('deficiencies.index')->with('success', 'Deficiency logged.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Deficiency $deficiency)
    {
        $this->authorizeAccess($deficiency);

        $users = $deficiency->equipment->vessel->users ?? collect();

        $deficiency->load([
            'updates.createdBy', 
            'equipment',
            'assignedTo',
            'openedBy',
            'workOrder.tasks.completedBy',
        ]);

        return view('maintenance.deficiencies.show', compact('deficiency', 'users'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Deficiency $deficiency)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Deficiency $deficiency)
    {
        //
    }

    // Update Assignment
    public function assign(Request $request, Deficiency $deficiency)
    {
        // Vessel-level access check
        $this->authorizeAccess($deficiency);

        $request->validate([
            'assigned_to' => 'nullable|exists:users,id',
        ]);

        $deficiency->assigned_to = $request->input('assigned_to');
        $deficiency->save();

        return back()->with('success', 'Assignee updated.');
    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Deficiency $deficiency)
    {
        //
    }
   
    // Create & Store Update
    public function storeUpdate(Request $request, Deficiency $deficiency)
    {
        $this->authorizeAccess($deficiency);

        $request->validate([
            'comment' => 'nullable|string',
            'new_status' => 'nullable|in:open,waiting,resolved',
            'new_priority' => 'nullable|in:low,medium,high',
            'files.*' => 'nullable|file|max:5120', // optional for later
        ]);

        $update = new DeficiencyUpdate([
            'deficiency_id'     => $deficiency->id,
            'created_by'        => auth()->id(),
            'comment'           => $request->input('comment'),
            'previous_status'   => $deficiency->status,
            'new_status'        => $request->input('new_status') !== $deficiency->status ? $request->input('new_status') : null,
            'previous_priority' => $deficiency->priority,
            'new_priority'      => $request->input('new_priority') !== $deficiency->priority ? $request->input('new_priority') : null,
        ]);

        // Only assign if something actually changed or comment was made
        if ($update->comment || $update->new_status || $update->new_priority) {
            $update->save();

            // Update the deficiency itself if needed
            if ($update->new_status) {
                $deficiency->status = $update->new_status;
            }
            if ($update->new_priority) {
                $deficiency->priority = $update->new_priority;
            }
            $deficiency->save();
        }

        return back()->with('success', 'Update saved.');
    }

    protected function authorizeAccess(Deficiency $deficiency)
    {
        if (!auth()->user()->hasSystemAccessToVessel($deficiency->equipment->vessel)) {
            abort(403, 'Access denied to this vessel');
        }
    }
    

}
