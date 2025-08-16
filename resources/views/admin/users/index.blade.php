@extends('layouts.admin')

@section('title', 'User Management')

@section('content')
<div class="space-y-6">
    {{-- Page Header --}}
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-white">User Management</h1>
            <p class="text-gray-400 mt-1">Manage regular users across all vessels</p>
        </div>
        <div class="flex items-center space-x-3">
            <button class="bg-accent-primary hover:bg-blue-600 text-white px-4 py-2 rounded-md transition-colors duration-200">
                <i class="fa-solid fa-plus mr-2"></i>Add User
            </button>
            <button class="bg-dark-700 hover:bg-dark-600 text-white px-4 py-2 rounded-md transition-colors duration-200">
                <i class="fa-solid fa-download mr-2"></i>Export
            </button>
        </div>
    </div>

    {{-- Statistics Cards --}}
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
        <div class="bg-dark-800 rounded-lg p-6 border border-dark-600">
            <div class="flex items-center">
                <div class="p-2 bg-blue-500/10 rounded-lg">
                    <i class="fa-solid fa-users text-blue-500 text-xl"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-400">Total Regular Users</p>
                    <p class="text-2xl font-bold text-white">{{ number_format($stats['total_users']) }}</p>
                </div>
            </div>
        </div>

        <div class="bg-dark-800 rounded-lg p-6 border border-dark-600">
            <div class="flex items-center">
                <div class="p-2 bg-green-500/10 rounded-lg">
                    <i class="fa-solid fa-user-check text-green-500 text-xl"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-400">Active Regular Users</p>
                    <p class="text-2xl font-bold text-white">{{ number_format($stats['active_users']) }}</p>
                </div>
            </div>
        </div>

        <div class="bg-dark-800 rounded-lg p-6 border border-dark-600">
            <div class="flex items-center">
                <div class="p-2 bg-purple-500/10 rounded-lg">
                    <i class="fa-solid fa-crown text-purple-500 text-xl"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-400">Primary Vessel Users</p>
                    <p class="text-2xl font-bold text-white">{{ number_format($stats['primary_users']) }}</p>
                </div>
            </div>
        </div>

        <div class="bg-dark-800 rounded-lg p-6 border border-dark-600">
            <div class="flex items-center">
                <div class="p-2 bg-orange-500/10 rounded-lg">
                    <i class="fa-solid fa-user-tie text-orange-500 text-xl"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-400">Crew Members</p>
                    <p class="text-2xl font-bold text-white">{{ number_format($stats['crew_users']) }}</p>
                </div>
            </div>
        </div>
    </div>

    {{-- Filters --}}
    <div class="bg-dark-800 rounded-lg p-6 border border-dark-600">
        <form method="GET" action="{{ route('admin.users.index') }}" class="space-y-4">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                {{-- Search --}}
                <div>
                    <label class="block text-sm font-medium text-gray-300 mb-2">Search</label>
                    <input type="text" name="search" value="{{ request('search') }}" 
                           placeholder="Name or email..." 
                           class="w-full bg-dark-700 border border-dark-600 text-white rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-accent-primary">
                </div>

                {{-- Status Filter --}}
                <div>
                    <label class="block text-sm font-medium text-gray-300 mb-2">Status</label>
                    <select name="status" class="w-full bg-dark-700 border border-dark-600 text-white rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-accent-primary">
                        @foreach($statusOptions as $value => $label)
                            <option value="{{ $value }}" {{ request('status') == $value ? 'selected' : '' }}>
                                {{ $label }}
                            </option>
                        @endforeach
                    </select>
                </div>

                {{-- Vessel Filter --}}
                <div>
                    <label class="block text-sm font-medium text-gray-300 mb-2">Vessel</label>
                    <select name="vessel" class="w-full bg-dark-700 border border-dark-600 text-white rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-accent-primary">
                        <option value="">All Vessels</option>
                        @foreach($vessels as $vessel)
                            <option value="{{ $vessel->id }}" {{ request('vessel') == $vessel->id ? 'selected' : '' }}>
                                {{ $vessel->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

            </div>

            <div class="flex items-center justify-between">
                <button type="submit" class="bg-accent-primary hover:bg-blue-600 text-white px-6 py-2 rounded-md transition-colors duration-200">
                    <i class="fa-solid fa-filter mr-2"></i>Apply Filters
                </button>
                
                @if(request()->hasAny(['search', 'status', 'vessel']))
                    <a href="{{ route('admin.users.index') }}" class="text-gray-400 hover:text-white transition-colors duration-200">
                        <i class="fa-solid fa-times mr-2"></i>Clear Filters
                    </a>
                @endif
            </div>
        </form>
    </div>

    {{-- Results Summary --}}
    <div class="flex items-center justify-between text-sm text-gray-400">
        <div>
            Showing {{ $users->firstItem() ?? 0 }} to {{ $users->lastItem() ?? 0 }} of {{ $users->total() }} regular users
        </div>
        <div>
                            @if(request()->hasAny(['search', 'status', 'vessel']))
                    <span class="bg-accent-primary text-white px-2 py-1 rounded text-xs">
                        {{ $users->total() }} results
                    </span>
                @endif
        </div>
    </div>

    {{-- Users Table --}}
    <div class="bg-dark-800 rounded-lg border border-dark-600 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-dark-700 border-b border-dark-600">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-300 uppercase tracking-wider">User</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-300 uppercase tracking-wider">Status</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-300 uppercase tracking-wider">Vessels</th>

                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-300 uppercase tracking-wider">Joined</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-300 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-dark-600">
                    @forelse($users as $user)
                        <tr class="hover:bg-dark-700 transition-colors duration-150">
                            <td class="px-6 py-4">
                                <div class="flex items-center">
                                    <img src="{{ $user->profile_pic ?? 'https://storage.googleapis.com/uxpilot-auth.appspot.com/avatars/avatar-3.jpg' }}" 
                                         alt="{{ $user->full_name ?? 'User' }}" 
                                         class="w-10 h-10 rounded-full border-2 border-dark-600">
                                    <div class="ml-4">
                                        <div class="text-sm font-medium text-white">{{ $user->full_name ?? 'Unknown User' }}</div>
                                        <div class="text-sm text-gray-400">{{ $user->email ?? 'No email' }}</div>
                                        @if($user->phone)
                                            <div class="text-xs text-gray-500">{{ $user->phone }}</div>
                                        @endif
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                @if($user->active_boardings_count > 0)
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                        <i class="fa-solid fa-circle text-green-400 mr-1"></i>Active
                                    </span>
                                @else
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                                        <i class="fa-solid fa-circle text-gray-400 mr-1"></i>Inactive
                                    </span>
                                @endif
                            </td>
                            <td class="px-6 py-4">
                                <div class="space-y-1">
                                    @forelse($user->boardings->take(3) as $boarding)
                                        <div class="flex items-center text-sm">
                                            <span class="w-2 h-2 rounded-full {{ $boarding->status === 'active' ? 'bg-green-400' : 'bg-gray-400' }} mr-2"></span>
                                            <span class="text-white">{{ $boarding->vessel->name ?? 'Unknown Vessel' }}</span>
                                            @if($boarding->is_primary)
                                                <span class="ml-2 text-xs bg-accent-primary text-white px-2 py-0.5 rounded">Primary</span>
                                            @endif
                                        </div>
                                    @empty
                                        <span class="text-gray-500 text-sm">No vessels</span>
                                    @endforelse
                                    
                                    @if($user->total_boardings_count > 3)
                                        <div class="text-xs text-gray-500">
                                            +{{ $user->total_boardings_count - 3 }} more
                                        </div>
                                    @endif
                                </div>
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-300">
                                {{ $user->created_at ? $user->created_at->format('M j, Y') : 'Unknown' }}
                            </td>
                            <td class="px-6 py-4">
                                <div class="flex items-center space-x-2">
                                    <a href="{{ route('admin.users.show', $user) }}" class="text-accent-primary hover:text-blue-400 transition-colors duration-200" title="View Details">
                                        <i class="fa-solid fa-eye"></i>
                                    </a>
                                    <button class="text-accent-secondary hover:text-green-400 transition-colors duration-200" title="Edit User">
                                        <i class="fa-solid fa-edit"></i>
                                    </button>
                                    <button class="text-accent-warning hover:text-yellow-400 transition-colors duration-200" title="Manage Access">
                                        <i class="fa-solid fa-key"></i>
                                    </button>
                                    @if($user->system_role !== 'admin')
                                        <button class="text-accent-danger hover:text-red-400 transition-colors duration-200" title="Suspend User">
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
                                    <i class="fa-solid fa-users text-4xl mb-4"></i>
                                    <p class="text-lg font-medium">No users found</p>
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
    @if($users->hasPages())
        <div class="flex items-center justify-between">
            <div class="text-sm text-gray-400">
                Showing {{ $users->firstItem() ?? 0 }} to {{ $users->lastItem() ?? 0 }} of {{ $users->total() }} results
            </div>
            <div class="flex items-center space-x-2">
                {{ $users->appends(request()->query())->links() }}
            </div>
        </div>
    @endif
</div>
@endsection
