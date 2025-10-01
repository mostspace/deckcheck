<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Mail\InviteUserMail;
use App\Models\Boarding;
use App\Models\Invitation;
use App\Models\User;
use App\Models\Vessel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

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

        // Ensure user has access to this vessel
        if (! auth()->user()->hasSystemAccessToVessel($vessel)) {
            abort(403, 'Access denied to this vessel');
        }

        // Ensure inviter has permission
        $boarding = currentUserBoarding();
        if (! in_array($boarding?->access_level, ['owner', 'admin'])) {
            abort(403, 'You do not have permission to invite users to this vessel.');
        }

        // Validate request
        $validated = $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'required|string|max:20',
            'department' => 'required|string|max:255',
            'role' => 'required|string|max:255',
            'access_level' => 'required|in:admin,crew,viewer',

        ]);

        // Check if user already exists
        $user = User::where('email', $validated['email'])->first();

        // If not, create a stub user record
        if (! $user) {

            $isTestUser = auth()->user()->can('is-superadmin') && $request->boolean('is_test_user', false);

            $user = User::create([
                'first_name' => $validated['first_name'],
                'last_name' => $validated['last_name'],
                'email' => $validated['email'],
                'phone' => $validated['phone'],
                'password' => Hash::make(Str::random(32)), // Temporary password
                'is_test_user' => $isTestUser,
            ]);
        }

        // Create or update the boarding record
        $boarding = Boarding::updateOrCreate(
            [
                'user_id' => $user->id,
                'vessel_id' => $vessel->id,
            ],
            [
                'access_level' => $validated['access_level'],
                'department' => $validated['department'],
                'role' => $validated['role'],
                'status' => 'invited',
            ]
        );

        // Generate secure token
        $token = Str::uuid()->toString();

        // Create invitation record
        $invitation = Invitation::create([
            'boarding_id' => $boarding->id,
            'email' => $validated['email'],
            'token' => $token,
            'expires_at' => now()->addDays(7),
            'invited_by' => auth()->id(),
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
            'token' => 'required|string|exists:invitations,token',
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
            'email_verified_at' => now(),
        ]);

        // Store token in session to persist between steps
        session(['invitation_token' => $validated['token']]);

        return redirect()->route('invitations.accept.avatar', ['token' => $invitation->token]);
    }

    public function showAvatarForm($token)
    {
        $invitation = Invitation::with('boarding.user')->where('token', $token)->firstOrFail();

        $user = $invitation->boarding->user;

        // Only allow if onboarding is not complete
        if ($user->has_completed_onboarding) {
            return redirect()->route('login');
        }

        return view('auth.invite.accept-avatar', compact('invitation', 'token'));
    }

    public function storeAvatar(Request $request)
    {
        $request->validate([
            'token' => ['required', 'exists:invitations,token'],
            'avatar' => ['required', 'image', 'max:2048'], // Max 2MB
        ]);

        $invitation = Invitation::where('token', $request->token)->firstOrFail();
        $user = $invitation->boarding->user;

        // Upload to S3
        $path = $request->file('avatar')->store('profile_pictures', 's3_public');

        // Save the S3 path to the profile_pic column
        $user->profile_pic = $path;
        $user->save();

        // Optionally make the file publicly accessible (if you're serving avatars directly from S3)
        // Storage::disk('s3')->setVisibility($path, 'public');

        return redirect()->route('invitations.accept.terms', ['token' => $invitation->token]);

    }

    public function showTermsForm($token)
    {
        $invitation = Invitation::with('boarding.user')->where('token', $token)->firstOrFail();
        $user = $invitation->boarding->user;

        if ($user->has_completed_onboarding) {
            return redirect()->route('login');
        }

        return view('auth.invite.accept-terms', compact('invitation', 'token'));
    }

    public function storeTerms(Request $request)
    {
        $validated = $request->validate([
            'token' => 'required|exists:invitations,token',
            'accept' => 'accepted', // ensures the checkbox is checked
        ]);

        $invitation = Invitation::where('token', $validated['token'])
            ->with('boarding.user')
            ->firstOrFail();

        $user = $invitation->boarding->user;

        // Update user onboarding status
        $user->update([
            'is_beta_user' => true,
            'has_completed_onboarding' => true,
            'accepts_marketing' => true,
            'accepts_updates' => true,
            'agreed_at' => now(),
            'agreed_ip' => $request->ip(),
            'agreed_user_agent' => $request->userAgent(),
            'terms_version' => '1.0',
        ]);

        $user->save();

        // Update the invitation to mark it as accepted
        $invitation->accepted_at = now();
        $invitation->save();

        // Update boarding & set primary
        $invitation->boarding->is_primary = true;
        $invitation->boarding->joined_at = now();
        $invitation->boarding->status = 'active';
        $invitation->boarding->save();

        // Log the user in and redirect to dashboard
        Auth::login($user);

        return redirect()->route('dashboard')->with('success', 'Welcome aboard! Your account has been set up.');
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
