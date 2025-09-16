<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Carbon\Carbon;

class StaffManagementController extends Controller
{
    public function index(Request $request)
    {
        $query = User::whereIn('system_role', ['superadmin', 'staff', 'dev'])
            ->with(['boardings.vessel'])
            ->withCount(['boardings as active_boardings_count' => function ($query) {
                $query->where('status', 'active');
            }])
            ->withCount(['boardings as total_boardings_count']);

        // Filter by system role
        if ($request->filled('role')) {
            $query->where('system_role', $request->role);
        }

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
            } elseif ($status === 'vessel_access') {
                $query->whereHas('boardings');
            } elseif ($status === 'no_vessel_access') {
                $query->whereDoesntHave('boardings');
            }
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

        $staff = $query->latest()->paginate(20);

        // Get system roles for filter dropdown
        $systemRoles = ['superadmin', 'staff', 'dev'];

        // Get status options
        $statusOptions = [
            'all' => 'All Staff',
            'active' => 'Active Vessel Access',
            'inactive' => 'Inactive Vessel Access',
            'vessel_access' => 'Has Vessel Access',
            'no_vessel_access' => 'No Vessel Access'
        ];

        // Get additional statistics (only for staff members)
        $stats = [
            'total_staff' => User::whereIn('system_role', ['superadmin', 'staff', 'dev'])->count(),
            'superadmins' => User::where('system_role', 'superadmin')->count(),
            'staff_members' => User::where('system_role', 'staff')->count(),
            'developers' => User::where('system_role', 'dev')->count(),
            'with_vessel_access' => User::whereIn('system_role', ['superadmin', 'staff', 'dev'])
                ->whereHas('boardings')->count(),
        ];

        return view('v1.admin.staff.index', compact(
            'staff',
            'systemRoles',
            'statusOptions',
            'stats'
        ));
    }

    public function show(User $user)
    {
        // Ensure only staff members can be viewed
        if (!in_array($user->system_role, ['superadmin', 'staff', 'dev'])) {
            abort(404, 'User not found');
        }

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

        return view('v1.admin.staff.show', compact('user'));
    }

    public function edit(User $user)
    {
        // Ensure only staff members can be edited
        if (!in_array($user->system_role, ['superadmin', 'staff', 'dev'])) {
            abort(404, 'User not found');
        }

        return view('v1.admin.staff.edit', compact('user'));
    }

    public function update(Request $request, User $user)
    {
        // Ensure only staff members can be updated
        if (!in_array($user->system_role, ['superadmin', 'staff', 'dev'])) {
            abort(404, 'User not found');
        }

        $validated = $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'phone' => 'nullable|string|max:255',
            'system_role' => 'required|in:superadmin,staff,dev',
        ]);

        $user->update($validated);

        return redirect()->route('admin.staff.show', $user)
            ->with('success', 'Staff member updated successfully!');
    }
}
