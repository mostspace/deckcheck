<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Vessel;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class VesselDataController extends Controller
{
    public function index()
    {
        $vessels = Vessel::with(['owner'])
            ->withCount(['activeUsers as users_count'])
            ->latest()
            ->get();

        // Define size ranges and initialize bins
        $buckets = [
            '<30m'    => [],
            '30–39m'  => [],
            '40–49m'  => [],
            '50–59m'  => [],
            '60–69m'  => [],
            '70–79m'  => [],
            '≥80m'    => [],
        ];

        $totalUsers = 0;
        $totalVessels = 0;

        foreach ($vessels as $vessel) {
            $size = $vessel->vessel_size;
            $users = $vessel->users_count ?? 0;

            // Tally for global average
            $totalUsers += $users;
            $totalVessels++;

            // Assign to bucket
            if ($size < 30) {
                $buckets['<30m'][] = $users;
            } elseif ($size < 40) {
                $buckets['30–39m'][] = $users;
            } elseif ($size < 50) {
                $buckets['40–49m'][] = $users;
            } elseif ($size < 60) {
                $buckets['50–59m'][] = $users;
            } elseif ($size < 70) {
                $buckets['60–69m'][] = $users;
            } elseif ($size < 80) {
                $buckets['70–79m'][] = $users;
            } else {
                $buckets['≥80m'][] = $users;
            }
        }

        // Calculate average per range
        $avgUsersPerRange = collect($buckets)->map(function ($userCounts) {
            return count($userCounts)
                ? round(array_sum($userCounts) / count($userCounts), 1)
                : null;
        });

        // Calculate global average
        $overallAvg = $totalVessels > 0
            ? round($totalUsers / $totalVessels, 1)
            : null;

        return view('v1.admin.data.vessel.index', [
            'vessels' => $vessels,
            'overallAvgUsers' => $overallAvg,
            'avgUsersPerRange' => $avgUsersPerRange,
        ]);
    }

    public function create()
    {
        $users = User::where('system_role', '!=', 'user')->get();
        return view('v1.admin.data.vessel.create', compact('users'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'type' => ['required', Rule::in(['MY', 'SY', 'MV', 'SV', 'FV', 'RV'])],
            'flag' => 'nullable|string|max:255',
            'registry_port' => 'nullable|string|max:255',
            'build_year' => 'nullable|string|max:4',
            'vessel_make' => 'nullable|string|max:255',
            'vessel_size' => 'nullable|integer|min:1',
            'vessel_loa' => 'nullable|integer|min:1',
            'vessel_lwl' => 'nullable|integer|min:1',
            'vessel_beam' => 'nullable|integer|min:1',
            'vessel_draft' => 'nullable|integer|min:1',
            'vessel_gt' => 'nullable|integer|min:1',
            'official_number' => 'nullable|string|max:255',
            'mmsi_number' => 'nullable|string|max:255',
            'imo_number' => 'nullable|string|max:255',
            'callsign' => 'nullable|string|max:255',
            'hero_photo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'dpa_name' => 'nullable|string|max:255',
            'dpa_phone' => 'nullable|string|max:255',
            'dpa_email' => 'nullable|email|max:255',
            'vessel_phone' => 'nullable|string|max:255',
            'vessel_email' => 'nullable|email|max:255',
            'account_owner' => 'nullable|exists:users,id',
        ]);

        // Handle hero photo upload
        if ($request->hasFile('hero_photo')) {
            $heroPhotoPath = $request->file('hero_photo')->store('vessels/hero-photos', 'public');
            $validated['hero_photo'] = $heroPhotoPath;
        }

        // If no account owner is specified, set it to the authenticated user (admin)
        if (empty($validated['account_owner'])) {
            $validated['account_owner'] = auth()->id();
        }

        $vessel = Vessel::create($validated);

        return redirect()->route('admin.vessels.show', $vessel)
            ->with('success', 'Vessel created successfully!');
    }

    public function edit(Vessel $vessel)
    {
        $users = User::where('system_role', '!=', 'user')->get();
        return view('v1.admin.data.vessel.edit', compact('vessel', 'users'));
    }

    public function update(Request $request, Vessel $vessel)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'type' => ['required', Rule::in(['MY', 'SY', 'MV', 'SV', 'FV', 'RV'])],
            'flag' => 'nullable|string|max:255',
            'registry_port' => 'nullable|string|max:255',
            'build_year' => 'nullable|string|max:4',
            'vessel_make' => 'nullable|string|max:255',
            'vessel_size' => 'nullable|integer|min:1',
            'vessel_loa' => 'nullable|integer|min:1',
            'vessel_lwl' => 'nullable|integer|min:1',
            'vessel_beam' => 'nullable|integer|min:1',
            'vessel_draft' => 'nullable|integer|min:1',
            'vessel_gt' => 'nullable|integer|min:1',
            'official_number' => 'nullable|string|max:255',
            'mmsi_number' => 'nullable|string|max:255',
            'imo_number' => 'nullable|string|max:255',
            'callsign' => 'nullable|string|max:255',
            'hero_photo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'dpa_name' => 'nullable|string|max:255',
            'dpa_phone' => 'nullable|string|max:255',
            'dpa_email' => 'nullable|email|max:255',
            'vessel_phone' => 'nullable|string|max:255',
            'vessel_email' => 'nullable|email|max:255',
            'account_owner' => 'nullable|exists:users,id',
        ]);

        // Handle hero photo upload
        if ($request->hasFile('hero_photo')) {
            // Delete old photo if it exists
            if ($vessel->hero_photo) {
                \Storage::disk('public')->delete($vessel->hero_photo);
            }
            $heroPhotoPath = $request->file('hero_photo')->store('vessels/hero-photos', 'public');
            $validated['hero_photo'] = $heroPhotoPath;
        }

        $vessel->update($validated);

        return redirect()->route('admin.vessels.show', $vessel)
            ->with('success', 'Vessel updated successfully!');
    }

    public function show(Vessel $vessel)
    {
        $vessel->load([
            'owner:id,first_name,last_name,email,phone,profile_pic',
            'boardings' => function ($query) {
                $query->whereIn('status', ['active', 'invited'])
                    ->orderBy('crew_number')
                    ->with('user:id,first_name,last_name,profile_pic');
            }
        ]);

        // Safely get owner boarding only if owner exists
        $ownerBoarding = null;
        if ($vessel->owner) {
            $ownerBoarding = $vessel
                ->boardings
                ->firstWhere('user_id', $vessel->owner->id);
        }

        return view('v1.admin.data.vessel.show', compact('vessel','ownerBoarding'));
    }

    public function addUser(Vessel $vessel)
    {
        // Load boardings to get existing user IDs
        $vessel->load('boardings');
        
        // Get users who don't already have a boarding with this vessel
        $existingUserIds = $vessel->boardings->pluck('user_id')->toArray();
        $availableUsers = User::whereNotIn('id', $existingUserIds)
            ->orderBy('first_name')
            ->orderBy('last_name')
            ->get();

        return view('v1.admin.data.vessel.add-user', compact('vessel', 'availableUsers'));
    }

    public function storeUser(Request $request, Vessel $vessel)
    {
        $validated = $request->validate([
            'user_id' => 'required|exists:users,id',
            'access_level' => 'required|in:admin,crew,viewer',
            'department' => 'required|in:bridge,interior,exterior,galley,engineering,management,owner',
            'role' => 'required|string|max:255',
            'crew_number' => 'nullable|integer|min:1',
        ]);

        // Check if user already has a boarding with this vessel
        if ($vessel->boardings()->where('user_id', $validated['user_id'])->exists()) {
            return back()->withErrors(['user_id' => 'This user is already associated with this vessel.']);
        }

        // Check if user has any existing active boardings
        $existingActiveBoardings = \App\Models\Boarding::where('user_id', $validated['user_id'])
            ->where('status', 'active')
            ->count();

        // Determine if this should be the primary vessel
        $isPrimary = $existingActiveBoardings === 0;

        // Create the boarding record
        $boarding = $vessel->boardings()->create([
            'user_id' => $validated['user_id'],
            'status' => 'active',
            'is_crew' => true,
            'is_primary' => $isPrimary,
            'access_level' => $validated['access_level'],
            'department' => $validated['department'],
            'role' => $validated['role'],
            'crew_number' => $validated['crew_number'],
            'joined_at' => now(),
        ]);

        return redirect()->route('admin.vessels.show', $vessel)
            ->with('success', 'User added to vessel successfully!' . ($isPrimary ? ' This is now their primary vessel.' : ''));
    }

    public function transferOwnership(Vessel $vessel)
    {
        // Get users with active boardings who can become owners
        $eligibleUsers = $vessel->boardings()
            ->where('status', 'active')
            ->where('user_id', '!=', $vessel->account_owner) // Exclude current owner
            ->with('user:id,first_name,last_name,email,profile_pic')
            ->get();

        return view('v1.admin.data.vessel.transfer-ownership', compact('vessel', 'eligibleUsers'));
    }

    public function processOwnershipTransfer(Request $request, Vessel $vessel)
    {
        $validated = $request->validate([
            'new_owner_id' => 'required|exists:users,id',
        ]);

        // Check if the new owner has an active boarding
        $newOwnerBoarding = $vessel->boardings()
            ->where('user_id', $validated['new_owner_id'])
            ->where('status', 'active')
            ->first();

        if (!$newOwnerBoarding) {
            return back()->withErrors(['new_owner_id' => 'Selected user must have an active boarding on this vessel.']);
        }

        // Get current owner's boarding record
        $currentOwnerBoarding = null;
        if ($vessel->account_owner) {
            $currentOwnerBoarding = $vessel->boardings()
                ->where('user_id', $vessel->account_owner)
                ->first();
        }

        // Start transaction to ensure data consistency
        \DB::transaction(function () use ($vessel, $validated, $newOwnerBoarding, $currentOwnerBoarding) {
            // Update vessel ownership
            $vessel->update(['account_owner' => $validated['new_owner_id']]);

            // Update new owner's access level to 'owner'
            $newOwnerBoarding->update(['access_level' => 'owner']);

            // Update previous owner's access level to 'admin' if they exist
            if ($currentOwnerBoarding) {
                $currentOwnerBoarding->update(['access_level' => 'admin']);
            }
        });

        return redirect()->route('admin.vessels.show', $vessel)
            ->with('success', 'Vessel ownership transferred successfully!');
    }


}
