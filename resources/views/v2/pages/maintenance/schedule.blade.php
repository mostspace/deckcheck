@extends('v2.layouts.app')

@section('title', 'Maintenance Schedule')

@section('content')

    @php
        use Carbon\CarbonInterval;

        $durationMap = [
            'daily' => '1 day',
            'weekly' => '1 week',
            'bi-weekly' => '2 weeks',
            'monthly' => '1 month',
            'quarterly' => '3 months',
            'bi-annually' => '6 months',
            'annual' => '1 year',
            '2-yearly' => '1 year',
            '3-yearly' => '1 year',
            '5-yearly' => '1 year',
            '6-yearly' => '1 year',
            '10-yearly' => '1 year',
            '12-yearly' => '1 year'
        ];

        $step = CarbonInterval::createFromDateString($durationMap[$frequency] ?? '1 day');
        $prevDate = $date->copy()->sub($step);
        $nextDate = $date->copy()->add($step);

        $formattedRange = match ($frequency) {
            'daily' => $date->format('F j, Y'),
            'weekly', 'bi-weekly' => $date->copy()->startOfWeek()->format('M j') .
                ' – ' .
                $date->copy()->endOfWeek()->format('M j, Y'),
            'monthly', 'quarterly', 'bi-annually' => $date->format('F Y'),
            default => $date->format('Y')
        };
    @endphp

    @php
        $navParams = ['frequency' => $frequency, 'assigned' => request('assigned'), 'group' => $group];
    @endphp

    {{-- Enhanced Maintenance Header --}}
    @include('v2.components.nav.header-routing', [
        'activeTab' => 'workflow',
        'breadcrumbs' => [
            ['label' => 'Maintenance', 'icon' => asset('assets/media/icons/sidebar-solid-wrench-scredriver.svg')],
            ['label' => 'Workflow']
        ]
    ])

    <div class="px-3 py-4 sm:px-6 sm:py-6 lg:px-8">
        {{-- System Messages --}}
        @if (session('success'))
            <div class="mb-6 rounded-lg border border-green-300 bg-green-100 p-4 text-sm text-green-800">
                {{ session('success') }}
            </div>
        @endif

        {{-- Header --}}
        <div class="mb-6">
            <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
                <div>
                    <h1 class="text-2xl font-semibold text-[#0f1728]">Schedule</h1>
                    <p class="text-[#475466]">List View of All Open Interval Maintenance</p>
                </div>
            </div>
        </div>

        {{-- Conditional Display for New Account / No Intervals Yet --}}
        @if (empty($visibleFrequencies))
            <div
                class="flex flex-col items-center justify-center space-y-4 rounded-lg border border-[#e4e7ec] p-12 text-center text-sm text-[#475466]">
                <i class="fa-regular fa-calendar-xmark text-4xl text-[#d0d5dd]"></i>
                <p class="text-base font-medium text-[#344053]">You haven't scheduled any maintenance intervals yet</p>
                <p class="max-w-xs text-xs text-[#667084]">
                    Once you create your first maintenance interval, work orders will begin generating automatically based
                    on your vessel schedule.
                </p>
                <a href="#"
                    class="mt-2 inline-flex items-center gap-2 rounded-lg bg-primary-500 px-4 py-2 text-sm font-medium text-slate-800 transition hover:bg-primary-600">
                    <i class="fa-solid fa-calendar-plus"></i> Create Interval
                </a>
            </div>
        @else
            {{-- Include the workflow content --}}
            @include('v2.sections.crew.maintenance.workflow.index')
        @endif
    </div>

    {{-- Flow Modal Structure --}}
    <div id="flow-slideout-wrapper" class="pointer-events-none fixed inset-0 z-50">
        <div id="flow-slideout-overlay" class="fixed inset-0 hidden bg-black bg-opacity-50"></div>
        <div id="flow-slideout-panel"
            class="fixed right-0 top-0 h-full w-full max-w-2xl translate-x-full transform bg-white shadow-xl transition-transform duration-300 ease-in-out">
            {{-- Modal content will be loaded here via JavaScript --}}
        </div>
    </div>

    {{-- Include all the JavaScript from the original workflow --}}
    @push('scripts')
        <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
        <script>
            let workOrderIds = [];
            let currentIndex = 0;

            function updateFlowProgressBar() {
                const totalSteps = workOrderIds.length;
                const currentStep = currentIndex + 1;

                // Update step number
                const stepNumEl = document.getElementById('flow-step-num');
                if (stepNumEl) {
                    stepNumEl.textContent = currentStep;
                }

                // Update progress bar width
                const progressBarEl = document.getElementById('flow-progress-bar');
                if (progressBarEl) {
                    const percentage = Math.round((currentStep / totalSteps) * 100);
                    progressBarEl.style.width = `${percentage}%`;
                }

                // Enable/disable Prev and Next buttons
                const prevButton = document.getElementById('prev-button');
                const nextButton = document.getElementById('next-button');
                const completeButton = document.getElementById('complete-work-order-button');

                const atFirst = currentIndex === 0;
                const atLast = currentIndex === totalSteps - 1;

                if (prevButton) {
                    prevButton.disabled = atFirst;
                    prevButton.classList.toggle('opacity-50', atFirst);
                    prevButton.classList.toggle('cursor-not-allowed', atFirst);
                }

                if (nextButton) {
                    nextButton.disabled = atLast;
                    nextButton.classList.toggle('opacity-50', atLast);
                    nextButton.classList.toggle('cursor-not-allowed', atLast);
                }

                // Update Complete button label and behavior
                if (completeButton) {
                    completeButton.textContent = atLast ? 'Complete & Close' : 'Complete & Next';
                    completeButton.setAttribute('data-action', atLast ? 'close' : 'next');
                }
            }

            function launchFlow(selectedIds, groupName) {
                const params = new URLSearchParams({
                    frequency: '{{ $frequency ?? 'daily' }}',
                    date: '{{ $date->toDateString() ?? now()->toDateString() }}',
                    group: '{{ $group ?? 'date' }}',
                    groupName: groupName,
                    dateRangeLabel: '{{ $formattedRange ?? now()->format('F j, Y') }}',
                    @if (request('assigned'))
                        assigned: '1',
                    @endif
                });

                selectedIds.forEach(id => params.append('ids[]', id));

                fetch(`/maintenance/schedule/flow?${params.toString()}`)
                    .then(res => res.text())
                    .then(html => {
                        const panel = document.getElementById('flow-slideout-panel');
                        const overlay = document.getElementById('flow-slideout-overlay');
                        const wrapper = document.getElementById('flow-slideout-wrapper');

                        panel.innerHTML = html;

                        // Pull workOrderIds and currentIndex from modal markup
                        const idContainer = panel.querySelector('#flow-work-order-ids');
                        const currentIdEl = panel.querySelector('#flow-current-id');
                        if (idContainer && currentIdEl) {
                            workOrderIds = JSON.parse(idContainer.textContent);
                            currentIndex = workOrderIds.indexOf(parseInt(currentIdEl.textContent));
                            updateFlowProgressBar();
                        }

                        // Slide in
                        panel.classList.remove('translate-x-full');
                        overlay.classList.remove('hidden');
                        overlay.classList.add('opacity-100');
                        wrapper.classList.remove('pointer-events-none');

                        bindCompletionForm();
                        checkWorkOrderCompletable();
                    })
                    .catch(err => {
                        console.error('Failed to load flow modal:', err);
                        alert('Something went wrong loading the flow interface.');
                    });
            }

            function closeFlowModal() {
                const panel = document.getElementById('flow-slideout-panel');
                const overlay = document.getElementById('flow-slideout-overlay');
                const wrapper = document.getElementById('flow-slideout-wrapper');

                panel.classList.add('translate-x-full');
                overlay.classList.add('hidden');
                wrapper.classList.add('pointer-events-none');

                setTimeout(() => {
                    panel.innerHTML = '';
                }, 300);
            }

            function loadWorkOrderAt(index) {
                if (index < 0 || index >= workOrderIds.length) return;

                const workOrderId = workOrderIds[index];
                fetch(`/maintenance/schedule/flow/load/${workOrderId}`)
                    .then(res => res.text())
                    .then(html => {
                        currentIndex = index;
                        const container = document.getElementById('work-order-container');

                        container.classList.add('opacity-0', 'transition-opacity', 'duration-200');

                        setTimeout(() => {
                            container.innerHTML = html;
                            const newStatus = container.querySelector('[data-status]')?.getAttribute('data-status');
                            if (newStatus) {
                                container.setAttribute('data-status', newStatus);
                            }
                            bindCompletionForm();
                            checkWorkOrderCompletable();
                            updateFlowProgressBar();

                            // Fade in after small delay
                            setTimeout(() => {
                                container.classList.remove('opacity-0');
                            }, 10);
                        }, 200);

                        updateFlowProgressBar();
                        bindCompletionForm();
                        checkWorkOrderCompletable();
                    });
            }

            function loadNextWorkOrder() {
                loadWorkOrderAt(currentIndex + 1);
                updateFlowProgressBar();
            }

            function loadPrevWorkOrder() {
                loadWorkOrderAt(currentIndex - 1);
                updateFlowProgressBar();
            }

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
                    .then(res => res.json())
                    .then(data => {
                        if (data.success) {
                            const container = button.closest('.flex');
                            const completeBtn = container.querySelector('button[data-status="completed"]');
                            const flagBtn = container.querySelector('button[data-status="flagged"]');

                            completeBtn.className =
                                'px-3 py-1.5 text-sm font-medium rounded-lg bg-[#ebfdf2] text-[#027947] hover:bg-[#d0fadf]';
                            flagBtn.className =
                                'px-3 py-1.5 text-sm font-medium rounded-lg bg-[#fef3f2] text-[#b42318] hover:bg-[#fee4e2]';
                            completeBtn.disabled = false;
                            flagBtn.disabled = false;

                            if (status === 'completed') {
                                completeBtn.classList.add('ring-2', 'ring-offset-1', 'ring-[#6840c6]', 'bg-[#6840c6]',
                                    'text-white');
                                completeBtn.classList.remove('text-[#027947]', 'hover:bg-[#d0fadf]', 'bg-[#ebfdf2]');
                                completeBtn.disabled = true;
                            } else if (status === 'flagged') {
                                flagBtn.classList.add('ring-2', 'ring-offset-1', 'ring-[#b42318]', 'bg-[#b42318]',
                                    'text-white');
                                flagBtn.classList.remove('text-[#b42318]', 'hover:bg-[#fee4e2]', 'bg-[#fef3f2]');
                                flagBtn.disabled = true;
                            }

                            checkWorkOrderCompletable();
                        } else {
                            alert('Failed to update task.');
                        }
                    })
                    .catch(error => {
                        console.error(error);
                        alert('There was a problem updating the task.');
                    });
            }

            function checkWorkOrderCompletable() {
                const taskButtons = document.querySelectorAll('[data-task-id]');
                const completeButton = document.getElementById('complete-work-order-button');
                const container = document.getElementById('work-order-container');

                if (!completeButton || !container) return;

                const statusEl = container.querySelector('[data-status]');
                const status = statusEl?.getAttribute('data-status') || '';

                const allResolved = Array.from(taskButtons).every(btn => {
                    const row = btn.closest('.flex');
                    return row.querySelector('button[disabled]');
                });

                const shouldEnable = allResolved && status !== 'scheduled';

                if (shouldEnable) {
                    completeButton.disabled = false;
                    completeButton.classList.remove('bg-[#e4e7ec]', 'text-[#667085]', 'cursor-not-allowed');
                    completeButton.classList.add('bg-[#6840c6]', 'text-white', 'hover:bg-[#5a35a8]', 'cursor-pointer');
                } else {
                    completeButton.disabled = true;
                    completeButton.classList.add('bg-[#e4e7ec]', 'text-[#667085]', 'cursor-not-allowed');
                    completeButton.classList.remove('bg-[#6840c6]', 'text-white', 'hover:bg-[#5a35a8]', 'cursor-pointer');
                }
            }

            function toggleAssigneeDropdown(id = null) {
                const dropdown = id ?
                    document.getElementById(`assignee-options-${id}`) :
                    document.getElementById('assignee-options');

                if (dropdown) dropdown.classList.toggle('hidden');
            }

            function toggleIndexAssigneeDropdown(id = null) {
                document.querySelectorAll('[id^="assignee-options-"]').forEach(el => {
                    if (el.id !== `assignee-options-${id}`) {
                        el.classList.add('hidden');
                    }
                });

                const dropdown = document.getElementById(`assignee-options-${id}`);
                if (dropdown) dropdown.classList.toggle('hidden');
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
                    .then(data => {
                        if (!data.success) throw new Error('Assignment failed');

                        const dropdown = document.getElementById(`assignee-dropdown-${workOrderId}`);
                        const options = document.getElementById(`assignee-options-${workOrderId}`);
                        if (!dropdown || !options) return;

                        const button = dropdown.querySelector('button');

                        let rawPath = data.user?.avatar_url || '';
                        let avatar;

                        // If it's already a full URL (starts with http), use as-is
                        if (/^https?:\/\//.test(rawPath)) {
                            avatar = rawPath;
                        }
                        // If it's a relative path, assume it's a file from storage/app/public
                        else if (rawPath) {
                            avatar = `/storage/${rawPath}`;
                        } else {
                            avatar = '{{ Storage::url('profile_pictures/placeholder.svg.hi.png') }}';
                        }

                        const name = data.user?.first_name || 'Unassigned';

                        button.innerHTML = `
                <img src="${avatar}" class="w-5 h-5 rounded-full" alt="Avatar"
                    onerror="this.onerror=null;this.src='{{ Storage::url('profile_pictures/placeholder.svg.hi.png') }}'">
                ${name}
                <i class="fa-solid fa-chevron-down ml-1 text-xs"></i>
            `;

                        options.classList.add('hidden');

                        // Refresh modal if open
                        const flowPanel = document.getElementById('flow-slideout-panel');
                        if (flowPanel && !flowPanel.classList.contains('translate-x-full')) {
                            loadWorkOrderAt(currentIndex);
                        }
                    })
                    .catch(error => {
                        console.error('Error assigning user:', error);
                        alert('Failed to assign user.');
                    });
            }

            function bindCompletionForm() {
                const completeForm = document.getElementById('complete-form');
                if (!completeForm) return;

                completeForm.onsubmit = function(e) {
                    e.preventDefault();

                    const notes = completeForm.querySelector('textarea[name="notes"]');
                    const taskButtons = document.querySelectorAll('[data-task-id]');
                    const hasFlaggedTasks = Array.from(taskButtons).some(btn => {
                        const container = btn.closest('.flex');
                        const flaggedBtn = container.querySelector('button[data-status="flagged"]');
                        return flaggedBtn && flaggedBtn.disabled;
                    });

                    // Clear any previous error state
                    if (notes) {
                        notes.classList.remove('border-red-500');
                        const error = completeForm.querySelector('#notes-error');
                        if (error) error.remove();
                    }

                    // Require notes if flagged tasks exist
                    if (hasFlaggedTasks && notes && notes.value.trim() === '') {
                        notes.classList.add('border-red-500');

                        // Insert inline error
                        if (!document.getElementById('notes-error')) {
                            const errorEl = document.createElement('p');
                            errorEl.id = 'notes-error';
                            errorEl.className = 'text-red-600 text-sm mt-1';
                            errorEl.textContent = 'Notes are required when completing a work order with flagged tasks.';
                            notes.parentNode.appendChild(errorEl);
                        }

                        return;
                    }

                    // Submit if valid
                    const formData = new FormData(completeForm);

                    fetch(completeForm.action, {
                            method: 'POST',
                            headers: {
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute(
                                    'content'),
                                'Accept': 'application/json'
                            },
                            body: formData
                        })
                        .then(response => response.json())
                        .then(data => {
                            if (data.status === 'success') {
                                const completeButton = document.getElementById('complete-work-order-button');
                                const action = completeButton?.getAttribute('data-action');

                                if (action === 'close') {
                                    closeFlowModal();

                                    // Refresh the index view while preserving query string
                                    const currentUrl = new URL(window.location.href);
                                    window.location.href = currentUrl.pathname + currentUrl.search;
                                } else {
                                    loadNextWorkOrder();
                                }
                            } else {
                                alert('There was an error completing this work order.');
                            }
                        })
                        .catch(error => {
                            console.error(error);
                            alert('There was a problem submitting the completion form.');
                        });
                };
            }

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
                            // If modal is open, refresh current work order
                            const flowPanel = document.getElementById('flow-slideout-panel');
                            if (flowPanel && !flowPanel.classList.contains('translate-x-full')) {
                                loadWorkOrderAt(currentIndex);
                            } else {
                                // Fallback to full reload
                                window.location.reload();
                            }
                        } else {
                            alert('Failed to open work order.');
                        }
                    })
                    .catch(error => {
                        console.error(error);
                        alert('There was a problem updating the work order.');
                    });
            }

            // For initial render - preloaded modal
            document.addEventListener('DOMContentLoaded', () => {
                checkWorkOrderCompletable();
                bindCompletionForm();
            });
        </script>
    @endpush

@endsection
