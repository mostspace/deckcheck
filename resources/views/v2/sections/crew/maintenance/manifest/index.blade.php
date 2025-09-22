@php
    $visibleColumns = session('visible_columns', $defaultColumns);
@endphp

@php
    $visibleColumns = session('visible_columns', $defaultColumns);
    $customizedColumns = array_diff($visibleColumns, $defaultColumns) || array_diff($defaultColumns, $visibleColumns);
@endphp

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
@include ('v2.components.widgets.status-cards.index')

@include('v2.sections.crew.maintenance.manifest.equipment-table')

@include('v2.sections.crew.maintenance.manifest.edit-columns-modal')

@push('scripts')
    @vite(['resources/js/pages/crew/maintenance/manifest.js'])
@endpush