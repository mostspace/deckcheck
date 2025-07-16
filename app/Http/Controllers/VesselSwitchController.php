<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Boarding;

class VesselSwitchController extends Controller
{
    public function switch(Request $request)
    {
        $validated = $request->validate([
            'boarding_id' => 'required|exists:boardings,id',
        ]);

        $boarding = \App\Models\Boarding::findOrFail($validated['boarding_id']);

        // Set all other boardings for the user to is_primary = false
        auth()->user()->boardings()->update(['is_primary' => false]);

        // Set the selected boarding to primary
        $boarding->is_primary = true;
        $boarding->save();

        return redirect()->back();
    }

}
