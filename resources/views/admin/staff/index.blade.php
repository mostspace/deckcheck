@extends('layouts.admin')

@section('title', 'Staff Management')

@section('content')
    <div class="space-y-6">
        {{-- Page Header --}}
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-bold text-white">Staff Management</h1>
                <p class="mt-1 text-gray-400">Manage system staff members and administrators</p>
            </div>
            <div class="flex items-center space-x-3">
                <button
                    class="bg-accent-primary rounded-md px-4 py-2 text-white transition-colors duration-200 hover:bg-blue-600">
                    <i class="fa-solid fa-plus mr-2"></i>Add Staff Member
                </button>
                <button class="bg-dark-700 hover:bg-dark-600 rounded-md px-4 py-2 text-white transition-colors duration-200">
                    <i class="fa-solid fa-download mr-2"></i>Export
                </button>
            </div>
        </div>

        {{-- Statistics Cards --}}
        <div class="grid grid-cols-1 gap-6 md:grid-cols-2 lg:grid-cols-5">
            <div class="bg-dark-800 border-dark-600 rounded-lg border p-6">
                <div class="flex items-center">
                    <div class="rounded-lg bg-blue-500/10 p-2">
                        <i class="fa-solid fa-users text-xl text-blue-500"></i>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-400">Total Staff</p>
                        <p class="text-2xl font-bold text-white">{{ number_format($stats['total_staff']) }}</p>
                    </div>
                </div>
            </div>

            <div class="bg-dark-800 border-dark-600 rounded-lg border p-6">
                <div class="flex items-center">
                    <div class="rounded-lg bg-red-500/10 p-2">
                        <i class="fa-solid fa-crown text-xl text-red-500"></i>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-400">Super Admins</p>
                        <p class="text-2xl font-bold text-white">{{ number_format($stats['superadmins']) }}</p>
                    </div>
                </div>
            </div>

            <div class="bg-dark-800 border-dark-600 rounded-lg border p-6">
                <div class="flex items-center">
                    <div class="rounded-lg bg-purple-500/10 p-2">
                        <i class="fa-solid fa-user-tie text-xl text-purple-500"></i>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-400">Staff Members</p>
                        <p class="text-2xl font-bold text-white">{{ number_format($stats['staff_members']) }}</p>
                    </div>
                </div>
            </div>

            <div class="bg-dark-800 border-dark-600 rounded-lg border p-6">
                <div class="flex items-center">
                    <div class="rounded-lg bg-green-500/10 p-2">
                        <i class="fa-solid fa-code text-xl text-green-500"></i>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-400">Developers</p>
                        <p class="text-2xl font-bold text-white">{{ number_format($stats['developers']) }}</p>
                    </div>
                </div>
            </div>

            <div class="bg-dark-800 border-dark-600 rounded-lg border p-6">
                <div class="flex items-center">
                    <div class="rounded-lg bg-orange-500/10 p-2">
                        <i class="fa-solid fa-ship text-xl text-orange-500"></i>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-400">With Vessel Access</p>
                        <p class="text-2xl font-bold text-white">{{ number_format($stats['with_vessel_access']) }}</p>
                    </div>
                </div>
            </div>
        </div>

        {{-- Filters --}}
        <div class="bg-dark-800 border-dark-600 rounded-lg border p-6">
            <form method="GET" action="{{ route('admin.staff.index') }}" class="space-y-4">
                <div class="grid grid-cols-1 gap-4 md:grid-cols-2 lg:grid-cols-4">
                    {{-- Search --}}
                    <div>
                        <label class="mb-2 block text-sm font-medium text-gray-300">Search</label>
                        <input type="text" name="search" value="{{ request('search') }}" placeholder="Name or email..."
                            class="bg-dark-700 border-dark-600 focus:ring-accent-primary w-full rounded-md border px-3 py-2 text-white focus:outline-none focus:ring-2">
                    </div>

                    {{-- Role Filter --}}
                    <div>
                        <label class="mb-2 block text-sm font-medium text-gray-300">System Role</label>
                        <select name="role"
                            class="bg-dark-700 border-dark-600 focus:ring-accent-primary w-full rounded-md border px-3 py-2 text-white focus:outline-none focus:ring-2">
                            <option value="">All Roles</option>
                            @foreach ($systemRoles as $role)
                                <option value="{{ $role }}" {{ request('role') == $role ? 'selected' : '' }}>
                                    {{ ucfirst($role) }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    {{-- Status Filter --}}
                    <div>
                        <label class="mb-2 block text-sm font-medium text-gray-300">Status</label>
                        <select name="status"
                            class="bg-dark-700 border-dark-600 focus:ring-accent-primary w-full rounded-md border px-3 py-2 text-white focus:outline-none focus:ring-2">
                            @foreach ($statusOptions as $value => $label)
                                <option value="{{ $value }}" {{ request('status') == $value ? 'selected' : '' }}>
                                    {{ $label }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="flex items-center justify-between">
                    <button type="submit"
                        class="bg-accent-primary rounded-md px-6 py-2 text-white transition-colors duration-200 hover:bg-blue-600">
                        <i class="fa-solid fa-filter mr-2"></i>Apply Filters
                    </button>

                    @if (request()->hasAny(['search', 'role', 'status']))
                        <a href="{{ route('admin.staff.index') }}"
                            class="text-gray-400 transition-colors duration-200 hover:text-white">
                            <i class="fa-solid fa-times mr-2"></i>Clear Filters
                        </a>
                    @endif
                </div>
            </form>
        </div>

        {{-- Results Summary --}}
        <div class="flex items-center justify-between text-sm text-gray-400">
            <div>
                Showing {{ $staff->firstItem() ?? 0 }} to {{ $staff->lastItem() ?? 0 }} of {{ $staff->total() }} staff
                members
            </div>
            <div>
                @if (request()->hasAny(['search', 'role', 'status']))
                    <span class="bg-accent-primary rounded px-2 py-1 text-xs text-white">
                        {{ $staff->total() }} results
                    </span>
                @endif
            </div>
        </div>

        {{-- Staff Table --}}
        <div class="bg-dark-800 border-dark-600 overflow-hidden rounded-lg border">
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-dark-700 border-dark-600 border-b">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-300">Staff
                                Member</th>
                            <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-300">
                                System Role</th>
                            <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-300">
                                Permissions Scope</th>
                            <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-300">
                                Vessel Access</th>
                            <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-300">
                                Joined</th>
                            <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-300">
                                Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-dark-600 divide-y">
                        @forelse($staff as $member)
                            <tr class="hover:bg-dark-700 transition-colors duration-150">
                                <td class="px-6 py-4">
                                    <div class="flex items-center">
                                        <img src="{{ $member->profile_pic ?? 'https://storage.googleapis.com/uxpilot-auth.appspot.com/avatars/avatar-3.jpg' }}"
                                            alt="{{ $member->full_name ?? 'Staff Member' }}"
                                            class="border-dark-600 h-10 w-10 rounded-full border-2">
                                        <div class="ml-4">
                                            <div class="text-sm font-medium text-white">
                                                {{ $member->full_name ?? 'Unknown Staff Member' }}</div>
                                            <div class="text-sm text-gray-400">{{ $member->email ?? 'No email' }}</div>
                                            @if ($member->phone)
                                                <div class="text-xs text-gray-500">{{ $member->phone }}</div>
                                            @endif
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4">
                                    @if ($member->system_role === 'superadmin')
                                        <span
                                            class="inline-flex items-center rounded-full bg-red-100 px-2.5 py-0.5 text-xs font-medium text-red-800">
                                            <i class="fa-solid fa-crown mr-1"></i>Super Admin
                                        </span>
                                    @elseif($member->system_role === 'staff')
                                        <span
                                            class="inline-flex items-center rounded-full bg-purple-100 px-2.5 py-0.5 text-xs font-medium text-purple-800">
                                            <i class="fa-solid fa-user-tie mr-1"></i>Staff
                                        </span>
                                    @elseif($member->system_role === 'dev')
                                        <span
                                            class="inline-flex items-center rounded-full bg-green-100 px-2.5 py-0.5 text-xs font-medium text-green-800">
                                            <i class="fa-solid fa-code mr-1"></i>Developer
                                        </span>
                                    @else
                                        <span class="text-sm text-gray-500">-</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4">
                                    <div class="space-y-1">
                                        @if ($member->system_role === 'superadmin')
                                            <div class="text-xs text-gray-300">
                                                <i class="fa-solid fa-shield-halved mr-1 text-red-400"></i>
                                                Full system access
                                            </div>
                                            <div class="text-xs text-gray-300">
                                                <i class="fa-solid fa-users mr-1 text-red-400"></i>
                                                User management
                                            </div>
                                            <div class="text-xs text-gray-300">
                                                <i class="fa-solid fa-database mr-1 text-red-400"></i>
                                                Data management
                                            </div>
                                        @elseif($member->system_role === 'staff')
                                            <div class="text-xs text-gray-300">
                                                <i class="fa-solid fa-ship mr-1 text-purple-400"></i>
                                                Vessel management
                                            </div>
                                            <div class="text-xs text-gray-300">
                                                <i class="fa-solid fa-tools mr-1 text-purple-400"></i>
                                                Maintenance tools
                                            </div>
                                            <div class="text-xs text-gray-300">
                                                <i class="fa-solid fa-chart-bar mr-1 text-purple-400"></i>
                                                Reports & analytics
                                            </div>
                                        @elseif($member->system_role === 'dev')
                                            <div class="text-xs text-gray-300">
                                                <i class="fa-solid fa-code mr-1 text-green-400"></i>
                                                System development
                                            </div>
                                            <div class="text-xs text-gray-300">
                                                <i class="fa-solid fa-bug mr-1 text-green-400"></i>
                                                Debug & testing
                                            </div>
                                            <div class="text-xs text-gray-300">
                                                <i class="fa-solid fa-server mr-1 text-green-400"></i>
                                                Technical operations
                                            </div>
                                        @endif
                                    </div>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="space-y-1">
                                        @forelse($member->boardings->take(2) as $boarding)
                                            <div class="flex items-center text-sm">
                                                <span
                                                    class="{{ $boarding->status === 'active' ? 'bg-green-400' : 'bg-gray-400' }} mr-2 h-2 w-2 rounded-full"></span>
                                                <span
                                                    class="text-white">{{ $boarding->vessel->name ?? 'Unknown Vessel' }}</span>
                                                @if ($boarding->is_primary)
                                                    <span
                                                        class="bg-accent-primary ml-2 rounded px-2 py-0.5 text-xs text-white">Primary</span>
                                                @endif
                                            </div>
                                        @empty
                                            <span class="text-sm text-gray-500">No vessel access</span>
                                        @endforelse

                                        @if ($member->total_boardings_count > 2)
                                            <div class="text-xs text-gray-500">
                                                +{{ $member->total_boardings_count - 2 }} more
                                            </div>
                                        @endif
                                    </div>
                                </td>
                                <td class="px-6 py-4 text-sm text-gray-300">
                                    {{ $member->created_at ? $member->created_at->format('M j, Y') : 'Unknown' }}
                                </td>
                                <td class="px-6 py-4">
                                    <div class="flex items-center space-x-2">
                                        <a href="{{ route('admin.staff.show', $member) }}"
                                            class="text-accent-primary transition-colors duration-200 hover:text-blue-400"
                                            title="View Details">
                                            <i class="fa-solid fa-eye"></i>
                                        </a>
                                        <a href="{{ route('admin.staff.edit', $member) }}"
                                            class="text-accent-secondary transition-colors duration-200 hover:text-green-400"
                                            title="Edit Staff Member">
                                            <i class="fa-solid fa-edit"></i>
                                        </a>
                                        <button
                                            class="text-accent-warning transition-colors duration-200 hover:text-yellow-400"
                                            title="Manage Permissions">
                                            <i class="fa-solid fa-key"></i>
                                        </button>
                                        @if ($member->system_role !== 'superadmin')
                                            <button
                                                class="text-accent-danger transition-colors duration-200 hover:text-red-400"
                                                title="Suspend Access">
                                                <i class="fa-solid fa-ban"></i>
                                            </button>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-6 py-12 text-center">
                                    <div class="text-gray-500">
                                        <i class="fa-solid fa-users mb-4 text-4xl"></i>
                                        <p class="text-lg font-medium">No staff members found</p>
                                        <p class="text-sm">Try adjusting your filters or search terms</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        {{-- Pagination --}}
        @if ($staff->hasPages())
            <div class="flex items-center justify-between">
                <div class="text-sm text-gray-400">
                    Showing {{ $staff->firstItem() ?? 0 }} to {{ $staff->lastItem() ?? 0 }} of {{ $staff->total() }}
                    results
                </div>
                <div class="flex items-center space-x-2">
                    {{ $staff->appends(request()->query())->links() }}
                </div>
            </div>
        @endif
    </div>
@endsection
