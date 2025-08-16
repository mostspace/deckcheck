<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Boarding;
use App\Models\Vessel;

class VesselSwitchController extends Controller
{
    public function switch(Request $request)
    {
        $user = auth()->user();
        
        // Log the request for debugging
        \Log::info('Vessel switch request', [
            'user_id' => $user->id,
            'user_role' => $user->system_role,
            'request_data' => $request->all(),
            'session_data' => session()->all()
        ]);
        
        if (in_array($user->system_role, ['superadmin', 'staff', 'dev'])) {
            // System users can switch to any vessel
            $validated = $request->validate([
                'vessel_id' => 'required|exists:vessels,id',
            ]);
            
            $vessel = Vessel::findOrFail($validated['vessel_id']);
            
            // Store the active vessel in session for system users
            session(['active_vessel_id' => $vessel->id]);
            
            \Log::info('System user vessel switch successful', [
                'user_id' => $user->id,
                'vessel_id' => $vessel->id,
                'session_active_vessel' => session('active_vessel_id')
            ]);
            
            // For system users, redirect to the dashboard instead of vessel index
            return redirect()->route('dashboard')->with('success', "Successfully switched to {$vessel->name}. You are now viewing this vessel's dashboard.");
        } else {
            // Regular users switch via boarding records
            $validated = $request->validate([
                'boarding_id' => 'required|exists:boardings,id',
            ]);

            $boarding = Boarding::findOrFail($validated['boarding_id']);

            // Set all other boardings for the user to is_primary = false
            $user->boardings()->update(['is_primary' => false]);

            // Set the selected boarding to primary
            $boarding->is_primary = true;
            $boarding->save();
            
            return redirect()->back();
        }
    }
}
