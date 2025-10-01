@extends('layouts.app')

@section('title', 'Deficiency Detail')

@section('content')

    @php
        $daysOpen = $deficiency->created_at->diffInDays(now());
        $daysOpenFormatted = $daysOpen < 1 ? '<1' : $daysOpen;
    @endphp

    {{-- Breadcrumb --}}
    <div class="mb-4">
        <nav class="flex items-center space-x-2 text-sm">
            <a href="{{ route('deficiencies.index') }}">
                <span class="cursor-pointer text-[#6840c6] hover:text-[#5a35a8]">Deficiencies</span>
            </a>
            <i class="fa-solid fa-chevron-right text-xs text-[#667084]"></i>
            <span class="text-[#475466]">Deficiency DEF-0000{{ $deficiency->id }}</span>
        </nav>
    </div>

    {{-- System Messages --}}
    @if (session('success'))
        <div class="mb-6 rounded-lg border border-green-300 bg-green-100 p-4 text-sm text-green-800">
            {{ session('success') }}
        </div>
    @endif

    {{-- #Hero --}}
    <div class="mb-6" id="deficiency-hero">
        <div class="space-y-8 rounded-md border border-[#e4e7ec] bg-white p-6 shadow-sm">

            {{-- ##Title Block --}}
            <div>
                <h1 class="mb-2 flex items-center text-2xl font-semibold text-[#0f1728]">
                    <div class="mr-1 flex h-8 w-8 items-center justify-center rounded-md border bg-[#fffaeb]">
                        <i class="fa-solid fa-flag text-xl text-[#b54708]"></i>
                    </div>
                    {{ $deficiency->subject }}
                </h1>

                <div class="flex">
                    {{-- Arrow SVG --}}
                    <span class="ml-2 mr-2 flex justify-center py-4">
                        <svg width="22" height="22" fill="none" viewBox="0 0 22 22">
                            <path
                                d="M4 4v8a2 2 0 0 0 2 2h7.586l-3.293-3.293a1 1 0 0 1 1.414-1.414l5 5a1 1 0 0 1 0 1.414l-5 5a1 1 0 1 1-1.414-1.414L13.586 16H6a4 4 0 0 1-4-4V4a1 1 0 1 1 2 0Z"
                                fill="#7e56d8" />
                        </svg>
                    </span>

                    {{-- Equipment Summary Card --}}
                    <a href="{{ route('equipment.show', $deficiency->equipment->id) }}">
                        <div class="mb-4 flex items-center rounded-md border border-[#e4e7ec] bg-[#f9fafb] p-2 shadow-sm transition hover:bg-[#f3f4f6]"
                            style="min-width:300px; max-width:100%;">
                            {{-- Hero Image --}}
                            <div
                                class="h-20 w-20 flex-shrink-0 overflow-hidden rounded-md border border-[#e4e7ec] bg-white">
                                <img src="{{ $deficiency->equipment->hero_photo ? Storage::url($deficiency->equipment->hero_photo) : asset('images/placeholders/placeholder.png') }}"
                                    class="h-full w-full object-cover" alt="Equipment">
                            </div>
                            {{-- Info --}}
                            <div class="ml-4 flex min-w-0 flex-col">
                                {{-- Name + Icon --}}
                                <div class="mb-1 flex items-center gap-2">
                                    <span
                                        class="inline-flex h-7 w-7 items-center justify-center rounded-md border border-[#d6bbfb] bg-[#f3ebff] text-[#6840c6]">
                                        <i
                                            class="fa-solid {{ $deficiency->equipment->category->icon ?? 'fa-screwdriver-wrench' }}"></i>
                                    </span>
                                    <span
                                        class="truncate text-base font-semibold text-[#0f1728]">{{ $deficiency->equipment->name ?? 'Unnamed' }}</span>
                                </div>
                                {{-- Location --}}
                                <div class="mb-1 flex items-center gap-1 text-xs text-[#667084]">
                                    <i class="fa-solid fa-location-dot text-[#6840c6]"></i>
                                    <span class="font-medium text-[#344053]">{{ $deficiency->equipment->deck->name }}</span>
                                    <span>/ {{ $deficiency->equipment->location->name }}</span>
                                </div>
                                {{-- Internal Info --}}
                                <div class="flex items-center gap-2 text-xs text-[#475467]">
                                    <span>{{ $deficiency->equipment->internal_id }}</span>
                                    <span class="text-[#d0d5dd]">•</span>
                                    <span>S/N: {{ $deficiency->equipment->serial_number }}</span>
                                </div>
                            </div>
                        </div>
                    </a>
                </div>
            </div>

            {{-- ##Opened --}}
            <div class="flex flex-col gap-12 text-sm md:flex-row">

                {{-- ###Opened By --}}
                <div class="flex items-center gap-2">
                    <div>
                        <p class="mb-1 text-xs font-medium uppercase text-[#667084]">Opened By</p>
                        <div class="flex items-center gap-2">
                            <img src="{{ $deficiency->openedBy?->profile_pic
                                ? Storage::url($deficiency->openedBy->profile_pic)
                                : Storage::url('profile_pictures/placeholder.svg.hi.png') }}"
                                class="h-6 w-6 rounded-full">
                            <span class="font-bold text-[#344053]">{{ $deficiency->openedBy?->first_name }}
                                {{ $deficiency->openedBy?->last_name }}</span>
                        </div>
                    </div>
                </div>

                {{-- ###Opened Date --}}
                <div>
                    <p class="mb-1 text-xs font-medium uppercase text-[#667084]">Date Opened</p>
                    <p class="font-bold text-[#344053]">{{ $deficiency->created_at->format('F j, Y') }}</p>
                </div>

                <div>
                    <p class="mb-1 text-xs font-medium uppercase text-[#667084]">Aging</p>
                    <span class="font-normal text-[#be123c]">{{ $daysOpenFormatted }} days ago</span>
                </div>

            </div>

            {{-- ##Description --}}
            <div class="">
                <div class="mb-2 flex items-center gap-2">
                    <p class="text-xs font-medium uppercase text-[#667084]">Initial Report</p>
                    <button onclick="toggleDescriptionEdit()"
                        class="text-xs text-[#6840c6] transition-colors hover:text-[#5a35a8] hover:underline"
                        id="editDescriptionBtn">
                        <i class="fa-solid fa-edit mr-1"></i>Edit
                    </button>
                </div>

                {{-- Display Mode --}}
                <div id="descriptionDisplay" class="min-h-[1.5rem] text-sm font-normal text-[#344053]">
                    @if ($deficiency->description)
                        {!! nl2br(e($deficiency->description)) !!}
                    @else
                        <span class="italic text-[#667084]">No initial report provided</span>
                    @endif
                </div>

                {{-- Edit Mode --}}
                <form id="descriptionEdit" class="hidden" method="POST"
                    action="{{ route('deficiencies.update-description', $deficiency) }}">
                    @csrf
                    <textarea name="description"
                        class="w-full resize-none rounded-lg border border-[#d1d5db] px-3 py-2 transition-colors duration-200 focus:border-[#6840c6] focus:ring-2 focus:ring-[#6840c6]"
                        rows="4" placeholder="Enter the initial report description...">{{ $deficiency->description }}</textarea>
                    <div class="mt-2 flex items-center justify-end space-x-2">
                        <button type="button" onclick="cancelDescriptionEdit()"
                            class="px-3 py-1 text-sm text-[#667084] transition-colors hover:text-[#344053]">
                            Cancel
                        </button>
                        <button type="submit"
                            class="rounded bg-[#6840c6] px-3 py-1 text-sm text-white transition-colors hover:bg-[#5a35a8]">
                            Save
                        </button>
                    </div>
                </form>
            </div>

            {{-- ##Work Order Reference --}}

            {{-- ###Work Order Reference Conditional --}}
            @if ($deficiency->workOrder && $deficiency->workOrder->tasks->where('status', 'flagged')->count())

                <div class="mb-6 rounded-md border border-[#e4e7ec] bg-white shadow-sm">

                    {{-- ####Title Block --}}
                    <div class="rounded-tl-lg rounded-tr-lg border-b border-[#e4e7ec] bg-[#f8f9fb]">
                        <h2 class="px-6 py-2 text-lg font-semibold text-[#0f1728]">
                            <span
                                class="{{ frequency_label_class($deficiency->workOrder->equipmentInterval->frequency) }} uppercase">
                                {{ $deficiency->workOrder->equipmentInterval->frequency }}</span>
                            {{ $deficiency->workOrder->equipmentInterval->description }}
                        </h2>
                    </div>

                    <div class="mx-6 my-3 leading-relaxed">
                        <h3 class="mb-1 text-sm font-semibold text-[#344053]">Comments / Observations</h3>
                        <p class="rounded-lg border border-[#d6bbfb] bg-[#f9f5ff] px-6 py-2 text-sm text-[#6941c6]">
                            {{ $deficiency->workOrder->notes }}
                        </p>
                    </div>

                    <ul class="space-y-3 px-6 pb-6 pt-1">

                        {{-- ####Flagged Task Loop --}}
                        @foreach ($deficiency->workOrder->tasks->where('status', 'flagged') as $task)
                            <li class="rounded-md border border-[#e4e7ec] bg-[#f9fafb] p-6">
                                <div class="flex items-start justify-between gap-4">
                                    <div class="flex-1">
                                        <div class="mb-3 flex cursor-pointer items-center gap-2"
                                            onclick="toggleInstructions({{ $task->id }})">
                                            <p class="text-base font-semibold text-[#344053]">{{ $task->name }}</p>
                                            <i class="fa-solid fa-eye text-xs text-[#667084]"></i>
                                        </div>

                                        @if ($task->instructions)
                                            <div id="instructions-{{ $task->id }}"
                                                class="mb-3 hidden rounded-lg border border-[#d6bbfb] bg-[#f9f5ff] px-4 py-3 text-sm leading-relaxed text-[#6941c6]">
                                                {!! nl2br(e($task->instructions)) !!}
                                            </div>
                                        @endif

                                        <hr><br>

                                        <span class="mb-1 text-xs">
                                            <strong>Recorded:</strong>
                                            <span class="text-[#6941c6]">{{ $task->completed_at ?? '—' }}</span>
                                        </span>

                                        @if ($task->completedBy)
                                            <div class="flex items-center gap-2 text-xs">
                                                <strong>By:</strong>
                                                <img src="{{ $task->completedBy->profile_pic ? Storage::url($task->completedBy->profile_pic) : Storage::url('profile_pictures/placeholder.svg.hi.png') }}"
                                                    alt="Avatar" class="h-5 w-5 rounded-full">
                                                <span class="text-[#6941c6]">{{ $task->completedBy->first_name }}
                                                    {{ $task->completedBy->last_name }}</span>
                                            </div>
                                        @endif

                                    </div>

                                    <div class="flex flex-col items-end whitespace-nowrap text-xs text-[#667084]">
                                        <p class="mt-1 text-xs text-[#667084]">
                                            {!! status_badge($task->status) !!}
                                        </p>
                                    </div>
                                </div>
                            </li>
                        @endforeach

                    </ul>
                </div>

            @endif

            {{-- ##Status, Priority, & Assigned To --}}
            <div class="mt-4 flex flex-col gap-8 border-t border-[#e4e7ec] pt-4 text-sm md:flex-row">

                {{-- ###Status --}}
                <div>
                    <p class="mb-3 text-xs font-medium uppercase text-[#667084]">Status</p>
                    <span
                        class="{{ deficiency_status_class($deficiency->status) }} rounded-full px-2 py-1 text-xs font-medium capitalize">{{ $deficiency->status }}</span>
                </div>

                {{-- ###Priority --}}
                <div>
                    <p class="mb-3 text-xs font-medium uppercase text-[#667084]">Priority</p>
                    {!! priority_badge($deficiency->priority) !!}
                </div>

                {{-- ###Assigned To --}}
                <div class="ml-auto">
                    <p class="mb-3 text-xs font-medium uppercase text-[#667084]">Assigned To</p>
                    <div class="max-w-sm">

                        <div x-data="{ open: false }" class="relative">
                            <button @click="open = !open" type="button"
                                class="flex w-full items-center gap-2 rounded-md border border-[#e4e7ec] bg-white px-3 py-1 text-sm text-[#344053] hover:border-[#c7c9d1]">
                                <img src="{{ $deficiency->assignedTo?->profile_pic
                                    ? Storage::url($deficiency->assignedTo->profile_pic)
                                    : Storage::url('profile_pictures/placeholder.svg.hi.png') }}"
                                    class="h-5 w-5 rounded-full">
                                <span>{{ $deficiency->assignedTo?->first_name ?? 'Unassigned' }}
                                    {{ $deficiency->assignedTo?->last_name ?? '' }}</span>
                                <i class="fa-solid fa-chevron-down ml-auto text-xs text-[#667084]"></i>
                            </button>

                            <div x-show="open" @click.away="open = false"
                                class="absolute z-10 mt-2 max-h-60 w-full overflow-y-auto rounded-lg border border-[#e4e7ec] bg-white text-sm shadow-lg">

                                @foreach ($users as $user)
                                    <form method="POST" action="{{ route('deficiencies.assign', $deficiency) }}">
                                        @csrf
                                        @method('PUT')
                                        <input type="hidden" name="assigned_to" value="{{ $user->id }}">
                                        <button type="submit"
                                            class="flex w-full items-center gap-2 px-3 py-2 text-left hover:bg-[#f9f9f9]">
                                            <img src="{{ $user->profile_pic ? Storage::url($user->profile_pic) : Storage::url('profile_pictures/placeholder.svg.hi.png') }}"
                                                class="h-5 w-5 rounded-full">
                                            <span>{{ $user->first_name }} {{ $user->last_name }}</span>
                                        </button>
                                    </form>
                                @endforeach

                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>

    {{-- #Updates --}}
    <div class="rounded-lg border border-[#e4e7ec] bg-white shadow-sm" id="activity-feed">
        <div class="p-6">

            {{-- ##Title --}}
            <h2 class="mb-6 text-lg font-semibold text-[#0f1728]">Updates</h2>

            {{-- ##Update Form --}}
            <div class="mb-6 rounded-lg border border-[#e4e7ec] bg-[#f8f9fb] p-4">
                <form method="POST" action="{{ route('deficiencies.updates.store', $deficiency) }}">
                    @csrf

                    {{-- ###Comment --}}
                    <div class="mb-4">
                        <label class="mb-2 block text-sm font-medium text-[#344053]">Add Comment</label>
                        <textarea name="comment" placeholder="Enter your comment..."
                            class="w-full resize-none rounded-lg border border-[#cfd4dc] bg-white px-3 py-2.5 text-sm leading-normal text-[#0f1728] focus:border-[#7e56d8] focus:outline-none focus:ring-2 focus:ring-[#7e56d8]"
                            rows="3">{{ old('comment') }}</textarea>
                    </div>

                    <div class="mb-4 flex flex-col gap-4 sm:flex-row">

                        {{-- ###Update Status --}}
                        <div class="flex-1">
                            <label class="mb-2 block text-sm font-medium text-[#344053]">Update Status</label>
                            <select name="new_status"
                                class="w-full rounded-lg border border-[#cfd4dc] bg-white px-3 py-2.5 text-sm text-[#0f1728] focus:border-[#7e56d8] focus:outline-none focus:ring-2 focus:ring-[#7e56d8]">
                                <option value="">No change</option>
                                <option value="open">Open</option>
                                <option value="waiting">Waiting</option>
                                <option value="resolved">Resolved</option>
                            </select>
                        </div>

                        {{-- ###Update Priority --}}
                        <div class="flex-1">
                            <label class="mb-2 block text-sm font-medium text-[#344053]">Update Priority</label>
                            <select name="new_priority"
                                class="w-full rounded-lg border border-[#cfd4dc] bg-white px-3 py-2.5 text-sm text-[#0f1728] focus:border-[#7e56d8] focus:outline-none focus:ring-2 focus:ring-[#7e56d8]">
                                <option value="">No change</option>
                                <option value="low">Low</option>
                                <option value="medium">Medium</option>
                                <option value="high">High</option>
                            </select>
                        </div>
                    </div>

                    {{-- ###Form Actions --}}
                    <div class="flex justify-end gap-3">
                        <button type="reset"
                            class="rounded-lg border border-[#cfd4dc] bg-white px-4 py-2 text-sm font-medium text-[#344053] hover:bg-[#f8f9fb]">
                            Cancel
                        </button>
                        <button type="submit"
                            class="rounded-lg border border-[#7e56d8] bg-[#7e56d8] px-4 py-2 text-sm font-medium text-white hover:bg-[#6840c6]">
                            Post Update
                        </button>
                    </div>
                </form>
            </div>

            {{-- ##Updates Feed --}}
            @foreach ($deficiency->updates as $update)
                <div class="mb-6 flex gap-4 border-b border-[#e4e7ec] pb-6">
                    <img src="{{ $update->createdBy?->profile_pic ? Storage::url($update->createdBy->profile_pic) : Storage::url('profile_pictures/placeholder.svg.hi.png') }}"
                        alt="User avatar" class="h-10 w-10 flex-shrink-0 rounded-full">

                    <div class="flex-1">
                        <div class="mb-2 flex items-center gap-2">
                            <span class="font-medium text-[#0f1728]">
                                {{ $update->createdBy?->first_name }} {{ $update->createdBy?->last_name }}
                            </span>
                            <span class="text-sm text-[#667084]">•</span>
                            <span class="text-sm text-[#667084]">
                                {{ $update->created_at->format('F j, Y \a\t g:i A') }}
                            </span>
                        </div>

                        @if ($update->comment)
                            <div class="mb-3 text-sm text-[#344053]">
                                {{ $update->comment }}
                            </div>
                        @endif

                        <div class="flex flex-wrap gap-3 text-sm text-[#667084]">

                            @if ($update->new_status)
                                <div class="flex items-center gap-2">
                                    <span>Status changed:</span>
                                    <span
                                        class="{{ deficiency_status_class($update->new_status) }} rounded-full px-2 py-1 text-xs font-medium">
                                        {{ ucfirst($update->new_status) }}
                                    </span>
                                </div>
                            @endif

                            @if ($update->new_priority)
                                <div class="flex items-center gap-2">
                                    <span>Priority changed:</span>
                                    {!! priority_badge($update->new_priority) !!}
                                </div>
                            @endif

                            @if ($update->new_assignee)
                                <div class="flex items-center gap-2">
                                    <span>Assigned to:</span>

                                    <div class="flex items-center gap-2">
                                        <img src="{{ $user?->profile_pic ? Storage::url($user->profile_pic) : Storage::url('profile_pictures/placeholder.svg.hi.png') }}"
                                            class="h-5 w-5 rounded-full">
                                        <span>{{ $user?->first_name }} {{ $user?->last_name }}</span>
                                    </div>
                                </div>
                            @endif

                        </div>
                    </div>
                </div>
            @endforeach

        </div>
    </div>

    {{-- Toggle Instructions on Task View --}}
    <script>
        function toggleInstructions(id) {
            const element = document.getElementById(`instructions-${id}`);
            if (element) {
                element.classList.toggle('hidden');
            }
        }
    </script>

    {{-- Inline Description Editing --}}
    <script>
        function toggleDescriptionEdit() {
            const display = document.getElementById('descriptionDisplay');
            const edit = document.getElementById('descriptionEdit');
            const editBtn = document.getElementById('editDescriptionBtn');

            if (display.classList.contains('hidden')) {
                // Switch to display mode
                display.classList.remove('hidden');
                edit.classList.add('hidden');
                editBtn.innerHTML = '<i class="fa-solid fa-edit mr-1"></i>Edit';
            } else {
                // Switch to edit mode
                display.classList.add('hidden');
                edit.classList.remove('hidden');
                editBtn.innerHTML = '<i class="fa-solid fa-eye mr-1"></i>View';

                // Focus on the textarea
                document.querySelector('#descriptionEdit textarea[name="description"]').focus();
            }
        }

        function cancelDescriptionEdit() {
            const display = document.getElementById('descriptionDisplay');
            const edit = document.getElementById('descriptionEdit');
            const editBtn = document.getElementById('editDescriptionBtn');

            // Reset textarea to original value
            document.querySelector('#descriptionEdit textarea[name="description"]').value =
                '{{ $deficiency->description ?? '' }}';

            // Switch back to display mode
            display.classList.remove('hidden');
            edit.classList.add('hidden');
            editBtn.innerHTML = '<i class="fa-solid fa-edit mr-1"></i>Edit';
        }

        function saveDescription() {
            // The form will submit naturally, no need for custom handling
            // The success message will be shown via session flash message
        }
    </script>

@endsection
