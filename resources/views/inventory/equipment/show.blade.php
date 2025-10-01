@extends('layouts.app')

@section('title', 'Equipment Data | ' . $equipment->name)

@section('content')

    @php
        use Illuminate\Support\Str;

        // Get the Referer header
        $referer = request()->headers->get('referer', '');
        $refererPath = parse_url($referer, PHP_URL_PATH) ?: '';

        // Route paths (relative)
        $maintenanceIndexPath = route('maintenance.index', [], false);
        $categoryShowPathPattern = route('maintenance.show', ['category' => $equipment->category->id], false);
        $equipmentIndexPath = route('equipment.index', [], false);
    @endphp

    {{-- Breadcrumb --}}
    <div class="mb-4">
        <nav class="flex items-center space-x-2 text-sm">

            @if (Str::startsWith($refererPath, $maintenanceIndexPath))
                <a href="{{ route('maintenance.index') }}" class="text-[#6840c6] hover:text-[#5a35a8]">
                    Maintenance Index
                </a>
                <i class="fa-solid fa-chevron-right text-xs text-[#667084]"></i>
                <a href="{{ route('maintenance.show', $equipment->category) }}" class="text-[#6840c6] hover:text-[#5a35a8]">
                    {{ $equipment->category->name }}
                </a>
                <i class="fa-solid fa-chevron-right text-xs text-[#667084]"></i>
                <span class="text-[#475466]">{{ $equipment->name }}</span>
            @elseif(Str::startsWith($refererPath, $categoryShowPathPattern))
                <a href="{{ route('maintenance.showCategory', $equipment->category) }}"
                    class="text-[#6840c6] hover:text-[#5a35a8]">
                    {{ $equipment->category->name }}
                </a>
                <i class="fa-solid fa-chevron-right text-xs text-[#667084]"></i>
                <span class="text-[#475466]">{{ $equipment->name }}</span>
            @elseif(Str::startsWith($refererPath, $equipmentIndexPath))
                <a href="{{ route('equipment.index') }}" class="text-[#6840c6] hover:text-[#5a35a8]">
                    Equipment
                </a>
                <i class="fa-solid fa-chevron-right text-xs text-[#667084]"></i>
                <span class="text-[#475466]">{{ $equipment->name }}</span>
            @else
                <a href="{{ route('equipment.index') }}" class="text-[#6840c6] hover:text-[#5a35a8]">
                    Equipment Index
                </a>
                <i class="fa-solid fa-chevron-right text-xs text-[#667084]"></i>
                <span class="text-[#475466]">{{ $equipment->name }}</span>
            @endif

        </nav>
    </div>

    {{-- System Messages --}}
    @if (session('success'))
        <div class="mb-6 rounded-lg border border-green-300 bg-green-100 p-4 text-sm text-green-800">
            {{ session('success') }}
        </div>
    @endif

    {{-- Header --}}
    <div class="mb-6 flex flex-col gap-6 lg:flex-row">

        {{-- #Hero Photo --}}
        <div class="flex-shrink-0">
            <div
                class="flex h-[149.8px] w-48 items-center justify-center overflow-hidden rounded-lg border border-[#e4e7ec] bg-[#f8f9fb]">
                <img src="{{ $equipment->hero_photo ? Storage::url($equipment->hero_photo) : asset('images/placeholders/placeholder.png') }}"
                    alt="Hero Photo for {{ $equipment->name }}" class="h-full w-full object-cover">
            </div>
        </div>

        <div class="flex-1">
            <div>

                {{-- #Title --}}
                <div class="flex gap-2">

                    {{-- ##Category Icon --}}
                    <div class="mr-1 flex h-8 w-8 items-center justify-center rounded-md border bg-[#f9f5ff]">
                        <a href="{{ route('maintenance.show', $equipment->category) }}">
                            <i class="fa-solid {{ $equipment->category->icon }} text-[#6840c6] hover:text-[#7e56d8]"></i>
                        </a>
                    </div>

                    {{-- ##Equipment Name --}}
                    <h1 class="mb-2 text-2xl font-semibold text-[#0f1728]">{{ $equipment->name ?? 'Unamed' }}</h1>

                    {{-- ##Edit --}}
                    <button id="basic-modal-open" class="text-[#6840c6] hover:text-[#7e56d8]">
                        <i class="fa-solid fa-edit"></i>
                    </button>
                </div>

                {{-- #Location --}}
                <div class="mb-4 flex items-center gap-1">
                    <i class="fa-solid fa-location-dot text-sm text-[#6840c6]"></i>
                    <span class="text-sm font-bold text-[#484f5d]">{{ $equipment->deck->name }}</span>
                    <span class="text-sm text-[#667084]">/ {{ $equipment->location->name }}</span>

                    {{-- ##Location Help --}}
                    <button id="location-description-modal-open" class="text-sm text-[#667084] hover:text-[#6840c6]">
                        <i class="fa-solid fa-circle-info"></i>
                    </button>
                </div>

                {{-- #Status Cards --}}
                <div class="flex flex-col gap-3 sm:flex-row">

                    {{-- ##Status --}}
                    <div class="{{ status_label_class($equipment->status) }} min-w-[140px] rounded-lg border p-4">
                        <div class="mb-1 flex items-center gap-2">
                            <i class="{{ status_label_icon($equipment->status) }}"></i>
                            <span class="text-sm font-medium">{{ $equipment->status ?? 'Unknown Status' }}</span>
                        </div>
                        <p class="text-xs text-[#475466]">Commissioned
                            {{ $equipment->in_service ? $equipment->in_service->format('F j, Y') : '—' }}</p>
                    </div>

                    {{-- ##Compliance --}}
                    <div class="min-w-[140px] rounded-lg border border-[#fecdca] bg-[#fef3f2] p-4">
                        <div class="mb-1 flex items-center gap-2">
                            <i class="fa-solid fa-triangle-exclamation text-[#b42318]"></i>
                            <span class="text-sm font-medium text-[#b42318]">## Overdue</span>
                        </div>
                        <p class="text-xs text-[#475466]">Test</p>
                    </div>

                    {{-- ##Scheduled --}}
                    <div class="min-w-[140px] rounded-lg border border-[#fed7aa] bg-[#fffaeb] p-4">
                        <div class="mb-1 flex items-center gap-2">
                            <i class="fa-solid fa-clock text-[#dc6803]"></i>
                            <span class="text-sm font-medium text-[#dc6803]">## Upcoming</span>
                        </div>
                        <p class="text-xs text-[#475466]">Test</p>
                    </div>

                </div>

            </div>
        </div>
    </div>

    {{-- Equipment Data & Attributes --}}
    <div class="mb-6 grid grid-cols-1 gap-6 lg:grid-cols-2">

        {{-- #Data Title Block --}}
        <div class="rounded-lg border border-[#e4e7ec] bg-white shadow-sm">
            <div class="flex items-center justify-between border-b border-[#e4e7ec] px-6 py-4">
                <h2 class="text-lg font-semibold text-[#0f1728]">Equipment Information</h2>

                {{-- ##Edit Equipment Data --}}
                <button id="edit-equipment-info-btn" class="text-right text-[#6840c6] hover:text-[#7e56d8]">
                    <i class="fa-solid fa-edit"></i>
                </button>
            </div>

            {{-- #Data --}}
            <div class="p-6">
                <div class="space-y-4">
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="mb-1 block text-sm font-light text-[#667084]">Manufacturer</label>
                            <p class="text-sm font-bold text-[#344053]">{{ $equipment->manufacturer ?? '—' }}</p>
                        </div>
                        <div>
                            <label class="mb-1 block text-sm font-light text-[#667084]">Model / Part #</label>
                            <p class="text-sm font-bold text-[#344053]">{{ $equipment->model ?? '—' }}</p>
                        </div>
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="mb-1 block text-sm font-light text-[#667084]">Serial Number</label>
                            <p class="text-sm font-bold text-[#344053]">{{ $equipment->serial_number ?? '—' }}</p>
                        </div>
                        <div>
                            <label class="mb-1 block text-sm font-light text-[#667084]">Custom ID</label>
                            <p class="text-sm font-bold text-[#344053]">{{ $equipment->internal_id ?? '—' }}</p>
                        </div>
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="mb-1 block text-sm font-light text-[#667084]">Purchase Date</label>
                            <p class="text-sm font-bold text-[#344053]">
                                {{ $equipment->purchase_date ? $equipment->purchase_date->format('F j, Y') : '—' }}
                            </p>
                        </div>
                        <div>
                            <label class="mb-1 block text-sm font-light text-[#667084]">Manufacturing Date</label>
                            <p class="text-sm font-bold text-[#344053]">
                                {{ $equipment->manufacturing_date ? $equipment->manufacturing_date->format('F j, Y') : '—' }}
                            </p>
                        </div>
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="mb-1 block text-sm font-light text-[#667084]">In Service Date</label>
                            <p class="text-sm font-bold text-[#344053]">
                                {{ $equipment->in_service ? $equipment->in_service->format('F j, Y') : '—' }}
                            </p>
                        </div>
                        <div>
                            <label class="mb-1 block text-sm font-light text-[#667084]">End of Life</label>
                            <p class="text-sm font-bold text-[#b42318]">
                                {{ $equipment->expiry_date ? $equipment->expiry_date->format('F j, Y') : 'Not Applicable' }}
                            </p>
                        </div>
                    </div>

                </div>
            </div>
        </div>

        {{-- #Attributes JSON Table --}}
        <div class="rounded-lg border border-[#e4e7ec] bg-white shadow-sm">

            {{-- ##Title Block --}}
            <div class="flex items-center justify-between border-b border-[#e4e7ec] px-6 py-4">
                <h2 class="text-lg font-semibold text-[#0f1728]">Attributes</h2>

                {{-- ##Edit Attributes --}}
                <button id="open-attributes-modal" class="text-right text-[#6840c6] hover:text-[#7e56d8]">
                    <i class="fa-solid fa-edit"></i>
                </button>
            </div>

            {{-- ##Table --}}
            <div class="overflow-hidden">
                <table class="w-full">
                    <thead class="bg-[#f8f9fb]">
                        <tr>
                            <th
                                class="border-b border-[#e4e7ec] px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-[#475466]">
                                Attribute
                            </th>
                            <th
                                class="border-b border-[#e4e7ec] px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-[#475466]">
                                Value
                            </th>
                        </tr>
                    </thead>

                    <tbody class="divide-y divide-[#e4e7ec]">
                        {{-- ###Attributes Loop --}}
                        @forelse ($equipment->attributes_json ?? [] as $key => $value)
                            <tr>
                                <td class="px-6 py-3 text-sm font-medium text-[#667084]">
                                    {{ $key }}
                                </td>
                                <td class="px-6 py-3 text-sm text-[#344053]">
                                    {{ $value }}
                                </td>
                            </tr>

                        @empty

                            <tr>
                                <td colspan="2" class="px-6 py-3 text-sm italic text-[#667084]">
                                    No attributes defined.
                                </td>
                            </tr>
                        @endforelse

                    </tbody>
                </table>
            </div>

        </div>
    </div>

    {{-- Equipment Attachments --}}
    <div class="mt-6 rounded-lg border border-[#e4e7ec] bg-white shadow-sm">
        <div class="border-b border-[#e4e7ec] px-6 py-4">
            <h2 class="text-lg font-semibold text-[#0f1728]">Equipment Attachments</h2>
        </div>
        <div class="p-6">
            {{-- Debug Info --}}
            @if (config('app.debug'))
                <div class="mb-4 rounded bg-gray-100 p-3 text-xs">
                    <strong>Debug:</strong> Equipment ID: {{ $equipment->id }},
                    Vessel ID: {{ $equipment->vessel_id ?? 'null' }},
                    Vessel: {{ $equipment->vessel->name ?? 'null' }}
                </div>
            @endif
        </div>
    </div>

    {{-- Maintenance Intervals --}}
    <div class="mt-6 rounded-lg border border-[#e4e7ec] bg-white shadow-sm">

        {{-- #Title Block --}}
        <div class="flex border-b border-[#e4e7ec] px-6 py-4">
            <h2 class="text-lg font-semibold text-[#0f1728]">Maintenance Schedule</h2>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-[#f8f9fb]">
                    <tr>
                        <th
                            class="border-b border-[#e4e7ec] px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-[#475466]">
                            Interval
                        </th>
                        <th
                            class="border-b border-[#e4e7ec] px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-[#475466]">
                            Requirement Name
                        </th>
                        <th
                            class="border-b border-[#e4e7ec] px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-[#475466]">
                            # of Tasks
                        </th>
                        <th
                            class="border-b border-[#e4e7ec] px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-[#475466]">
                            Facilitator
                        </th>
                        <th
                            class="border-b border-[#e4e7ec] px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-[#475466]">
                            Last Performed
                        </th>
                        <th
                            class="border-b border-[#e4e7ec] px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-[#475466]">
                            Next Due Date
                        </th>
                        <th
                            class="border-b border-[#e4e7ec] px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-[#475466]">
                        </th>

                    </tr>
                </thead>

                {{-- #Intervals Loop --}}
                <tbody class="divide-y divide-[#e4e7ec]">

                    @foreach ($equipment->intervals as $interval)
                        @php
                            $lastWorkOrder = $interval->workOrders
                                ->whereNotNull('completed_at')
                                ->sortByDesc('completed_at')
                                ->first();

                            $nextWorkOrder = $interval->workOrders
                                ->whereNull('completed_at')
                                ->sortBy('due_date')
                                ->first();
                        @endphp

                        <tr>
                            <td class="whitespace-nowrap px-6 py-4">
                                <span
                                    class="{{ frequency_label_class($interval->frequency) }} rounded-full px-2 py-1 text-xs font-medium">
                                    {{ $interval->frequency }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-sm text-[#344053]">
                                {{ $interval->description }}
                            </td>
                            <td class="px-6 py-4 text-sm text-[#344053]">
                                {{ $interval->workOrders->first()?->tasks->count() ?? 0 }}
                            </td>
                            <td class="px-6 py-4 text-sm text-[#344053]">
                                @php
                                    $labelClass =
                                        $interval->facilitator === 'crew'
                                            ? 'bg-[#ebfdf2] text-[#027947]'
                                            : 'bg-[#fef6ee] text-[#c4320a]';
                                @endphp
                                <span
                                    class="{{ facilitator_label_class($interval->facilitator) }} rounded px-2 py-1 text-xs font-medium">
                                    {{ ucfirst($interval->facilitator) }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-sm text-[#344053]">
                                {!! last_completed_badge(optional($lastWorkOrder?->completed_at)?->toDateString()) !!}
                            </td>
                            <td class="px-6 py-4 text-sm">
                                {!! next_due_badge(optional($nextWorkOrder?->due_date)?->toDateString()) !!}
                            </td>
                            <td class="px-6 py-4 text-sm font-medium">
                                <button onclick="window.location='{{ route('equipment-intervals.show', $interval) }}'"
                                    class="rounded-lg p-2 text-[#667084] transition-colors hover:bg-[#f8f9fb] hover:text-[#344053]">
                                    <i class="fa-solid fa-eye"></i>
                                </button>
                            </td>
                        </tr>
                    @endforeach

                </tbody>
            </table>

        </div>
    </div>

    {{-- Deficiencies --}}
    <div class="mt-6 rounded-lg border border-[#e4e7ec] bg-white shadow-sm">

        {{-- #Title Block --}}
        <div class="border-b border-[#e4e7ec] px-6 py-4">
            <h2 class="text-lg font-semibold text-[#0f1728]">Deficiencies</h2>
        </div>

        {{-- #Toggle Switch and Create Button --}}
        <div class="flex items-center justify-between border-b border-[#e4e7ec] px-6 py-3">
            <label class="flex cursor-pointer items-center">
                <input type="checkbox" id="show-resolved-toggle" class="sr-only">
                <div class="relative">
                    <div class="block h-6 w-10 rounded-full bg-[#e4e7ec]"></div>
                    <div
                        class="dot absolute left-1 top-1 h-4 w-4 rounded-full bg-white transition-transform duration-200 ease-in-out">
                    </div>
                </div>
                <span class="ml-3 text-sm font-medium text-[#475466]">Show Resolved</span>
            </label>

            <button id="openDeficiencyModal"
                class="flex items-center space-x-2 rounded-lg bg-[#6840c6] px-4 py-2 text-sm font-medium text-white transition-colors duration-200 hover:bg-[#5a35a8]">
                <i class="fa-solid fa-plus"></i>
                <span>New Deficiency</span>
            </button>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-[#f8f9fb]">
                    <tr>
                        <th
                            class="border-b border-[#e4e7ec] px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-[#475466]">
                            ID
                        </th>
                        <th
                            class="border-b border-[#e4e7ec] px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-[#475466]">
                            Status
                        </th>
                        <th
                            class="border-b border-[#e4e7ec] px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-[#475466]">
                            Subject
                        </th>
                        <th
                            class="border-b border-[#e4e7ec] px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-[#475466]">
                            Date Opened
                        </th>
                        <th
                            class="border-b border-[#e4e7ec] px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-[#475466]">
                            Priority
                        </th>
                        <th
                            class="border-b border-[#e4e7ec] px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-[#475466]">

                        </th>
                    </tr>
                </thead>

                {{-- #Deficiencies Loop --}}
                <tbody class="divide-y divide-[#e4e7ec]" id="deficiencies-tbody">
                    @if ($deficiencies->whereIn('status', ['open', 'waiting'])->count() > 0)
                        @foreach ($deficiencies->whereIn('status', ['open', 'waiting']) as $deficiency)
                            <tr class="deficiency-row transition-colors duration-200 hover:bg-[#f8f9fb]"
                                data-status="{{ $deficiency->status }}">
                                <td class="px-6 py-4 text-sm font-medium">
                                    <a href="{{ route('deficiencies.show', $deficiency) }}"
                                        class="text-[#6840c6] transition-colors duration-200 hover:text-[#5a35a8] hover:underline">
                                        {{ $deficiency->display_id ?: '#' . $deficiency->id }}
                                    </a>
                                </td>
                                <td class="whitespace-nowrap px-6 py-4">
                                    @if ($deficiency->status === 'open')
                                        <span
                                            class="rounded-full bg-[#fef3f2] px-2 py-1 text-xs font-medium text-[#b42318]">Open</span>
                                    @elseif($deficiency->status === 'waiting')
                                        <span
                                            class="rounded-full bg-[#fef7ed] px-2 py-1 text-xs font-medium text-[#c4320a]">Waiting</span>
                                    @else
                                        <span
                                            class="rounded-full bg-[#ecfdf3] px-2 py-1 text-xs font-medium text-[#027a48]">Resolved</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 text-sm text-[#344053]">
                                    {{ $deficiency->subject ?: 'No subject' }}
                                </td>
                                <td class="px-6 py-4 text-sm text-[#344053]">
                                    {{ $deficiency->created_at->format('M j, Y') }}
                                </td>
                                <td class="whitespace-nowrap px-6 py-4">
                                    @if ($deficiency->priority === 'high')
                                        <span
                                            class="rounded-full bg-[#fef3f2] px-2 py-1 text-xs font-medium text-[#b42318]">High</span>
                                    @elseif($deficiency->priority === 'medium')
                                        <span
                                            class="rounded-full bg-[#fef7ed] px-2 py-1 text-xs font-medium text-[#c4320a]">Medium</span>
                                    @elseif($deficiency->priority === 'low')
                                        <span
                                            class="rounded-full bg-[#ecfdf3] px-2 py-1 text-xs font-medium text-[#027a48]">Low</span>
                                    @else
                                        <span
                                            class="rounded-full bg-[#f1f5f9] px-2 py-1 text-xs font-medium text-[#475466]">Unset</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4">
                                    <a href="{{ route('deficiencies.show', $deficiency) }}"
                                        class="transform rounded-lg p-2 text-[#667084] transition-all duration-200 hover:scale-110 hover:bg-[#f4ebff] hover:text-[#6840c6]">
                                        <i class="fa-solid fa-arrow-right"></i>
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                    @else
                        <tr>
                            <td colspan="6" class="px-6 py-8 text-center text-sm text-[#667084]">
                                No Open Deficiencies to Display
                            </td>
                        </tr>
                    @endif
                </tbody>

            </table>
        </div>

    </div>

    {{-- Deficiency Create Modal --}}
    <div id="deficiencyCreateModal"
        class="fixed right-0 top-0 z-50 flex h-full w-full max-w-md translate-x-full transform flex-col bg-white shadow-2xl transition-transform duration-300 ease-in-out">
        {{-- HEADER --}}
        <header class="flex items-center justify-between border-b border-[#e4e7ec] px-6 py-4">
            <div class="flex items-center space-x-2">
                {{-- Main Action --}}
                <h2 class="text-2xl font-semibold text-[#0f1728]">
                    New Deficiency
                </h2>

                {{-- Arrow Separator --}}
                <i class="fa-solid fa-arrow-right text-gray-400"></i>

                {{-- Equipment Name --}}
                <div class="flex items-center space-x-1">
                    <div class="flex h-8 w-8 items-center justify-center rounded-md border border-[#e4e7ec] bg-[#f9f5ff]">
                        <i class="fa-solid fa-exclamation-triangle text-[#6840c6]"></i>
                    </div>
                    <span class="text-2xl font-semibold text-[#6840c6]">
                        {{ $equipment->name }}
                    </span>
                </div>
            </div>

            {{-- Close --}}
            <button id="closeDeficiencyModal" class="text-gray-500 hover:text-gray-800">
                <i class="fa-solid fa-xmark fa-lg"></i>
            </button>
        </header>

        {{-- FORM --}}
        <form id="deficiencyForm" action="{{ route('deficiencies.store') }}" method="POST"
            class="flex flex-1 flex-col overflow-hidden">
            @csrf
            <input type="hidden" name="equipment_id" value="{{ $equipment->id }}">

            {{-- FORM BODY (scrollable) --}}
            <div class="flex-1 overflow-y-auto px-6 py-4">
                <div class="space-y-6">
                    {{-- Subject Field --}}
                    <div>
                        <label for="subject" class="mb-2 block text-sm font-medium text-[#374151]">
                            Subject <span class="text-red-500">*</span>
                        </label>
                        <input type="text" id="subject" name="subject" required
                            class="w-full rounded-lg border border-[#d1d5db] px-3 py-2 transition-colors duration-200 focus:border-[#6840c6] focus:ring-2 focus:ring-[#6840c6]"
                            placeholder="Enter deficiency subject">
                    </div>

                    {{-- Description Field --}}
                    <div>
                        <label for="description" class="mb-2 block text-sm font-medium text-[#374151]">
                            Description
                        </label>
                        <textarea id="description" name="description" rows="4"
                            class="w-full resize-none rounded-lg border border-[#d1d5db] px-3 py-2 transition-colors duration-200 focus:border-[#6840c6] focus:ring-2 focus:ring-[#6840c6]"
                            placeholder="Provide additional details about the deficiency (optional)"></textarea>
                    </div>

                    {{-- Priority Field --}}
                    <div>
                        <label for="priority" class="mb-2 block text-sm font-medium text-[#374151]">
                            Priority <span class="text-red-500">*</span>
                        </label>
                        <select id="priority" name="priority" required
                            class="w-full rounded-lg border border-[#d1d5db] px-3 py-2 transition-colors duration-200 focus:border-[#6840c6] focus:ring-2 focus:ring-[#6840c6]">
                            <option value="low" selected>Low</option>
                            <option value="medium">Medium</option>
                            <option value="high">High</option>
                        </select>
                    </div>
                </div>
            </div>

            {{-- FOOTER --}}
            <footer
                class="flex flex-shrink-0 items-center justify-end space-x-3 border-t border-[#e4e7ec] bg-white px-6 py-4">
                <button type="button" id="cancelDeficiencyModal"
                    class="rounded-lg border border-[#d1d5db] px-4 py-2 text-sm text-[#374151] transition-colors duration-200 hover:bg-[#f9fafb]">
                    Cancel
                </button>
                <button type="submit"
                    class="rounded-lg bg-[#6840c6] px-5 py-2 text-sm font-medium text-white transition-colors duration-200 hover:bg-[#5a35a8]">
                    Create Deficiency
                </button>
            </footer>
        </form>
    </div>

    {{-- CSS for Toggle Switch --}}
    <style>
        #show-resolved-toggle:checked+.relative .block {
            background-color: #6840c6;
        }

        #show-resolved-toggle:checked+.relative .dot {
            transform: translateX(16px);
        }
    </style>

    {{-- JavaScript for Toggle Functionality --}}
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const toggle = document.getElementById('show-resolved-toggle');
            const tbody = document.getElementById('deficiencies-tbody');
            const allDeficiencies = @json($deficiencies);

            toggle.addEventListener('change', function() {
                const showResolved = toggle.checked;
                const filteredDeficiencies = showResolved ?
                    allDeficiencies :
                    allDeficiencies.filter(d => ['open', 'waiting'].includes(d.status));

                // Clear existing rows
                tbody.innerHTML = '';

                if (filteredDeficiencies.length > 0) {
                    filteredDeficiencies.forEach(function(deficiency) {
                        const row = document.createElement('tr');
                        row.className =
                            'deficiency-row hover:bg-[#f8f9fb] transition-colors duration-200';
                        row.setAttribute('data-status', deficiency.status);

                        // Format date
                        const date = new Date(deficiency.created_at);
                        const formattedDate = date.toLocaleDateString('en-US', {
                            month: 'short',
                            day: 'numeric',
                            year: 'numeric'
                        });

                        // Status badge
                        let statusBadge = '';
                        if (deficiency.status === 'open') {
                            statusBadge =
                                '<span class="px-2 py-1 text-xs font-medium bg-[#fef3f2] text-[#b42318] rounded-full">Open</span>';
                        } else if (deficiency.status === 'waiting') {
                            statusBadge =
                                '<span class="px-2 py-1 text-xs font-medium bg-[#fef7ed] text-[#c4320a] rounded-full">Waiting</span>';
                        } else {
                            statusBadge =
                                '<span class="px-2 py-1 text-xs font-medium bg-[#ecfdf3] text-[#027a48] rounded-full">Resolved</span>';
                        }

                        // Priority badge
                        let priorityBadge = '';
                        if (deficiency.priority === 'high') {
                            priorityBadge =
                                '<span class="px-2 py-1 text-xs font-medium bg-[#fef3f2] text-[#b42318] rounded-full">High</span>';
                        } else if (deficiency.priority === 'medium') {
                            priorityBadge =
                                '<span class="px-2 py-1 text-xs font-medium bg-[#fef7ed] text-[#c4320a] rounded-full">Medium</span>';
                        } else if (deficiency.priority === 'low') {
                            priorityBadge =
                                '<span class="px-2 py-1 text-xs font-medium bg-[#ecfdf3] text-[#027a48] rounded-full">Low</span>';
                        } else {
                            priorityBadge =
                                '<span class="px-2 py-1 text-xs font-medium bg-[#f1f5f9] text-[#475466] rounded-full">Unset</span>';
                        }

                        // Subject
                        const subject = deficiency.subject || 'No subject';

                        // Display ID
                        const displayId = deficiency.display_id || '#' + deficiency.id;

                        row.innerHTML = `
                            <td class="px-6 py-4 text-sm font-medium">
                                <a href="/deficiencies/${deficiency.id}" class="text-[#6840c6] hover:text-[#5a35a8] hover:underline transition-colors duration-200">
                                    ${displayId}
                                </a>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">${statusBadge}</td>
                            <td class="px-6 py-4 text-sm text-[#344053]">${subject}</td>
                            <td class="px-6 py-4 text-sm text-[#344053]">${formattedDate}</td>
                            <td class="px-6 py-4 whitespace-nowrap">${priorityBadge}</td>
                            <td class="px-6 py-4">
                                <a href="/deficiencies/${deficiency.id}" class="p-2 text-[#667084] hover:text-[#6840c6] hover:bg-[#f4ebff] rounded-lg transition-all duration-200 transform hover:scale-110">
                                    <i class="fa-solid fa-arrow-right"></i>
                                </a>
                            </td>
                        `;

                        tbody.appendChild(row);
                    });
                } else {
                    const noDataRow = document.createElement('tr');
                    const message = showResolved ? 'No Deficiencies to Display' :
                        'No Open Deficiencies to Display';
                    noDataRow.innerHTML = `
                        <td colspan="6" class="px-6 py-8 text-center text-sm text-[#667084]">
                            ${message}
                        </td>
                    `;
                    tbody.appendChild(noDataRow);
                }
            });
        });
    </script>

    {{-- JavaScript for Deficiency Modal --}}
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const modal = document.getElementById('deficiencyCreateModal');

            function openDeficiencyModal() {
                modal.classList.remove('translate-x-full');
            }

            function closeDeficiencyModal() {
                modal.classList.add('translate-x-full');
                // Reset form
                document.getElementById('deficiencyForm').reset();
            }

            // Open / Close
            document.getElementById('openDeficiencyModal')
                .addEventListener('click', openDeficiencyModal);
            document.getElementById('closeDeficiencyModal')
                .addEventListener('click', closeDeficiencyModal);
            document.getElementById('cancelDeficiencyModal')
                .addEventListener('click', closeDeficiencyModal);

            // ESC key
            document.addEventListener('keydown', e => {
                if (e.key === 'Escape' && !modal.classList.contains('translate-x-full')) {
                    closeDeficiencyModal();
                }
            });

            // Form submission
            document.getElementById('deficiencyForm').addEventListener('submit', function(e) {
                e.preventDefault();

                const formData = new FormData(this);

                fetch(this.action, {
                        method: 'POST',
                        body: formData,
                        headers: {
                            'X-Requested-With': 'XMLHttpRequest'
                        }
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            // Close modal
                            closeDeficiencyModal();

                            // Reload the page to show the new deficiency
                            window.location.reload();
                        } else {
                            // Handle validation errors
                            console.error('Error creating deficiency:', data);
                            alert('Error creating deficiency. Please try again.');
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        alert('Error creating deficiency. Please try again.');
                    });
            });

            // View Resource Function
            window.viewResource = function(fileId, mimeType, displayName) {
                // For images, videos, and audio, open in lightbox
                if (mimeType.startsWith('image/') || mimeType.startsWith('video/') || mimeType.startsWith(
                        'audio/')) {
                    // Show loading lightbox immediately
                    const lightbox = document.createElement('div');
                    lightbox.className =
                        'fixed inset-0 bg-black bg-opacity-90 z-50 flex items-center justify-center p-4';
                    lightbox.id = 'resource-lightbox';

                    // Loading content
                    lightbox.innerHTML = `
                        <div class="relative bg-white rounded-xl shadow-2xl border border-gray-200 overflow-hidden min-w-96">
                            <!-- Close Button -->
                            <button class="absolute top-4 right-4 w-10 h-10 bg-black bg-opacity-50 hover:bg-opacity-75 text-white rounded-full flex items-center justify-center transition-all duration-200 hover:scale-110 z-10" onclick="window.closeResourceLightbox()">
                                <i class="fa-solid fa-times text-lg"></i>
                            </button>
                            
                            <!-- Loading Content -->
                            <div class="p-12 text-center">
                                <div class="animate-spin rounded-full h-12 w-12 border-b-2 border-[#6840c6] mx-auto mb-4"></div>
                                <p class="text-gray-600">Loading resource...</p>
                            </div>
                        </div>
                    `;

                    // Add click outside to close
                    lightbox.onclick = (e) => {
                        if (e.target === lightbox) {
                            window.closeResourceLightbox();
                        }
                    };

                    document.body.appendChild(lightbox);

                    // Add escape key listener
                    document.addEventListener('keydown', window.handleResourceEscapeKey);

                    // Get file details and load content
                    fetch(`/files/${fileId}`)
                        .then(response => response.json())
                        .then(data => {
                            if (data.success) {
                                const file = data.file;
                                const uploadDate = new Date(file.created_at).toLocaleDateString('en-US', {
                                    year: 'numeric',
                                    month: 'short',
                                    day: 'numeric'
                                });

                                let content;
                                if (mimeType.startsWith('image/')) {
                                    content =
                                        `<img src="/files/${fileId}/view" alt="${displayName}" class="max-w-4xl max-h-[70vh] object-contain rounded-lg shadow-2xl" onload="this.parentElement.parentElement.querySelector('.loading-content').style.display='none'">`;
                                } else if (mimeType.startsWith('video/')) {
                                    content =
                                        `<video controls class="max-w-4xl max-h-[70vh] rounded-lg shadow-2xl" onloadeddata="this.parentElement.parentElement.querySelector('.loading-content').style.display='none'"><source src="/files/${fileId}/view" type="${mimeType}"></video>`;
                                } else if (mimeType.startsWith('audio/')) {
                                    content =
                                        `<audio controls class="max-w-4xl rounded-lg shadow-2xl" oncanplay="this.parentElement.parentElement.querySelector('.loading-content').style.display='none'"><source src="/files/${fileId}/view" type="${mimeType}"></audio>`;
                                }

                                // Update lightbox with content
                                lightbox.innerHTML = `
                                    <div class="relative bg-white rounded-xl shadow-2xl border border-gray-200 overflow-hidden">
                                        <!-- Close Button -->
                                        <button class="absolute top-4 right-4 w-10 h-10 bg-black bg-opacity-50 hover:bg-opacity-75 text-white rounded-full flex items-center justify-center transition-all duration-200 hover:scale-110 z-10" onclick="window.closeResourceLightbox()">
                                            <i class="fa-solid fa-times text-lg"></i>
                                        </button>
                                        
                                        <!-- Content -->
                                        <div class="p-6">
                                            ${content}
                                        </div>
                                        
                                        <!-- Loading Overlay (hidden when content loads) -->
                                        <div class="loading-content absolute inset-0 bg-white bg-opacity-90 flex items-center justify-center">
                                            <div class="text-center">
                                                <div class="animate-spin rounded-full h-8 w-8 border-b-2 border-[#6840c6] mx-auto mb-2"></div>
                                                <p class="text-sm text-gray-600">Loading...</p>
                                            </div>
                                        </div>
                                        
                                        <!-- Metadata Footer -->
                                        <div class="bg-gray-50 px-6 py-4 border-t border-gray-200">
                                            <div class="flex items-center justify-between text-sm text-gray-600">
                                                <div class="flex-1">
                                                    <h3 class="font-semibold text-gray-900 mb-1">${displayName}</h3>
                                                    <p class="text-gray-500">Uploaded on ${uploadDate}</p>
                                                </div>
                                                <div class="text-right">
                                                    <p class="text-gray-500">by ${file.uploaded_by?.first_name || 'Unknown'} ${file.uploaded_by?.last_name || ''}</p>
                                                    <p class="text-xs text-gray-400">${(file.size / 1024 / 1024).toFixed(2)} MB</p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                `;
                            }
                        })
                        .catch(error => {
                            console.error('Error fetching file details:', error);
                            // Show error in lightbox
                            lightbox.innerHTML = `
                                <div class="relative bg-white rounded-xl shadow-2xl border border-gray-200 overflow-hidden min-w-96">
                                    <button class="absolute top-4 right-4 w-10 h-10 bg-black bg-opacity-50 hover:bg-opacity-75 text-white rounded-full flex items-center justify-center transition-all duration-200 hover:scale-110 z-10" onclick="window.closeResourceLightbox()">
                                        <i class="fa-solid fa-times text-lg"></i>
                                    </button>
                                    <div class="p-12 text-center">
                                        <div class="text-red-500 mb-4">
                                            <i class="fa-solid fa-exclamation-triangle text-4xl"></i>
                                        </div>
                                        <p class="text-gray-600">Failed to load resource</p>
                                        <p class="text-sm text-gray-400 mt-2">Please try again</p>
                                    </div>
                                </div>
                            `;
                        });
                } else {
                    // For PDFs and other documents, open in new tab
                    window.open(`/files/${fileId}/view`, '_blank');
                }
            };

            // Global close lightbox function
            window.closeResourceLightbox = function() {
                const lightbox = document.getElementById('resource-lightbox');
                if (lightbox) {
                    lightbox.remove();
                    document.removeEventListener('keydown', window.handleResourceEscapeKey);
                }
            };

            // Global escape key handler
            window.handleResourceEscapeKey = function(e) {
                if (e.key === 'Escape') {
                    window.closeResourceLightbox();
                }
            };

            // Delete Resource Function
            window.deleteResource = function(attachmentId, displayName) {
                if (confirm(
                        `Are you sure you want to delete "${displayName}"? This action cannot be undone.`)) {
                    fetch(`/attachments/${attachmentId}`, {
                            method: 'DELETE',
                            headers: {
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')
                                    .getAttribute('content'),
                                'X-Requested-With': 'XMLHttpRequest'
                            }
                        })
                        .then(response => response.json())
                        .then(data => {
                            if (data.success) {
                                // Reload the page to reflect the changes
                                window.location.reload();
                            } else {
                                alert('Error deleting resource: ' + (data.message || 'Please try again.'));
                            }
                        })
                        .catch(error => {
                            console.error('Error:', error);
                            alert('Error deleting resource. Please try again.');
                        });
                }
            };
        });
    </script>

    {{-- JavaScript for Resource Modal --}}
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const modal = document.getElementById('resourceCreateModal');
            const fileInput = document.getElementById('resource-file');
            const displayNameInput = document.getElementById('display_name');
            const descriptionInput = document.getElementById('description');
            const uploadButton = document.getElementById('uploadResource');

            // Auto-fill display name when file is selected
            fileInput.addEventListener('change', function() {
                if (this.files.length > 0) {
                    const fileName = this.files[0].name;
                    const nameWithoutExt = fileName.replace(/\.[^/.]+$/, "");
                    displayNameInput.value = nameWithoutExt;
                }
            });

            function openResourceModal() {
                modal.classList.remove('translate-x-full');
            }

            function closeResourceModal() {
                modal.classList.add('translate-x-full');
                // Reset form
                fileInput.value = '';
                displayNameInput.value = '';
                descriptionInput.value = '';
            }

            // Open / Close
            document.getElementById('openResourceModal')
                .addEventListener('click', openResourceModal);
            document.getElementById('closeResourceModal')
                .addEventListener('click', closeResourceModal);
            document.getElementById('cancelResourceModal')
                .addEventListener('click', closeResourceModal);

            // ESC key
            document.addEventListener('keydown', e => {
                if (e.key === 'Escape' && !modal.classList.contains('translate-x-full')) {
                    closeResourceModal();
                }
            });

            // Upload Resource
            uploadButton.addEventListener('click', function() {
                if (!fileInput.files.length) {
                    alert('Please select a file to upload.');
                    return;
                }

                if (!displayNameInput.value.trim()) {
                    alert('Please enter a display name for the resource.');
                    return;
                }

                const formData = new FormData();
                formData.append('file', fileInput.files[0]);
                formData.append('vessel_id', '{{ $equipment->vessel_id }}');
                formData.append('attachable_id', '{{ $equipment->id }}');
                formData.append('attachable_type', 'Equipment');
                formData.append('role', 'resource'); // Default role for all resources
                formData.append('description', descriptionInput.value);
                formData.append('display_name', displayNameInput.value.trim());

                // Debug logging
                console.log('Form data being sent:', {
                    display_name: displayNameInput.value.trim(),
                    file_name: fileInput.files[0].name,
                    description: descriptionInput.value
                });

                // Show loading state
                uploadButton.disabled = true;
                uploadButton.innerHTML =
                    '<i class="fa-solid fa-spinner fa-spin"></i><span>Uploading...</span>';

                fetch('{{ route('files.upload') }}', {
                        method: 'POST',
                        body: formData,
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')
                                .getAttribute('content'),
                            'X-Requested-With': 'XMLHttpRequest'
                        }
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            // Close modal
                            closeResourceModal();

                            // Reload the page to show the new resource
                            window.location.reload();
                        } else {
                            // Handle validation errors
                            console.error('Error uploading resource:', data);
                            alert('Error uploading resource: ' + (data.message || 'Please try again.'));
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        alert('Error uploading resource. Please try again.');
                    })
                    .finally(() => {
                        // Reset button state
                        uploadButton.disabled = false;
                        uploadButton.innerHTML =
                            '<i class="fa-solid fa-upload"></i><span>Upload Resource</span>';
                    });
            });
        });
    </script>

    {{-- Resources --}}
    <div class="mt-6 rounded-lg border border-[#e4e7ec] bg-white shadow-sm">

        {{-- #Title Block --}}
        <div class="flex items-center justify-between border-b border-[#e4e7ec] px-6 py-4">
            <h2 class="text-lg font-semibold text-[#0f1728]">Resources</h2>

            <button id="openResourceModal"
                class="flex items-center space-x-2 rounded-lg bg-[#6840c6] px-4 py-2 text-sm font-medium text-white transition-colors duration-200 hover:bg-[#5a35a8]">
                <i class="fa-solid fa-plus"></i>
                <span>Add Resource</span>
            </button>
        </div>

        <div class="p-6">
            @if ($equipment->hasAttachments())
                <div class="grid grid-cols-1 gap-4 md:grid-cols-2 lg:grid-cols-3">
                    @foreach ($equipment->attachments as $attachment)
                        <div
                            class="flex cursor-pointer items-center rounded-lg border border-[#e4e7ec] bg-[#f8f9fb] p-4 transition-colors hover:bg-[#f2f3f6]">
                            <div class="mr-3 flex h-10 w-10 items-center justify-center rounded-lg bg-[#f4ebff]">
                                @if ($attachment->isImage())
                                    <i class="fa-solid fa-image text-[#6840c6]"></i>
                                @elseif($attachment->isPdf())
                                    <i class="fa-solid fa-file-pdf text-[#6840c6]"></i>
                                @elseif(str_contains($attachment->file->mime_type, 'excel') || str_contains($attachment->file->mime_type, 'spreadsheet'))
                                    <i class="fa-solid fa-file-excel text-[#6840c6]"></i>
                                @elseif(str_contains($attachment->file->mime_type, 'word') || str_contains($attachment->file->mime_type, 'document'))
                                    <i class="fa-solid fa-file-word text-[#6840c6]"></i>
                                @else
                                    <i class="fa-solid fa-file text-[#6840c6]"></i>
                                @endif
                            </div>
                            <div class="flex-1">
                                <h3 class="text-sm font-medium text-[#0f1728]">{{ $attachment->display_name }}</h3>
                                <p class="text-xs text-[#667084]">{{ strtoupper($attachment->file->extension) }} •
                                    {{ $attachment->file->human_size }}</p>
                            </div>
                            <div class="flex items-center space-x-2">
                                {{-- View button for all files --}}
                                <button
                                    onclick="viewResource('{{ $attachment->file->id }}', '{{ $attachment->file->mime_type }}', '{{ $attachment->display_name }}')"
                                    class="text-[#667084] transition-colors hover:text-[#6840c6]" title="View Resource">
                                    <i class="fa-solid fa-eye"></i>
                                </button>

                                {{-- Download button --}}
                                <a href="{{ route('files.download', $attachment->file) }}"
                                    class="text-[#667084] transition-colors hover:text-[#6840c6]">
                                    <i class="fa-solid fa-download"></i>
                                </a>

                                {{-- Delete button --}}
                                <button
                                    onclick="deleteResource({{ $attachment->id }}, '{{ $attachment->display_name }}')"
                                    class="text-[#667084] transition-colors hover:text-red-500" title="Delete Resource">
                                    <i class="fa-solid fa-trash"></i>
                                </button>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="py-8 text-center text-[#667084]">
                    <div class="mx-auto mb-4 flex h-16 w-16 items-center justify-center rounded-full bg-[#f4ebff]">
                        <i class="fa-solid fa-file text-2xl text-[#6840c6]"></i>
                    </div>
                    <h3 class="mb-2 text-lg font-medium text-[#0f1728]">No Resources Yet</h3>
                    <p class="text-sm">Upload manuals, certificates, and other important documents to keep them organized
                        and accessible.</p>
                </div>
            @endif
        </div>
    </div>

    {{-- Resource Create Modal --}}
    <div id="resourceCreateModal"
        class="fixed right-0 top-0 z-50 flex h-full w-full max-w-md translate-x-full transform flex-col bg-white shadow-2xl transition-transform duration-300 ease-in-out">
        {{-- HEADER --}}
        <header class="flex items-center justify-between border-b border-[#e4e7ec] px-6 py-4">
            <div class="flex items-center space-x-2">
                {{-- Main Action --}}
                <h2 class="text-2xl font-semibold text-[#0f1728]">
                    Add Resource
                </h2>

                {{-- Arrow Separator --}}
                <i class="fa-solid fa-arrow-right text-gray-400"></i>

                {{-- Equipment Name --}}
                <div class="flex items-center space-x-1">
                    <div class="flex h-8 w-8 items-center justify-center rounded-md border border-[#e4e7ec] bg-[#f9f5ff]">
                        <i class="fa-solid fa-file text-[#6840c6]"></i>
                    </div>
                    <span class="text-2xl font-semibold text-[#6840c6]">
                        {{ $equipment->name }}
                    </span>
                </div>
            </div>

            {{-- Close --}}
            <button id="closeResourceModal" class="text-gray-500 hover:text-gray-800">
                <i class="fa-solid fa-xmark fa-lg"></i>
            </button>
        </header>

        {{-- FORM --}}
        <div class="flex flex-1 flex-col overflow-hidden">
            {{-- FORM BODY (scrollable) --}}
            <div class="flex-1 overflow-y-auto px-6 py-4">
                <div class="space-y-6">
                    {{-- File Upload --}}
                    <div>
                        <label class="mb-2 block text-sm font-medium text-[#374151]">
                            File <span class="text-red-500">*</span>
                        </label>
                        <div
                            class="rounded-lg border-2 border-dashed border-gray-300 p-6 text-center transition-colors hover:border-gray-400">
                            <div class="space-y-2">
                                <svg class="mx-auto h-12 w-12 text-gray-400" stroke="currentColor" fill="none"
                                    viewBox="0 0 48 48">
                                    <path
                                        d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02"
                                        stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                                </svg>

                                <div class="text-sm text-gray-600">
                                    <label for="resource-file"
                                        class="relative cursor-pointer rounded-md bg-white font-medium text-indigo-600 focus-within:outline-none focus-within:ring-2 focus-within:ring-indigo-500 focus-within:ring-offset-2 hover:text-indigo-500">
                                        <span>Upload file</span>
                                        <input id="resource-file" name="file" type="file" class="sr-only"
                                            accept=".pdf,.doc,.docx,.xls,.xlsx,.txt,image/*">
                                    </label>
                                    <span class="ml-1">or drag and drop</span>
                                </div>

                                <p class="text-xs text-gray-500">
                                    50MB max file size. Supported formats: PDF, DOC, DOCX, XLS, XLSX, images, and more.
                                </p>
                            </div>
                        </div>
                    </div>

                    {{-- Display Name --}}
                    <div>
                        <label for="display_name" class="mb-2 block text-sm font-medium text-[#374151]">
                            Display Name <span class="text-red-500">*</span>
                        </label>
                        <input type="text" id="display_name" name="display_name" required
                            class="w-full rounded-lg border border-[#d1d5db] px-3 py-2 transition-colors duration-200 focus:border-[#6840c6] focus:ring-2 focus:ring-[#6840c6]"
                            placeholder="e.g., Operation Manual, Installation Guide">
                    </div>

                    {{-- Description --}}
                    <div>
                        <label for="description" class="mb-2 block text-sm font-medium text-[#374151]">
                            Description
                        </label>
                        <textarea id="description" name="description" rows="3"
                            class="w-full resize-none rounded-lg border border-[#d1d5db] px-3 py-2 transition-colors duration-200 focus:border-[#6840c6] focus:ring-2 focus:ring-[#6840c6]"
                            placeholder="Brief description of this resource (optional)"></textarea>
                    </div>

                </div>
            </div>

            {{-- FOOTER --}}
            <footer
                class="flex flex-shrink-0 items-center justify-end space-x-3 border-t border-[#e4e7ec] bg-white px-6 py-4">
                <button type="button" id="cancelResourceModal"
                    class="rounded-lg border border-[#d1d5db] bg-white px-4 py-2 text-sm font-medium text-[#374151] transition-colors duration-200 hover:bg-[#f9fafb]">
                    Cancel
                </button>
                <button type="button" id="uploadResource"
                    class="flex items-center space-x-2 rounded-lg bg-[#6840c6] px-4 py-2 text-sm font-medium text-white transition-colors duration-200 hover:bg-[#5a35a8]">
                    <i class="fa-solid fa-upload"></i>
                    <span>Upload Resource</span>
                </button>
            </footer>
        </div>
    </div>

    {{-- History --}}
    <div class="mt-6 rounded-lg border border-[#e4e7ec] bg-white shadow-sm">

        {{-- #Title Block --}}
        <div class="border-b border-[#e4e7ec] px-6 py-4">
            <h2 class="text-lg font-semibold text-[#0f1728]">History</h2>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-[#f8f9fb]">
                    <tr>
                        <th
                            class="border-b border-[#e4e7ec] px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-[#475466]">
                            Date
                        </th>
                        <th
                            class="border-b border-[#e4e7ec] px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-[#475466]">
                            Activity
                        </th>
                        <th
                            class="border-b border-[#e4e7ec] px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-[#475466]">
                            User
                        </th>
                        <th
                            class="border-b border-[#e4e7ec] px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-[#475466]">
                            Actions
                        </th>
                    </tr>
                </thead>

                {{-- #History Loop --}}
                <tbody class="divide-y divide-[#e4e7ec]">

                    <tr>
                        <td class="px-6 py-4 text-sm text-[#344053]">Dec 15, 2024</td>
                        <td class="px-6 py-4 text-sm text-[#344053]">
                            <div class="flex flex-col gap-1">
                                <span>Interval Maintenance Completed</span>
                                <span
                                    class="inline-block w-fit rounded-full bg-[#f2f3f6] px-2 py-1 text-xs font-medium text-[#344053]">Weekly
                                    - Visual
                                    Inspection</span>
                            </div>
                        </td>
                        <td class="px-6 py-4 text-sm text-[#344053]">Alex Morgan</td>
                        <td class="px-6 py-4">
                            <button
                                class="rounded-lg p-2 text-[#667084] transition-colors hover:bg-[#f8f9fb] hover:text-[#344053]">
                                <i class="fa-solid fa-eye"></i>
                            </button>
                        </td>
                    </tr>

                </tbody>

            </table>
        </div>

    </div>

    {{-- Modal Includes --}}
    @include('components.equipment.attributes-modal')
    @include('components.equipment.location-description-modal')
    @include('components.equipment.data-modal')
    @include('components.equipment.basic-modal', [
        'equipment' => $equipment,
        'categories' => $categories,
        'decks' => $decks,
        'locations' => $locations
    ])

    {{-- End Content --}}

    {{-- Scripts --}}
    {{-- #Location Information Modal --}}
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const openBtn = document.getElementById('location-description-modal-open');
            const closeBtn = document.getElementById('location-description-modal-close');
            const overlay = document.getElementById('location-description-modal-overlay');
            const modal = document.getElementById('location-description-modal');

            // Show modal
            function showModal() {
                overlay.classList.remove('hidden');
                modal.classList.remove('hidden');
            }

            // Hide modal
            function closeModal() {
                overlay.classList.add('hidden');
                modal.classList.add('hidden');
            }

            // Wire up events
            openBtn.addEventListener('click', showModal);
            closeBtn.addEventListener('click', closeModal);
            overlay.addEventListener('click', closeModal);

            // Also close when clicking outside the content (i.e. on the modal wrapper itself)
            modal.addEventListener('click', (e) => {
                if (e.target === modal) {
                    closeModal();
                }
            });

            // Close on ESC
            document.addEventListener('keydown', (e) => {
                if (e.key === 'Escape' && !modal.classList.contains('hidden')) {
                    closeModal();
                }
            });
        });
    </script>

    {{-- #Basic Info Modal --}}
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            // IDs must match your HTML
            const openBtn = document.getElementById('basic-modal-open');
            const overlay = document.getElementById('basic-modal-overlay');
            const modal = document.getElementById('basic-modal');
            const closeEls = document.querySelectorAll('#close-basic-modal');

            // Rename to “showModal”/“hideModal” for clarity
            function showModal() {
                overlay.classList.remove('hidden');
                modal.classList.remove('hidden');
            }

            function hideModal() {
                overlay.classList.add('hidden');
                modal.classList.add('hidden');
            }

            // Wire up your clicks
            openBtn.addEventListener('click', showModal);
            overlay.addEventListener('click', hideModal);
            closeEls.forEach(btn => btn.addEventListener('click', hideModal));

            // Also close when clicking outside the content (i.e. on the modal wrapper itself)
            modal.addEventListener('click', (e) => {
                if (e.target === modal) {
                    hideModal();
                }
            });

            // ESC key
            document.addEventListener('keydown', e => {
                if (e.key === 'Escape' && !modal.classList.contains('hidden')) {
                    hideModal();
                }
            });

            // Auto-open if there are validation errors
            @if ($errors->any())
                showModal();
            @endif



            // **Dependent Location Loader** in the modal
            const deckSelect = document.getElementById('modal_deck_id');
            const locationSelect = document.getElementById('modal_location_id');

            deckSelect.addEventListener('change', () => {
                const deckId = deckSelect.value;

                if (!deckId) {
                    locationSelect.innerHTML = '<option value="">Select deck first</option>';
                    locationSelect.disabled = true;
                    locationSelect.classList.add('bg-gray-100', 'text-gray-500');
                    return;
                }

                fetch(`/inventory/decks/${deckId}/locations`)
                    .then(res => res.json())
                    .then(locations => {
                        locationSelect.innerHTML = '<option value="">Select location</option>';
                        locations.forEach(loc => {
                            const opt = document.createElement('option');
                            opt.value = loc.id;
                            opt.text = loc.name;
                            locationSelect.appendChild(opt);
                        });
                        locationSelect.disabled = false;
                        locationSelect.classList.remove('bg-gray-100', 'text-gray-500');
                    });
            });
        });
    </script>

    {{-- #Data Edit Modal --}}
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const openBtn = document.getElementById('edit-equipment-info-btn');
            const overlay = document.getElementById('edit-equipment-overlay');
            const modal = document.getElementById('edit-equipment-modal');
            const closeEls = document.querySelectorAll('.close-edit-equipment');

            function showModal() {
                overlay.classList.remove('hidden');
                modal.classList.remove('hidden');
            }

            function closeModal() {
                overlay.classList.add('hidden');
                modal.classList.add('hidden');
            }

            openBtn.addEventListener('click', showModal);
            overlay.addEventListener('click', closeModal);
            closeEls.forEach(btn => btn.addEventListener('click', closeModal));

            // Also close when clicking outside the content (i.e. on the modal wrapper itself)
            modal.addEventListener('click', (e) => {
                if (e.target === modal) {
                    closeModal();
                }
            });

            document.addEventListener('keydown', (e) => {
                if (e.key === 'Escape' && !modal.classList.contains('hidden')) {
                    closeModal();
                }
            });
        });
    </script>

    {{-- #Attributes Modal --}}
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const openBtn = document.getElementById('open-attributes-modal');
            const overlay = document.getElementById('attributes-modal-overlay');
            const modal = document.getElementById('attributes-modal');
            const closeBtns = document.querySelectorAll('#close-attributes-modal');
            const attrList = document.getElementById('attribute-list');
            const addBtn = document.getElementById('add-attribute');
            const newKey = document.getElementById('new-attr-key');
            const newValue = document.getElementById('new-attr-value');

            function show() {
                modal.classList.remove('hidden');
                overlay.classList.remove('hidden');
            }

            function hide() {
                modal.classList.add('hidden');
                overlay.classList.add('hidden');
            }

            openBtn.addEventListener('click', show);
            overlay.addEventListener('click', hide);
            closeBtns.forEach(btn => btn.addEventListener('click', hide));

            // Also close when clicking outside the content (i.e. on the modal wrapper itself)
            modal.addEventListener('click', (e) => {
                if (e.target === modal) {
                    hide();
                }
            });

            addBtn.addEventListener('click', () => {
                const key = newKey.value.trim();
                if (!key) {
                    alert('Attribute field cannot be empty.');
                    return;
                }
                const val = newValue.value.trim();
                const row = document.createElement('div');
                row.className = 'flex items-center gap-3 attribute-row';
                row.innerHTML = `
      <span class="w-1/3 text-gray-700 break-all">${key}</span>
      <input 
        type="text" 
        name="attributes_json[${key}]" 
        value="${val}" 
        class="w-2/3 px-3 py-2 border rounded-md"
      >
      <button type="button" class="p-1 remove-attribute text-red-500 hover:bg-red-100 rounded">&times;</button>
    `;
                attrList.appendChild(row);
                newKey.value = '';
                newValue.value = '';
            });

            attrList.addEventListener('click', e => {
                if (e.target.matches('.remove-attribute')) {
                    e.target.closest('.attribute-row').remove();
                }
            });
        });
    </script>

@endsection
