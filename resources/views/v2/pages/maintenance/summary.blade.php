@extends('v2.layouts.app')

@section('title', 'Maintenance Summary')

@section('content')

    {{-- Enhanced Maintenance Header --}}
    @include('v2.components.nav.header-routing', [
        'activeTab' => 'summary',
        'breadcrumbs' => [
            ['label' => 'Maintenance', 'icon' => asset('assets/media/icons/sidebar-solid-wrench-scredriver.svg')],
            ['label' => 'Summary']
        ]
    ])

    <div class="px-3 py-4 sm:px-6 sm:py-6 lg:px-8">
        {{-- Flash Messages --}}
        @if (session('success'))
            <div class="mb-6 rounded-lg border border-green-300 bg-green-100 p-4 text-sm text-green-800">
                {{ session('success') }}
            </div>
        @endif

        @if (session('error'))
            <div class="mb-6 rounded-lg border border-red-300 bg-red-100 p-4 text-sm text-red-800">
                {{ session('error') }}
            </div>
        @endif

        {{-- Main Content --}}
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
