@extends('layouts.admin')

@section('title', 'Staff Details - ' . ($user->full_name ?? 'Unknown Staff Member'))

@section('content')
<div class="space-y-6">
    {{-- Page Header --}}
    <div class="flex items-center justify-between">
        <div class="flex items-center space-x-4">
            <a href="{{ route('admin.staff.index') }}" class="text-accent-primary hover:text-blue-400 transition-colors duration-200">
                <i class="fa-solid fa-arrow-left mr-2"></i>Back to Staff
            </a>
            <div>
                <h1 class="text-2xl font-bold text-white">Staff Details</h1>
                <p class="text-gray-400 mt-1">{{ $user->email ?? 'No email' }}</p>
            </div>
        </div>
        <div class="flex items-center space-x-3">
            <a href="{{ route('admin.staff.edit', $user) }}" class="bg-accent-secondary hover:bg-green-600 text-white px-4 py-2 rounded-md transition-colors duration-200">
                <i class="fa-solid fa-edit mr-2"></i>Edit Staff Member
            </a>
            <button class="bg-accent-warning hover:bg-yellow-600 text-white px-4 py-2 rounded-md transition-colors duration-200">
                <i class="fa-solid fa-key mr-2"></i>Manage Permissions
            </button>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        {{-- Staff Profile Card --}}
        <div class="lg:col-span-1">
            <div class="bg-dark-800 rounded-lg p-6 border border-dark-600">
                <div class="text-center">
                    <img src="{{ $user->profile_pic ?? 'https://storage.googleapis.com/uxpilot-auth.appspot.com/avatars/avatar-3.jpg' }}" 
                         alt="{{ $user->full_name ?? 'Staff Member' }}" 
                         class="w-24 h-24 rounded-full border-4 border-dark-600 mx-auto mb-4">
                    <h2 class="text-xl font-bold text-white">{{ $user->full_name ?? 'Unknown Staff Member' }}</h2>
                    
                    {{-- Role Badge --}}
                    @if($user->system_role === 'superadmin')
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-red-100 text-red-800 mt-2">
                            <i class="fa-solid fa-crown mr-2"></i>Super Administrator
                        </span>
                    @elseif($user->system_role === 'staff')
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-purple-100 text-purple-800 mt-2">
                            <i class="fa-solid fa-user-tie mr-2"></i>Staff Member
                        </span>
                    @elseif($user->system_role === 'dev')
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-green-100 text-green-800 mt-2">
                            <i class="fa-solid fa-code mr-2"></i>Developer
                        </span>
                    @endif
                </div>

                <div class="mt-6 space-y-4">
                    <div class="flex items-center justify-between">
                        <span class="text-gray-400">Email</span>
                        <span class="text-white">{{ $user->email ?? 'No email' }}</span>
                    </div>
                    
                    @if($user->phone)
                        <div class="flex items-center justify-between">
                            <span class="text-gray-400">Phone</span>
                            <span class="text-white">{{ $user->phone }}</span>
                        </div>
                    @endif

                    @if($user->date_of_birth)
                        <div class="flex items-center justify-between">
                            <span class="text-gray-400">Date of Birth</span>
                            <span class="text-white">{{ $user->date_of_birth instanceof \Carbon\Carbon ? $user->date_of_birth->format('M j, Y') : $user->date_of_birth }}</span>
                        </div>
                    @endif

                    @if($user->nationality)
                        <div class="flex items-center justify-between">
                            <span class="text-gray-400">Nationality</span>
                            <span class="text-white">{{ $user->nationality }}</span>
                        </div>
                    @endif

                    <div class="flex items-center justify-between">
                        <span class="text-gray-400">Staff Since</span>
                        <span class="text-white">{{ $user->created_at ? $user->created_at->format('M j, Y') : 'Unknown' }}</span>
                    </div>

                    @if($user->email_verified_at)
                        <div class="flex items-center justify-between">
                            <span class="text-gray-400">Email Verified</span>
                            <span class="text-green-400 text-sm">{{ $user->email_verified_at instanceof \Carbon\Carbon ? $user->email_verified_at->format('M j, Y') : $user->email_verified_at }}</span>
                        </div>
                    @endif
                </div>

                <div class="mt-6 pt-6 border-t border-dark-600">
                    <div class="grid grid-cols-2 gap-4 text-center">
                        <div>
                            <div class="text-2xl font-bold text-white">{{ $user->total_boardings_count ?? 0 }}</div>
                            <div class="text-sm text-gray-400">Total Vessels</div>
                        </div>
                        <div>
                            <div class="text-2xl font-bold text-white">{{ $user->active_boardings_count ?? 0 }}</div>
                            <div class="text-sm text-gray-400">Active Vessels</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Permissions & Access --}}
        <div class="lg:col-span-2 space-y-6">
            {{-- Permissions Scope --}}
            <div class="bg-dark-800 rounded-lg border border-dark-600">
                <div class="px-6 py-4 border-b border-dark-600">
                    <h3 class="text-lg font-semibold text-white">Permissions Scope</h3>
                </div>
                <div class="p-6">
                    @if($user->system_role === 'superadmin')
                        <div class="space-y-4">
                            <div class="flex items-start space-x-3">
                                <div class="p-2 bg-red-500/10 rounded-lg">
                                    <i class="fa-solid fa-shield-halved text-red-500"></i>
                                </div>
                                <div>
                                    <h4 class="font-medium text-white">Full System Access</h4>
                                    <p class="text-sm text-gray-400">Complete control over all system functions and data</p>
                                </div>
                            </div>
                            <div class="flex items-start space-x-3">
                                <div class="p-2 bg-red-500/10 rounded-lg">
                                    <i class="fa-solid fa-users text-red-500"></i>
                                </div>
                                <div>
                                    <h4 class="font-medium text-white">User Management</h4>
                                    <p class="text-sm text-gray-400">Create, edit, and manage all user accounts and permissions</p>
                                </div>
                            </div>
                            <div class="flex items-start space-x-3">
                                <div class="p-2 bg-red-500/10 rounded-lg">
                                    <i class="fa-solid fa-database text-red-500"></i>
                                </div>
                                <div>
                                    <h4 class="font-medium text-white">Data Management</h4>
                                    <p class="text-sm text-gray-400">Full access to all vessel data, maintenance records, and system logs</p>
                                </div>
                            </div>
                            <div class="flex items-start space-x-3">
                                <div class="p-2 bg-red-500/10 rounded-lg">
                                    <i class="fa-solid fa-cog text-red-500"></i>
                                </div>
                                <div>
                                    <h4 class="font-medium text-white">System Configuration</h4>
                                    <p class="text-sm text-gray-400">Modify system settings, security policies, and global configurations</p>
                                </div>
                            </div>
                        </div>
                    @elseif($user->system_role === 'staff')
                        <div class="space-y-4">
                            <div class="flex items-start space-x-3">
                                <div class="p-2 bg-purple-500/10 rounded-lg">
                                    <i class="fa-solid fa-ship text-purple-500"></i>
                                </div>
                                <div>
                                    <h4 class="font-medium text-white">Vessel Management</h4>
                                    <p class="text-sm text-gray-400">Full access to vessel data, crew management, and operational tools</p>
                                </div>
                            </div>
                            <div class="flex items-start space-x-3">
                                <div class="p-2 bg-purple-500/10 rounded-lg">
                                    <i class="fa-solid fa-tools text-purple-500"></i>
                                </div>
                                <div>
                                    <h4 class="font-medium text-white">Maintenance Tools</h4>
                                    <p class="text-sm text-gray-400">Access to maintenance schedules, work orders, and equipment management</p>
                                </div>
                            </div>
                            <div class="flex items-start space-x-3">
                                <div class="p-2 bg-purple-500/10 rounded-lg">
                                    <i class="fa-solid fa-chart-bar text-purple-500"></i>
                                </div>
                                <div>
                                    <h4 class="font-medium text-white">Reports & Analytics</h4>
                                    <p class="text-sm text-gray-400">Generate reports, view analytics, and access business intelligence tools</p>
                                </div>
                            </div>
                            <div class="flex items-start space-x-3">
                                <div class="p-2 bg-purple-500/10 rounded-lg">
                                    <i class="fa-solid fa-user-check text-purple-500"></i>
                                </div>
                                <div>
                                    <h4 class="font-medium text-white">Limited User Management</h4>
                                    <p class="text-sm text-gray-400">Manage regular user accounts and vessel access permissions</p>
                                </div>
                            </div>
                        </div>
                    @elseif($user->system_role === 'dev')
                        <div class="space-y-4">
                            <div class="flex items-start space-x-3">
                                <div class="p-2 bg-green-500/10 rounded-lg">
                                    <i class="fa-solid fa-code text-green-500"></i>
                                </div>
                                <div>
                                    <h4 class="font-medium text-white">System Development</h4>
                                    <p class="text-sm text-gray-400">Access to development tools, API endpoints, and system architecture</p>
                                </div>
                            </div>
                            <div class="flex items-start space-x-3">
                                <div class="p-2 bg-green-500/10 rounded-lg">
                                    <i class="fa-solid fa-bug text-green-500"></i>
                                </div>
                                <div>
                                    <h4 class="font-medium text-white">Debug & Testing</h4>
                                    <p class="text-sm text-gray-400">Access to testing environments, error logs, and debugging tools</p>
                                </div>
                            </div>
                            <div class="flex items-start space-x-3">
                                <div class="p-2 bg-green-500/10 rounded-lg">
                                    <i class="fa-solid fa-server text-green-500"></i>
                                </div>
                                <div>
                                    <h4 class="font-medium text-white">Technical Operations</h4>
                                    <p class="text-sm text-gray-400">Monitor system performance, database operations, and technical metrics</p>
                                </div>
                            </div>
                            <div class="flex items-start space-x-3">
                                <div class="p-2 bg-green-500/10 rounded-lg">
                                    <i class="fa-solid fa-database text-green-500"></i>
                                </div>
                                <div>
                                    <h4 class="font-medium text-white">Data Access</h4>
                                    <p class="text-sm text-gray-400">Read access to all system data for development and debugging purposes</p>
                                </div>
                            </div>
                        </div>
                    @endif
                </div>
            </div>

            {{-- Vessel Access --}}
            <div class="bg-dark-800 rounded-lg border border-dark-600">
                <div class="px-6 py-4 border-b border-dark-600">
                    <h3 class="text-lg font-semibold text-white">Vessel Access</h3>
                </div>
                <div class="p-6">
                    @forelse($user->boardings as $boarding)
                        <div class="flex items-center justify-between p-4 bg-dark-700 rounded-lg mb-3">
                            <div class="flex items-center space-x-4">
                                <div class="w-3 h-3 rounded-full {{ $boarding->status === 'active' ? 'bg-green-400' : 'bg-gray-400' }}"></div>
                                <div>
                                    <h4 class="font-medium text-white">{{ $boarding->vessel->name ?? 'Unknown Vessel' }}</h4>
                                    <div class="flex items-center space-x-4 text-sm text-gray-400">
                                        <span>{{ ucfirst($boarding->status) }}</span>
                                        @if($boarding->is_primary)
                                            <span class="bg-accent-primary text-white px-2 py-0.5 rounded text-xs">Primary</span>
                                        @endif
                                        @if($boarding->is_crew)
                                            <span class="bg-accent-secondary text-white px-2 py-0.5 rounded text-xs">Crew</span>
                                        @endif
                                        @if($boarding->role)
                                            <span class="bg-dark-600 text-gray-300 px-2 py-0.5 rounded text-xs">{{ $boarding->role }}</span>
                                        @endif
                                    </div>
                                </div>
                            </div>
                            <div class="text-right text-sm text-gray-400">
                                @if($boarding->joined_at && $boarding->joined_at instanceof \Carbon\Carbon)
                                    <div>Joined: {{ $boarding->joined_at->format('M j, Y') }}</div>
                                @elseif($boarding->joined_at)
                                    <div>Joined: {{ $boarding->joined_at }}</div>
                                @endif
                                @if($boarding->access_level)
                                    <div>Access: {{ ucfirst($boarding->access_level) }}</div>
                                @endif
                            </div>
                        </div>
                    @empty
                        <div class="text-center py-8 text-gray-500">
                            <i class="fa-solid fa-ship text-4xl mb-4"></i>
                            <p>No vessel access</p>
                            <p class="text-sm">This staff member doesn't have access to any vessels</p>
                        </div>
                    @endforelse
                </div>
            </div>

            {{-- Recent Activity --}}
            <div class="bg-dark-800 rounded-lg border border-dark-600">
                <div class="px-6 py-4 border-b border-dark-600">
                    <h3 class="text-lg font-semibold text-white">Recent Activity</h3>
                </div>
                <div class="p-6">
                    <div class="text-center py-8 text-gray-500">
                        <i class="fa-solid fa-chart-line text-4xl mb-4"></i>
                        <p>Activity tracking coming soon</p>
                        <p class="text-sm">This will show recent logins, actions, and system usage for staff members</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
