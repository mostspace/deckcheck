@extends('layouts.app')

@section('title', 'Maintenance Index')

@section('content')

    {{-- Header --}}
    <div class="mb-6">

        {{-- Breadcrumb --}}
        <div class="mb-4">
            <nav class="flex items-center space-x-2 text-sm">
                <a href="{{ route('maintenance.index') }}">
                    <span class="text-[#6840c6] hover:text-[#5a35a8] cursor-pointer">Maintenance Index</span>
                </a>
                <i class="fa-solid fa-chevron-right text-[#667084] text-xs"></i>
                <span class="text-[#475466]">{{ $category->name }}</span>
            </nav>
        </div>

        {{-- System Messages --}}
        @if (session('success'))
            <div class="mb-6 p-4 bg-green-100 border border-green-300 text-green-800 rounded-lg text-sm">
                {{ session('success') }}
            </div>
        @endif

        {{-- Header --}}
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <div class="flex space-x-2">
                    <div class="w-8 h-8 bg-[#f9f5ff] border rounded-md flex items-center justify-center mr-1">
                        <i class="fa-solid hover:text-[#7e56d8] {{ $category->icon }} text-[#6840c6]"></i>
                    </div>
                    <h1 class="text-2xl font-semibold text-[#0f1728]">Maintenance Index for <span
                            class=" font-bold text-[#6840c6]">{{ $category->name }}</span></h1>
                    <button onclick="window.location='{{ route('maintenance.edit', $category) }}'" class="text-[#667084] hover:text-[#7e56d8]">
                        <i class="fa-solid fa-edit"></i>
                    </button>
                </div>
                <p class="text-[#475466]">Manage maintenance requirements and view equipment manifest.</p>
            </div>
        </div>

    </div>

    @include ('components.maintenance.detail-stat-cards')

    {{-- Intervals --}}
    <section id="maintenance-schedule-section" class="bg-white rounded-lg border border-[#e4e7ec] mb-6">

        {{-- Header & Controls --}}
        <div class="px-6 py-4 border-b border-[#e4e7ec] flex items-center justify-between">
            <div>
                <h2 class="text-lg font-semibold text-[#0f1728]">Maintenance Schedule</h2>
                <p class="text-sm text-[#475466] mt-1">Configure maintenance and inspection requirements.</p>
            </div>

            {{-- Add New --}}
            <button onclick="window.location='{{ route('maintenance.intervals.create', $category) }}'"
                class="px-4 py-2 bg-[#7e56d8] text-white rounded-lg hover:bg-[#6840c6] flex items-center gap-2">
                <i class="fa-solid fa-plus"></i>
                Add Interval
            </button>
        </div>

        {{-- Table --}}
        <div class="overflow-x-auto">
            <table class="w-full">

                <thead class="bg-[#f8f9fb]">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-[#7e56d8] uppercase tracking-wider">Interval</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-[#7e56d8] uppercase tracking-wider">Requirement</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-[#7e56d8] uppercase tracking-wider">Tasks</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-[#7e56d8] uppercase tracking-wider">Affected Equipment</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-[#7e56d8] uppercase tracking-wider">Facilitator</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-[#7e56d8] uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>

                <tbody class="divide-y divide-[#e4e7ec]">

                    {{-- Intervals Loop --}}

                    @if ($category->intervals->isNotEmpty())

                        @foreach ($category->intervals as $interval)
                            <tr class="hover:bg-[#f9f5ff]">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="inline-flex px-2 py-1 text-xs font-medium rounded-full {{ frequency_label_class($interval->interval) }}">
                                        {{ ucfirst($interval->interval) }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-[#0f1728]">{{ $interval->description }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-[#0f1728]">{{ $interval->tasks_count }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-[#0f1728]">##</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-[#0f1728]">
                                    <span class="px-2 py-1 rounded text-xs font-medium {{ facilitator_label_class($interval->facilitator) }}">
                                        {{ ucfirst($interval->facilitator) }}
                                    </span>
                                </td>

                                {{-- Actions --}}
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                    <div class="flex items-center gap-2">
                                        <a href="{{ route('maintenance.intervals.show', [$category, $interval]) }}"
                                            class="p-2 text-[#667084] hover:text-[#344053] hover:bg-[#f8f9fb] rounded-lg">
                                            <i class="fa-solid fa-eye"></i>
                                        </a>
                                        <form
                                            action="
                                            {{ route('maintenance.intervals.destroy', [$category, $interval]) }}"
                                            method="POST"
                                            onsubmit="return confirm('Are you sure you want to delete this interval and its tasks? This action cannot be undone.');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="p-2 text-[#667084] hover:text-[#f04438] hover:bg-[#fef3f2] rounded-lg">
                                                <i class="fa-solid fa-trash"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    @else
                        <tr>
                            <td colspan="6" class="px-6 py-4 text-center text-sm text-[#667084]">No intervals defined for category yet.</td>
                        </tr>
                    @endif

                </tbody>

            </table>
        </div>
    </section>


    {{-- Equipment Manifest --}}
    <section id="equipment-manifest-section" class="bg-white rounded-lg border border-[#e4e7ec]">

        {{-- Header & Controls --}}
        <div class="px-6 py-4 border-b border-[#e4e7ec] flex items-center justify-between">
            <div>
                <h2 class="text-lg font-semibold text-[#0f1728]">Equipment Manifest</h2>
                <p class="text-sm text-[#475466] mt-1">All equipment assigned to <span class="uppercase text-[#6840c6]">{{ $category->name }}</span></p>
            </div>

            <div class="flex items-center gap-3">

                {{-- Edit Columns --}}
                <button class="px-4 py-2 bg-white border border-[#cfd4dc] rounded-lg text-[#344053] hover:bg-[#f8f9fb] flex items-center gap-2">
                    <i class="fa-solid fa-columns"></i>
                    Edit Columns
                </button>

                {{-- Filters --}}
                <button class="px-4 py-2 bg-white border border-[#cfd4dc] rounded-lg text-[#344053] hover:bg-[#f8f9fb] flex items-center gap-2">
                    <i class="fa-solid fa-filter"></i>
                    Filter
                </button>

                {{-- Export --}}
                <button class="px-4 py-2 bg-white border border-[#cfd4dc] rounded-lg text-[#344053] hover:bg-[#f8f9fb] flex items-center gap-2">
                    <i class="fa-solid fa-download"></i>
                    Export
                </button>

                {{-- Bulk Add Equipment --}}
                <button type="button" id="openBulkCreateModal"
                    class="px-4 py-2 bg-[#7e56d8] text-white rounded-lg hover:bg-[#6840c6] flex items-center gap-2">
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
                                class="sort-button flex items-center text-xs font-medium text-[#6840c6] hover:text-[#7e56d8] uppercase tracking-wider">
                                ID
                                <i class="fa-solid fa-sort ml-1 text-xs transition-colors text-[#475466]"></i>
                            </button>
                        </th>
                        <th class="px-6 py-3 text-left font-medium">
                            <button data-sort-key="name" type="button"
                                class="sort-button flex items-center text-xs font-medium text-[#6840c6] hover:text-[#7e56d8] uppercase tracking-wider">
                                name
                                <i class="fa-solid fa-sort ml-1 text-xs transition-colors text-[#475466]"></i>
                            </button>
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-[#7e56d8] uppercase tracking-wider">Serial Number</th>
                        <th class="px-6 py-3 text-left font-medium">
                            <button data-sort-key="deck" type="button"
                                class="sort-button flex items-center text-xs font-medium text-[#6840c6] hover:text-[#7e56d8] uppercase tracking-wider">
                                Deck
                                <i class="fa-solid fa-sort ml-1 text-xs transition-colors text-[#475466]"></i>
                            </button>
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-[#7e56d8] uppercase tracking-wider">Location</th>
                        <th class="px-6 py-3 text-left font-medium">
                            <button data-sort-key="status" type="button"
                                class="sort-button flex items-center text-xs font-medium text-[#6840c6] hover:text-[#7e56d8] uppercase tracking-wider">
                                Status
                                <i class="fa-solid fa-sort ml-1 text-xs transition-colors text-[#475466]"></i>
                            </button>
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-[#7e56d8] uppercase tracking-wider"></th>
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
                            <tr class="hover:bg-[#f9f5ff]" data-id="{{ strtolower($eq->internal_id) }}" data-name="{{ strtolower($eq->name) }}"
                                data-deck="{{ strtolower($eq->deck) }}" data-status="{{ strtolower($eq->status) }}">
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-[#0f1728]">{{ $eq->internal_id ?? '-' }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-[#0f1728]">{{ $eq->name ?? '—' }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-[#0f1728]">{{ $eq->serial_number ?? '—' }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-[#0f1728]">{{ $eq->deck->name ?? '—' }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-[#0f1728]">{{ $eq->location->name ?? '—' }}</td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span
                                        class="inline-flex px-2 py-1 text-xs font-medium rounded-full {{ status_label_class($eq->status) }}">{{ $eq->status }}</span>
                                </td>
                                {{-- Actions --}}
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                    <div class="flex items-center gap-2">
                                        <a href="{{ route('equipment.show', $eq) }}"
                                            class="p-2 text-[#667084] hover:text-[#344053] hover:bg-[#f8f9fb] rounded-lg">
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
        <div class="px-6 py-4 flex items-center justify-between border-t border-[#e4e7ec]">
            <div class="text-sm text-[#475466]">
                Showing 1 to {{ $category->equipment_count }} of {{ $category->equipment_count }} records
            </div>

            <!--<div class="h-10 shadow-[0px_1px_2px_0px_rgba(16,24,40,0.05)] justify-start items-start inline-flex">
                        <div
                            class="px-4 py-2.5 bg-white rounded-tl-lg rounded-bl-lg border border-[#cfd4dc] justify-center items-center gap-2 flex overflow-hidden">
                            <i class="fa-solid fa-chevron-left text-sm text-[#1d2838]"></i>
                            <div class="text-[#1d2838] text-sm leading-tight">Previous</div>
                        </div>
                        <div class="w-10 bg-[#f9f5ff] flex-col justify-center items-center inline-flex">
                            <div class="self-stretch h-px bg-[#cfd4dc]"></div>
                            <div class="px-2 py-[9px] justify-center items-center gap-2.5 inline-flex">
                                <div class="text-[#6840c6] text-sm leading-tight">1</div>
                            </div>
                            <div class="self-stretch h-px bg-[#cfd4dc]"></div>
                        </div>
                        <div class="w-10 bg-white flex-col justify-center items-center inline-flex">
                            <div class="self-stretch h-px bg-[#cfd4dc]"></div>
                            <div class="px-2 py-[9px] justify-center items-center gap-2.5 inline-flex">
                                <div class="text-[#344053] text-sm leading-tight">2</div>
                            </div>
                            <div class="self-stretch h-px bg-[#cfd4dc]"></div>
                        </div>
                        <div class="w-10 bg-white flex-col justify-center items-center inline-flex">
                            <div class="self-stretch h-px bg-[#cfd4dc]"></div>
                            <div class="px-2 py-[9px] justify-center items-center gap-2.5 inline-flex">
                                <div class="text-[#344053] text-sm leading-tight">3</div>
                            </div>
                            <div class="self-stretch h-px bg-[#cfd4dc]"></div>
                        </div>
                        <div
                            class="px-4 py-2.5 bg-white rounded-tr-lg rounded-br-lg border border-[#cfd4dc] justify-center items-center gap-2 flex overflow-hidden">
                            <div class="text-[#1d2838] text-sm leading-tight">Next</div>
                            <i class="fa-solid fa-chevron-right text-sm text-[#1d2838]"></i>
                        </div>
                    </div> -->
        </div>

    </section>

    @include('components.maintenance.category.equipment-bulk-create')

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

                        return (aVal > bVal ? 1 : aVal < bVal ? -1 : 0) * (ascending ? 1 : -1);
                    });

                    rows.forEach(row => tableBody.appendChild(row));

                    // Reset all sort icons
                    buttons.forEach(btn => {
                        const icon = btn.querySelector('svg');
                        if (icon) {
                            icon.classList.remove('fa-arrow-up-short-wide', 'fa-arrow-down-wide-short');
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

  // Deck → Location & “Add new location” flow
  document.addEventListener('change', async e => {
    // When deck changes, load locations + “Add new…” option
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

        // inject “Add new” option
        const addOpt = new Option('+ Add new location…', '__add__');
        addOpt.className = 'text-purple-600';
        loc.append(addOpt);

        loc.disabled = false;
      } catch (err) {
        console.error(err);
      }
    }

    // When user selects “Add new”, prompt and call AJAX endpoint
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
          body: JSON.stringify({ name })
        });
        if (!res.ok) throw new Error('Network error');
        const locData = await res.json();

        // remove the “add new” option
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
