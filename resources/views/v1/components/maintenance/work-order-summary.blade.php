{{-- resources/views/work-orders/partials/summary.blade.php --}}
@props(['workOrder', 'withButton' => false])

{{-- ##Title Block --}}
<div class="border-b border-[#e4e7ec] px-6 py-4">
    <h2 class="text-lg font-semibold text-[#0f1728]">{{ $workOrder->equipmentInterval->description }}</h2>
</div>

{{-- ##Record Details --}}
<div class="p-6">
    <div class="grid grid-cols-2 gap-6 lg:grid-cols-4">

        {{-- ###Record ID --}}
        <div>
            <label class="mb-1 block text-sm font-medium text-[#667084]">Record ID</label>
            <p class="text-sm font-medium text-[#344053]">WO-{{ str_pad($workOrder->id, 6, '0', STR_PAD_LEFT) }}</p>
        </div>

        {{-- ###Status --}}
        <div>
            <label class="mb-1 block text-sm font-medium text-[#667084]">Status</label>
            <p class="rounded-full py-1 text-xs font-medium">
                {!! status_badge($workOrder->status) !!}
            </p>
        </div>

        {{-- ###Due Date --}}
        <div>
            <label class="mb-1 block text-sm font-medium text-[#667084]">Due Date</label>
            <p class="text-sm text-[#344053]">{!! work_order_due_badge($workOrder) !!}</p>
        </div>

        {{-- ###Complete Date --}}
        <div>
            <label class="mb-1 block text-sm font-medium text-[#667084]">Completed Date</label>
            <p class="text-sm text-[#344053]">{!! work_order_completed_badge($workOrder) !!}</p>
        </div>

        {{-- ###Assignee --}}
        <div class="relative inline-block text-left" id="assignee-dropdown">
            <label for="assigned_to" class="mb-1 block text-sm font-medium text-[#344053]">Assigned To</label>

            {{-- ####Trigger --}}
            <button onclick="toggleAssigneeDropdown()"
                class="flex items-center gap-2 rounded-lg border border-[#e4e7ec] bg-white px-4 py-2 text-sm font-medium text-[#344053] shadow-sm hover:shadow-md">
                @if ($assignedUser)
                    <img src="{{ $assignedUser->profile_pic ? Storage::url($assignedUser->profile_pic) : asset('images/placeholders/user.png') }}"
                        class="h-6 w-6 rounded-full" alt="Avatar">
                    {{ $assignedUser->first_name }} {{ $assignedUser->last_name }}
                @else
                    <img src="{{ asset('images/placeholders/user.png') }}" class="h-6 w-6 rounded-full" alt="Avatar">
                    Unassigned
                @endif

                <i class="fa-solid fa-chevron-down ml-1 text-xs"></i>
            </button>

            {{-- ####Dropdown --}}
            <div id="assignee-options"
                class="absolute z-10 mt-2 hidden w-56 rounded-lg border border-[#e4e7ec] bg-white shadow-lg">
                <ul class="py-2">
                    @foreach ($users->sortBy('first_name') as $user)
                        <li>
                            <button onclick="assignUser({{ $workOrder->id }}, {{ $user->id }})"
                                class="flex w-full items-center px-4 py-2 text-sm text-[#344053] hover:bg-[#f9fafb]">
                                <img src="{{ $user->profile_pic ? Storage::url($user->profile_pic) : asset('images/placeholders/user.png') }}"
                                    class="mr-3 h-6 w-6 rounded-full" alt="Avatar">
                                {{ $user->first_name }} {{ $user->last_name }}
                            </button>
                        </li>
                    @endforeach
                    <li>
                        <button onclick="assignUser({{ $workOrder->id }}, null)"
                            class="flex w-full items-center px-4 py-2 text-sm text-[#b42318] hover:bg-[#fef3f2]">
                            <i class="fa-solid fa-xmark mr-3"></i> Clear Assignee
                        </button>
                    </li>
                </ul>
            </div>
        </div>

        {{-- ###Equipment Reference --}}
        <div>
            <label class="mb-1 block text-sm font-medium text-[#667084]">Equipment</label>
            <p class="text-sm text-[#344053]">{{ $workOrder->equipmentInterval->equipment->name ?? '—' }}</p>
        </div>

        {{-- ###Location --}}
        <div>
            <label class="mb-1 block text-sm font-medium text-[#667084]">Location</label>
            <p class="text-sm text-[#344053]">{{ $workOrder->equipmentInterval->equipment->location->name ?? '—' }}</p>
        </div>

        {{-- ###Interval --}}
        <div>
            <label class="mb-1 block text-sm font-medium text-[#667084]">Interval</label>
            <p class="text-sm"><span
                    class="{{ frequency_label_class($workOrder->equipmentInterval->frequency) }} rounded-full px-2 py-1 text-xs font-medium uppercase">{{ $workOrder->equipmentInterval->frequency }}</span>
            </p>
        </div>
    </div>
</div>

<div id="tasks-section" class="mb-6 p-6">
    <div class="rounded-lg border border-[#e4e7ec] bg-white shadow-sm">
        {{-- ## COMPLETED --}}
        @if ($workOrder->status === 'completed')
            <div class="border-b border-[#e4e7ec] bg-[#f8f9fb]">
                <h2 class="px-6 py-2 text-lg font-semibold text-[#0f1728]">Inspection Summary</h2>
            </div>
            <div class="space-y-4 p-6 text-sm text-[#475466]">
                <p>
                    This work order has been marked
                    <span class="font-bold uppercase">{!! status_badge($workOrder->status) !!}</span>
                </p>
                <p class="italic">A detailed summary of completed tasks will appear here.</p>

                @if ($workOrder->notes)
                    <div class="mt-4">
                        <h3 class="mb-1 text-sm font-semibold text-[#344053]">Comments / Observations</h3>
                        <div
                            class="rounded-lg border border-[#d6bbfb] bg-[#f9f5ff] px-4 py-3 text-sm leading-relaxed text-[#6941c6]">
                            {{ $workOrder->notes }}
                        </div>
                    </div>
                @endif

                @if ($workOrder->deficiency)
                    <div class="mt-6 rounded-lg border border-[#d4bf6e] bg-[#fef3c7] p-4">
                        <h3 class="mb-2 text-sm font-semibold text-[#92400e]">
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
                        <h3 class="mb-2 text-sm font-semibold text-[#344053]">Task Completion Log</h3>
                        <ul class="space-y-3">
                            @foreach ($workOrder->tasks as $task)
                                <li class="rounded-md border border-[#e4e7ec] bg-[#f9fafb] p-3">
                                    <div class="flex items-start justify-between gap-4">
                                        <div class="flex-1">
                                            <div class="mb-3 flex cursor-pointer items-center gap-2"
                                                onclick="toggleInstructions({{ $task->id }})">
                                                <p class="text-base font-semibold text-[#344053]">{{ $task->name }}
                                                </p>
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
                                                <span class="text-[#6941c6]">{{ $task->completed_at ?: '—' }}</span>
                                            </span>
                                            @if ($task->completedBy)
                                                <div class="flex items-center gap-2 text-xs">
                                                    <strong>By:</strong>
                                                    <img src="{{ $task->completedBy->profile_pic ? Storage::url($task->completedBy->profile_pic) : asset('images/placeholders/user.png') }}"
                                                        alt="Avatar" class="h-5 w-5 rounded-full">
                                                    <span class="text-[#6941c6]">
                                                        {{ $task->completedBy->first_name }}
                                                        {{ $task->completedBy->last_name }}
                                                    </span>
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
            </div>

            {{-- ## SCHEDULED --}}
        @elseif ($workOrder->status === 'scheduled')
            <div class="relative">
                <div class="pointer-events-none opacity-50">
                    <div class="border-b border-[#e4e7ec] bg-[#f8f9fb]">
                        <div class="flex items-center gap-2 px-6 py-2 text-lg font-semibold">
                            <i class="fa-solid fa-clipboard-list text-[#6840c6]"></i>
                            <h2 class="text-[#0f1728]">Procedure</h2>
                        </div>
                    </div>
                    <div class="p-6">
                        <div class="space-y-4">
                            @forelse ($workOrder->tasks->sortBy('sequence_position') as $task)
                                <div class="rounded-lg border border-[#e4e7ec] p-4">
                                    <div class="mb-3 flex items-start justify-between">
                                        <div class="flex-1">
                                            <h3 class="mb-2 text-sm font-medium text-[#0f1728]">{{ $task->name }}
                                            </h3>
                                            <p class="mb-3 text-sm text-[#667084]">{{ $task->instructions }}</p>
                                            @if ($task->completedBy)
                                                <div class="flex items-center gap-2">
                                                    <i class="fa-solid fa-user text-sm text-[#667084]"></i>
                                                    <span class="text-sm text-[#667084]">
                                                        {{ $task->completedBy->first_name }}
                                                        {{ $task->completedBy->last_name }}
                                                    </span>
                                                </div>
                                            @endif
                                        </div>
                                        <div class="ml-4 flex gap-2">
                                            <button
                                                class="cursor-not-allowed rounded-lg bg-[#ebfdf2] px-3 py-1.5 text-sm font-medium text-[#027947]"
                                                disabled>
                                                <i class="fa-solid fa-check mr-1"></i> Complete
                                            </button>
                                            <button
                                                class="cursor-not-allowed rounded-lg bg-[#fef3f2] px-3 py-1.5 text-sm font-medium text-[#b42318]"
                                                disabled>
                                                <i class="fa-solid fa-flag mr-1"></i> Flag
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            @empty
                                <p class="text-sm italic text-[#667084]">No tasks assigned to this work order.</p>
                            @endforelse
                        </div>
                    </div>
                </div>
                <div class="absolute inset-0 z-10 flex items-center justify-center">
                    <div
                        class="w-full max-w-lg space-y-4 rounded-lg border border-[#e4e7ec] bg-white p-6 text-center shadow-xl">
                        <p class="text-lg font-semibold text-[#0f1728]">
                            This work order is scheduled for a future date.
                        </p>
                        <button onclick="openScheduledWorkOrder({{ $workOrder->id }})"
                            class="inline-flex items-center gap-2 rounded-lg bg-[#6840c6] px-4 py-2 text-sm font-medium text-white hover:bg-[#5a35a8]">
                            <i class="fa-solid fa-play"></i> Open Now?
                        </button>
                    </div>
                </div>
            </div>

            {{-- ## OPEN --}}
        @else
            <div class="">
                <div class="flex items-center gap-2 px-6 pb-2 pt-4 text-lg font-semibold">
                    <i class="fa-solid fa-clipboard-list text-[#6840c6]"></i>
                    <h2 class="text-[#0f1728]">Procedure</h2>
                </div>
            </div>
            <div class="px-6 pb-6 pt-2">
                <div class="space-y-4">
                    @forelse ($workOrder->tasks->sortBy('sequence_position') as $task)
                        <div class="rounded-lg border border-[#e4e7ec] p-4">
                            <div class="mb-3 flex items-start justify-between">
                                <div class="flex-1">
                                    <h3 class="mb-2 text-sm font-medium text-[#0f1728]">{{ $task->name }}</h3>
                                    <p class="mb-3 text-sm text-[#667084]">{{ $task->instructions }}</p>
                                    @if ($task->completedBy)
                                        <div class="flex items-center gap-2">
                                            <i class="fa-solid fa-user text-sm text-[#667084]"></i>
                                            <span class="text-sm text-[#667084]">
                                                {{ $task->completedBy->first_name }}
                                                {{ $task->completedBy->last_name }}
                                            </span>
                                        </div>
                                    @endif
                                </div>
                                <div class="ml-4 flex gap-2">
                                    <button
                                        class="{{ $task->status === 'completed'
                                            ? 'bg-[#6840c6] text-white ring-2 ring-offset-1 ring-[#6840c6]'
                                            : 'bg-[#ebfdf2] text-[#027947] hover:bg-[#d0fadf]' }} rounded-lg px-3 py-1.5 text-sm font-medium"
                                        data-task-id="{{ $task->id }}" data-status="completed"
                                        onclick="updateTaskStatus(this)"
                                        {{ $task->status === 'completed' ? 'disabled' : '' }}>
                                        <i class="fa-solid fa-check mr-1"></i> Complete
                                    </button>
                                    <button
                                        class="{{ $task->is_flagged ? 'bg-[#b42318] text-white ring-2 ring-offset-1 ring-[#b42318]' : 'bg-[#fef3f2] text-[#b42318] hover:bg-[#fee4e2]' }} rounded-lg px-3 py-1.5 text-sm font-medium"
                                        data-task-id="{{ $task->id }}" data-status="flagged"
                                        onclick="updateTaskStatus(this)" {{ $task->is_flagged ? 'disabled' : '' }}>
                                        <i class="fa-solid fa-flag mr-1"></i> Flag
                                    </button>
                                </div>
                            </div>
                        </div>
                    @empty
                        <p class="text-sm italic text-[#667084]">No tasks assigned to this work order.</p>
                    @endforelse
                </div>
            </div>
        @endif

        {{-- ## Mark Work Order Complete form (only on OPEN + withButton) --}}
        @if ($withButton && in_array($workOrder->status, ['open', 'in_progress', 'flagged']) && $workOrder->tasks->count())
            <div class="m-6 border-t border-[#e4e7ec] pt-4">
                <form id="complete-form" action="{{ route('work-orders.complete', $workOrder) }}" method="POST">
                    @csrf
                    <label class="mb-2 block text-lg font-bold text-[#344053]">Comments / Observations</label>
                    <textarea name="notes"
                        class="w-full resize-none rounded-lg border border-[#cfd4dc] bg-white px-3.5 py-2.5 text-base leading-normal text-[#0f1728] shadow focus:outline-none focus:ring-2 focus:ring-[#6840c6]"
                        rows="4" placeholder="Enter any additional notes or observations about this maintenance record...">{{ old('notes', $workOrder->notes) }}</textarea>
                    <div class="mt-6 text-right">
                        <button type="submit" id="complete-work-order-button"
                            class="cursor-not-allowed rounded-lg bg-[#e4e7ec] px-4 py-2 text-sm font-semibold text-[#667085]">
                            <i class="fa-solid fa-check mr-2"></i> Mark Work Order Complete
                        </button>
                    </div>
                </form>
            </div>
        @endif

    </div>
</div>
