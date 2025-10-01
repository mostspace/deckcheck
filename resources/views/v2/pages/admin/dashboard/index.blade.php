@extends('v2.layouts.admin')

@section('title', 'Admin Dashboard')

@section('page-title', 'System Administration')
@section('page-description', 'Manage users, vessels, and system settings')

@section('page-actions')
    <x-v2-ui-button variant="primary" icon="fas fa-plus" href="{{ route('admin.vessels.create') }}">
        Add Vessel
    </x-v2-ui-button>
@endsection

@section('admin-tabs')
    <a href="{{ route('admin.dashboard') }}"
        class="whitespace-nowrap border-b-2 border-blue-500 px-1 py-2 text-sm font-medium text-blue-600">
        Overview
    </a>
    <a href="{{ route('admin.users.index') }}"
        class="whitespace-nowrap border-b-2 border-transparent px-1 py-2 text-sm font-medium text-gray-500 hover:border-gray-300 hover:text-gray-700">
        Users
    </a>
    <a href="{{ route('admin.staff.index') }}"
        class="whitespace-nowrap border-b-2 border-transparent px-1 py-2 text-sm font-medium text-gray-500 hover:border-gray-300 hover:text-gray-700">
        Staff
    </a>
    <a href="{{ route('admin.vessels.index') }}"
        class="whitespace-nowrap border-b-2 border-transparent px-1 py-2 text-sm font-medium text-gray-500 hover:border-gray-300 hover:text-gray-700">
        Vessels
    </a>
@endsection

@section('admin-content')
    <!-- System Statistics -->
    <div class="grid grid-cols-1 gap-5 sm:grid-cols-2 lg:grid-cols-4">
        <x-v2-ui-card>
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <i class="fas fa-users text-2xl text-blue-600"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-500">Total Users</p>
                    <p class="text-2xl font-semibold text-gray-900">{{ $totalUsers ?? 0 }}</p>
                </div>
            </div>
        </x-v2-ui-card>

        <x-v2-ui-card>
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <i class="fas fa-ship text-2xl text-green-600"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-500">Active Vessels</p>
                    <p class="text-2xl font-semibold text-gray-900">{{ $activeVessels ?? 0 }}</p>
                </div>
            </div>
        </x-v2-ui-card>

        <x-v2-ui-card>
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <i class="fas fa-user-tie text-2xl text-purple-600"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-500">Staff Members</p>
                    <p class="text-2xl font-semibold text-gray-900">{{ $staffMembers ?? 0 }}</p>
                </div>
            </div>
        </x-v2-ui-card>

        <x-v2-ui-card>
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <i class="fas fa-chart-line text-2xl text-yellow-600"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-500">System Health</p>
                    <p class="text-2xl font-semibold text-gray-900">98%</p>
                </div>
            </div>
        </x-v2-ui-card>
    </div>

    <!-- Recent Activity -->
    <div class="grid grid-cols-1 gap-6 lg:grid-cols-2">
        <x-v2-ui-card>
            <x-slot name="header">
                <h3 class="text-lg font-medium text-gray-900">Recent Users</h3>
            </x-slot>

            <div class="space-y-4">
                @forelse($recentUsers ?? [] as $user)
                    <div class="flex items-center space-x-3">
                        <img class="h-8 w-8 rounded-full bg-gray-50"
                            src="{{ $user->profile_pic ? Storage::url($user->profile_pic) : asset('images/placeholders/user.png') }}"
                            alt="{{ $user->first_name }}">
                        <div class="min-w-0 flex-1">
                            <p class="text-sm font-medium text-gray-900">{{ $user->first_name }} {{ $user->last_name }}
                            </p>
                            <p class="text-sm text-gray-500">{{ $user->email }}</p>
                        </div>
                        <div class="text-sm text-gray-500">
                            {{ $user->created_at->diffForHumans() }}
                        </div>
                    </div>
                @empty
                    <p class="py-4 text-center text-gray-500">No recent users</p>
                @endforelse
            </div>
        </x-v2-ui-card>

        <x-v2-ui-card>
            <x-slot name="header">
                <h3 class="text-lg font-medium text-gray-900">System Alerts</h3>
            </x-slot>

            <div class="space-y-4">
                <div class="flex items-start space-x-3">
                    <i class="fas fa-exclamation-triangle mt-1 text-yellow-500"></i>
                    <div>
                        <p class="text-sm font-medium text-gray-900">High CPU Usage</p>
                        <p class="text-sm text-gray-500">Server performance may be affected</p>
                    </div>
                </div>

                <div class="flex items-start space-x-3">
                    <i class="fas fa-info-circle mt-1 text-blue-500"></i>
                    <div>
                        <p class="text-sm font-medium text-gray-900">Database Backup</p>
                        <p class="text-sm text-gray-500">Scheduled backup completed successfully</p>
                    </div>
                </div>

                <div class="flex items-start space-x-3">
                    <i class="fas fa-check-circle mt-1 text-green-500"></i>
                    <div>
                        <p class="text-sm font-medium text-gray-900">SSL Certificate</p>
                        <p class="text-sm text-gray-500">Certificate renewed and active</p>
                    </div>
                </div>
            </div>
        </x-v2-ui-card>
    </div>
@endsection
