{{-- resources/views/work-orders/partials/summary.blade.php --}}
@props(['workOrder', 'withButton' => false])

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
                    <img src="{{ $assignedUser->profile_pic ? Storage::url($assignedUser->profile_pic) : asset('images/placeholders/user.png') }}"
                        class="w-6 h-6 rounded-full" alt="Avatar">
                    {{ $assignedUser->first_name }} {{ $assignedUser->last_name }}
                @else
                    <img src="{{ asset('images/placeholders/user.png') }}" class="w-6 h-6 rounded-full" alt="Avatar">
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
                                <img src="{{ $user->profile_pic ? Storage::url($user->profile_pic) : asset('images/placeholders/user.png') }}"
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

<div id="tasks-section" class="mb-6 p-6">
    <div class="bg-white rounded-lg border border-[#e4e7ec] shadow-sm">
        {{-- ## COMPLETED --}}
        @if ($workOrder->status === 'completed')
            <div class="bg-[#f8f9fb] border-b border-[#e4e7ec]">
                <h2 class="text-lg font-semibold text-[#0f1728] px-6 py-2">Inspection Summary</h2>
            </div>
            <div class="p-6 text-sm text-[#475466] space-y-4">
                <p>
                    This work order has been marked
                    <span class="uppercase font-bold">{!! status_badge($workOrder->status) !!}</span>
                </p>
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
                            <i class="fa-solid fa-circle-exclamation"></i> Related Deficiency
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
                                            <div class="flex items-center gap-2 cursor-pointer mb-3"
                                                onclick="toggleInstructions({{ $task->id }})">
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
                                                <span class="text-[#6941c6]">{{ $task->completed_at ?: '—' }}</span>
                                            </span>
                                            @if ($task->completedBy)
                                                <div class="flex items-center gap-2 text-xs">
                                                    <strong>By:</strong>
                                                    <img src="{{ $task->completedBy->profile_pic ? Storage::url($task->completedBy->profile_pic) : asset('images/placeholders/user.png') }}"
                                                        alt="Avatar" class="w-5 h-5 rounded-full">
                                                    <span class="text-[#6941c6]">
                                                        {{ $task->completedBy->first_name }} {{ $task->completedBy->last_name }}
                                                    </span>
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
            </div>

            {{-- ## SCHEDULED --}}
        @elseif ($workOrder->status === 'scheduled')
            <div class="relative">
                <div class="opacity-50 pointer-events-none">
                    <div class="bg-[#f8f9fb] border-b border-[#e4e7ec]">
                        <div class="px-6 py-2 flex gap-2 items-center text-lg font-semibold">
                            <i class="fa-solid fa-clipboard-list text-[#6840c6]"></i>
                            <h2 class="text-[#0f1728]">Procedure</h2>
                        </div>
                    </div>
                    <div class="p-6">
                        <div class="space-y-4">
                            @forelse ($workOrder->tasks->sortBy('sequence_position') as $task)
                                <div class="border border-[#e4e7ec] rounded-lg p-4">
                                    <div class="flex items-start justify-between mb-3">
                                        <div class="flex-1">
                                            <h3 class="text-sm font-medium text-[#0f1728] mb-2">{{ $task->name }}</h3>
                                            <p class="text-sm text-[#667084] mb-3">{{ $task->instructions }}</p>
                                            @if ($task->completedBy)
                                                <div class="flex items-center gap-2">
                                                    <i class="fa-solid fa-user text-[#667084] text-sm"></i>
                                                    <span class="text-sm text-[#667084]">
                                                        {{ $task->completedBy->first_name }} {{ $task->completedBy->last_name }}
                                                    </span>
                                                </div>
                                            @endif
                                        </div>
                                        <div class="flex gap-2 ml-4">
                                            <button class="px-3 py-1.5 text-sm font-medium rounded-lg bg-[#ebfdf2] text-[#027947] cursor-not-allowed"
                                                disabled>
                                                <i class="fa-solid fa-check mr-1"></i> Complete
                                            </button>
                                            <button class="px-3 py-1.5 text-sm font-medium rounded-lg bg-[#fef3f2] text-[#b42318] cursor-not-allowed"
                                                disabled>
                                                <i class="fa-solid fa-flag mr-1"></i> Flag
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            @empty
                                <p class="text-sm text-[#667084] italic">No tasks assigned to this work order.</p>
                            @endforelse
                        </div>
                    </div>
                </div>
                <div class="absolute inset-0 flex items-center justify-center z-10">
                    <div class="bg-white border border-[#e4e7ec] shadow-xl rounded-lg p-6 max-w-lg w-full text-center space-y-4">
                        <p class="text-lg font-semibold text-[#0f1728]">
                            This work order is scheduled for a future date.
                        </p>
                        <button onclick="openScheduledWorkOrder({{ $workOrder->id }})"
                            class="inline-flex items-center gap-2 px-4 py-2 text-sm font-medium rounded-lg bg-[#6840c6] text-white hover:bg-[#5a35a8]">
                            <i class="fa-solid fa-play"></i> Open Now?
                        </button>
                    </div>
                </div>
            </div>

            {{-- ## OPEN --}}
        @else
            <div class="">
                <div class="px-6 pb-2 pt-4 flex gap-2 items-center text-lg font-semibold">
                    <i class="fa-solid fa-clipboard-list text-[#6840c6]"></i>
                    <h2 class="text-[#0f1728]">Procedure</h2>
                </div>
            </div>
            <div class="px-6 pt-2 pb-6">
                <div class="space-y-4">
                    @forelse ($workOrder->tasks->sortBy('sequence_position') as $task)
                        <div class="border border-[#e4e7ec] rounded-lg p-4">
                            <div class="flex items-start justify-between mb-3">
                                <div class="flex-1">
                                    <h3 class="text-sm font-medium text-[#0f1728] mb-2">{{ $task->name }}</h3>
                                    <p class="text-sm text-[#667084] mb-3">{{ $task->instructions }}</p>
                                    @if ($task->completedBy)
                                        <div class="flex items-center gap-2">
                                            <i class="fa-solid fa-user text-[#667084] text-sm"></i>
                                            <span class="text-sm text-[#667084]">
                                                {{ $task->completedBy->first_name }} {{ $task->completedBy->last_name }}
                                            </span>
                                        </div>
                                    @endif
                                </div>
                                <div class="flex gap-2 ml-4">
                                    <button
                                        class="px-3 py-1.5 text-sm font-medium rounded-lg
                                           {{ $task->status === 'completed'
                                               ? 'bg-[#6840c6] text-white ring-2 ring-offset-1 ring-[#6840c6]'
                                               : 'bg-[#ebfdf2] text-[#027947] hover:bg-[#d0fadf]' }}"
                                        data-task-id="{{ $task->id }}" data-status="completed" onclick="updateTaskStatus(this)"
                                        {{ $task->status === 'completed' ? 'disabled' : '' }}>
                                        <i class="fa-solid fa-check mr-1"></i> Complete
                                    </button>
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
            </div>
        @endif

        {{-- ## Mark Work Order Complete form (only on OPEN + withButton) --}}
        @if ($withButton && in_array($workOrder->status, ['open', 'in_progress', 'flagged']) && $workOrder->tasks->count())
            <div class="border-t border-[#e4e7ec] m-6 pt-4">
                <form id="complete-form" action="{{ route('work-orders.complete', $workOrder) }}" method="POST">
                    @csrf
                    <label class="block text-lg font-bold text-[#344053] mb-2">Comments / Observations</label>
                    <textarea name="notes"
                        class="w-full px-3.5 py-2.5 bg-white rounded-lg shadow border border-[#cfd4dc] text-[#0f1728] text-base leading-normal resize-none focus:outline-none focus:ring-2 focus:ring-[#6840c6]"
                        rows="4" placeholder="Enter any additional notes or observations about this maintenance record...">{{ old('notes', $workOrder->notes) }}</textarea>
                    <div class="mt-6 text-right">
                        <button type="submit" id="complete-work-order-button"
                            class="px-4 py-2 rounded-lg text-sm font-semibold bg-[#e4e7ec] text-[#667085] cursor-not-allowed">
                            <i class="fa-solid fa-check mr-2"></i> Mark Work Order Complete
                        </button>
                    </div>
                </form>
            </div>
        @endif

    </div>
</div>
