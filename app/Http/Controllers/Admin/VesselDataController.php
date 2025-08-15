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

        return view('admin.data.vessel.index', [
            'vessels' => $vessels,
            'overallAvgUsers' => $overallAvg,
            'avgUsersPerRange' => $avgUsersPerRange,
        ]);
    }

    public function create()
    {
        $users = User::where('system_role', '!=', 'user')->get();
        return view('admin.data.vessel.create', compact('users'));
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
            'hero_photo' => 'nullable|string|max:255',
            'dpa_name' => 'nullable|string|max:255',
            'dpa_phone' => 'nullable|string|max:255',
            'dpa_email' => 'nullable|email|max:255',
            'vessel_phone' => 'nullable|string|max:255',
            'vessel_email' => 'nullable|email|max:255',
            'account_owner' => 'nullable|exists:users,id',
        ]);

        // If no account owner is specified, set it to the authenticated user (admin)
        if (empty($validated['account_owner'])) {
            $validated['account_owner'] = auth()->id();
        }

        $vessel = Vessel::create($validated);

        return redirect()->route('admin.vessels.show', $vessel)
            ->with('success', 'Vessel created successfully!');
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

        return view('admin.data.vessel.show', compact('vessel','ownerBoarding'));
    }



}
