@php
    $equipment = $wo->equipmentInterval->equipment ?? null;
    $location = $equipment?->location;
    $deck = $location?->deck ?? null;
    $interval = $wo->equipmentInterval;
@endphp

<div
    class="group mb-4 grid grid-cols-1 items-center gap-4 rounded-xl border border-[#e4e7ec] bg-white px-6 py-4 shadow-sm transition-shadow hover:shadow-md lg:grid-cols-[1.25fr_2fr_1fr_1fr_1.5fr_40px]">

    {{-- Status + WO ID + Description --}}
    <div class="flex flex-col justify-center">

        {{-- #Status & ID --}}
        <div class="flex items-center gap-2 text-xs text-gray-500">
            {!! status_badge($wo->status) !!}
            <span class="tracking-tight">WO #{{ $wo->id }}</span>
        </div>

        {{-- #Description --}}
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

    {{-- Due Date --}}
    <div class="flex h-full items-center text-sm text-gray-700">
        {!! work_order_due_badge($wo) !!}
    </div>

    {{-- Task Count --}}
    <div class="flex h-full items-center text-sm text-gray-700">
        {{ $wo->tasks()->count() }} task{{ $wo->tasks()->count() === 1 ? '' : 's' }}
    </div>

    {{-- Assignee --}}
    <div class="relative inline-block text-left text-sm" id="assignee-dropdown-{{ $wo->id }}">
        <button onclick="toggleIndexAssigneeDropdown({{ $wo->id }})"
            class="inline-flex items-center gap-2 rounded-lg border border-[#e4e7ec] bg-white px-3 py-2 text-sm font-medium text-[#344053] shadow-sm">
            @if ($wo->assignee)
                <img src="{{ $wo->assignee->profile_pic ? Storage::url($wo->assignee->profile_pic) : asset('images/placeholders/user.png') }}"
                    class="h-5 w-5 rounded-full" alt="Avatar">
                {{ $wo->assignee->first_name }}
            @else
                <img src="{{ asset('images/placeholders/user.png') }}" class="h-5 w-5 rounded-full" alt="Avatar">
                Unassigned
            @endif
            <i class="fa-solid fa-chevron-down ml-1 text-xs"></i>
        </button>

        <div id="assignee-options-{{ $wo->id }}"
            class="absolute left-0 z-20 mt-1.5 hidden w-48 rounded-lg border border-[#e4e7ec] bg-white shadow-lg">
            <ul class="py-2">
                @foreach ($availableUsers as $user)
                    <li>
                        <button onclick="assignUser({{ $wo->id }}, {{ $user->id }})"
                            class="flex w-full items-center px-4 py-2 text-sm text-[#344053] hover:bg-[#f9fafb]">
                            <img src="{{ $user->profile_pic ? Storage::url($user->profile_pic) : asset('images/placeholders/user.png') }}"
                                class="mr-2 h-5 w-5 rounded-full" alt="Avatar">
                            {{ $user->first_name }} {{ $user->last_name }}
                        </button>
                    </li>
                @endforeach
                <li>
                    <button onclick="assignUser({{ $wo->id }}, null)"
                        class="flex w-full items-center px-4 py-2 text-sm text-[#b42318] hover:bg-[#fef3f2]">
                        <i class="fa-solid fa-xmark mr-2"></i> Clear
                    </button>
                </li>
            </ul>
        </div>
    </div>

    @if (!empty($showFlowCta))
        <div class="flex h-full items-center justify-end">
            <button
                onclick="launchFlow([{{ $wo->id }}], '{{ addslashes($wo->equipmentInterval->equipment->name ?? 'Work Order') }}')"
                class="text-purple-600 transition-colors duration-150 hover:text-purple-800"
                title="Open Work Order Flow">
                <i class="fa-solid fa-circle-play text-xl transition-transform duration-150 group-hover:scale-110"></i>
            </button>
        </div>
    @endif

</div>
