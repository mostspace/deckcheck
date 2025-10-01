{{-- Maintenance Index --}}
<div id="maintenance-table" class="flex flex-col gap-3 bg-white sm:gap-4">

    {{-- Header & Controls --}}
    <div class="flex flex-col justify-between gap-3 sm:flex-row sm:items-center">
        <h2 class="text-base font-semibold text-slate-800 sm:text-lg">Maintenance Requirements</h2>
        <div class="flex items-center gap-2 sm:gap-3">
            {{-- Search --}}
            <div class="relative">
                <input id="category-search" type="text" placeholder="Search by name..."
                    class="h-9 rounded-lg border border-[#e4e7ec] py-2 pl-9 pr-4 text-sm transition focus:border-primary-500 focus:outline-none focus:ring-1 focus:ring-primary-500">
                <i class="fa-solid fa-search absolute left-3 top-1/2 -translate-y-1/2 transform text-[#5F6472]"></i>
            </div>

            <x-v2.components.ui.button :href="route('maintenance.create')" variant="primary" size="sm"
                class="flex h-9 items-center px-3 py-2 text-slate-900" icon="fa-solid fa-plus">
                <span class="hidden sm:block">Add New</span>
            </x-v2.components.ui.button>
        </div>
    </div>

    {{-- Table --}}
    <div class="overflow-x-auto rounded-lg border border-slate-200"
        style="scrollbar-width: thin; scrollbar-color: #cbd5e1 #f1f5f9;">
        <table class="w-full min-w-[800px]">
            <thead class="border-b border-slate-200">
                <tr>
                    <th
                        class="w-48 px-3 py-5 text-left text-xs font-medium tracking-wider text-slate-500 sm:px-6 sm:text-sm">
                        <button data-sort-key="name" type="button"
                            class="sort-button flex items-center tracking-wider text-slate-500 hover:text-slate-800">
                            Category <i class="fa-solid fa-sort ml-1 text-sm text-[#475466] transition-colors"></i>
                        </button>
                    </th>

                    <th
                        class="w-32 px-3 py-5 text-left text-xs font-medium tracking-wider text-slate-500 sm:px-6 sm:text-sm">
                        <button data-sort-key="type" type="button"
                            class="sort-button flex items-center tracking-wider text-slate-500 hover:text-slate-800">
                            Type <i class="fa-solid fa-sort ml-1 text-sm text-[#475466] transition-colors"></i>
                        </button>
                    </th>

                    <th
                        class="w-32 px-3 py-5 text-left text-xs font-medium tracking-wider text-slate-500 sm:px-6 sm:text-sm">
                        Affected Equipment</th>
                    <th
                        class="w-48 px-3 py-5 text-left text-xs font-medium tracking-wider text-slate-500 sm:px-6 sm:text-sm">
                        Intervals</th>
                    <th
                        class="w-24 px-3 py-5 text-left text-xs font-medium tracking-wider text-slate-500 sm:px-6 sm:text-sm">
                        Actions</th>
                </tr>
            </thead>

            {{-- Category Loop --}}
            <tbody class="divide-y divide-slate-200 bg-white" id="category-list">

                @forelse ($categories as $category)

                    <tr class="hover:bg-slate-50" data-name="{{ strtolower($category->name) }}"
                        data-type="{{ strtolower($category->type) }}">
                        <td class="w-48 px-3 py-3 text-xs text-slate-900 sm:px-6 sm:text-sm">
                            <div class="flex items-center">
                                <div class="name flex items-center">
                                    <div class="mr-3 flex h-8 w-8 items-center justify-center">
                                        <i class="fa-solid {{ $category->icon }}"></i>
                                    </div>
                                    <span class="">{{ $category->name ?? '—' }}</span>
                                </div>
                            </div>
                        </td>
                        <td class="w-32 px-3 py-3 text-xs text-slate-500 sm:px-6 sm:text-sm">{{ $category->type }}</td>
                        <td class="w-32 px-3 py-3 text-xs text-slate-900 sm:px-6 sm:text-sm">
                            <span class="">{{ $category->equipment_count }}</span>
                        </td>

                        {{-- Interval Requirements --}}
                        <td class="w-48 px-3 py-3 sm:px-6">

                            @foreach ($intervalTypes as $freq)
                                @php
                                    $count = $category->intervals->where('interval', $freq)->count();
                                @endphp

                                @if ($count > 0)
                                    <span
                                        class="inline-flex items-center gap-1 rounded-md border border-[#ECEDEA] bg-[#FAFAF8] px-1 py-1 text-xs font-medium text-slate-700 sm:gap-2 sm:px-2">
                                        <div class="{{ frequency_label_class($freq) }} h-1.5 w-1.5 rounded-full"></div>
                                        {{ $freq }}: {{ $count }}
                                    </span>
                                @endif
                            @endforeach
                        </td>

                        {{-- Actions --}}
                        <td class="w-24 px-3 py-3 sm:px-6">
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
                        <td colspan="5" class="px-3 py-3 text-center text-sm text-[#667084] sm:px-6">No requirements
                            defined for this vessel.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
