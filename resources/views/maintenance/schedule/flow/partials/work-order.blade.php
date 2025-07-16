@php
    $assignedUser = $workOrder->assignee;
    $users = $availableUsers ?? collect();
@endphp

{{-- System Message --}}
@if (session('error'))
    <div class="mb-4 p-3 bg-red-100 text-red-700 text-sm rounded">
        {{ session('error') }}
    </div>
@endif

{{-- Work Order Header --}}
<div class="bg-white rounded-lg border border-[#e4e7ec] shadow-sm mb-8">

    {{-- ##Title Block --}}
    <div class="px-6 py-4 border-b border-[#e4e7ec]">
        <h2 class="text-lg font-semibold text-[#0f1728]">{{ $workOrder->equipmentInterval->description }}</h2>
    </div>

    {{-- ##Record Details --}}
    <div class="p-6 grid grid-cols-2 lg:grid-cols-4 gap-6">

        {{-- ###Record ID --}}
        <div>
            <label class="block text-sm font-medium text-[#667084] mb-1">Record ID</label>
            <p class="text-sm font-medium text-[#344053]">WO-{{ str_pad($workOrder->id, 6, '0', STR_PAD_LEFT) }}</p>
        </div>

        {{-- ###Status --}}
        <div>
            <label class="block text-sm font-medium text-[#667084] mb-1">Status</label>
            <p class="py-1 text-xs font-medium rounded-full">{!! status_badge($workOrder->status) !!}</p>
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
            <label class="block text-sm font-medium text-[#344053] mb-1">Assigned To</label>

            {{-- ####Trigger --}}
            <button onclick="toggleAssigneeDropdown()"
                class="flex items-center gap-2 px-4 py-2 border border-[#e4e7ec] rounded-lg shadow-sm bg-white text-sm font-medium text-[#344053]">
                @if ($assignedUser)
                    <img src="{{ $assignedUser->profile_pic ? Storage::url($assignedUser->profile_pic) : Storage::url('profile_pictures/placeholder.svg.hi.png') }}"
                        class="w-6 h-6 rounded-full">
                    {{ $assignedUser->first_name }} {{ $assignedUser->last_name }}
                @else
                    <img src="{{ Storage::url('profile_pictures/placeholder.svg.hi.png') }}" class="w-6 h-6 rounded-full">
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
                                    class="w-6 h-6 rounded-full mr-3">
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

{{-- Tasks and Completion --}}
<div class="mb-12 p-6 bg-white rounded-lg border border-[#e4e7ec] shadow-sm">

    {{-- #Header --}}
    <div class="mb-4">
        <h2 class="text-lg font-semibold text-[#0f1728]">
            <i class="fa-solid fa-clipboard-list text-[#6840c6] mr-2"></i> Procedure
        </h2>
    </div>

    <div class="space-y-4">
        
        {{-- #Tasks Loop --}}
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

                    {{-- ##Action Buttons --}}
                    <div class="flex gap-2 ml-4">
                        {{-- ###Complete --}}
                        <button
                            class="px-3 py-1.5 text-sm font-medium rounded-lg
                            {{ $task->status === 'completed' ? 'bg-[#6840c6] text-white ring-2 ring-offset-1 ring-[#6840c6]' : 'bg-[#ebfdf2] text-[#027947] hover:bg-[#d0fadf]' }}"
                            data-task-id="{{ $task->id }}" data-status="completed" onclick="updateTaskStatus(this)"
                            {{ $task->status === 'completed' ? 'disabled' : '' }}>
                            <i class="fa-solid fa-check mr-1"></i> Complete
                        </button>

                        {{-- ###Flag --}}
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

    </div>

    {{-- #Completion Form --}}
    @if ($workOrder->tasks->count())

        <div class="border-t border-[#e4e7ec] pt-6 mt-6">
            <form id="complete-form" action="{{ route('work-orders.complete', $workOrder) }}" method="POST">
                @csrf

                <label class="block text-base font-bold text-[#344053] mb-2">Comments / Observations</label>
                <textarea name="notes"
                    class="w-full px-3.5 py-2.5 bg-white rounded-lg shadow-sm border border-[#cfd4dc] text-[#0f1728] text-sm resize-none focus:outline-none focus:ring-2 focus:ring-[#6840c6]"
                    rows="4" placeholder="Enter any final notes about this record...">{{ old('notes', $workOrder->notes) }}</textarea>

                <div class="mt-6 text-right">
                    <button type="submit" id="complete-work-order-button"
                        class="px-4 py-2 rounded-lg text-sm font-semibold bg-[#e4e7ec] text-[#667085] cursor-not-allowed" disabled>
                        <i class="fa-solid fa-check mr-2"></i> Mark Work Order Complete
                    </button>
                </div>
            </form>
        </div>
    
    @endif

</div>



