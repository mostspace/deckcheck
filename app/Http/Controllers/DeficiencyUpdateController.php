<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\DeficiencyUpdate;
use Illuminate\Http\Request;

class DeficiencyUpdateController extends Controller
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
    public function show(DeficiencyUpdate $deficiencyUpdate)
    {
        // TODO: Add vessel access validation when implementing
        // if (!auth()->user()->hasSystemAccessToVessel($deficiencyUpdate->deficiency->equipment->vessel)) {
        //     abort(403, 'Access denied to this vessel');
        // }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(DeficiencyUpdate $deficiencyUpdate)
    {
        // TODO: Add vessel access validation when implementing
        // if (!auth()->user()->hasSystemAccessToVessel($deficiencyUpdate->deficiency->equipment->vessel)) {
        //     abort(403, 'Access denied to this vessel');
        // }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, DeficiencyUpdate $deficiencyUpdate)
    {
        // TODO: Add vessel access validation when implementing
        // if (!auth()->user()->hasSystemAccessToVessel($deficiencyUpdate->deficiency->equipment->vessel)) {
        //     abort(403, 'Access denied to this vessel');
        // }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(DeficiencyUpdate $deficiencyUpdate)
    {
        // TODO: Add vessel access validation when implementing
        // if (!auth()->user()->hasSystemAccessToVessel($deficiencyUpdate->deficiency->equipment->vessel)) {
        //     abort(403, 'Access denied to this vessel');
        // }
    }

    /**
     * Helper method to validate vessel access for deficiency updates
     */
    protected function authorizeDeficiencyUpdate(DeficiencyUpdate $deficiencyUpdate)
    {
        if (! auth()->user()->hasSystemAccessToVessel($deficiencyUpdate->deficiency->equipment->vessel)) {
            abort(403, 'Access denied to this vessel');
        }
    }
}
