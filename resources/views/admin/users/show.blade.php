@extends('layouts.admin')

@section('title', 'User Details - ' . ($user->full_name ?? 'Unknown User'))

@section('content')
    <div class="space-y-6">
        {{-- Page Header --}}
        <div class="flex items-center justify-between">
            <div class="flex items-center space-x-4">
                <a href="{{ route('admin.users.index') }}"
                    class="text-accent-primary transition-colors duration-200 hover:text-blue-400">
                    <i class="fa-solid fa-arrow-left mr-2"></i>Back to Users
                </a>
                <div>
                    <h1 class="text-2xl font-bold text-white">User Details</h1>
                    <p class="mt-1 text-gray-400">{{ $user->email ?? 'No email' }}</p>
                </div>
            </div>
            <div class="flex items-center space-x-3">
                <button
                    class="bg-accent-secondary rounded-md px-4 py-2 text-white transition-colors duration-200 hover:bg-green-600">
                    <i class="fa-solid fa-edit mr-2"></i>Edit User
                </button>
                <button
                    class="bg-accent-warning rounded-md px-4 py-2 text-white transition-colors duration-200 hover:bg-yellow-600">
                    <i class="fa-solid fa-key mr-2"></i>Manage Access
                </button>
            </div>
        </div>

        <div class="grid grid-cols-1 gap-6 lg:grid-cols-3">
            {{-- User Profile Card --}}
            <div class="lg:col-span-1">
                <div class="bg-dark-800 border-dark-600 rounded-lg border p-6">
                    <div class="text-center">
                        <img src="{{ $user->profile_pic ?? 'https://storage.googleapis.com/uxpilot-auth.appspot.com/avatars/avatar-3.jpg' }}"
                            alt="{{ $user->full_name ?? 'User' }}"
                            class="border-dark-600 mx-auto mb-4 h-24 w-24 rounded-full border-4">
                        <h2 class="text-xl font-bold text-white">{{ $user->full_name ?? 'Unknown User' }}</h2>
                        <p class="text-gray-400">{{ $user->system_role ? ucfirst($user->system_role) : 'No role assigned' }}
                        </p>
                    </div>

                    <div class="mt-6 space-y-4">
                        <div class="flex items-center justify-between">
                            <span class="text-gray-400">Email</span>
                            <span class="text-white">{{ $user->email ?? 'No email' }}</span>
                        </div>

                        @if ($user->phone)
                            <div class="flex items-center justify-between">
                                <span class="text-gray-400">Phone</span>
                                <span class="text-white">{{ $user->phone }}</span>
                            </div>
                        @endif

                        @if ($user->date_of_birth)
                            <div class="flex items-center justify-between">
                                <span class="text-gray-400">Date of Birth</span>
                                <span
                                    class="text-white">{{ $user->date_of_birth instanceof \Carbon\Carbon ? $user->date_of_birth->format('M j, Y') : $user->date_of_birth }}</span>
                            </div>
                        @endif

                        @if ($user->nationality)
                            <div class="flex items-center justify-between">
                                <span class="text-gray-400">Nationality</span>
                                <span class="text-white">{{ $user->nationality }}</span>
                            </div>
                        @endif

                        <div class="flex items-center justify-between">
                            <span class="text-gray-400">Member Since</span>
                            <span
                                class="text-white">{{ $user->created_at ? $user->created_at->format('M j, Y') : 'Unknown' }}</span>
                        </div>

                        @if ($user->email_verified_at)
                            <div class="flex items-center justify-between">
                                <span class="text-gray-400">Email Verified</span>
                                <span
                                    class="text-sm text-green-400">{{ $user->email_verified_at instanceof \Carbon\Carbon ? $user->email_verified_at->format('M j, Y') : $user->email_verified_at }}</span>
                            </div>
                        @endif
                    </div>

                    <div class="border-dark-600 mt-6 border-t pt-6">
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

            {{-- Vessel Access & Activity --}}
            <div class="space-y-6 lg:col-span-2">
                {{-- Vessel Access --}}
                <div class="bg-dark-800 border-dark-600 rounded-lg border">
                    <div class="border-dark-600 border-b px-6 py-4">
                        <h3 class="text-lg font-semibold text-white">Vessel Access</h3>
                    </div>
                    <div class="p-6">
                        @forelse($user->boardings as $boarding)
                            <div class="bg-dark-700 mb-3 flex items-center justify-between rounded-lg p-4">
                                <div class="flex items-center space-x-4">
                                    <div
                                        class="{{ $boarding->status === 'active' ? 'bg-green-400' : 'bg-gray-400' }} h-3 w-3 rounded-full">
                                    </div>
                                    <div>
                                        <h4 class="font-medium text-white">
                                            {{ $boarding->vessel->name ?? 'Unknown Vessel' }}</h4>
                                        <div class="flex items-center space-x-4 text-sm text-gray-400">
                                            <span>{{ ucfirst($boarding->status) }}</span>
                                            @if ($boarding->is_primary)
                                                <span
                                                    class="bg-accent-primary rounded px-2 py-0.5 text-xs text-white">Primary</span>
                                            @endif
                                            @if ($boarding->is_crew)
                                                <span
                                                    class="bg-accent-secondary rounded px-2 py-0.5 text-xs text-white">Crew</span>
                                            @endif
                                            @if ($boarding->role)
                                                <span
                                                    class="bg-dark-600 rounded px-2 py-0.5 text-xs text-gray-300">{{ $boarding->role }}</span>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                                <div class="text-right text-sm text-gray-400">
                                    @if ($boarding->joined_at && $boarding->joined_at instanceof \Carbon\Carbon)
                                        <div>Joined: {{ $boarding->joined_at->format('M j, Y') }}</div>
                                    @elseif($boarding->joined_at)
                                        <div>Joined: {{ $boarding->joined_at }}</div>
                                    @endif
                                    @if ($boarding->access_level)
                                        <div>Access: {{ ucfirst($boarding->access_level) }}</div>
                                    @endif
                                </div>
                            </div>
                        @empty
                            <div class="py-8 text-center text-gray-500">
                                <i class="fa-solid fa-ship mb-4 text-4xl"></i>
                                <p>No vessel access</p>
                            </div>
                        @endforelse
                    </div>
                </div>

                {{-- Recent Activity --}}
                <div class="bg-dark-800 border-dark-600 rounded-lg border">
                    <div class="border-dark-600 border-b px-6 py-4">
                        <h3 class="text-lg font-semibold text-white">Recent Activity</h3>
                    </div>
                    <div class="p-6">
                        <div class="py-8 text-center text-gray-500">
                            <i class="fa-solid fa-chart-line mb-4 text-4xl"></i>
                            <p>Activity tracking coming soon</p>
                            <p class="text-sm">This will show recent logins, actions, and system usage</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
