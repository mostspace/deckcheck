@php
    $equipment = $wo->equipmentInterval->equipment ?? null;
    $location = $equipment?->location;
    $deck = $location?->deck ?? null;
    $interval = $wo->equipmentInterval;
    $completedBy = $wo->completedBy ?? null;
    $completedTasks = $wo->tasks()->where('status', 'completed')->count();
    $flaggedTasks = $wo->tasks()->where('status', 'flagged')->count();
@endphp

<div
    class="group mb-4 grid grid-cols-1 items-center gap-4 rounded-xl border border-[#e4e7ec] bg-white px-6 py-4 shadow-sm transition-shadow hover:shadow-md lg:grid-cols-[1.25fr_2fr_1fr_1fr_1.5fr_40px]">

    {{-- Status + WO ID + Description --}}
    <div class="flex flex-col justify-center">
        <div class="flex items-center gap-2 text-xs text-gray-500">
            {!! status_badge($wo->status) !!}
            <span class="tracking-tight">WO #{{ $wo->id }}</span>
        </div>
        <div class="my-1 text-sm font-semibold leading-tight text-gray-800">
            {{ $interval->description }}
        </div>
    </div>

    {{-- Equipment Block --}}
    <div class="flex items-center gap-3">

        {{-- #Hero Photo --}}
        <div
            class="flex h-12 w-12 items-center justify-center overflow-hidden rounded-lg border border-[#e4e7ec] bg-[#f8f9fb]">
            <img src="{{ $equipment->hero_photo ? Storage::url($equipment->hero_photo) : asset('images/placeholders/placeholder.png') }}"
                class="h-full w-full object-cover" alt="Equipment">
        </div>

        {{-- #Equipment Attributes --}}
        <div class="space-y-0.5 text-sm">
            <p class="font-semibold leading-snug text-gray-800">{{ $equipment?->name ?? 'Unnamed' }}</p>

            @if ($equipment?->internal_id || $equipment?->serial_number)
                <p class="text-xs text-gray-500">
                    @if ($equipment?->internal_id)
                        ID: {{ $equipment->internal_id }}
                    @endif
                    @if ($equipment?->internal_id && $equipment?->serial_number)
                        &nbsp;|&nbsp;
                    @endif
                    @if ($equipment?->serial_number)
                        SN: {{ $equipment->serial_number }}
                    @endif
                </p>
            @endif

            {{-- #Location --}}
            @if ($deck || $location)
                <p class="flex items-center gap-1 text-xs text-gray-500">
                    <i class="fa-solid fa-location-dot text-[10px] text-[#667084]"></i>
                    <span class="font-bold">{{ $deck->name }}</span> / <span
                        class="text-[#6840c6]">{{ $location->name }}</span>
                </p>
            @endif
        </div>
    </div>

    {{-- Completed Date --}}
    <div class="flex h-full items-center text-sm text-gray-700">
        {{ $wo->completed_at ? $wo->completed_at->format('M j, Y g:i A') : '—' }}
    </div>

    {{-- Task Summary --}}
    <div class="flex min-h-[1.5rem] items-center gap-2 text-sm">
        {{-- Completed Tasks --}}
        <span class="flex items-center font-medium text-green-600">
            <i class="fa-regular fa-circle-check mr-1"></i> {{ $completedTasks }}
        </span>

        {{-- Separator --}}
        <span class="font-medium text-gray-400">/</span>

        {{-- Flagged Tasks --}}
        <span class="{{ $flaggedTasks == 0 ? 'text-gray-400' : 'text-red-500' }} flex items-center font-medium">
            {{ $flaggedTasks }} <i class="fa-solid fa-flag ml-1"></i>
        </span>
    </div>

    {{-- Completed By --}}
    <div class="flex items-center text-sm text-gray-700">
        @if ($completedBy)
            <img src="{{ $completedBy->profile_pic ? Storage::url($completedBy->profile_pic) : asset('images/placeholders/user.png') }}"
                class="mr-2 h-5 w-5 rounded-full" alt="Avatar">
            {{ $completedBy->first_name }} {{ $completedBy->last_name }}
        @else
            <span class="italic text-gray-400">Unknown</span>
        @endif
    </div>

    {{-- View Work Order Link --}}
    <div class="flex h-full items-center justify-end">
        <a href="{{ route('work-orders.show', $wo) }}"
            class="text-gray-500 transition-colors duration-150 hover:text-gray-800" title="View Work Order">
            <i class="fa-solid fa-eye text-s transition-transform duration-150 group-hover:scale-110"></i>
        </a>
    </div>
</div>
