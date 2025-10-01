@extends('layouts.app')

@section('title', 'Maintenance Index')

@section('content')

    @php
        $intervalTypes = [
            'Daily',
            'Bi-Weekly',
            'Weekly',
            'Monthly',
            'Quarterly',
            'Bi-Annually',
            'Annual',
            '2-Yearly',
            '3-Yearly',
            '5-Yearly',
            '6-Yearly',
            '10-Yearly',
            '12-Yearly'
        ];
    @endphp

    {{-- Header --}}
    <div class="mb-6">
        <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <h1 class="text-2xl font-semibold text-[#0f1728]">Maintenance Index</h1>
                <p class="text-[#475466]">Overview of equipment maintenance requirements.</p>
            </div>
        </div>
    </div>

    @include ('components.maintenance.stat-cards')

    {{-- Maintenance Index --}}
    <div id="maintenance-table" class="overflow-hidden rounded-lg border border-[#e4e7ec] bg-white shadow-sm">

        {{-- Header & Controls --}}
        <div class="flex items-center justify-between border-b border-[#e4e7ec] px-6 py-4">
            <h2 class="text-lg font-semibold text-[#0f1728]">Maintenance Requirements</h2>
            <div class="flex items-center space-x-2">

                {{-- Search --}}
                <div class="relative">
                    <input id="category-search" type="text" placeholder="Search by name..."
                        class="rounded-lg border border-[#e4e7ec] py-2 pl-9 pr-4 text-sm focus:border-[#6840c6] focus:outline-none focus:ring-1 focus:ring-[#6840c6]">
                    <i class="fa-solid fa-search absolute left-3 top-1/2 -translate-y-1/2 transform text-[#667084]"></i>
                </div>

                <button onclick="window.location='{{ route('maintenance.create') }}'"
                    class="flex items-center rounded-lg bg-[#6840c6] px-3 py-2 text-sm text-white hover:bg-[#5a35a8]">
                    <i class="fa-solid fa-plus mr-2"></i>
                    Add New
                </button>

            </div>
        </div>

        {{-- Table --}}
        <div class="overflow-x-auto">
            <table class="w-full">

                <thead>
                    <tr class="bg-[#f8f9fb] text-xs uppercase text-[#475466]">
                        <th class="px-6 py-3 text-left font-medium">
                            <button data-sort-key="name" type="button"
                                class="sort-button flex items-center text-xs font-medium uppercase tracking-wider text-[#6840c6] hover:text-[#7e56d8]">
                                Category
                                <i class="fa-solid fa-sort ml-1 text-xs text-[#475466] transition-colors"></i>
                            </button>
                        </th>

                        <th class="px-6 py-3 text-left font-medium">
                            <button data-sort-key="type" type="button"
                                class="sort-button flex items-center text-xs font-medium uppercase tracking-wider text-[#6840c6] hover:text-[#7e56d8]">
                                Type
                                <i class="fa-solid fa-sort ml-1 text-xs text-[#475466] transition-colors"></i>
                            </button>
                        </th>

                        <th class="px-6 py-3 text-left font-medium">Affected Equipment</th>
                        <th class="px-6 py-3 text-left font-medium">Intervals</th>
                        <th class="px-6 py-3 text-left font-medium">Actions</th>
                    </tr>
                </thead>

                {{-- Category Loop --}}
                <tbody class="divide-y divide-[#e4e7ec]" id="category-list">

                    @forelse ($categories as $category)

                        <tr class="hover:bg-[#f9f5ff]" data-name="{{ strtolower($category->name) }}"
                            data-type="{{ strtolower($category->type) }}">
                            <td class="px-6 py-4">
                                <div class="flex items-center">
                                    <div class="name flex items-center">
                                        <div class="mr-3 flex h-8 w-8 items-center justify-center rounded-md bg-[#f9f5ff]">
                                            <i class="fa-solid {{ $category->icon }} text-[#6840c6]"></i>
                                        </div>
                                        <span class="text-sm text-[#344053]">{{ $category->name ?? '—' }}</span>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 text-sm font-bold text-[#344053]">{{ $category->type }}</td>
                            <td class="px-6 py-4">
                                <span class="text-sm text-[#6840c6]">{{ $category->equipment_count }}</span>
                            </td>

                            {{-- Interval Requirements --}}
                            <td class="px-6 py-4">

                                @foreach ($intervalTypes as $freq)
                                    @php
                                        $count = $category->intervals->where('interval', $freq)->count();
                                    @endphp

                                    @if ($count > 0)
                                        <span
                                            class="{{ frequency_label_class($freq) }} inline-flex items-center rounded-full px-2 py-1 text-xs font-medium">
                                            {{ $freq }}: {{ $count }}
                                        </span>
                                    @endif
                                @endforeach
                            </td>

                            {{-- Actions --}}
                            <td class="px-6 py-4">
                                <div class="flex space-x-2">

                                    {{-- View Category --}}
                                    <button onclick="window.location='{{ route('maintenance.show', $category) }}'"
                                        class="rounded p-2 text-sm text-[#667084] hover:bg-[#f8f9fb] hover:text-[#344053]">
                                        <i class="fa-solid fa-eye"></i>
                                    </button>

                                </div>
                            </td>
                        </tr>

                    @empty
                        <tr>
                            <td colspan="5" class="px-6 py-4 text-center text-sm text-[#667084]">No requirements defined
                                for this vessel.</td>
                        </tr>
                    @endforelse

                </tbody>

            </table>
        </div>

    </div>

    {{-- Search Filtering --}}
    <script>
        document.addEventListener('DOMContentLoaded', function() {

            // Search filtering logic
            const searchInput = document.getElementById('category-search');
            const rows = document.querySelectorAll('#category-list tr');

            searchInput.addEventListener('input', function() {
                const query = this.value.toLowerCase();

                rows.forEach(row => {
                    const category = row.children[0]?.textContent.toLowerCase() || '';

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
            const tableBody = document.getElementById('category-list');
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

@endsection
