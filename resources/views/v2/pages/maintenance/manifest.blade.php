@extends('v2.layouts.app')

@section('title', 'Equipment Manifest')

@section('content')

    @php
        $visibleColumns = session('visible_columns', $defaultColumns);
        $customizedColumns = array_diff($visibleColumns, $defaultColumns) || array_diff($defaultColumns, $visibleColumns);
    @endphp

    {{-- Enhanced Maintenance Header --}}
    @include('v2.components.maintenance.header', [
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

    <div class="px-3 sm:px-6 lg:px-8 py-4 sm:py-6">
        {{-- Header --}}
        <div class="mb-6">
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                <div>
                    <h1 class="text-2xl font-semibold text-[#0f1728]">Equipment</h1>
                    <p class="text-[#475466]">Manage and track vessel equipment inventory.</p>
                </div>
            </div>
        </div>

        {{-- System Messages --}}
        @if (session('success'))
            <div class="mb-6 p-4 bg-green-100 border border-green-300 text-green-800 rounded-lg text-sm">
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
