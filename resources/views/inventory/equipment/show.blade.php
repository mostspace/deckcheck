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
                <i class="fa-solid fa-chevron-right text-[#667084] text-xs"></i>
                <a href="{{ route('maintenance.show', $equipment->category) }}" class="text-[#6840c6] hover:text-[#5a35a8]">
                    {{ $equipment->category->name }}
                </a>
                <i class="fa-solid fa-chevron-right text-[#667084] text-xs"></i>
                <span class="text-[#475466]">{{ $equipment->name }}</span>
            @elseif(Str::startsWith($refererPath, $categoryShowPathPattern))
                <a href="{{ route('maintenance.showCategory', $equipment->category) }}" class="text-[#6840c6] hover:text-[#5a35a8]">
                    {{ $equipment->category->name }}
                </a>
                <i class="fa-solid fa-chevron-right text-[#667084] text-xs"></i>
                <span class="text-[#475466]">{{ $equipment->name }}</span>
            @elseif(Str::startsWith($refererPath, $equipmentIndexPath))
                <a href="{{ route('equipment.index') }}" class="text-[#6840c6] hover:text-[#5a35a8]">
                    Equipment
                </a>
                <i class="fa-solid fa-chevron-right text-[#667084] text-xs"></i>
                <span class="text-[#475466]">{{ $equipment->name }}</span>
            @else
                <a href="{{ route('equipment.index') }}" class="text-[#6840c6] hover:text-[#5a35a8]">
                    Equipment Index
                </a>
                <i class="fa-solid fa-chevron-right text-[#667084] text-xs"></i>
                <span class="text-[#475466]">{{ $equipment->name }}</span>
            @endif

        </nav>
    </div>

    {{-- System Messages --}}
    @if (session('success'))
        <div class="mb-6 p-4 bg-green-100 border border-green-300 text-green-800 rounded-lg text-sm">
            {{ session('success') }}
        </div>
    @endif

    {{-- Header --}}
    <div class="mb-6 flex flex-col lg:flex-row gap-6">

        {{-- #Hero Photo --}}
        <div class="flex-shrink-0">
            <div class="w-48 h-[149.8px] bg-[#f8f9fb] rounded-lg border border-[#e4e7ec] flex items-center justify-center overflow-hidden">
                @if ($equipment->hero_photo)
                    <img src="{{ $equipment->hero_photo ? Storage::url($equipment->hero_photo) : asset('images/placeholders/placeholder.png') }}" alt="Hero Photo for {{ $equipment->name }}"
                        class="w-full h-full object-cover">
                @else
                    <img class="w-full h-full object-cover border border-[#6840c6] rounded-lg" src="/storage/hero_photos/placeholder.webp"
                        alt="yacht life raft equipment Viking orange safety device">
                @endif

            </div>
        </div>

        <div class="flex-1">
            <div>

                {{-- #Title --}}
                <div class="flex gap-2">

                    {{-- ##Category Icon --}}
                    <div class="w-8 h-8 bg-[#f9f5ff] border rounded-md flex items-center justify-center mr-1">
                        <a href="{{ route('maintenance.show', $equipment->category) }}">
                            <i class="fa-solid hover:text-[#7e56d8] {{ $equipment->category->icon }} text-[#6840c6]"></i>
                        </a>
                    </div>

                    {{-- ##Equipment Name --}}
                    <h1 class="text-2xl font-semibold text-[#0f1728] mb-2">{{ $equipment->name ?? 'Unamed' }}</h1>

                    {{-- ##Edit --}}
                    <button id="basic-modal-open" class="text-[#6840c6] hover:text-[#7e56d8]">
                        <i class="fa-solid fa-edit"></i>
                    </button>
                </div>

                {{-- #Location --}}
                <div class="flex items-center gap-1 mb-4">
                    <i class="fa-solid fa-location-dot text-sm text-[#6840c6]"></i>
                    <span class="text-sm font-bold text-[#484f5d]">{{ $equipment->deck->name }}</span>
                    <span class="text-sm text-[#667084]">/ {{ $equipment->location->name }}</span>

                    {{-- ##Location Help --}}
                    <button id="location-description-modal-open" class="text-sm text-[#667084] hover:text-[#6840c6]">
                        <i class="fa-solid fa-circle-info"></i>
                    </button>
                </div>

                {{-- #Status Cards --}}
                <div class="flex flex-col sm:flex-row gap-3">

                    {{-- ##Status --}}
                    <div class="border rounded-lg p-4 min-w-[140px] {{ status_label_class($equipment->status) }}">
                        <div class="flex items-center gap-2 mb-1">
                            <i class="{{ status_label_icon($equipment->status) }}"></i>
                            <span class="text-sm font-medium">{{ $equipment->status ?? 'Unknown Status' }}</span>
                        </div>
                        <p class="text-xs text-[#475466]">Commissioned {{ $equipment->in_service ? $equipment->in_service->format('F j, Y') : '—' }}</p>
                    </div>

                    {{-- ##Compliance --}}
                    <div class="bg-[#fef3f2] border border-[#fecdca] rounded-lg p-4 min-w-[140px]">
                        <div class="flex items-center gap-2 mb-1">
                            <i class="text-[#b42318] fa-solid fa-triangle-exclamation"></i>
                            <span class="text-sm font-medium text-[#b42318]">## Overdue</span>
                        </div>
                        <p class="text-xs text-[#475466]">Test</p>
                    </div>

                    {{-- ##Scheduled --}}
                    <div class="bg-[#fffaeb] border border-[#fed7aa] rounded-lg p-4 min-w-[140px]">
                        <div class="flex items-center gap-2 mb-1">
                            <i class="text-[#dc6803] fa-solid fa-clock"></i>
                            <span class="text-sm font-medium text-[#dc6803]">## Upcoming</span>
                        </div>
                        <p class="text-xs text-[#475466]">Test</p>
                    </div>

                </div>

            </div>
        </div>
    </div>

    {{-- Equipment Data & Attributes --}}
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">

        {{-- #Data Title Block --}}
        <div class="bg-white rounded-lg border border-[#e4e7ec] shadow-sm">
            <div class="flex items-center justify-between px-6 py-4 border-b border-[#e4e7ec]">
                <h2 class="text-lg font-semibold text-[#0f1728]">Equipment Information</h2>

                {{-- ##Edit Equipment Data --}}
                <button id="edit-equipment-info-btn" class="text-[#6840c6] hover:text-[#7e56d8] text-right">
                    <i class="fa-solid fa-edit"></i>
                </button>
            </div>

            {{-- #Data --}}
            <div class="p-6">
                <div class="space-y-4">
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-light text-[#667084] mb-1">Manufacturer</label>
                            <p class="text-sm font-bold text-[#344053]">{{ $equipment->manufacturer ?? '—' }}</p>
                        </div>
                        <div>
                            <label class="block text-sm font-light text-[#667084] mb-1">Model / Part #</label>
                            <p class="text-sm font-bold text-[#344053]">{{ $equipment->model ?? '—' }}</p>
                        </div>
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-light text-[#667084] mb-1">Serial Number</label>
                            <p class="text-sm font-bold text-[#344053]">{{ $equipment->serial_number ?? '—' }}</p>
                        </div>
                        <div>
                            <label class="block text-sm font-light text-[#667084] mb-1">Custom ID</label>
                            <p class="text-sm font-bold text-[#344053]">{{ $equipment->internal_id ?? '—' }}</p>
                        </div>
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-light text-[#667084] mb-1">Purchase Date</label>
                            <p class="text-sm font-bold text-[#344053]">
                                {{ $equipment->purchase_date ? $equipment->purchase_date->format('F j, Y') : '—' }}
                            </p>
                        </div>
                        <div>
                            <label class="block text-sm font-light text-[#667084] mb-1">Manufacturing Date</label>
                            <p class="text-sm font-bold text-[#344053]">
                                {{ $equipment->manufacturing_date ? $equipment->manufacturing_date->format('F j, Y') : '—' }}
                            </p>
                        </div>
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-light text-[#667084] mb-1">In Service Date</label>
                            <p class="text-sm font-bold text-[#344053]">
                                {{ $equipment->in_service ? $equipment->in_service->format('F j, Y') : '—' }}
                            </p>
                        </div>
                        <div>
                            <label class="block text-sm font-light text-[#667084] mb-1">End of Life</label>
                            <p class="text-sm font-bold text-[#b42318]">
                                {{ $equipment->expiry_date ? $equipment->expiry_date->format('F j, Y') : 'Not Applicable' }}
                            </p>
                        </div>
                    </div>

                </div>
            </div>
        </div>

        {{-- #Attributes JSON Table --}}
        <div class="bg-white rounded-lg border border-[#e4e7ec] shadow-sm">

            {{-- ##Title Block --}}
            <div class="flex items-center justify-between px-6 py-4 border-b border-[#e4e7ec]">
                <h2 class="text-lg font-semibold text-[#0f1728]">Attributes</h2>

                {{-- ##Edit Attributes --}}
                <button id="open-attributes-modal" class="text-[#6840c6] hover:text-[#7e56d8] text-right">
                    <i class="fa-solid fa-edit"></i>
                </button>
            </div>

            {{-- ##Table --}}
            <div class="overflow-hidden">
                <table class="w-full">
                    <thead class="bg-[#f8f9fb]">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-[#475466] uppercase tracking-wider border-b border-[#e4e7ec]">
                                Attribute
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-[#475466] uppercase tracking-wider border-b border-[#e4e7ec]">
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
                                <td colspan="2" class="px-6 py-3 text-sm text-[#667084] italic">
                                    No attributes defined.
                                </td>
                            </tr>
                        @endforelse

                    </tbody>
                </table>
            </div>


        </div>
    </div>

    {{-- Maintenance Intervals --}}
    <div class="bg-white rounded-lg border border-[#e4e7ec] shadow-sm mt-6">

        {{-- #Title Block --}}
        <div class="flex px-6 py-4 border-b border-[#e4e7ec]">
            <h2 class="text-lg font-semibold text-[#0f1728]">Maintenance Schedule</h2>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-[#f8f9fb]">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-[#475466] uppercase tracking-wider border-b border-[#e4e7ec]">
                            Interval
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-[#475466] uppercase tracking-wider border-b border-[#e4e7ec]">
                            Requirement Name
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-[#475466] uppercase tracking-wider border-b border-[#e4e7ec]">
                            # of Tasks
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-[#475466] uppercase tracking-wider border-b border-[#e4e7ec]">
                            Facilitator
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-[#475466] uppercase tracking-wider border-b border-[#e4e7ec]">
                            Last Performed
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-[#475466] uppercase tracking-wider border-b border-[#e4e7ec]">
                            Next Due Date
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-[#475466] uppercase tracking-wider border-b border-[#e4e7ec]"></th>

                    </tr>
                </thead>

                {{-- #Intervals Loop --}}
                <tbody class="divide-y divide-[#e4e7ec]">

                    @foreach ($equipment->intervals as $interval)
                        @php
                            $lastWorkOrder = $interval->workOrders->whereNotNull('completed_at')->sortByDesc('completed_at')->first();

                            $nextWorkOrder = $interval->workOrders->whereNull('completed_at')->sortBy('due_date')->first();
                        @endphp

                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="px-2 py-1 text-xs font-medium {{ frequency_label_class($interval->frequency) }} rounded-full">
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
                                    $labelClass = $interval->facilitator === 'crew' ? 'bg-[#ebfdf2] text-[#027947]' : 'bg-[#fef6ee] text-[#c4320a]';
                                @endphp
                                <span class="px-2 py-1 text-xs font-medium {{ facilitator_label_class($interval->facilitator) }} rounded">
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
                                    class="p-2 text-[#667084] hover:text-[#344053] hover:bg-[#f8f9fb] rounded-lg transition-colors">
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
    <div class="bg-white rounded-lg border border-[#e4e7ec] shadow-sm mt-6">

        {{-- #Title Block --}}
        <div class="px-6 py-4 border-b border-[#e4e7ec]">
            <h2 class="text-lg font-semibold text-[#0f1728]">Deficiencies</h2>
        </div>


        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-[#f8f9fb]">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-[#475466] uppercase tracking-wider border-b border-[#e4e7ec]">
                            Date Opened
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-[#475466] uppercase tracking-wider border-b border-[#e4e7ec]">
                            Status
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-[#475466] uppercase tracking-wider border-b border-[#e4e7ec]">
                            Description
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-[#475466] uppercase tracking-wider border-b border-[#e4e7ec]">
                            Actions
                        </th>
                    </tr>
                </thead>

                {{-- #Deficiencies Loop --}}
                <tbody class="divide-y divide-[#e4e7ec]">
                    <tr>
                        <td class="px-6 py-4 text-sm text-[#344053]">Nov 28, 2024</td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="px-2 py-1 text-xs font-medium bg-[#fef3f2] text-[#b42318] rounded-full">Open</span>
                        </td>
                        <td class="px-6 py-4 text-sm text-[#344053]">Minor crack observed on canister housing during
                            monthly inspection</td>
                        <td class="px-6 py-4">
                            <button class="p-2 text-[#667084] hover:text-[#344053] hover:bg-[#f8f9fb] rounded-lg transition-colors">
                                <i class="fa-solid fa-eye"></i>
                            </button>
                        </td>
                    </tr>
                </tbody>

            </table>
        </div>

    </div>


    {{-- Resources --}}
    <div class="bg-white rounded-lg border border-[#e4e7ec] shadow-sm mt-6">

        {{-- #Title Block --}}
        <div class="px-6 py-4 border-b border-[#e4e7ec]">
            <h2 class="text-lg font-semibold text-[#0f1728]">Resources</h2>
        </div>

        <div class="p-6">

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                <!-- Manual/Documentation -->
                <div class="flex items-center p-4 bg-[#f8f9fb] rounded-lg border border-[#e4e7ec] hover:bg-[#f2f3f6] cursor-pointer transition-colors">
                    <div class="w-10 h-10 bg-[#f4ebff] rounded-lg flex items-center justify-center mr-3">
                        <i class="text-[#6840c6] fa-solid fa-file-pdf"></i>
                    </div>
                    <div class="flex-1">
                        <h3 class="text-sm font-medium text-[#0f1728]">Operation Manual</h3>
                        <p class="text-xs text-[#667084]">PDF • 2.4 MB</p>
                    </div>
                    <i class="text-[#667084] hover:text-[#6840c6] fa-solid fa-eye"></i>
                </div>

                <!-- Installation Guide -->
                <div class="flex items-center p-4 bg-[#f8f9fb] rounded-lg border border-[#e4e7ec] hover:bg-[#f2f3f6] cursor-pointer transition-colors">
                    <div class="w-10 h-10 bg-[#f4ebff] rounded-lg flex items-center justify-center mr-3">
                        <i class="text-[#6840c6] fa-solid fa-file-pdf"></i>
                    </div>
                    <div class="flex-1">
                        <h3 class="text-sm font-medium text-[#0f1728]">Installation Guide</h3>
                        <p class="text-xs text-[#667084]">PDF • 1.8 MB</p>
                    </div>
                    <i class="text-[#667084] fa-solid fa-download"></i>
                </div>

                <!-- Maintenance Schedule -->
                <div class="flex items-center p-4 bg-[#f8f9fb] rounded-lg border border-[#e4e7ec] hover:bg-[#f2f3f6] cursor-pointer transition-colors">
                    <div class="w-10 h-10 bg-[#f4ebff] rounded-lg flex items-center justify-center mr-3">
                        <i class="text-[#6840c6] fa-solid fa-file-excel"></i>
                    </div>
                    <div class="flex-1">
                        <h3 class="text-sm font-medium text-[#0f1728]">Maintenance Schedule</h3>
                        <p class="text-xs text-[#667084]">XLSX • 0.5 MB</p>
                    </div>
                    <i class="text-[#667084] fa-solid fa-download"></i>
                </div>

                <!-- Certificate -->
                <div class="flex items-center p-4 bg-[#f8f9fb] rounded-lg border border-[#e4e7ec] hover:bg-[#f2f3f6] cursor-pointer transition-colors">
                    <div class="w-10 h-10 bg-[#f4ebff] rounded-lg flex items-center justify-center mr-3">
                        <i class="text-[#6840c6] fa-solid fa-certificate"></i>
                    </div>
                    <div class="flex-1">
                        <h3 class="text-sm font-medium text-[#0f1728]">MED Certificate</h3>
                        <p class="text-xs text-[#667084]">PDF • 0.8 MB</p>
                    </div>
                    <i class="text-[#667084] fa-solid fa-download"></i>
                </div>

                <!-- Inspection Checklist -->
                <div class="flex items-center p-4 bg-[#f8f9fb] rounded-lg border border-[#e4e7ec] hover:bg-[#f2f3f6] cursor-pointer transition-colors">
                    <div class="w-10 h-10 bg-[#f4ebff] rounded-lg flex items-center justify-center mr-3">
                        <i class="text-[#6840c6] fa-solid fa-list-check"></i>
                    </div>
                    <div class="flex-1">
                        <h3 class="text-sm font-medium text-[#0f1728]">Inspection Checklist</h3>
                        <p class="text-xs text-[#667084]">PDF • 0.3 MB</p>
                    </div>
                    <i class="text-[#667084] fa-solid fa-download"></i>
                </div>

                <!-- Parts Diagram -->
                <div class="flex items-center p-4 bg-[#f8f9fb] rounded-lg border border-[#e4e7ec] hover:bg-[#f2f3f6] cursor-pointer transition-colors">
                    <div class="w-10 h-10 bg-[#f4ebff] rounded-lg flex items-center justify-center mr-3">
                        <i class="text-[#6840c6] fa-solid fa-image"></i>
                    </div>
                    <div class="flex-1">
                        <h3 class="text-sm font-medium text-[#0f1728]">Parts Diagram</h3>
                        <p class="text-xs text-[#667084]">PDF • 1.2 MB</p>
                    </div>
                    <i class="text-[#667084] fa-solid fa-download"></i>
                </div>
            </div>

        </div>
    </div>

    {{-- History --}}
    <div class="bg-white rounded-lg border border-[#e4e7ec] shadow-sm mt-6">

        {{-- #Title Block --}}
        <div class="px-6 py-4 border-b border-[#e4e7ec]">
            <h2 class="text-lg font-semibold text-[#0f1728]">History</h2>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-[#f8f9fb]">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-[#475466] uppercase tracking-wider border-b border-[#e4e7ec]">
                            Date
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-[#475466] uppercase tracking-wider border-b border-[#e4e7ec]">
                            Activity
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-[#475466] uppercase tracking-wider border-b border-[#e4e7ec]">
                            User
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-[#475466] uppercase tracking-wider border-b border-[#e4e7ec]">
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
                                <span class="px-2 py-1 text-xs font-medium bg-[#f2f3f6] text-[#344053] rounded-full inline-block w-fit">Weekly - Visual
                                    Inspection</span>
                            </div>
                        </td>
                        <td class="px-6 py-4 text-sm text-[#344053]">Alex Morgan</td>
                        <td class="px-6 py-4">
                            <button class="p-2 text-[#667084] hover:text-[#344053] hover:bg-[#f8f9fb] rounded-lg transition-colors">
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
        'locations' => $locations,
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
