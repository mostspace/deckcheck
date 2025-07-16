@extends('layouts.app')

@section('title', 'Work Order')

@section('content')

    @php
        $assignedUser = $workOrder->assignee;
        $users = $workOrder->equipmentInterval->equipment->vessel->users;
    @endphp

    {{-- #Breadcrumb --}}
    <div class="mb-4">
        <nav class="flex items-center space-x-2 text-sm">
            <a href="{{ route('equipment.index') }}">
                <span class="text-[#6840c6] hover:text-[#5a35a8] cursor-pointer">Equipment Index</span>
            </a>
            <i class="fa-solid fa-chevron-right text-[#667084] text-xs"></i>

            <a href="{{ route('equipment.show', $workOrder->equipmentInterval->equipment) }}">
                <span class="text-[#6840c6] hover:text-[#5a35a8] cursor-pointer">
                    {{ $workOrder->equipmentInterval->equipment->name }}
                </span>
            </a>
            <i class="fa-solid fa-chevron-right text-[#667084] text-xs"></i>

            <a href="{{ route('equipment-intervals.show', $workOrder->equipmentInterval) }}">
                <span class="text-[#6840c6] hover:text-[#5a35a8] cursor-pointer">
                    {{ ucfirst($workOrder->equipmentInterval->frequency) }}: {{ $workOrder->equipmentInterval->description }}
                </span>
            </a>
            <i class="fa-solid fa-chevron-right text-[#667084] text-xs"></i>

            <span class="text-[#475466] font-medium">Record #{{ $workOrder->id }}</span>
        </nav>
    </div>

    {{-- #System Messages --}}
    @if (session('error'))
        <div class="mb-4 p-3 bg-red-100 text-red-700 text-sm rounded">
            {{ session('error') }}
        </div>
    @endif

    {{-- #Main --}}
    <div class="mb-6" id="record-details">
        <div class="bg-white rounded-lg border border-[#e4e7ec] shadow-sm">

            {{-- ##Title Block --}}
            <div class="px-6 py-4 border-b border-[#e4e7ec]">
                <h2 class="text-lg font-semibold text-[#0f1728]">{{ $workOrder->equipmentInterval->description }}</h2>
            </div>

            {{-- ##Record Details --}}
            <div class="p-6">
                <div class="grid grid-cols-2 lg:grid-cols-4 gap-6">

                    {{-- ###Record ID --}}
                    <div>
                        <label class="block text-sm font-medium text-[#667084] mb-1">Record ID</label>
                        <p class="text-sm text-[#344053] font-medium">WO-{{ str_pad($workOrder->id, 6, '0', STR_PAD_LEFT) }}</p>
                    </div>

                    {{-- ###Status --}}
                    <div>
                        <label class="block text-sm font-medium text-[#667084] mb-1">Status</label>
                        <p class="py-1 text-xs font-medium rounded-full">
                            {!! status_badge($workOrder->status) !!}
                        </p>
                    </div>

                    {{-- ###Due Date --}}
                    <div>
                        <label class="block text-sm font-medium text-[#667084] mb-1">Due Date</label>
                        <p class="text-sm text-[#344053]">{!! work_order_due_badge($workOrder) !!}</p>
                    </div>

                    {{-- ###Complete Date --}}
                    <div>
                        <label class="block text-sm font-medium text-[#667084] mb-1">Completed Date</label>
                        <p class="text-sm text-[#344053]">{!! work_order_completed_badge($workOrder) !!}</p>
                    </div>

                    {{-- ###Assignee --}}
                    <div class="relative inline-block text-left" id="assignee-dropdown">
                        <label for="assigned_to" class="block text-sm font-medium text-[#344053] mb-1">Assigned To</label>

                        {{-- ####Trigger --}}
                        <button onclick="toggleAssigneeDropdown()"
                            class="flex items-center gap-2 px-4 py-2 border border-[#e4e7ec] rounded-lg shadow-sm hover:shadow-md bg-white text-sm font-medium text-[#344053]">
                            @if ($assignedUser)
                                <img src="{{ $assignedUser->profile_pic ? Storage::url($assignedUser->profile_pic) : Storage::url('profile_pictures/placeholder.svg.hi.png') }}"
                                    class="w-6 h-6 rounded-full" alt="Avatar">
                                {{ $assignedUser->first_name }} {{ $assignedUser->last_name }}
                            @else
                                <img src="{{ Storage::url('profile_pictures/placeholder.svg.hi.png') }}" class="w-6 h-6 rounded-full" alt="Avatar">
                                Unassigned
                            @endif

                            <i class="fa-solid fa-chevron-down ml-1 text-xs"></i>
                        </button>

                        {{-- ####Dropdown --}}
                        <div id="assignee-options" class="hidden absolute z-10 mt-2 w-56 bg-white rounded-lg shadow-lg border border-[#e4e7ec]">
                            <ul class="py-2">
                                @foreach ($users->sortBy('first_name') as $user)
                                    <li>
                                        <button onclick="assignUser({{ $workOrder->id }}, {{ $user->id }})"
                                            class="flex items-center w-full px-4 py-2 text-sm text-[#344053] hover:bg-[#f9fafb]">
                                            <img src="{{ $user->profile_pic ? Storage::url($user->profile_pic) : Storage::url('profile_pictures/placeholder.svg.hi.png') }}"
                                                class="w-6 h-6 rounded-full mr-3" alt="Avatar">
                                            {{ $user->first_name }} {{ $user->last_name }}
                                        </button>
                                    </li>
                                @endforeach
                                <li>
                                    <button onclick="assignUser({{ $workOrder->id }}, null)"
                                        class="flex items-center w-full px-4 py-2 text-sm text-[#b42318] hover:bg-[#fef3f2]">
                                        <i class="fa-solid fa-xmark mr-3"></i> Clear Assignee
                                    </button>
                                </li>
                            </ul>
                        </div>
                    </div>

                    {{-- ###Equipment Reference --}}
                    <div>
                        <label class="block text-sm font-medium text-[#667084] mb-1">Equipment</label>
                        <p class="text-sm text-[#344053]">{{ $workOrder->equipmentInterval->equipment->name ?? '—' }}</p>
                    </div>

                    {{-- ###Location --}}
                    <div>
                        <label class="block text-sm font-medium text-[#667084] mb-1">Location</label>
                        <p class="text-sm text-[#344053]">{{ $workOrder->equipmentInterval->equipment->location->name ?? '—' }}</p>
                    </div>

                    {{-- ###Interval --}}
                    <div>
                        <label class="block text-sm font-medium text-[#667084] mb-1">Interval</label>
                        <p class="text-sm"><span
                                class="px-2 py-1 text-xs font-medium rounded-full {{ frequency_label_class($workOrder->equipmentInterval->frequency) }} uppercase">{{ $workOrder->equipmentInterval->frequency }}</span>
                        </p>
                    </div>
                </div>
            </div>

            {{-- ##Work Order Summary --}}
            <div class="mb-6 p-6" id="tasks-section">
                <div class="bg-white rounded-lg border border-[#e4e7ec] shadow-sm">

                    {{-- ###Status Conditional --}}
                    @if ($workOrder->status === 'completed')

                        {{-- ###IF COMPLETE --}}

                        {{-- ####Header --}}
                        <div class="bg-[#f8f9fb] border-b border-[#e4e7ec]">
                            <h2 class="text-lg font-semibold text-[#0f1728] px-6 py-2">Inspection Summary</h2>
                        </div>

                        {{-- ####Procedure Summary --}}
                        <div class="p-6 text-sm text-[#475466] space-y-4">
                            <p>This work order has been marked <span class="uppercase font-bold">{!! status_badge($workOrder->status) !!}</span></p>
                            <p class="italic">A detailed summary of completed tasks will appear here.</p>

                            @if ($workOrder->notes)
                                <div class="mt-4">
                                    <h3 class="text-sm font-semibold text-[#344053] mb-1">Comments / Observations</h3>
                                    <div class="px-4 py-3 rounded-lg bg-[#f9f5ff] border border-[#d6bbfb] text-[#6941c6] text-sm leading-relaxed">
                                        {{ $workOrder->notes }}
                                    </div>
                                </div>
                            @endif

                            @if ($workOrder->deficiency)
                                <div class="mt-6 bg-[#fef3c7] border border-[#d4bf6e] rounded-lg p-4">
                                    <h3 class="text-sm font-semibold text-[#92400e] mb-2">
                                        <i class="fa-solid fa-circle-exclamation"></i>
                                        Related Deficiency
                                    </h3>

                                    <p class="text-sm text-[#78350f]">
                                        A deficiency was automatically generated upon completion of this work order.
                                    </p>

                                    <a href="{{ route('deficiencies.show', $workOrder->deficiency) }}"
                                        class="mt-2 inline-block text-sm font-medium text-[#92400e] hover:underline">
                                        View Deficiency →
                                    </a>
                                </div>
                            @endif

                            @if ($workOrder->tasks->count())
                                <div class="mt-6">
                                    <h3 class="text-sm font-semibold text-[#344053] mb-2">Task Completion Log</h3>
                                    <ul class="space-y-3">
                                        @foreach ($workOrder->tasks as $task)
                                            <li class="p-3 rounded-md border border-[#e4e7ec] bg-[#f9fafb]">
                                                <div class="flex justify-between items-start gap-4">
                                                    <div class="flex-1">

                                                        {{-- ##Task Name --}}
                                                        <div class="flex items-center gap-2 cursor-pointer mb-3"
                                                            onclick="toggleInstructions({{ $task->id }})">
                                                            <p class="font-semibold text-base text-[#344053]">{{ $task->name }}</p>
                                                            <i class="fa-solid fa-eye text-xs text-[#667084]"></i>
                                                        </div>

                                                        {{-- ##Task Instructions --}}
                                                        @if ($task->instructions)
                                                            <div id="instructions-{{ $task->id }}"
                                                                class="px-4 py-3 mb-3 rounded-lg bg-[#f9f5ff] border border-[#d6bbfb] text-[#6941c6] text-sm leading-relaxed hidden">
                                                                {!! nl2br(e($task->instructions)) !!}
                                                            </div>
                                                        @endif

                                                        <hr><br>

                                                        {{-- ##Timestamp --}}
                                                        <span class="mb-1 text-xs">
                                                            <strong>Recorded:</strong>
                                                            <span class="text-[#6941c6]">{{ $task->completed_at ? $task->completed_at : '—' }}
                                                            </span>

                                                            {{-- ##Completed By --}}
                                                            @if ($task->completedBy)
                                                                <div class="flex items-center gap-2 text-xs">
                                                                    <strong>By:</strong>
                                                                    <img src="{{ $task->completedBy->profile_pic ? Storage::url($task->completedBy->profile_pic) : Storage::url('profile_pictures/placeholder.svg.hi.png') }}"
                                                                        alt="Avatar" class="w-5 h-5 rounded-full">
                                                                    <span class="text-[#6941c6]"> {{ $task->completedBy->first_name }}
                                                                        {{ $task->completedBy->last_name }}</span>
                                                                </div>
                                                            @endif
                                                    </div>

                                                    {{-- Right Column --}}
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

                        </div>
                    @elseif ($workOrder->status === 'scheduled')
                        {{-- ###IF SCHEDULED --}}

                        <div class="relative">
                            {{-- Greyed-out Open Procedure --}}
                            <div class="opacity-50 pointer-events-none">
                                {{-- ####Header --}}
                                <div class="bg-[#f8f9fb] border-b border-[#e4e7ec]">
                                    <div class="px-6 py-2 flex gap-2 items-center text-lg font-semibold">
                                        <i class="fa-solid fa-clipboard-list align-bottom text-[#6840c6]"></i>
                                        <h2 class="text-[#0f1728]">Procedure</h2>
                                    </div>
                                </div>

                                {{-- ####Procedure --}}
                                <div class="p-6">
                                    <div class="space-y-4">

                                        {{-- #####Tasks Loop --}}
                                        @forelse ($workOrder->tasks->sortBy('sequence_position') as $task)
                                            <div class="border border-[#e4e7ec] rounded-lg p-4">
                                                <div class="flex items-start justify-between mb-3">
                                                    <div class="flex-1">
                                                        <h3 class="text-sm font-medium text-[#0f1728] mb-2">{{ $task->name }}</h3>
                                                        <p class="text-sm text-[#667084] mb-3">{{ $task->instructions }}</p>

                                                        @if ($task->completedBy)
                                                            <div class="flex items-center gap-2">
                                                                <i class="fa-solid fa-user text-[#667084] text-sm"></i>
                                                                <span class="text-sm text-[#667084]">{{ $task->completedBy->name }}</span>
                                                            </div>
                                                        @endif
                                                    </div>

                                                    {{-- ######Actions (Disabled) --}}
                                                    <div class="flex gap-2 ml-4">
                                                        <button
                                                            class="px-3 py-1.5 text-sm font-medium rounded-lg bg-[#ebfdf2] text-[#027947] cursor-not-allowed"
                                                            disabled>
                                                            <i class="fa-solid fa-check mr-1"></i> Complete
                                                        </button>
                                                        <button
                                                            class="px-3 py-1.5 text-sm font-medium rounded-lg bg-[#fef3f2] text-[#b42318] cursor-not-allowed"
                                                            disabled>
                                                            <i class="fa-solid fa-flag mr-1"></i> Flag
                                                        </button>
                                                    </div>
                                                </div>
                                            </div>
                                        @empty
                                            <p class="text-sm text-[#667084] italic">No tasks assigned to this work order.</p>
                                        @endforelse

                                        {{-- #####Notes + Completion Form --}}
                                        @if ($workOrder->tasks->count())
                                            <div class="border-t border-[#e4e7ec] pt-4">
                                                <form>
                                                    <label class="block text-lg font-bold text-[#344053] mb-2">Comments / Observations</label>
                                                    <textarea class="w-full px-3.5 py-2.5 bg-white rounded-lg shadow border border-[#cfd4dc] text-[#0f1728] text-base leading-normal resize-none"
                                                        rows="4" placeholder="Enter any additional notes..." disabled></textarea>

                                                    <div class="mt-6 text-right">
                                                        <button type="button"
                                                            class="px-4 py-2 rounded-lg text-sm font-semibold bg-[#e4e7ec] text-[#667085] cursor-not-allowed"
                                                            disabled>
                                                            <i class="fa-solid fa-check mr-2"></i> Mark Work Order Complete
                                                        </button>
                                                    </div>
                                                </form>
                                            </div>
                                        @endif

                                    </div>
                                </div>
                            </div>

                            {{-- Overlay --}}
                            <div class="absolute inset-0 flex items-center justify-center z-10">
                                <div class="bg-white border border-[#e4e7ec] shadow-xl rounded-lg p-6 max-w-lg w-full text-center space-y-4">
                                    <p class="text-lg font-semibold text-[#0f1728]">This work order is scheduled for a future date.</p>
                                    <button onclick="openScheduledWorkOrder({{ $workOrder->id }})"
                                        class="inline-flex items-center gap-2 px-4 py-2 text-sm font-medium rounded-lg bg-[#6840c6] text-white hover:bg-[#5a35a8]">
                                        <i class="fa-solid fa-play"></i> Open Now?
                                    </button>
                                </div>
                            </div>
                        </div>
                        
                    @else
                        {{-- ###IF OPEN --}}

                        {{-- ####Header --}}
                        <div class="bg-[#f8f9fb] border-b border-[#e4e7ec]">
                            <div class="px-6 py-2 flex gap-2 items-center text-lg font-semibold">
                                <i class="fa-solid fa-clipboard-list align-bottom text-[#6840c6]"></i>
                                <h2 class="text-[#0f1728]">Procedure</h2>
                            </div>
                        </div>

                        {{-- ####Procedure --}}
                        <div class="p-6">
                            <div class="space-y-4">

                                {{-- #####Tasks Loop --}}
                                @forelse ($workOrder->tasks->sortBy('sequence_position') as $task)
                                    <div class="border border-[#e4e7ec] rounded-lg p-4">
                                        <div class="flex items-start justify-between mb-3">
                                            <div class="flex-1">
                                                <h3 class="text-sm font-medium text-[#0f1728] mb-2">{{ $task->name }}</h3>
                                                <p class="text-sm text-[#667084] mb-3">{{ $task->instructions }}</p>

                                                @if ($task->completedBy)
                                                    <div class="flex items-center gap-2">
                                                        <i class="fa-solid fa-user text-[#667084] text-sm"></i>
                                                        <span class="text-sm text-[#667084]">{{ $task->completedBy->name }}</span>
                                                    </div>
                                                @endif

                                            </div>

                                            {{-- ######Actions --}}
                                            <div class="flex gap-2 ml-4">

                                                {{-- #######Complete Button --}}
                                                <button
                                                    class="px-3 py-1.5 text-sm font-medium rounded-lg
                                                    {{ $task->status === 'completed' ? 'bg-[#6840c6] text-white ring-2 ring-offset-1 ring-[#6840c6]' : 'bg-[#ebfdf2] text-[#027947] hover:bg-[#d0fadf]' }}"
                                                    data-task-id="{{ $task->id }}" data-status="completed" onclick="updateTaskStatus(this)"
                                                    {{ $task->status === 'completed' ? 'disabled' : '' }}>
                                                    <i class="fa-solid fa-check mr-1"></i> Complete
                                                </button>

                                                {{-- #######Flag Button --}}
                                                <button
                                                    class="px-3 py-1.5 text-sm font-medium rounded-lg
                                                    {{ $task->is_flagged ? 'bg-[#b42318] text-white ring-2 ring-offset-1 ring-[#b42318]' : 'bg-[#fef3f2] text-[#b42318] hover:bg-[#fee4e2]' }}"
                                                    data-task-id="{{ $task->id }}" data-status="flagged" onclick="updateTaskStatus(this)"
                                                    {{ $task->is_flagged ? 'disabled' : '' }}>
                                                    <i class="fa-solid fa-flag mr-1"></i> Flag
                                                </button>
                                            </div>
                                        </div>
                                    </div>

                                @empty
                                    <p class="text-sm text-[#667084] italic">No tasks assigned to this work order.</p>
                                @endforelse

                                {{-- #####Notes + Completion Form --}}
                                @if ($workOrder->tasks->count())
                                    <div class="border-t border-[#e4e7ec] pt-4">
                                        <form id="complete-form" action="{{ route('work-orders.complete', $workOrder) }}" method="POST">
                                            @csrf

                                            <label class="block text-lg font-bold text-[#344053] mb-2">Comments / Observations</label>
                                            <textarea name="notes"
                                                class="w-full px-3.5 py-2.5 bg-white rounded-lg shadow-[0px_1px_2px_0px_rgba(16,24,40,0.05)] border border-[#cfd4dc] text-[#0f1728] text-base leading-normal resize-none focus:outline-none focus:ring-2 focus:ring-[#6840c6] focus:border-transparent"
                                                rows="4" placeholder="Enter any additional notes or observations about this maintenance record...">{{ old('notes', $workOrder->notes) }}</textarea>

                                            <div class="mt-6 text-right">
                                                <button type="submit" id="complete-work-order-button"
                                                    class="px-4 py-2 rounded-lg text-sm font-semibold bg-[#e4e7ec] text-[#667085] cursor-not-allowed"
                                                    disabled>
                                                    <i class="fa-solid fa-check mr-2"></i> Mark Work Order Complete
                                                </button>
                                            </div>
                                        </form>
                                    </div>
                                @endif

                            </div>
                        </div>

                    @endif

                </div>
            </div>

        </div>
    </div>


    {{-- Task Status Handling --}}
    <script>
        function updateTaskStatus(button) {
            const taskId = button.getAttribute('data-task-id');
            const status = button.getAttribute('data-status');

            fetch(`/work-orders/tasks/${taskId}`, {
                    method: 'PUT',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                        'Accept': 'application/json',
                    },
                    body: JSON.stringify({
                        status
                    })
                })
                .then(response => {
                    if (!response.ok) throw new Error('Network response was not ok.');
                    return response.json();
                })
                .then(data => {
                    if (data.success) {
                        const container = button.closest('.flex');
                        const completeBtn = container.querySelector('button[data-status="completed"]');
                        const flagBtn = container.querySelector('button[data-status="flagged"]');

                        // Reset both buttons
                        completeBtn.className = 'px-3 py-1.5 text-sm font-medium rounded-lg bg-[#ebfdf2] text-[#027947] hover:bg-[#d0fadf]';
                        flagBtn.className = 'px-3 py-1.5 text-sm font-medium rounded-lg bg-[#fef3f2] text-[#b42318] hover:bg-[#fee4e2]';
                        completeBtn.disabled = false;
                        flagBtn.disabled = false;

                        // Apply selected styling
                        if (status === 'completed') {
                            completeBtn.classList.add('ring-2', 'ring-offset-1', 'ring-[#6840c6]', 'bg-[#6840c6]', 'text-white');
                            completeBtn.classList.remove('text-[#027947]', 'hover:bg-[#d0fadf]', 'bg-[#ebfdf2]');
                            completeBtn.disabled = true;
                        } else if (status === 'flagged') {
                            flagBtn.classList.add('ring-2', 'ring-offset-1', 'ring-[#b42318]', 'bg-[#b42318]', 'text-white');
                            flagBtn.classList.remove('text-[#b42318]', 'hover:bg-[#fee4e2]', 'bg-[#fef3f2]');
                            flagBtn.disabled = true;
                        }

                        checkWorkOrderCompletable();
                    } else {
                        alert('Update failed.');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('There was a problem updating the task.');
                });
        }


        function checkWorkOrderCompletable() {
            const taskCards = document.querySelectorAll('[data-task-id]');
            const completeButton = document.getElementById('complete-work-order-button');

            const allResolved = Array.from(taskCards).every(card => {
                const btnContainer = card.closest('.flex');
                return btnContainer.querySelector('button[disabled]'); // At least one button in each group is disabled
            });

            if (completeButton) {
                if (allResolved) {
                    completeButton.disabled = false;
                    completeButton.classList.remove('bg-[#e4e7ec]', 'text-[#667085]', 'cursor-not-allowed');
                    completeButton.classList.add('bg-[#6840c6]', 'text-white', 'hover:bg-[#5a35a8]', 'cursor-pointer');
                } else {
                    completeButton.disabled = true;
                    completeButton.classList.add('bg-[#e4e7ec]', 'text-[#667085]', 'cursor-not-allowed');
                    completeButton.classList.remove('bg-[#6840c6]', 'text-white', 'hover:bg-[#5a35a8]', 'cursor-pointer');
                }
            }
        }

        // Run on page load
        document.addEventListener('DOMContentLoaded', checkWorkOrderCompletable);
    </script>

    {{-- AJAX User Assignment Update --}}
    <script>
        function toggleAssigneeDropdown() {
            const dropdown = document.getElementById('assignee-options');
            dropdown.classList.toggle('hidden');
        }

        function assignUser(workOrderId, userId) {
            fetch(`/work-orders/${workOrderId}/assign`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                        'Accept': 'application/json',
                    },
                    body: JSON.stringify({
                        assigned_to: userId
                    })
                })
                .then(response => {
                    if (!response.ok) throw new Error('Network response was not ok');
                    return response.json();
                })
                .then(() => window.location.reload())
                .catch(error => {
                    console.error('Error assigning user:', error);
                    alert('Failed to assign user.');
                });
        }
    </script>

    {{-- Toggle Instructions on Completed View --}}
    <script>
        function toggleInstructions(id) {
            const element = document.getElementById(`instructions-${id}`);
            if (element) {
                element.classList.toggle('hidden');
            }
        }
    </script>

    {{-- Open Scheduled Task --}}
    <script>
        function openScheduledWorkOrder(workOrderId) {
            fetch(`/work-orders/${workOrderId}/open`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                        'Accept': 'application/json',
                    },
                })
                .then(response => {
                    if (!response.ok) throw new Error('Failed to update status');
                    return response.json();
                })
                .then(data => {
                    if (data.success) {
                        window.location.reload();
                    } else {
                        alert('Failed to open work order.');
                    }
                })
                .catch(error => {
                    console.error(error);
                    alert('There was a problem updating the work order.');
                });
        }
    </script>


@endsection
