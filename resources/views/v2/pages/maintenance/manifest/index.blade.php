@extends('v2.layouts.app')

@section('title', 'Equipment Manifest')

@section('content')

    @include('v2.components.nav.header-routing', [
        'activeTab' => 'manifest',
        'context' => 'maintenance',
        'breadcrumbs' => [
            ['label' => 'Maintenance', 'icon' => asset('assets/media/icons/sidebar-solid-wrench-scredriver.svg')],
            ['label' => 'Manifest']
        ]
    ])

    <div class="px-3 py-4 sm:px-6 sm:py-6 lg:px-8">

        @php
            $visibleColumns = session('visible_columns', $defaultColumns);
        @endphp

        @php
            $visibleColumns = session('visible_columns', $defaultColumns);
            $customizedColumns =
                array_diff($visibleColumns, $defaultColumns) || array_diff($defaultColumns, $visibleColumns);
        @endphp

        {{-- Header --}}
        <div class="mb-6">
            <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
                <div>
                    <h1 class="text-2xl font-semibold text-[#0f1728]">Equipment Manifest</h1>
                    <p class="text-[#475466]">Manage and track vessel equipment for maintenance scheduling.</p>
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
        <div class="mb-6 grid grid-cols-1 gap-6 md:grid-cols-3">
            @include ('components.equipment.operational-card')
            @include ('components.equipment.action-needed-card')
            @include ('components.equipment.out-of-service-card')
        </div>

        {{-- Table Controls --}}
        <div class="mb-6 flex flex-col items-start justify-between gap-4 md:flex-row md:items-center">
            <div class="flex w-full flex-col gap-3 sm:flex-row md:w-auto">

                {{-- Search --}}
                <div class="relative">
                    <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3">
                        <i class="text-[#667084]" data-fa-i2svg=""><svg class="svg-inline--fa fa-magnifying-glass"
                                aria-hidden="true" focusable="false" data-prefix="fas" data-icon="magnifying-glass"
                                role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512" data-fa-i2svg="">
                                <path fill="currentColor"
                                    d="M416 208c0 45.9-14.9 88.3-40 122.7L502.6 457.4c12.5 12.5 12.5 32.8 0 45.3s-32.8 12.5-45.3 0L330.7 376c-34.4 25.2-76.8 40-122.7 40C93.1 416 0 322.9 0 208S93.1 0 208 0S416 93.1 416 208zM208 352a144 144 0 1 0 0-288 144 144 0 1 0 0 288z">
                                </path>
                            </svg></i>
                    </div>
                    <input id="equipment-search" type="text" placeholder="Search equipment by name..."
                        class="w-full rounded-lg border border-[#cfd4dc] bg-white py-2.5 pl-10 pr-4 text-sm focus:border-transparent focus:outline-none focus:ring-2 focus:ring-[#6840c6] sm:w-[300px]">
                </div>

                <div class="flex gap-2">

                    {{-- Filters --}}
                    <button
                        class="flex items-center gap-2 rounded-lg border border-[#cfd4dc] bg-white px-4 py-2.5 text-sm text-[#344053]">
                        <i data-fa-i2svg=""><svg class="svg-inline--fa fa-filter" aria-hidden="true" focusable="false"
                                data-prefix="fas" data-icon="filter" role="img" xmlns="http://www.w3.org/2000/svg"
                                viewBox="0 0 512 512" data-fa-i2svg="">
                                <path fill="currentColor"
                                    d="M3.9 54.9C10.5 40.9 24.5 32 40 32H472c15.5 0 29.5 8.9 36.1 22.9s4.6 30.5-5.2 42.5L320 320.9V448c0 12.1-6.8 23.2-17.7 28.6s-23.8 4.3-33.5-3l-64-48c-8.1-6-12.8-15.5-12.8-25.6V320.9L9 97.3C-.7 85.4-2.8 68.8 3.9 54.9z">
                                </path>
                            </svg></i>
                        Filters
                    </button>

                    {{-- Table Controls --}}
                    <button
                        class="{{ $customizedColumns ? 'bg-[#f3e8ff] border-[#c084fc] text-[#7e22ce]' : 'bg-white border-[#cfd4dc] text-[#344053]' }} flex items-center gap-2 rounded-lg border px-4 py-2.5 text-sm">
                        <i class="fa-solid fa-table-columns"></i>
                        Edit Columns
                    </button>

                    {{-- Export --}}
                    <button
                        class="flex items-center gap-2 rounded-lg border border-[#cfd4dc] bg-white px-4 py-2.5 text-sm text-[#344053]">
                        <i data-fa-i2svg=""><svg class="svg-inline--fa fa-download" aria-hidden="true" focusable="false"
                                data-prefix="fas" data-icon="download" role="img" xmlns="http://www.w3.org/2000/svg"
                                viewBox="0 0 512 512" data-fa-i2svg="">
                                <path fill="currentColor"
                                    d="M288 32c0-17.7-14.3-32-32-32s-32 14.3-32 32V274.7l-73.4-73.4c-12.5-12.5-32.8-12.5-45.3 0s-12.5 32.8 0 45.3l128 128c12.5 12.5 32.8 12.5 45.3 0l128-128c12.5-12.5 12.5-32.8 0-45.3s-32.8-12.5-45.3 0L288 274.7V32zM64 352c-35.3 0-64 28.7-64 64v32c0 35.3 28.7 64 64 64H448c35.3 0 64-28.7 64-64V416c0-35.3-28.7-64-64-64H346.5l-45.3 45.3c-25 25-65.5 25-90.5 0L165.5 352H64zm368 56a24 24 0 1 1 0 48 24 24 0 1 1 0-48z">
                                </path>
                            </svg></i>
                        Export
                    </button>

                </div>
            </div>

            {{-- Equipment Create --}}
            <button onclick="window.location='{{ route('maintenance.manifest.create') }}'" id="add-equipment-btn"
                class="flex items-center gap-2 rounded-lg bg-[#6840c6] px-4 py-2.5 text-sm font-medium text-white transition-colors hover:bg-[#52379e]">
                <i data-fa-i2svg=""><svg class="svg-inline--fa fa-plus" aria-hidden="true" focusable="false"
                        data-prefix="fas" data-icon="plus" role="img" xmlns="http://www.w3.org/2000/svg"
                        viewBox="0 0 448 512" data-fa-i2svg="">
                        <path fill="currentColor"
                            d="M256 80c0-17.7-14.3-32-32-32s-32 14.3-32 32V224H48c-17.7 0-32 14.3-32 32s14.3 32 32 32H192V432c0 17.7 14.3 32 32 32s32-14.3 32-32V288H400c17.7 0 32-14.3 32-32s-14.3-32-32-32H256V80z">
                        </path>
                    </svg></i>
                Add Equipment
            </button>

        </div>

        {{-- Equipment Index Table --}}
        <div class="space-y-6">
            <div class="overflow-x-auto rounded-lg border bg-white shadow">

                <table class="w-full divide-y divide-gray-200 text-left text-sm">
                    <thead>
                        <tr class="bg-[#f8f9fb] text-xs uppercase text-[#475466]">
                            {{-- Always show category and name first --}}
                            <th class="px-6 py-3 font-medium">
                                <button data-sort-key="category" type="button"
                                    class="sort-button flex items-center text-xs font-medium uppercase tracking-wider text-[#6840c6] hover:text-[#7e56d8]">
                                    Category
                                    <i class="fa-solid fa-sort ml-1 text-xs text-[#475466] transition-colors"></i>
                                </button>
                            </th>
                            <th class="px-6 py-3 font-medium">
                                <button data-sort-key="name" type="button"
                                    class="sort-button flex items-center text-xs font-medium uppercase tracking-wider text-[#6840c6] hover:text-[#7e56d8]">
                                    Name
                                    <i class="fa-solid fa-sort ml-1 text-xs text-[#475466] transition-colors"></i>
                                </button>
                            </th>

                            {{-- Serial Number as 3rd column if selected --}}
                            @if (in_array('serial_number', $visibleColumns))
                                <th class="px-6 py-3 font-medium">
                                    <button data-sort-key="serial" type="button"
                                        class="sort-button flex items-center text-xs font-medium uppercase tracking-wider text-[#6840c6] hover:text-[#7e56d8]">
                                        Serial Number
                                        <i class="fa-solid fa-sort ml-1 text-xs text-[#475466] transition-colors"></i>
                                    </button>
                                </th>
                            @endif

                            {{-- Render remaining fields, excluding ones we've already done --}}
                            @foreach ($staticFields as $field => $label)
                                @if (in_array($field, $visibleColumns) && !in_array($field, ['category', 'name', 'serial_number']))
                                    <th class="px-6 py-3 font-medium">{{ $label }}</th>
                                @endif
                            @endforeach

                            {{-- JSON attributes --}}
                            @foreach ($attributeKeys as $attr)
                                @if (in_array($attr, $visibleColumns))
                                    <th class="px-6 py-3 font-medium">{{ $attr }}</th>
                                @endif
                            @endforeach

                            <th class="px-6 py-3 font-medium">Actions</th>

                        </tr>
                    </thead>

                    <tbody id="equipment-list" class="divide-y divide-gray-200">

                        @forelse($equipment as $item)
                            @php
                                // Normalize attributes_json to array
                                $attributes = is_array($item->attributes_json)
                                    ? $item->attributes_json
                                    : json_decode($item->attributes_json, true) ?? [];
                            @endphp

                            <tr class="hover:bg-gray-50" data-category="{{ strtolower($item->category->name) }}"
                                data-name="{{ strtolower($item->name) }}"
                                data-serial="{{ strtolower($item->serial_number) }}">

                                {{-- Always show category and name first --}}
                                <td class="px-6 py-4">
                                    <div class="flex items-center">
                                        <div class="mr-3 flex h-8 w-8 items-center justify-center rounded-md bg-[#f9f5ff]">
                                            <i
                                                class="fa-solid {{ $item->category->icon ?? 'fa-question' }} text-[#6840c6]"></i>
                                        </div>
                                        <span class="text-sm text-[#344053]">{{ $item->category->name ?? '—' }}</span>
                                    </div>
                                </td>

                                <td class="px-6 py-4 text-sm text-[#344053]">
                                    {{ $item->name ?? '—' }}
                                </td>

                                {{-- Serial Number in 3rd position, if selected --}}
                                @if (in_array('serial_number', $visibleColumns))
                                    <td class="px-6 py-4 text-sm text-[#344053]">{{ $item->serial_number ?? '—' }}</td>
                                @endif

                                {{-- Remaining fields, excluding ones we've already rendered --}}
                                @foreach ($staticFields as $field => $label)
                                    @if (in_array($field, $visibleColumns) && !in_array($field, ['category', 'name', 'serial_number']))
                                        <td class="px-6 py-4 text-sm text-[#344053]">
                                            @switch($field)
                                                @case('deck')
                                                    {{ $item->deck->name ?? '—' }}
                                                @break

                                                @case('location')
                                                    {{ $item->location->name ?? '—' }}
                                                @break

                                                @case('status')
                                                    <span
                                                        class="{{ status_label_class($item->status) }} inline-flex rounded px-2 py-1 text-xs font-medium">
                                                        {{ $item->status }}
                                                    </span>
                                                @break

                                                @case('in_service')
                                                @case('removed_from_service')

                                                @case('manufacturing_date')
                                                @case('purchase_date')

                                                @case('expiry_date')
                                                    {{ optional($item->$field)->format('M j, Y') ?? '—' }}
                                                @break

                                                @default
                                                    {{ $item->$field ?? '—' }}
                                            @endswitch
                                        </td>
                                    @endif
                                @endforeach

                                {{-- JSON attributes --}}
                                @foreach ($attributeKeys as $attr)
                                    @if (in_array($attr, $visibleColumns))
                                        <td class="px-6 py-4 text-sm text-[#344053]">
                                            @if (isset($attributes[$attr]) && $attributes[$attr] !== '')
                                                {{ $attributes[$attr] }}
                                            @else
                                                <span
                                                    class="inline-flex rounded bg-gray-100 px-2 py-1 text-xs font-medium text-gray-600">
                                                    Not Defined
                                                </span>
                                            @endif
                                        </td>
                                    @endif
                                @endforeach

                                {{-- Actions --}}
                                <td class="px-6 py-4">
                                    <a href="{{ route('maintenance.manifest.show', $item) }}"
                                        class="rounded p-2 text-[#667084] hover:bg-[#f8f9fb] hover:text-[#344053]">
                                        <i class="fa-solid fa-eye"></i>
                                    </a>
                                </td>
                            </tr>
                            @empty
                                <tr>
                                    <td colspan="100%" class="px-6 py-4 text-center text-sm text-gray-500">
                                        No equipment found for this vessel.
                                    </td>
                                </tr>
                            @endforelse

                        </tbody>
                    </table>

                </div>
            </div>

            @include('components.equipment.edit-columns-modal')

            {{-- Search Filter --}}
            <script>
                document.addEventListener('DOMContentLoaded', function() {

                    // Search filtering logic
                    const searchInput = document.getElementById('equipment-search');
                    const rows = document.querySelectorAll('#equipment-list tr');

                    searchInput.addEventListener('input', function() {
                        const query = this.value.toLowerCase();

                        rows.forEach(row => {
                            const category = row.children[1]?.textContent.toLowerCase() || '';

                            const match = category.includes(query);
                            row.style.display = match ? '' : 'none';
                        });
                    });
                });
            </script>

            {{-- Column Sort --}}
            <script>
                document.addEventListener('DOMContentLoaded', () => {
                    const buttons = document.querySelectorAll('.sort-button');
                    const tableBody = document.getElementById('equipment-list');
                    let currentSortKey = null;
                    let ascending = true;

                    buttons.forEach(button => {
                        button.addEventListener('click', () => {
                            const sortKey = button.dataset.sortKey;
                            ascending = (currentSortKey === sortKey) ? !ascending : true;
                            currentSortKey = sortKey;

                            const rows = Array.from(tableBody.querySelectorAll('tr'));

                            rows.sort((a, b) => {
                                let aVal = a.dataset[sortKey] || '';
                                let bVal = b.dataset[sortKey] || '';

                                aVal = aVal.toLowerCase();
                                bVal = bVal.toLowerCase();

                                return (aVal > bVal ? 1 : aVal < bVal ? -1 : 0) * (ascending ? 1 : -
                                    1);
                            });

                            rows.forEach(row => tableBody.appendChild(row));

                            // Reset all sort icons
                            buttons.forEach(btn => {
                                const icon = btn.querySelector('svg');
                                if (icon) {
                                    icon.classList.remove('fa-arrow-up-short-wide',
                                        'fa-arrow-down-wide-short');
                                    icon.classList.add('fa-sort');
                                    icon.classList.remove('text-[#6840c6]');
                                    icon.classList.add('text-[#475466]');
                                }
                            });

                            // Update clicked button's icon
                            const icon = button.querySelector('svg');
                            if (icon) {
                                icon.classList.remove('fa-sort', 'text-[#475466]');
                                icon.classList.add(
                                    ascending ? 'fa-arrow-up-short-wide' : 'fa-arrow-down-wide-short',
                                    'text-[#6840c6]'
                                );
                            } else {
                                console.warn('Icon not found in clicked sort button:', button);
                            }
                        });
                    });
                });
            </script>

            {{-- Column Edit Modal --}}
            <script>
                function toggleColumnModal(show) {
                    document.getElementById('column-modal').classList.toggle('hidden', !show);
                    document.getElementById('column-modal-overlay').classList.toggle('hidden', !show);
                }

                document.querySelectorAll('button').forEach(btn => {
                    if (btn.textContent.includes('Edit Columns')) {
                        btn.addEventListener('click', () => toggleColumnModal(true));
                    }
                });

                document.getElementById('column-modal-overlay').addEventListener('click', () => toggleColumnModal(false));
            </script>

        </div>

    @endsection
