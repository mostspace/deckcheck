@extends('v2.layouts.app')

@section('title', 'Maintenance Category')

@section('content')

    @include('v2.components.nav.header-routing', [
        'activeTab' => 'index',
        'breadcrumbs' => [
            ['label' => 'Maintenance', 'icon' => asset('assets/media/icons/sidebar-solid-wrench-scredriver.svg')],
            ['label' => 'Index', 'url' => route('maintenance.index')],
            ['label' => $category->name]
        ]
    ])

    <div class="flex flex-col gap-4 px-3 py-4 sm:gap-8 sm:px-6 sm:py-6 lg:px-8">
        {{-- Header --}}
        <div class="">
            {{-- System Messages --}}
            @if (session('success'))
                <div class="mb-6 rounded-lg border border-green-300 bg-green-100 p-4 text-sm text-green-800">
                    {{ session('success') }}
                </div>
            @endif

            {{-- Header --}}
            <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
                <div>
                    <div class="flex space-x-2">
                        <div class="mr-1 flex h-8 w-8 items-center justify-center rounded-md border bg-[#f9f5ff]">
                            <i class="fa-solid {{ $category->icon }} text-[#6840c6] hover:text-[#7e56d8]"></i>
                        </div>
                        <h1 class="text-2xl font-semibold text-[#0f1728]">Maintenance Index for <span
                                class="font-bold text-[#6840c6]">{{ $category->name }}</span></h1>
                        <button onclick="window.location='{{ route('maintenance.edit', $category) }}'"
                            class="text-[#667084] hover:text-[#7e56d8]">
                            <i class="fa-solid fa-edit"></i>
                        </button>
                    </div>
                    <p class="text-[#475466]">Manage maintenance requirements and view equipment manifest.</p>
                </div>
            </div>
        </div>

        @include ('components.maintenance.detail-stat-cards')

        {{-- Intervals --}}
        <section id="maintenance-schedule-section" class="mb-6 rounded-lg border border-[#e4e7ec] bg-white">
            {{-- Header & Controls --}}
            <div class="flex items-center justify-between border-b border-[#e4e7ec] px-6 py-4">
                <div>
                    <h2 class="text-lg font-semibold text-[#0f1728]">Maintenance Schedule</h2>
                    <p class="mt-1 text-sm text-[#475466]">Configure maintenance and inspection requirements.</p>
                </div>

                {{-- Add New --}}
                <button onclick="window.location='{{ route('maintenance.intervals.create', $category) }}'"
                    class="flex items-center gap-2 rounded-lg bg-[#7e56d8] px-4 py-2 text-white hover:bg-[#6840c6]">
                    <i class="fa-solid fa-plus"></i>
                    Add Interval
                </button>
            </div>

            {{-- Table --}}
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-[#f8f9fb]">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-[#7e56d8]">
                                Interval</th>
                            <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-[#7e56d8]">
                                Requirement</th>
                            <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-[#7e56d8]">
                                Tasks</th>
                            <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-[#7e56d8]">
                                Affected Equipment</th>
                            <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-[#7e56d8]">
                                Facilitator</th>
                            <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-[#7e56d8]">
                                Actions</th>
                        </tr>
                    </thead>

                    <tbody class="divide-y divide-[#e4e7ec]">
                        {{-- Intervals Loop --}}
                        @if ($category->intervals->isNotEmpty())
                            @foreach ($category->intervals as $interval)
                                <tr class="hover:bg-[#f9f5ff]">
                                    <td class="whitespace-nowrap px-6 py-4">
                                        <span
                                            class="{{ frequency_label_class($interval->interval) }} inline-flex rounded-full px-2 py-1 text-xs font-medium">
                                            {{ ucfirst($interval->interval) }}
                                        </span>
                                    </td>
                                    <td class="whitespace-nowrap px-6 py-4 text-sm text-[#0f1728]">
                                        {{ $interval->description }}</td>
                                    <td class="whitespace-nowrap px-6 py-4 text-sm text-[#0f1728]">
                                        {{ $interval->tasks_count }}</td>
                                    <td class="whitespace-nowrap px-6 py-4 text-sm text-[#0f1728]">##</td>
                                    <td class="whitespace-nowrap px-6 py-4 text-sm text-[#0f1728]">
                                        <span
                                            class="{{ facilitator_label_class($interval->facilitator) }} rounded px-2 py-1 text-xs font-medium">
                                            {{ ucfirst($interval->facilitator) }}
                                        </span>
                                    </td>

                                    {{-- Actions --}}
                                    <td class="whitespace-nowrap px-6 py-4 text-sm font-medium">
                                        <div class="flex items-center gap-2">
                                            <a href="{{ route('maintenance.intervals.show', [$category, $interval]) }}"
                                                class="rounded-lg p-2 text-[#667084] hover:bg-[#f8f9fb] hover:text-[#344053]">
                                                <i class="fa-solid fa-eye"></i>
                                            </a>
                                            <a href="{{ route('maintenance.intervals.edit', [$category, $interval]) }}"
                                                class="rounded-lg p-2 text-[#667084] hover:bg-[#f8f9fb] hover:text-[#344053]">
                                                <i class="fa-solid fa-edit"></i>
                                            </a>
                                            <form
                                                action="{{ route('maintenance.intervals.destroy', [$category, $interval]) }}"
                                                method="POST"
                                                onsubmit="return confirm('Are you sure you want to delete this interval and its tasks? This action cannot be undone.');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit"
                                                    class="rounded-lg p-2 text-[#667084] hover:bg-[#fef3f2] hover:text-[#f04438]">
                                                    <i class="fa-solid fa-trash"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        @else
                            <tr>
                                <td colspan="6" class="px-6 py-4 text-center text-sm text-[#667084]">No intervals defined
                                    for category yet.</td>
                            </tr>
                        @endif
                    </tbody>
                </table>
            </div>
        </section>

        {{-- Equipment Manifest --}}
        <section id="equipment-manifest-section" class="rounded-lg border border-[#e4e7ec] bg-white">
            {{-- Header & Controls --}}
            <div class="flex items-center justify-between border-b border-[#e4e7ec] px-6 py-4">
                <div>
                    <h2 class="text-lg font-semibold text-[#0f1728]">Equipment Manifest</h2>
                    <p class="mt-1 text-sm text-[#475466]">All equipment assigned to <span
                            class="uppercase text-[#6840c6]">{{ $category->name }}</span></p>
                </div>

                <div class="flex items-center gap-3">
                    {{-- Edit Columns --}}
                    <button
                        class="flex items-center gap-2 rounded-lg border border-[#cfd4dc] bg-white px-4 py-2 text-[#344053] hover:bg-[#f8f9fb]">
                        <i class="fa-solid fa-columns"></i>
                        Edit Columns
                    </button>

                    {{-- Filters --}}
                    <button
                        class="flex items-center gap-2 rounded-lg border border-[#cfd4dc] bg-white px-4 py-2 text-[#344053] hover:bg-[#f8f9fb]">
                        <i class="fa-solid fa-filter"></i>
                        Filter
                    </button>

                    {{-- Export --}}
                    <button
                        class="flex items-center gap-2 rounded-lg border border-[#cfd4dc] bg-white px-4 py-2 text-[#344053] hover:bg-[#f8f9fb]">
                        <i class="fa-solid fa-download"></i>
                        Export
                    </button>

                    {{-- Bulk Add Equipment --}}
                    <button type="button" id="openBulkCreateModal"
                        class="flex items-center gap-2 rounded-lg bg-[#7e56d8] px-4 py-2 text-white hover:bg-[#6840c6]">
                        <i class="fa-solid fa-plus"></i>
                        Add Equipment
                    </button>
                </div>
            </div>

            {{-- Table --}}
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-[#f8f9fb]">
                        <tr class="bg-[#f8f9fb]">
                            <th class="px-6 py-3 text-left font-medium">
                                <button data-sort-key="id" type="button"
                                    class="sort-button flex items-center text-xs font-medium uppercase tracking-wider text-[#6840c6] hover:text-[#7e56d8]">
                                    ID
                                    <i class="fa-solid fa-sort ml-1 text-xs text-[#475466] transition-colors"></i>
                                </button>
                            </th>
                            <th class="px-6 py-3 text-left font-medium">
                                <button data-sort-key="name" type="button"
                                    class="sort-button flex items-center text-xs font-medium uppercase tracking-wider text-[#6840c6] hover:text-[#7e56d8]">
                                    name
                                    <i class="fa-solid fa-sort ml-1 text-xs text-[#475466] transition-colors"></i>
                                </button>
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-[#7e56d8]">
                                Serial Number</th>
                            <th class="px-6 py-3 text-left font-medium">
                                <button data-sort-key="deck" type="button"
                                    class="sort-button flex items-center text-xs font-medium uppercase tracking-wider text-[#6840c6] hover:text-[#7e56d8]">
                                    Deck
                                    <i class="fa-solid fa-sort ml-1 text-xs text-[#475466] transition-colors"></i>
                                </button>
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-[#7e56d8]">
                                Location</th>
                            <th class="px-6 py-3 text-left font-medium">
                                <button data-sort-key="status" type="button"
                                    class="sort-button flex items-center text-xs font-medium uppercase tracking-wider text-[#6840c6] hover:text-[#7e56d8]">
                                    Status
                                    <i class="fa-solid fa-sort ml-1 text-xs text-[#475466] transition-colors"></i>
                                </button>
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-[#7e56d8]">
                            </th>
                        </tr>
                    </thead>

                    <tbody id="equipment-list" class="divide-y divide-[#e4e7ec]">
                        {{-- Equipment Loop --}}
                        @php
                            $equipList = $category->equipment;
                        @endphp

                        @if ($equipList->isEmpty())
                            <tr class="hover:bg-[#f9f5ff]">
                                <td colspan="7" class="px-6 py-4 text-center text-sm text-[#667084]">
                                    No
                                    <a href="{{ route('inventory.equipment') }}" class="text-[#7e56d8]">equipment</a>
                                    assigned to category yet.
                                </td>
                            </tr>
                        @else
                            @foreach ($equipList as $eq)
                                <tr class="hover:bg-[#f9f5ff]" data-id="{{ strtolower($eq->internal_id) }}"
                                    data-name="{{ strtolower($eq->name) }}" data-deck="{{ strtolower($eq->deck) }}"
                                    data-status="{{ strtolower($eq->status) }}">
                                    <td class="whitespace-nowrap px-6 py-4 text-sm font-medium text-[#0f1728]">
                                        {{ $eq->internal_id ?? '-' }}</td>
                                    <td class="whitespace-nowrap px-6 py-4 text-sm text-[#0f1728]">{{ $eq->name ?? '—' }}
                                    </td>
                                    <td class="whitespace-nowrap px-6 py-4 text-sm text-[#0f1728]">
                                        {{ $eq->serial_number ?? '—' }}</td>
                                    <td class="whitespace-nowrap px-6 py-4 text-sm text-[#0f1728]">
                                        {{ $eq->deck->name ?? '—' }}</td>
                                    <td class="whitespace-nowrap px-6 py-4 text-sm text-[#0f1728]">
                                        {{ $eq->location->name ?? '—' }}</td>
                                    <td class="whitespace-nowrap px-6 py-4">
                                        <span
                                            class="{{ status_label_class($eq->status) }} inline-flex rounded-full px-2 py-1 text-xs font-medium">{{ $eq->status }}</span>
                                    </td>
                                    {{-- Actions --}}
                                    <td class="whitespace-nowrap px-6 py-4 text-sm font-medium">
                                        <div class="flex items-center gap-2">
                                            <a href="{{ route('equipment.show', $eq) }}"
                                                class="rounded-lg p-2 text-[#667084] hover:bg-[#f8f9fb] hover:text-[#344053]">
                                                <i class="fa-solid fa-eye"></i>
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        @endif
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <div class="flex items-center justify-between border-t border-[#e4e7ec] px-6 py-4">
                <div class="text-sm text-[#475466]">
                    Showing 1 to {{ $category->equipment_count }} of {{ $category->equipment_count }} records
                </div>
            </div>
        </section>

        @include('components.maintenance.category.equipment-bulk-create')
    </div>

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

    <script>
        const modal = document.getElementById('equipmentBulkModal');

        function openModal() {
            modal.classList.remove('translate-x-full');
        }

        function closeModal() {
            modal.classList.add('translate-x-full');
        }

        // Open / Close
        document.getElementById('openBulkCreateModal')
            .addEventListener('click', openModal);
        document.getElementById('closeBulkModal')
            .addEventListener('click', closeModal);
        document.getElementById('cancelBulkModal')
            .addEventListener('click', closeModal);

        // ESC key
        document.addEventListener('keydown', e => {
            if (e.key === 'Escape') closeModal();
        });

        // Add new row
        let rowIndex = 1;
        document.getElementById('addBulkRow')
            .addEventListener('click', () => {
                fetch("{{ route('equipment.bulk-row.partial') }}?index=" + rowIndex)
                    .then(r => r.text())
                    .then(html => {
                        document.getElementById('bulkRowsContainer')
                            .insertAdjacentHTML('beforeend', html);
                        rowIndex++;
                    });
            });

        // Remove row
        document.addEventListener('click', e => {
            if (e.target.matches('.remove-row')) {
                e.target.closest('tr').remove();
            }
        });

        // Deck → Location & "Add new location" flow
        document.addEventListener('change', async e => {
            // When deck changes, load locations + "Add new…" option
            if (e.target.matches('.deck-select')) {
                const deckId = e.target.value;
                const row = e.target.closest('tr');
                const loc = row.querySelector('.location-select');

                loc.innerHTML = '<option value="">Loading…</option>';
                loc.disabled = true;

                try {
                    const res = await fetch(`/inventory/decks/${deckId}/locations`);
                    const data = await res.json();

                    loc.innerHTML = '<option value="">Select location</option>';
                    data.forEach(l => loc.append(new Option(l.name, l.id)));

                    // inject "Add new" option
                    const addOpt = new Option('+ Add new location…', '__add__');
                    addOpt.className = 'text-purple-600';
                    loc.append(addOpt);

                    loc.disabled = false;
                } catch (err) {
                    console.error(err);
                }
            }

            // When user selects "Add new", prompt and call AJAX endpoint
            if (e.target.matches('.location-select') && e.target.value === '__add__') {
                const row = e.target.closest('tr');
                const deckId = row.querySelector('.deck-select').value;
                const name = prompt('Enter new location name:');
                if (!name) {
                    e.target.value = '';
                    return;
                }

                try {
                    const res = await fetch(`/inventory/decks/${deckId}/locations/ajax`, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        },
                        body: JSON.stringify({
                            name
                        })
                    });
                    if (!res.ok) throw new Error('Network error');
                    const locData = await res.json();

                    // remove the "add new" option
                    const addOption = e.target.querySelector('option[value="__add__"]');
                    if (addOption) addOption.remove();

                    // append the new location and select it
                    const newOpt = new Option(locData.name, locData.id, true, true);
                    e.target.append(newOpt);
                } catch (err) {
                    console.error(err);
                    alert('Could not create location.');
                    e.target.value = '';
                }
            }
        });
    </script>

@endsection
