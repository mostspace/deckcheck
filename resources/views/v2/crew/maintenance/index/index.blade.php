<div class="mb-6">
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h1 class="text-2xl font-semibold text-[#0f1728]">Maintenance Index</h1>
            <p class="text-[#475466]">Overview of equipment maintenance requirements.</p>
        </div>
    </div>
</div>

@include ('components.maintenance.stat-cards')

{{-- Maintenance Index --}}
<div id="maintenance-table" class="bg-white rounded-lg border border-[#e4e7ec] shadow-sm overflow-hidden">

    {{-- Header & Controls --}}
    <div class="flex items-center justify-between px-6 py-4 border-b border-[#e4e7ec]">
        <h2 class="text-lg font-semibold text-[#0f1728]">Maintenance Requirements</h2>
        <div class="flex items-center space-x-2">

            {{-- Search --}}
            <div class="relative">
                <input id="category-search" type="text" placeholder="Search by name..."
                    class="pl-9 pr-4 py-2 border border-[#e4e7ec] rounded-lg text-sm focus:outline-none focus:ring-1 focus:ring-[#6840c6] focus:border-[#6840c6]">
                <i class="fa-solid fa-search absolute left-3 top-1/2 transform -translate-y-1/2 text-[#667084]"></i>
            </div>

            <button onclick="window.location='{{ route('maintenance.create') }}'"
                class="px-3 py-2 bg-[#6840c6] text-white rounded-lg text-sm hover:bg-[#5a35a8] flex items-center">
                <i class="fa-solid fa-plus mr-2"></i>
                Add New
            </button>
        </div>
    </div>

    {{-- Table --}}
    <div class="overflow-x-auto">
        <table class="w-full">
            <thead>
                <tr class="bg-[#f8f9fb] text-[#475466] text-xs uppercase">
                    <th class="px-6 py-3 text-left font-medium">
                        <button data-sort-key="name" type="button"
                            class="sort-button flex items-center text-xs font-medium text-[#6840c6] hover:text-[#7e56d8] uppercase tracking-wider">
                            Category
                            <i class="fa-solid fa-sort ml-1 text-xs transition-colors text-[#475466]"></i>
                        </button>
                    </th>

                    <th class="px-6 py-3 text-left font-medium">
                        <button data-sort-key="type" type="button"
                            class="sort-button flex items-center text-xs font-medium text-[#6840c6] hover:text-[#7e56d8] uppercase tracking-wider">
                            Type
                            <i class="fa-solid fa-sort ml-1 text-xs transition-colors text-[#475466]"></i>
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

                    <tr class="hover:bg-[#f9f5ff]" data-name="{{ strtolower($category->name) }}" data-type="{{ strtolower($category->type) }}">
                        <td class="px-6 py-4">
                            <div class="flex items-center">
                                <div class="flex items-center name">
                                    <div class="w-8 h-8 bg-[#f9f5ff] rounded-md flex items-center justify-center mr-3">
                                        <i class="text-[#6840c6] fa-solid {{ $category->icon }}"></i>
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
                                        class="inline-flex items-center px-2 py-1 text-xs font-medium rounded-full {{ frequency_label_class($freq) }}">
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
                                    class="p-2 text-sm text-[#667084] hover:text-[#344053] hover:bg-[#f8f9fb] rounded">
                                    <i class="fa-solid fa-eye"></i>
                                </button>

                            </div>
                        </td>
                    </tr>

                    @empty
                        <tr>
                            <td colspan="5" class="px-6 py-4 text-center text-sm text-[#667084]">No requirements defined for this vessel.</td>
                        </tr>
                    @endforelse
            </tbody>
        </table>
    </div>
</div>