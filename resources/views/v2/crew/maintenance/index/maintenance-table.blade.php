{{-- Maintenance Index --}}
<div id="maintenance-table" class="bg-white rounded-lg border border-[#e4e7ec] shadow-sm overflow-hidden">

    {{-- Header & Controls --}}
    <div class="flex items-center justify-between px-6 py-4 border-b border-[#e4e7ec]">
        <h2 class="text-lg font-semibold text-[#0f1728]">Maintenance Requirements</h2>
        <div class="flex items-center space-x-2">

            {{-- Search --}}
            <div class="relative">
                <input id="category-search" type="text" placeholder="Search by name..."
                    class="pl-9 pr-4 py-2 border border-[#e4e7ec] rounded-lg text-sm transition focus:outline-none focus:ring-1 focus:ring-primary-500 focus:border-primary-500">
                <i class="fa-solid fa-search absolute left-3 top-1/2 transform -translate-y-1/2 text-[#667084]"></i>
            </div>

            <button onclick="window.location='{{ route('maintenance.create') }}'" class="px-3 py-2 bg-primary-500 text-slate-900 rounded-lg text-sm hover:bg-primary-600 transition flex items-center">
                <i class="fa-solid fa-plus mr-2"></i> Add New
            </button>
        </div>
    </div>

    {{-- Table --}}
    <div class="overflow-x-auto">
        <table class="w-full">
            <thead>
                <tr class="bg-[#f8f9fb] text-[#475466] text-sm">
                    <th class="px-6 py-5 text-left">
                        <button data-sort-key="name" type="button"
                            class="sort-button flex items-center text-sm text-black hover:text-slate-700 tracking-wider">
                            Category
                            <i class="fa-solid fa-sort ml-1 text-sm transition-colors text-[#475466]"></i>
                        </button>
                    </th>

                    <th class="px-6 py-5 text-left">
                        <button data-sort-key="type" type="button"
                            class="sort-button flex items-center text-sm text-black hover:text-slate-700 tracking-wider">
                            Type
                            <i class="fa-solid fa-sort ml-1 text-sm transition-colors text-[#475466]"></i>
                        </button>
                    </th>

                    <th class="px-6 py-5 text-left font-medium">Affected Equipment</th>
                    <th class="px-6 py-5 text-left font-medium">Intervals</th>
                    <th class="px-6 py-5 text-left font-medium">Actions</th>
                </tr>
            </thead>

            {{-- Category Loop --}}
            <tbody class="divide-y divide-[#e4e7ec]" id="category-list">

                @forelse ($categories as $category)

                    <tr class="hover:bg-[#f9f5ff]" data-name="{{ strtolower($category->name) }}" data-type="{{ strtolower($category->type) }}">
                        <td class="px-6 py-4">
                            <div class="flex items-center">
                                <div class="flex items-center name">
                                    <div class="w-8 h-8 flex items-center justify-center mr-3">
                                        <i class="text-slate-700 fa-solid {{ $category->icon }}"></i>
                                    </div>
                                    <span class="text-sm text-[#344053]">{{ $category->name ?? '—' }}</span>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4 text-sm font-semibold text-[#344053]">{{ $category->type }}</td>
                        <td class="px-6 py-4">
                            <span class="text-sm text-slate-700">{{ $category->equipment_count }}</span>
                        </td>

                        {{-- Interval Requirements --}}
                        <td class="px-6 py-4">

                            @foreach ($intervalTypes as $freq)
                                @php
                                    $count = $category->intervals->where('interval', $freq)->count();
                                @endphp

                                @if ($count > 0)
                                    <span class="inline-flex items-center border border-[#ECEDEA] px-1 sm:px-2 py-1 text-xs font-medium rounded-md bg-[#FAFAF8] text-slate-700 gap-1 sm:gap-2">
                                        <div class="w-1.5 h-1.5 rounded-full {{ frequency_label_class($freq) }}"></div>
                                        {{ $freq }}: {{ $count }}
                                    </span>

                                    <!-- <span class="border border-slate-200 px-1 sm:px-2 py-1 text-xs rounded-md bg-slate-100 text-slate-700 flex items-center gap-1 sm:gap-2">
                                        <div class="w-1.5 h-1.5 sm:w-2 sm:h-2 rounded-full bg-${interval.color}"></div>
                                        <span class="hidden sm:inline">{{ $freq }}: {{ $count }}</span>
                                        <span class="sm:hidden">{{ $freq }}:{{ $count }}</span>
                                    </span> -->
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