<?php

namespace App\Http\Controllers;

use App\Models\EquipmentInterval;
use Illuminate\Http\Request;

class EquipmentIntervalController extends Controller
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
    public function show(EquipmentInterval $interval)
    {
        $vessel = currentVessel();

        if ($interval->equipment->vessel_id !== $vessel->id) {
            abort(403);
        }

        // Load all users on the same vessel
        $users = $vessel->users()->get();

        // Optional: eager load assignees on the work orders to avoid N+1
        $interval->load('workOrders.assignee');
        
        // Eager-load work orders and tasks
        $interval->load('equipment');
        $interval->load('workOrders.assignee');
        $interval->load([
            'workOrders.tasks' => fn($query) =>
                $query->orderBy('sequence_position'),
        ]);

        return view('inventory.equipment.intervals.show', compact('interval', 'users'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(EquipmentInterval $equipmentInterval)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, EquipmentInterval $equipmentInterval)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(EquipmentInterval $equipmentInterval)
    {
        //
    }
}
