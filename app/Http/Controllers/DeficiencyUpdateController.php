<?php

namespace App\Http\Controllers;

use App\Models\DeficiencyUpdate;
use Illuminate\Http\Request;

class DeficiencyUpdateController extends Controller
{

    public function assignee()
    {
        return $this->belongsTo(User::class, 'new_assignee')->where('vessel_id', auth()->user()->vessel_id);
    }


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
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(DeficiencyUpdate $deficiencyUpdate)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, DeficiencyUpdate $deficiencyUpdate)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(DeficiencyUpdate $deficiencyUpdate)
    {
        //
    }
}
