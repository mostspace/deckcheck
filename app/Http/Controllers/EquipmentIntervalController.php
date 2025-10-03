<?php

declare(strict_types=1);

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
        // Check if user has access to this equipment's vessel
        if (! auth()->user()->hasSystemAccessToVessel($interval->equipment->vessel)) {
            abort(403, 'Access denied to this equipment interval');
        }

        // Load all users on the same vessel
        $users = $interval->equipment->vessel->users()->get();

        // Optional: eager load assignees on the work orders to avoid N+1
        $interval->load('workOrders.assignee');

        // Eager-load work orders and tasks
        $interval->load('equipment');
        $interval->load('workOrders.assignee');
        $interval->load([
            'workOrders.tasks' => fn ($query) => $query->orderBy('sequence_position'),
        ]);

        // Check if request came from manifest context
        $requestPath = request()->getPathInfo();
        if (str_contains($requestPath, 'maintenance/manifest')) {
            return view('v2.pages.maintenance.manifest.intervals.show', compact('interval', 'users'));
        }

        return view('v2.pages.inventory.equipment.intervals.show', compact('interval', 'users'));
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
