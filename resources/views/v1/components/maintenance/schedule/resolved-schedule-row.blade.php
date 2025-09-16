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
    class="group grid grid-cols-1 lg:grid-cols-[1.25fr_2fr_1fr_1fr_1.5fr_40px] gap-4 px-6 py-4 border border-[#e4e7ec] rounded-xl shadow-sm mb-4 bg-white items-center transition-shadow hover:shadow-md">

    {{-- Status + WO ID + Description --}}
    <div class="flex flex-col justify-center">
        <div class="flex items-center gap-2 text-xs text-gray-500">
            {!! status_badge($wo->status) !!}
            <span class="tracking-tight">WO #{{ $wo->id }}</span>
        </div>
        <div class="text-sm font-semibold text-gray-800 my-1 leading-tight">
            {{ $interval->description }}
        </div>
    </div>

    {{-- Equipment Block --}}
    <div class="flex items-center gap-3">

        {{-- #Hero Photo --}}
        <div class="w-12 h-12 rounded-lg border border-[#e4e7ec] bg-[#f8f9fb] flex items-center justify-center overflow-hidden">
            <img src="{{ $equipment->hero_photo ? Storage::url($equipment->hero_photo) : asset('images/placeholders/placeholder.png') }}"
                class="object-cover w-full h-full" alt="Equipment">
        </div>

        {{-- #Equipment Attributes --}}
        <div class="text-sm space-y-0.5">
            <p class="font-semibold text-gray-800 leading-snug">{{ $equipment?->name ?? 'Unnamed' }}</p>

            @if ($equipment?->internal_id || $equipment?->serial_number)
                <p class="text-gray-500 text-xs">
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
                <p class="text-gray-500 text-xs flex items-center gap-1">
                    <i class="fa-solid fa-location-dot text-[10px] text-[#667084]"></i>
                    <span class="font-bold">{{ $deck->name }}</span> / <span class="text-[#6840c6]">{{ $location->name }}</span>
                </p>
            @endif
        </div>
    </div>

    {{-- Completed Date --}}
    <div class="flex items-center text-sm text-gray-700 h-full">
        {{ $wo->completed_at ? $wo->completed_at->format('M j, Y g:i A') : '—' }}
    </div>

    {{-- Task Summary --}}
    <div class="flex items-center gap-2 text-sm min-h-[1.5rem]">
        {{-- Completed Tasks --}}
        <span class="flex items-center text-green-600 font-medium">
            <i class="fa-regular fa-circle-check mr-1"></i> {{ $completedTasks }}
        </span>

        {{-- Separator --}}
        <span class="text-gray-400 font-medium">/</span>

        {{-- Flagged Tasks --}}
        <span class="flex items-center {{ $flaggedTasks == 0 ? 'text-gray-400' : 'text-red-500' }} font-medium">
            {{ $flaggedTasks }} <i class="fa-solid fa-flag ml-1"></i>
        </span>
    </div>



    {{-- Completed By --}}
    <div class="flex items-center text-sm text-gray-700">
        @if ($completedBy)
            <img src="{{ $completedBy->profile_pic ? Storage::url($completedBy->profile_pic) : asset('images/placeholders/user.png') }}"
                class="w-5 h-5 rounded-full mr-2" alt="Avatar">
            {{ $completedBy->first_name }} {{ $completedBy->last_name }}
        @else
            <span class="text-gray-400 italic">Unknown</span>
        @endif
    </div>

    {{-- View Work Order Link --}}
    <div class="flex items-center justify-end h-full">
        <a href="{{ route('work-orders.show', $wo) }}" class="text-gray-500 hover:text-gray-800 transition-colors duration-150"
            title="View Work Order">
            <i class="fa-solid fa-eye text-s group-hover:scale-110 transition-transform duration-150"></i>
        </a>
    </div>
</div>
