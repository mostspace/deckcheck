@extends('v2.layouts.app')

@section('title', 'Equipment Manifest')

@section('content')

    @php
        $visibleColumns = session('visible_columns', $defaultColumns);
        $customizedColumns =
            array_diff($visibleColumns, $defaultColumns) || array_diff($defaultColumns, $visibleColumns);
    @endphp

    {{-- Enhanced Maintenance Header --}}
    @include('v2.components.nav.header-routing', [
        'activeTab' => 'manifest',
        'breadcrumbs' => [
            ['label' => 'Maintenance', 'icon' => asset('assets/media/icons/sidebar-solid-wrench-scredriver.svg')],
            ['label' => 'Manifest']
        ],
        'actions' => [
            [
                'type' => 'link',
                'label' => 'Add Equipment',
                'url' => '#', // Add route when available
                'icon' => 'fas fa-plus',
                'class' => 'bg-primary-500 text-slate-800 hover:bg-primary-600'
            ]
        ]
    ])

    <div class="px-3 py-4 sm:px-6 sm:py-6 lg:px-8">
        {{-- Header --}}
        <div class="mb-6">
            <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
                <div>
                    <h1 class="text-2xl font-semibold text-[#0f1728]">Equipment</h1>
                    <p class="text-[#475466]">Manage and track vessel equipment inventory.</p>
                </div>
            </div>
        </div>

        {{-- System Messages --}}
        @if (session('success'))
            <div class="mb-6 rounded-lg border border-green-300 bg-green-100 p-4 text-sm text-green-800">
                {{ session('success') }}
            </div>
        @endif

        {{-- Status Cards --}}
        @include('v2.components.widgets.status-cards.index')

        @include('v2.sections.crew.maintenance.manifest.equipment-table')
        @include('v2.sections.crew.maintenance.manifest.edit-columns-modal')
    </div>

    @push('scripts')
        @vite(['resources/js/pages/crew/maintenance/manifest.js'])
    @endpush

@endsection
