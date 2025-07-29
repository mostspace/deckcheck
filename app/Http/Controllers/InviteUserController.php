<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;

use App\Models\User;
use App\Models\Boarding;
use App\Models\Invitation;
use App\Models\Vessel;

use App\Mail\InviteUserMail;

class InviteUserController extends Controller
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
        $vessel = currentVessel();

        // Ensure inviter has permission
        $boarding = currentUserBoarding();
        if (!in_array($boarding?->access_level, ['owner', 'admin'])) {
            abort(403, 'You do not have permission to invite users to this vessel.');
        }

        // Validate request
        $validated = $request->validate([
            'first_name'    => 'required|string|max:255',
            'last_name'     => 'required|string|max:255',
            'email'         => 'required|email|max:255',
            'phone'         => 'required|string|max:20',
            'department'    => 'required|string|max:255',
            'role'          => 'required|string|max:255',
            'access_level'  => 'required|in:admin,crew,viewer',
        ]);

        // Check if user already exists
        $user = User::where('email', $validated['email'])->first();

        // If not, create a stub user record
        if (! $user) {
            $user = User::create([
                'first_name' => $validated['first_name'],
                'last_name'  => $validated['last_name'],
                'email'      => $validated['email'],
                'phone'      => $validated['phone'],
                'password'   => Hash::make(Str::random(32)), // Temporary password
            ]);
        }

        // Create or update the boarding record
        $boarding = Boarding::updateOrCreate(
            [
                'user_id'    => $user->id,
                'vessel_id'  => $vessel->id,
            ],
            [
                'access_level' => $validated['access_level'],
                'department'   => $validated['department'],
                'role'         => $validated['role'],
                'status'       => 'invited',
            ]
        );

        // Generate secure token
        $token = Str::uuid()->toString();

        // Create invitation record
        $invitation = Invitation::create([
            'boarding_id' => $boarding->id,
            'email'       => $validated['email'],
            'token'       => $token,
            'expires_at'  => now()->addDays(7),
            'invited_by'  => auth()->id(),
        ]);

        Mail::to($user->email)->send(new InviteUserMail($invitation));

        return redirect()->route('vessel.crew')->with('success', 'Invitation sent successfully.');
    }


    public function showAcceptForm(Request $request)
    {
        $token = $request->query('token');

        $invitation = Invitation::where('token', $token)
            ->whereNull('accepted_at')
            ->where('expires_at', '>=', now())
            ->with('boarding.user', 'boarding.vessel')
            ->firstOrFail();

        $user = $invitation->boarding->user;

        // If user already has a password, redirect to login (they’re already onboarded)
        if ($user->has_completed_onboarding) {
            return redirect()->route('login')->with('info', 'You already have an account. Please log in to access your new vessel.');
        }

        return view('auth.invite.accept-password', [
            'invitation' => $invitation,
            'token' => $token,
        ]);
    }

    public function storePassword(Request $request)
    {
        $validated = $request->validate([
            'token'    => 'required|string|exists:invitations,token',
            'password' => 'required|string|min:8|confirmed',
        ]);

        $invitation = Invitation::where('token', $validated['token'])
            ->whereNull('accepted_at')
            ->where('expires_at', '>=', now())
            ->with('boarding.user')
            ->firstOrFail();

        $user = $invitation->boarding->user;

        // Update password
        $user->update([
            'password' => Hash::make($validated['password']),
        ]);

        // Store token in session to persist between steps
        session(['invitation_token' => $validated['token']]);

        return redirect()->route('invitations.accept.profile');
    }



    /**
     * Display the specified resource.
     */
    public function show(Invitation $invitation)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Invitation $invitation)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Invitation $invitation)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Invitation $invitation)
    {
        //
    }
}
