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
                <span class="text-[#6840c6] hover:text-[#5a35a8] cursor-pointer">Deficiencies</span>
            </a>
            <i class="fa-solid fa-chevron-right text-[#667084] text-xs"></i>
            <span class="text-[#475466]">Deficiency DEF-0000{{ $deficiency->id }}</span>
        </nav>
    </div>

    {{-- System Messages --}}
    @if (session('success'))
        <div class="mb-6 p-4 bg-green-100 border border-green-300 text-green-800 rounded-lg text-sm">
            {{ session('success') }}
        </div>
    @endif



    {{-- #Hero --}}
    <div class="mb-6" id="deficiency-hero">
        <div class="bg-white rounded-md border border-[#e4e7ec] shadow-sm p-6 space-y-8">

            {{-- ##Title Block --}}
            <div>
                <h1 class="flex items-center text-2xl font-semibold text-[#0f1728] mb-2">
                    <div class="w-8 h-8 bg-[#fffaeb] border rounded-md flex items-center justify-center mr-1">
                        <i class="fa-solid fa-flag text-[#b54708] text-xl"></i>
                    </div>
                    {{ $deficiency->subject }}
                </h1>

                <div class="flex">
                    {{-- Arrow SVG --}}
                    <span class="mr-2 ml-2 flex py-4 justify-center">
                        <svg width="22" height="22" fill="none" viewBox="0 0 22 22">
                            <path
                                d="M4 4v8a2 2 0 0 0 2 2h7.586l-3.293-3.293a1 1 0 0 1 1.414-1.414l5 5a1 1 0 0 1 0 1.414l-5 5a1 1 0 1 1-1.414-1.414L13.586 16H6a4 4 0 0 1-4-4V4a1 1 0 1 1 2 0Z"
                                fill="#7e56d8" />
                        </svg>
                    </span>

                    {{-- Equipment Summary Card --}}
                    <a href="{{ route('equipment.show', $deficiency->equipment->id) }}">
                        <div class="flex items-center rounded-md border border-[#e4e7ec] bg-[#f9fafb] p-2 shadow-sm hover:bg-[#f3f4f6] transition mb-4"
                            style="min-width:300px; max-width:100%;">
                            {{-- Hero Image --}}
                            <div class="w-20 h-20 flex-shrink-0 rounded-md overflow-hidden border border-[#e4e7ec] bg-white">
                                <img src="{{ $deficiency->equipment->hero_photo ? Storage::url($deficiency->equipment->hero_photo) : asset('images/placeholders/placeholder.png') }}"
                                    class="object-cover w-full h-full" alt="Equipment">
                            </div>
                            {{-- Info --}}
                            <div class="ml-4 flex flex-col min-w-0">
                                {{-- Name + Icon --}}
                                <div class="flex items-center gap-2 mb-1">
                                    <span
                                        class="inline-flex items-center justify-center w-7 h-7 rounded-md bg-[#f3ebff] border border-[#d6bbfb] text-[#6840c6]">
                                        <i class="fa-solid {{ $deficiency->equipment->category->icon ?? 'fa-screwdriver-wrench' }}"></i>
                                    </span>
                                    <span class="text-base font-semibold text-[#0f1728] truncate">{{ $deficiency->equipment->name ?? 'Unnamed' }}</span>
                                </div>
                                {{-- Location --}}
                                <div class="flex items-center text-xs text-[#667084] gap-1 mb-1">
                                    <i class="fa-solid fa-location-dot text-[#6840c6]"></i>
                                    <span class="font-medium text-[#344053]">{{ $deficiency->equipment->deck->name }}</span>
                                    <span>/ {{ $deficiency->equipment->location->name }}</span>
                                </div>
                                {{-- Internal Info --}}
                                <div class="flex items-center text-xs text-[#475467] gap-2">
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
            <div class="flex flex-col md:flex-row gap-12 text-sm">

                {{-- ###Opened By --}}
                <div class="flex items-center gap-2">
                    <div>
                        <p class="text-[#667084] text-xs font-medium uppercase mb-1">Opened By</p>
                        <div class="flex items-center gap-2">
                            <img src="{{ $deficiency->openedBy?->profile_pic
                                ? Storage::url($deficiency->openedBy->profile_pic)
                                : Storage::url('profile_pictures/placeholder.svg.hi.png') }}"
                                class="w-6 h-6 rounded-full">
                            <span class="text-[#344053] font-bold">{{ $deficiency->openedBy?->first_name }}
                                {{ $deficiency->openedBy?->last_name }}</span>
                        </div>
                    </div>
                </div>

                {{-- ###Opened Date --}}
                <div>
                    <p class="text-[#667084] text-xs font-medium uppercase mb-1">Date Opened</p>
                    <p class="text-[#344053] font-bold">{{ $deficiency->created_at->format('F j, Y') }}</p>
                </div>

                <div>
                    <p class="text-[#667084] text-xs font-medium uppercase mb-1">Aging</p>
                    <span class="text-[#be123c] font-normal">{{ $daysOpenFormatted }} days ago</span>
                </div>



            </div>

            {{-- ##Description --}}
            <div class="">
                <div class="flex items-center gap-2 mb-2">
                    <p class="text-[#667084] text-xs font-medium uppercase">Initial Report</p>
                    <button 
                        onclick="toggleDescriptionEdit()" 
                        class="text-[#6840c6] hover:text-[#5a35a8] text-xs hover:underline transition-colors"
                        id="editDescriptionBtn"
                    >
                        <i class="fa-solid fa-edit mr-1"></i>Edit
                    </button>
                </div>
                
                {{-- Display Mode --}}
                <div id="descriptionDisplay" class="text-sm font-normal text-[#344053] min-h-[1.5rem]">
                    @if($deficiency->description)
                        {!! nl2br(e($deficiency->description)) !!}
                    @else
                        <span class="text-[#667084] italic">No initial report provided</span>
                    @endif
                </div>
                
                {{-- Edit Mode --}}
                <form id="descriptionEdit" class="hidden" method="POST" action="{{ route('deficiencies.update-description', $deficiency) }}">
                    @csrf
                    <textarea 
                        name="description"
                        class="w-full px-3 py-2 border border-[#d1d5db] rounded-lg focus:ring-2 focus:ring-[#6840c6] focus:border-[#6840c6] transition-colors duration-200 resize-none"
                        rows="4"
                        placeholder="Enter the initial report description..."
                    >{{ $deficiency->description }}</textarea>
                    <div class="flex items-center justify-end space-x-2 mt-2">
                        <button 
                            type="button"
                            onclick="cancelDescriptionEdit()" 
                            class="px-3 py-1 text-sm text-[#667084] hover:text-[#344053] transition-colors"
                        >
                            Cancel
                        </button>
                        <button 
                            type="submit"
                            class="px-3 py-1 text-sm bg-[#6840c6] text-white rounded hover:bg-[#5a35a8] transition-colors"
                        >
                            Save
                        </button>
                    </div>
                </form>
            </div>

            {{-- ##Work Order Reference --}}


            {{-- ###Work Order Reference Conditional --}}
            @if ($deficiency->workOrder && $deficiency->workOrder->tasks->where('status', 'flagged')->count())

                <div class="bg-white rounded-md border border-[#e4e7ec] shadow-sm mb-6">

                    {{-- ####Title Block --}}
                    <div class="bg-[#f8f9fb] rounded-tl-lg rounded-tr-lg border-b border-[#e4e7ec]">
                        <h2 class="text-lg font-semibold text-[#0f1728] px-6 py-2">
                            <span class="uppercase {{ frequency_label_class($deficiency->workOrder->equipmentInterval->frequency) }}">
                                {{ $deficiency->workOrder->equipmentInterval->frequency }}</span>
                            {{ $deficiency->workOrder->equipmentInterval->description }}
                        </h2>
                    </div>

                    <div class="mx-6 my-3 leading-relaxed">
                        <h3 class="text-sm font-semibold text-[#344053] mb-1">Comments / Observations</h3>
                        <p class="px-6 py-2 rounded-lg bg-[#f9f5ff] border border-[#d6bbfb] text-[#6941c6] text-sm">{{ $deficiency->workOrder->notes }}
                        </p>
                    </div>

                    <ul class="space-y-3 px-6 pt-1 pb-6">

                        {{-- ####Flagged Task Loop --}}
                        @foreach ($deficiency->workOrder->tasks->where('status', 'flagged') as $task)
                            <li class="p-6 rounded-md border border-[#e4e7ec] bg-[#f9fafb]">
                                <div class="flex justify-between items-start gap-4">
                                    <div class="flex-1">
                                        <div class="flex items-center gap-2 cursor-pointer mb-3" onclick="toggleInstructions({{ $task->id }})">
                                            <p class="font-semibold text-base text-[#344053]">{{ $task->name }}</p>
                                            <i class="fa-solid fa-eye text-xs text-[#667084]"></i>
                                        </div>

                                        @if ($task->instructions)
                                            <div id="instructions-{{ $task->id }}"
                                                class="px-4 py-3 mb-3 rounded-lg bg-[#f9f5ff] border border-[#d6bbfb] text-[#6941c6] text-sm leading-relaxed hidden">
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
                                                    alt="Avatar" class="w-5 h-5 rounded-full">
                                                <span class="text-[#6941c6]">{{ $task->completedBy->first_name }}
                                                    {{ $task->completedBy->last_name }}</span>
                                            </div>
                                        @endif

                                    </div>

                                    <div class="flex flex-col items-end text-xs text-[#667084] whitespace-nowrap">
                                        <p class="text-xs text-[#667084] mt-1">
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
            <div class="flex flex-col md:flex-row gap-8 text-sm border-t border-[#e4e7ec] pt-4 mt-4">

                {{-- ###Status --}}
                <div>
                    <p class="text-[#667084] text-xs font-medium uppercase mb-3">Status</p>
                    <span
                        class="px-2 py-1 text-xs font-medium rounded-full capitalize {{ deficiency_status_class($deficiency->status) }}">{{ $deficiency->status }}</span>
                </div>

                {{-- ###Priority --}}
                <div>
                    <p class="text-[#667084] text-xs font-medium uppercase mb-3">Priority</p>
                    {!! priority_badge($deficiency->priority) !!}
                </div>

                {{-- ###Assigned To --}}
                <div class="ml-auto">
                    <p class="text-[#667084] text-xs font-medium uppercase mb-3">Assigned To</p>
                    <div class="max-w-sm">

                        <div x-data="{ open: false }" class="relative">
                            <button @click="open = !open" type="button"
                                class="flex items-center gap-2 border border-[#e4e7ec] bg-white px-3 py-1 rounded-md text-sm text-[#344053] hover:border-[#c7c9d1] w-full">
                                <img src="{{ $deficiency->assignedTo?->profile_pic
                                    ? Storage::url($deficiency->assignedTo->profile_pic)
                                    : Storage::url('profile_pictures/placeholder.svg.hi.png') }}"
                                    class="w-5 h-5 rounded-full">
                                <span>{{ $deficiency->assignedTo?->first_name ?? 'Unassigned' }}
                                    {{ $deficiency->assignedTo?->last_name ?? '' }}</span>
                                <i class="fa-solid fa-chevron-down text-xs ml-auto text-[#667084]"></i>
                            </button>

                            <div x-show="open" @click.away="open = false"
                                class="absolute z-10 mt-2 w-full bg-white border border-[#e4e7ec] rounded-lg shadow-lg max-h-60 overflow-y-auto text-sm">

                                @foreach ($users as $user)
                                    <form method="POST" action="{{ route('deficiencies.assign', $deficiency) }}">
                                        @csrf
                                        @method('PUT')
                                        <input type="hidden" name="assigned_to" value="{{ $user->id }}">
                                        <button type="submit" class="w-full text-left px-3 py-2 hover:bg-[#f9f9f9] flex items-center gap-2">
                                            <img src="{{ $user->profile_pic ? Storage::url($user->profile_pic) : Storage::url('profile_pictures/placeholder.svg.hi.png') }}"
                                                class="w-5 h-5 rounded-full">
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
    <div class="bg-white rounded-lg border border-[#e4e7ec] shadow-sm" id="activity-feed">
        <div class="p-6">

            {{-- ##Title --}}
            <h2 class="text-lg font-semibold text-[#0f1728] mb-6">Updates</h2>

            {{-- ##Update Form --}}
            <div class="bg-[#f8f9fb] rounded-lg p-4 mb-6 border border-[#e4e7ec]">
                <form method="POST" action="{{ route('deficiencies.updates.store', $deficiency) }}">
                    @csrf

                    {{-- ###Comment --}}
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-[#344053] mb-2">Add Comment</label>
                        <textarea name="comment" placeholder="Enter your comment..."
                            class="w-full px-3 py-2.5 bg-white rounded-lg border border-[#cfd4dc] text-[#0f1728] text-sm leading-normal resize-none focus:outline-none focus:ring-2 focus:ring-[#7e56d8] focus:border-[#7e56d8]"
                            rows="3">{{ old('comment') }}</textarea>
                    </div>

                    <div class="flex flex-col sm:flex-row gap-4 mb-4">

                        {{-- ###Update Status --}}
                        <div class="flex-1">
                            <label class="block text-sm font-medium text-[#344053] mb-2">Update Status</label>
                            <select name="new_status"
                                class="w-full px-3 py-2.5 bg-white rounded-lg border border-[#cfd4dc] text-[#0f1728] text-sm focus:outline-none focus:ring-2 focus:ring-[#7e56d8] focus:border-[#7e56d8]">
                                <option value="">No change</option>
                                <option value="open">Open</option>
                                <option value="waiting">Waiting</option>
                                <option value="resolved">Resolved</option>
                            </select>
                        </div>

                        {{-- ###Update Priority --}}
                        <div class="flex-1">
                            <label class="block text-sm font-medium text-[#344053] mb-2">Update Priority</label>
                            <select name="new_priority"
                                class="w-full px-3 py-2.5 bg-white rounded-lg border border-[#cfd4dc] text-[#0f1728] text-sm focus:outline-none focus:ring-2 focus:ring-[#7e56d8] focus:border-[#7e56d8]">
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
                            class="px-4 py-2 bg-white rounded-lg border border-[#cfd4dc] text-[#344053] text-sm font-medium hover:bg-[#f8f9fb]">
                            Cancel
                        </button>
                        <button type="submit"
                            class="px-4 py-2 bg-[#7e56d8] rounded-lg border border-[#7e56d8] text-white text-sm font-medium hover:bg-[#6840c6]">
                            Post Update
                        </button>
                    </div>
                </form>
            </div>

            {{-- ##Updates Feed --}}
            @foreach ($deficiency->updates as $update)
                <div class="flex gap-4 pb-6 border-b border-[#e4e7ec] mb-6">
                    <img src="{{ $update->createdBy?->profile_pic ? Storage::url($update->createdBy->profile_pic) : Storage::url('profile_pictures/placeholder.svg.hi.png') }}"
                        alt="User avatar" class="w-10 h-10 rounded-full flex-shrink-0">

                    <div class="flex-1">
                        <div class="flex items-center gap-2 mb-2">
                            <span class="font-medium text-[#0f1728]">
                                {{ $update->createdBy?->first_name }} {{ $update->createdBy?->last_name }}
                            </span>
                            <span class="text-sm text-[#667084]">•</span>
                            <span class="text-sm text-[#667084]">
                                {{ $update->created_at->format('F j, Y \a\t g:i A') }}
                            </span>
                        </div>

                        @if ($update->comment)
                            <div class="text-sm text-[#344053] mb-3">
                                {{ $update->comment }}
                            </div>
                        @endif

                        <div class="flex flex-wrap gap-3 text-sm text-[#667084]">

                            @if ($update->new_status)
                                <div class="flex items-center gap-2">
                                    <span>Status changed:</span>
                                    <span class="px-2 py-1 text-xs font-medium rounded-full {{ deficiency_status_class($update->new_status) }}">
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
                                            class="w-5 h-5 rounded-full">
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
            document.querySelector('#descriptionEdit textarea[name="description"]').value = '{{ $deficiency->description ?? "" }}';
            
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
