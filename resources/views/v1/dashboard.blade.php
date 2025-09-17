@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
    @include('components.dash.announcement')
    
    <!-- V2 Preview Link -->
    <div class="mb-6 bg-gradient-to-r from-blue-500 to-purple-600 border border-blue-500 text-white px-4 py-3 rounded relative" role="alert">
        <div class="flex items-center justify-between">
            <div>
                <h3 class="font-semibold">🚀 New v2 Interface Available!</h3>
                <p class="text-sm opacity-90">Experience the modern sidebar and improved design</p>
            </div>
            <a href="{{ route('dashboard') }}" class="bg-white text-blue-600 px-4 py-2 rounded font-medium hover:bg-gray-100 transition-colors">
                Try v2 Dashboard
            </a>
        </div>
    </div>

    <!-- Flash Messages -->
    @if(session('success'))
        <div class="mb-6 bg-green-600 border border-green-500 text-white px-4 py-3 rounded relative" role="alert">
            <span class="block sm:inline">{{ session('success') }}</span>
            <button type="button" class="absolute top-0 bottom-0 right-0 px-4 py-3" onclick="this.parentElement.remove()">
                <i class="fa-solid fa-times"></i>
            </button>
        </div>
    @endif

    @if(session('info'))
        <div class="mb-6 bg-blue-600 border border-blue-500 text-white px-4 py-3 rounded relative" role="alert">
            <span class="block sm:inline">{{ session('info') }}</span>
            <button type="button" class="absolute top-0 bottom-0 right-0 px-4 py-3" onclick="this.parentElement.remove()">
                <i class="fa-solid fa-times"></i>
            </button>
        </div>
    @endif

    @if(session('error'))
        <div class="mb-6 bg-red-600 border border-red-500 text-white px-4 py-3 rounded relative" role="alert">
            <span class="block sm:inline">{{ session('error') }}</span>
            <button type="button" class="absolute top-0 bottom-0 right-0 px-4 py-3" onclick="this.parentElement.remove()">
                <i class="fa-solid fa-times"></i>
            </button>
        </div>
    @endif

    <!-- Vessel Indicator for System Users -->
    @if(auth()->user() && in_array(auth()->user()->system_role, ['superadmin', 'staff', 'dev']) && currentVessel())
        <div class="mb-6 bg-blue-50 border border-blue-200 rounded-lg p-4">
            <div class="flex items-center justify-between">
                <div class="flex items-center gap-3">
                    <i class="fa-solid fa-ship text-blue-600"></i>
                    <div>
                        <p class="text-sm font-medium text-blue-900">Currently Viewing</p>
                        <p class="text-lg font-semibold text-blue-800">{{ currentVessel()->type ?? 'M/Y' }} {{ currentVessel()->name }}</p>
                    </div>
                </div>
                <div class="flex items-center gap-2">
                    <a href="{{ route('admin.vessels.index') }}" class="text-sm text-blue-600 hover:text-blue-800 font-medium">
                        <i class="fa-solid fa-arrow-left mr-1"></i>
                        Back to Admin
                    </a>
                    <a href="{{ route('vessel.index') }}" class="text-sm text-blue-600 hover:text-blue-800 font-medium">
                        <i class="fa-solid fa-ship mr-1"></i>
                        Vessel Details
                    </a>
                </div>
            </div>
        </div>
    @elseif(auth()->user() && in_array(auth()->user()->system_role, ['superadmin', 'staff', 'dev']) && !currentVessel())
        <div class="mb-6 bg-yellow-50 border border-yellow-200 rounded-lg p-4">
            <div class="flex items-center gap-3">
                <i class="fa-solid fa-exclamation-triangle text-yellow-600"></i>
                <div>
                    <p class="text-sm font-medium text-yellow-900">No Vessel Selected</p>
                    <p class="text-yellow-800">Use the user modal in the sidebar or visit the admin vessel list to select a vessel to view.</p>
                </div>
            </div>
        </div>
    @endif

    <div class="mb-6">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <h1 class="text-2xl font-semibold text-[#0f1728]">Dashboard</h1>
                @if(currentVessel())
                    <p class="text-[#475466]">{{ currentVessel()->type ?? 'M/Y' }} {{ currentVessel()->name }} - Maintenance Overview</p>
                @else
                    <p class="text-[#475466]">Maintenance Overview</p>
                @endif
            </div>

            @include('components.dash.quick-actions')
        </div>
    </div>

    @include('components.dash.dashboard-grid')
@endsection