{{-- Maintenance Index --}}
<div id="maintenance-table" class="bg-white flex flex-col gap-3 sm:gap-4">

    {{-- Header & Controls --}}
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3">
        <h2 class="text-base sm:text-lg font-semibold text-slate-800">Maintenance Requirements</h2>
        <div class="flex items-center gap-2 sm:gap-3">
            {{-- Search --}}
            <div class="relative">
                <input id="category-search" type="text" placeholder="Search by name..."
                    class="pl-9 pr-4 py-2 border h-9 border-[#e4e7ec] rounded-lg text-sm transition focus:outline-none focus:ring-1 focus:ring-primary-500 focus:border-primary-500">
                <i class="fa-solid fa-search absolute left-3 top-1/2 transform -translate-y-1/2 text-[#5F6472]"></i>
            </div>

            <button onclick="window.location='{{ route('maintenance.create') }}'" class="px-3 py-2 bg-primary-500 text-slate-900 rounded-lg text-sm hover:bg-primary-600 transition flex items-center">
                <i class="fa-solid fa-plus mr-2"></i> Add New
            </button>
        </div>
    </div>

    {{-- Table --}}
    <div class="overflow-x-auto rounded-lg border border-slate-200">
        <table class="w-full min-w-[600px]">
            <thead class="border-b border-slate-200">
                <tr>
                    <th class="px-3 sm:px-6 py-5 text-left text-xs sm:text-sm font-medium text-slate-500 tracking-wider">
                        <button data-sort-key="name" type="button" class="sort-button flex items-center text-slate-500 hover:text-slate-800 tracking-wider">
                            Category <i class="fa-solid fa-sort ml-1 text-sm transition-colors text-primary-800"></i>
                        </button>
                    </th>

                    <th class="px-3 sm:px-6 py-5 text-left text-xs sm:text-sm font-medium text-slate-500 tracking-wider">
                        <button data-sort-key="type" type="button" class="sort-button flex items-center text-slate-500 hover:text-slate-800 tracking-wider">
                            Type <i class="fa-solid fa-sort ml-1 text-sm transition-colors text-primary-800"></i>
                        </button>
                    </th>

                    <th class="px-3 sm:px-6 py-5 text-left text-xs sm:text-sm font-medium text-slate-500 tracking-wider">Affected Equipment</th>
                    <th class="px-3 sm:px-6 py-5 text-left text-xs sm:text-sm font-medium text-slate-500 tracking-wider">Intervals</th>
                    <th class="px-3 sm:px-6 py-5 text-left text-xs sm:text-sm font-medium text-slate-500 tracking-wider">Actions</th>
                </tr>
            </thead>

            {{-- Category Loop --}}
            <tbody class="bg-white divide-y divide-slate-200" id="category-list">

                @forelse ($categories as $category)

                    <tr class="hover:bg-slate-50" data-name="{{ strtolower($category->name) }}" data-type="{{ strtolower($category->type) }}">
                        <td class="px-3 sm:px-6 py-3 text-xs sm:text-sm text-slate-900">
                            <div class="flex items-center">
                                <div class="flex items-center name">
                                    <div class="w-8 h-8 flex items-center justify-center mr-3">
                                        <i class="fa-solid {{ $category->icon }}"></i>
                                    </div>
                                    <span class="">{{ $category->name ?? '—' }}</span>
                                </div>
                            </div>
                        </td>
                        <td class="px-3 sm:px-6 py-3 text-xs sm:text-sm text-slate-500">{{ $category->type }}</td>
                        <td class="px-3 sm:px-6 py-3 text-xs sm:text-sm text-slate-900">
                            <span class="">{{ $category->equipment_count }}</span>
                        </td>

                        {{-- Interval Requirements --}}
                        <td class="px-3 sm:px-6 py-3">

                            @foreach ($intervalTypes as $freq)
                                @php
                                    $count = $category->intervals->where('interval', $freq)->count();
                                @endphp

                                @if ($count > 0)
                                    <span class="inline-flex items-center border border-[#ECEDEA] px-1 sm:px-2 py-1 text-xs font-medium rounded-md bg-[#FAFAF8] text-slate-700 gap-1 sm:gap-2">
                                        <div class="w-1.5 h-1.5 rounded-full {{ frequency_label_class($freq) }}"></div>
                                        {{ $freq }}: {{ $count }}
                                    </span>
                                @endif
                            @endforeach
                        </td>

                        {{-- Actions --}}
                        <td class="px-3 sm:px-6 py-3">
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
                            <td colspan="5" class="px-3 sm:px-6 py-3 text-center text-sm text-[#667084]">No requirements defined for this vessel.</td>
                        </tr>
                    @endforelse
            </tbody>
        </table>
    </div>
</div>