@php
    $equipment = $wo->equipmentInterval->equipment ?? null;
    $location = $equipment?->location;
    $deck = $location?->deck ?? null;
    $interval = $wo->equipmentInterval;
@endphp

<div class="group grid grid-cols-1 lg:grid-cols-[1.25fr_2fr_1fr_1fr_1.5fr_40px] gap-4 px-6 py-4 border border-[#e4e7ec] rounded-xl shadow-sm mb-4 bg-white items-center transition-shadow hover:shadow-md">

    {{-- Status + WO ID + Description --}}
    <div class="flex flex-col justify-center">
        
        {{-- #Status & ID --}}
        <div class="flex items-center gap-2 text-xs text-gray-500">
            {!! status_badge($wo->status) !!}
            <span class="tracking-tight">WO #{{ $wo->id }}</span>
        </div>

        {{-- #Description --}}
        <div class="text-sm font-semibold text-gray-800 my-1 leading-tight">
                {{ $interval->description }}
        </div>
    </div>

    {{-- Equipment Block --}}
    <div class="flex items-center gap-3">

        {{-- #Hero Photo --}}
        <div class="w-12 h-12 rounded-lg border border-[#e4e7ec] bg-[#f8f9fb] flex items-center justify-center overflow-hidden">
            <img src="{{ $equipment->hero_photo ? Storage::url($equipment->hero_photo) : asset('storage/hero_photos/placeholder.png') }}"
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

    {{-- Due Date --}}
    <div class="flex items-center text-sm text-gray-700 h-full">
        {!! work_order_due_badge($wo) !!}
    </div>

    {{-- Task Count --}}
    <div class="flex items-center text-sm text-gray-700 h-full">
        {{ $wo->tasks()->count() }} task{{ $wo->tasks()->count() === 1 ? '' : 's' }}
    </div>

    {{-- Assignee --}}
    <div class="text-sm relative inline-block text-left" id="assignee-dropdown-{{ $wo->id }}">
        <button onclick="toggleAssigneeDropdown({{ $wo->id }})"
            class="inline-flex items-center gap-2 px-3 py-2 border border-[#e4e7ec] rounded-lg shadow-sm bg-white text-sm font-medium text-[#344053]">
            @if ($wo->assignee)
                <img src="{{ $wo->assignee->profile_pic ? Storage::url($wo->assignee->profile_pic) : Storage::url('profile_pictures/placeholder.svg.hi.png') }}"
                    class="w-5 h-5 rounded-full" alt="Avatar">
                {{ $wo->assignee->first_name }}
            @else
                <img src="{{ Storage::url('profile_pictures/placeholder.svg.hi.png') }}" class="w-5 h-5 rounded-full" alt="Avatar">
                Unassigned
            @endif
            <i class="fa-solid fa-chevron-down ml-1 text-xs"></i>
        </button>

        <div id="assignee-options-{{ $wo->id }}"
            class="hidden absolute left-0 z-20 mt-1.5 w-48 bg-white rounded-lg shadow-lg border border-[#e4e7ec]">
            <ul class="py-2">
                @foreach ($availableUsers as $user)
                    <li>
                        <button onclick="assignUser({{ $wo->id }}, {{ $user->id }})"
                            class="flex items-center w-full px-4 py-2 text-sm text-[#344053] hover:bg-[#f9fafb]">
                            <img src="{{ $user->profile_pic ? Storage::url($user->profile_pic) : Storage::url('profile_pictures/placeholder.svg.hi.png') }}"
                                class="w-5 h-5 rounded-full mr-2" alt="Avatar">
                            {{ $user->first_name }} {{ $user->last_name }}
                        </button>
                    </li>
                @endforeach
                <li>
                    <button onclick="assignUser({{ $wo->id }}, null)"
                        class="flex items-center w-full px-4 py-2 text-sm text-[#b42318] hover:bg-[#fef3f2]">
                        <i class="fa-solid fa-xmark mr-2"></i> Clear
                    </button>
                </li>
            </ul>
        </div>
    </div>

    {{-- CTA Icon Link 
    <div class="flex items-center justify-end h-full">
        <a href="{{ route('schedule.flow', ['workOrder' => $wo->id, 'frequency' => $frequency, 'date' => $date->toDateString()]) }}"
            class="text-purple-600 hover:text-purple-800 transition-colors duration-150"
            title="View Work Order Flow">
            <i class="fa-solid fa-arrow-up-right-from-square text-s group-hover:scale-110 transition-transform duration-150"></i>
        </a>
    </div> --}}
</div>
