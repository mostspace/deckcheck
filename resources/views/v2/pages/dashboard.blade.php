@extends('v2.layouts.app')

@section('title', 'Dashboard')

@section('content')
    @include('v2.components.nav.header', [
        'tabs' => [],
        'showSubHeader' => false
    ])

    <div class="bg-white px-3 py-4 sm:px-6 sm:py-6 lg:px-8">
        @include('components.dash.announcement')

        <!-- V2 Preview Link -->
        <div class="relative mb-6 rounded border border-blue-500 bg-gradient-to-r from-blue-500 to-purple-600 px-4 py-3 text-white"
            role="alert">
            <div class="flex items-center justify-between">
                <div>
                    <h3 class="font-semibold">🚀 New v2 Interface Available!</h3>
                    <p class="text-sm opacity-90">Experience the modern sidebar and improved design</p>
                </div>
                <a href="{{ route('dashboard') }}"
                    class="rounded bg-primary-500 px-4 py-2 font-medium text-slate-800 transition-colors hover:bg-primary-600">
                    Try v2 Dashboard
                </a>
            </div>
        </div>

        <!-- Flash Messages -->
        @if (session('success'))
            <div class="relative mb-6 rounded border border-green-500 bg-green-600 px-4 py-3 text-white" role="alert">
                <span class="block sm:inline">{{ session('success') }}</span>
                <button type="button" class="absolute bottom-0 right-0 top-0 px-4 py-3"
                    onclick="this.parentElement.remove()">
                    <i class="fa-solid fa-times"></i>
                </button>
            </div>
        @endif

        @if (session('info'))
            <div class="relative mb-6 rounded border border-blue-500 bg-blue-600 px-4 py-3 text-white" role="alert">
                <span class="block sm:inline">{{ session('info') }}</span>
                <button type="button" class="absolute bottom-0 right-0 top-0 px-4 py-3"
                    onclick="this.parentElement.remove()">
                    <i class="fa-solid fa-times"></i>
                </button>
            </div>
        @endif

        @if (session('error'))
            <div class="relative mb-6 rounded border border-red-500 bg-red-600 px-4 py-3 text-white" role="alert">
                <span class="block sm:inline">{{ session('error') }}</span>
                <button type="button" class="absolute bottom-0 right-0 top-0 px-4 py-3"
                    onclick="this.parentElement.remove()">
                    <i class="fa-solid fa-times"></i>
                </button>
            </div>
        @endif

        <!-- Vessel Indicator for System Users -->
        @if (auth()->user() && in_array(auth()->user()->system_role, ['superadmin', 'staff', 'dev']) && currentVessel())
            <div class="mb-6 rounded-lg border border-blue-200 bg-blue-50 p-4">
                <div class="flex items-center justify-between">
                    <div class="flex items-center gap-3">
                        <i class="fa-solid fa-ship text-blue-600"></i>
                        <div>
                            <p class="text-sm font-medium text-blue-900">Currently Viewing</p>
                            <p class="text-lg font-semibold text-blue-800">{{ currentVessel()->type ?? 'M/Y' }}
                                {{ currentVessel()->name }}</p>
                        </div>
                    </div>
                    <div class="flex items-center gap-2">
                        <a href="{{ route('admin.vessels.index') }}"
                            class="text-sm font-medium text-blue-600 hover:text-blue-800">
                            <i class="fa-solid fa-arrow-left mr-1"></i>
                            Back to Admin
                        </a>
                        <a href="{{ route('vessel.index') }}" class="text-sm font-medium text-blue-600 hover:text-blue-800">
                            <i class="fa-solid fa-ship mr-1"></i>
                            Vessel Details
                        </a>
                    </div>
                </div>
            </div>
        @elseif(auth()->user() && in_array(auth()->user()->system_role, ['superadmin', 'staff', 'dev']) && !currentVessel())
            <div class="mb-6 rounded-lg border border-yellow-200 bg-yellow-50 p-4">
                <div class="flex items-center gap-3">
                    <i class="fa-solid fa-exclamation-triangle text-yellow-600"></i>
                    <div>
                        <p class="text-sm font-medium text-yellow-900">No Vessel Selected</p>
                        <p class="text-yellow-800">Use the user modal in the sidebar or visit the admin vessel list to
                            select a vessel to view.</p>
                    </div>
                </div>
            </div>
        @endif

        <div class="mb-6">
            <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
                <div>
                    <h1 class="text-2xl font-semibold text-[#0f1728]">Dashboard</h1>
                    @if (currentVessel())
                        <p class="text-[#475466]">{{ currentVessel()->type ?? 'M/Y' }} {{ currentVessel()->name }} -
                            Maintenance Overview</p>
                    @else
                        <p class="text-[#475466]">Maintenance Overview</p>
                    @endif
                </div>

                @include('components.dash.quick-actions')
            </div>
        </div>

        @include('components.dash.dashboard-grid')
    </div>
@endsection
