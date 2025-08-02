<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Vessel;

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

    public function show(Vessel $vessel)
    {
        $vessel->load([
            'owner:id,first_name,last_name,email',
            'boardings' => function ($query) {
                $query->whereIn('status', ['active', 'invited'])
                    ->orderBy('crew_number')
                    ->with('user:id,first_name,last_name,profile_pic');
            }
        ]);

        return view('admin.data.vessel.show', compact('vessel'));
    }



}
