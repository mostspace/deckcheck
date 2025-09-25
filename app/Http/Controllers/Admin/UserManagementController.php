<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Vessel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class UserManagementController extends Controller
{
    public function index(Request $request)
    {
        $query = User::where('system_role', 'user')->with(['boardings.vessel'])
            ->withCount(['boardings as active_boardings_count' => function ($query) {
                $query->where('status', 'active');
            }])
            ->withCount(['boardings as total_boardings_count']);

        // Filter by status
        if ($request->filled('status')) {
            $status = $request->status;
            if ($status === 'active') {
                $query->whereHas('boardings', function ($q) {
                    $q->where('status', 'active');
                });
            } elseif ($status === 'inactive') {
                $query->whereDoesntHave('boardings', function ($q) {
                    $q->where('status', 'active');
                });
            } elseif ($status === 'crew') {
                $query->whereHas('boardings', function ($q) {
                    $q->where('is_crew', true);
                });
            }
        }

        // Filter by vessel
        if ($request->filled('vessel')) {
            $vesselId = $request->vessel;
            $query->whereHas('boardings', function ($q) use ($vesselId) {
                $q->where('vessel_id', $vesselId);
            });
        }

        // Search by name or email
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('first_name', 'like', "%{$search}%")
                  ->orWhere('last_name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        }

        $users = $query->latest()->paginate(20);

        // Get vessels for filter dropdown
        $vessels = Vessel::orderBy('name')->get();

        // Get status options
        $statusOptions = [
            'all' => 'All Users',
            'active' => 'Active Users',
            'inactive' => 'Inactive Users',
            'crew' => 'Crew Members Only'
        ];

        // Get additional statistics
        $stats = [
            'total_users' => User::where('system_role', 'user')->count(),
            'active_users' => User::where('system_role', 'user')->whereHas('boardings', function ($q) {
                $q->where('status', 'active');
            })->count(),
            'crew_users' => User::where('system_role', 'user')->whereHas('boardings', function ($q) {
                $q->where('is_crew', true);
            })->count(),
        ];

        return view('admin.users.index', compact(
            'users',
            'vessels',
            'statusOptions',
            'stats'
        ));
    }

    public function show(User $user)
    {
        $user->load([
            'boardings.vessel',
            'boardings' => function ($query) {
                $query->orderBy('is_primary', 'desc')
                      ->orderBy('joined_at', 'desc');
            }
        ]);

        // Ensure all date fields are properly cast
        $user->boardings->each(function ($boarding) {
            if ($boarding->joined_at && !($boarding->joined_at instanceof \Carbon\Carbon)) {
                $boarding->joined_at = \Carbon\Carbon::parse($boarding->joined_at);
            }
            if ($boarding->terminated_at && !($boarding->terminated_at instanceof \Carbon\Carbon)) {
                $boarding->terminated_at = \Carbon\Carbon::parse($boarding->terminated_at);
            }
        });

        return view('admin.users.show', compact('user'));
    }
}
