@extends('layouts.app')

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
            '12-yearly' => '1 year',
        ];

        $step = CarbonInterval::createFromDateString($durationMap[$frequency] ?? '1 day');
        $prevDate = $date->copy()->sub($step);
        $nextDate = $date->copy()->add($step);

        $formattedRange = match ($frequency) {
            'daily' => $date->format('F j, Y'),
            'weekly', 'bi-weekly' => $date->copy()->startOfWeek()->format('M j') . ' – ' . $date->copy()->endOfWeek()->format('M j, Y'),
            'monthly', 'quarterly', 'bi-annually' => $date->format('F Y'),
            default => $date->format('Y'),
        };
    @endphp

    @php
        $navParams = ['frequency' => $frequency, 'assigned' => request('assigned'), 'group' => $group];
    @endphp

    {{-- System Messages --}}
    @if (session('success'))
        <div class="mb-6 p-4 bg-green-100 border border-green-300 text-green-800 rounded-lg text-sm">
            {{ session('success') }}
        </div>
    @endif

    {{-- Header --}}
    <div class="mb-6">

        {{-- #Title Block --}}
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <h1 class="text-2xl font-semibold text-[#0f1728]">Schedule</h1>
                <p class="text-[#475466]">List View of All Open Interval Maintenance</p>
            </div>
        </div>

    </div>

    {{-- Conditional Display for New Account / No Intervals Yet --}}
    @if (empty($visibleFrequencies))

        <div class="border border-[#e4e7ec] rounded-lg p-12 flex flex-col items-center justify-center text-center text-sm text-[#475466] space-y-4">
            <i class="fa-regular fa-calendar-xmark text-4xl text-[#d0d5dd]"></i>
            <p class="font-medium text-[#344053] text-base">You haven’t scheduled any maintenance intervals yet</p>
            <p class="text-xs text-[#667084] max-w-xs">
                Once you create your first maintenance interval, work orders will begin generating automatically based on your vessel schedule.
            </p>
            <a href="#"
                class="inline-flex items-center gap-2 px-4 py-2 mt-2 text-sm font-medium text-white bg-[#7e56d8] hover:bg-[#6840c6] rounded-lg transition">
                <i class="fa-solid fa-calendar-plus"></i> Create Interval
            </a>
        </div>

    {{-- Regular Content --}}
    @else

        {{-- Interval Navigation --}}
        <div class="bg-white rounded-lg border border-[#e4e7ec] mb-6">

            {{-- #Frequency & Range Control --}}
            <div class="px-6 py-4 border-b border-[#e4e7ec]">
                <div class="flex items-center justify-between">

                    {{-- ##Frequency Tabs --}}
                    <nav class="flex space-x-1">
                        @foreach ($visibleFrequencies as $freq)
                            @php
                                $tabDate = match ($freq) {
                                    'daily', 'weekly', 'bi-weekly' => now()->toDateString(),
                                    'monthly', 'quarterly', 'bi-annually' => now()->startOfMonth()->toDateString(),
                                    default => now()->startOfYear()->toDateString(),
                                };
                            @endphp

                            @php
                                $queryParams = ['frequency' => $freq, 'date' => $tabDate];
                                if (request('assigned')) {
                                    $queryParams['assigned'] = '1';
                                }
                            @endphp

                            <a href="{{ route('schedule.index', $queryParams) }}"
                                class="px-4 py-2 rounded-lg text-sm font-medium transition-colors
                              {{ $frequency === $freq ? 'bg-[#f9f5ff] text-[#6840c6]' : 'text-[#667084] hover:text-[#344053] hover:bg-[#f8f9fb]' }}">
                                {{ ucfirst($freq) }}
                            </a>
                        @endforeach
                    </nav>

                    {{-- ##Date Navigation --}}
                    <div class="flex items-center gap-2">
                        <a href="{{ route('schedule.index', array_merge($navParams, ['date' => $prevDate->toDateString()])) }}"
                            class="p-2 text-[#667084] hover:text-[#344053] hover:bg-[#f8f9fb] rounded-lg transition-colors">
                            <i class="fa-solid fa-chevron-left"></i>
                        </a>

                        <div
                            class="px-3 py-2 bg-white rounded-lg shadow-[0px_1px_2px_0px_rgba(16,24,40,0.05)] border border-[#cfd4dc] text-[#6840c6] text-sm">
                            <i class="fa-regular fa-calendar-days text-gray-400 pr-2"></i>
                            {{ $formattedRange }}
                        </div>

                        <a href="{{ route('schedule.index', array_merge($navParams, ['date' => $nextDate->toDateString()])) }}"
                            class="p-2 text-[#667084] hover:text-[#344053] hover:bg-[#f8f9fb] rounded-lg transition-colors">
                            <i class="fa-solid fa-chevron-right"></i>
                        </a>
                    </div>

                </div>
            </div>

            {{-- #Filters and Controls (static for now) --}}
            <div class="px-6 py-4">
                <div class="flex flex-col lg:flex-row gap-4 items-start lg:items-center justify-between">

                    {{-- ##Group By Buttons --}}
                    <div class="flex items-center gap-3">
                        <span class="text-sm font-medium text-[#344053]">Group By:</span>
                        @php
                            $group = request('group', 'date');
                        @endphp

                        @foreach (['date' => 'None', 'category' => 'Category', 'location' => 'Location'] as $key => $label)
                            <a href="{{ route('schedule.index', array_merge(request()->query(), ['group' => $key])) }}"
                                class="px-4 py-2 rounded-md border text-sm font-medium
                        {{ $group === $key ? 'text-[#6840c6] border-[#6840c6] bg-[#f9f5ff]' : 'text-[#344053] border-[#d0d5dd] hover:bg-[#f8f9fb]' }}">
                                {{ $label }}
                            </a>
                        @endforeach
                    </div>

                    {{-- ##Assigned to Me Toggle --}}
                    <form method="GET" class="ml-auto">
                        {{-- Preserve frequency/date/group --}}
                        <input type="hidden" name="frequency" value="{{ $frequency }}">
                        <input type="hidden" name="date" value="{{ $date->toDateString() }}">
                        <input type="hidden" name="group" value="{{ $group }}">

                        <label class="flex items-center gap-2 cursor-pointer">
                            <input type="checkbox" name="assigned" onchange="this.form.submit()" class="sr-only"
                                {{ request('assigned') ? 'checked' : '' }}>
                            @php
                                $isAssignedActive = request()->boolean('assigned');
                            @endphp

                            {{-- ###Toggle w/ Active/Inactive Styles --}}
                            <div
                                class="w-10 h-6 rounded-full relative transition-colors duration-200 {{ $isAssignedActive ? 'bg-[#6840c6]' : 'bg-[#e4e7ec]' }}">
                                <div
                                    class="w-4 h-4 bg-white rounded-full absolute top-1 left-1 transition-transform duration-200 shadow-sm {{ $isAssignedActive ? 'translate-x-4' : '' }}">
                                </div>
                            </div>
                            <span class="text-sm text-[#344053]">Assigned to Me</span>
                        </label>
                    </form>

                </div>
            </div>
        </div>



        {{-- Work Orders --}}

        {{-- !!If No Work Orders to Display!! --}}
        @if ($activeWorkOrders->isEmpty() && $resolvedWorkOrders->isEmpty())

            {{-- #Empty State --}}
            <div class="border border-[#e4e7ec] rounded-lg p-8 flex flex-col items-center justify-center text-center text-sm text-[#475466] space-y-3">
                <i class="fa-regular fa-calendar-xmark text-3xl text-[#d0d5dd]"></i>
                <p class="font-medium text-[#344053]">No work orders scheduled for this interval</p>
                <p class="text-xs text-[#667084] max-w-sm">
                    You haven’t scheduled any maintenance tasks for this frequency yet.
                    Use the "Schedule Task" button to get started.
                </p>
            </div>

        @else

            <div class="bg-white shadow rounded-lg p-6 space-y-6">

                {{-- ─── DATE GROUPING (“All” tab) ─────────────────────────────────── --}}
                @if ($group === 'date')
   
                    {{-- Active Work Orders Container --}}
                    @if ($activeWorkOrders->isNotEmpty())
                        
                        {{-- #Active Column Headers --}}
                        <div
                            class="hidden lg:grid grid-cols-[1.25fr_2fr_1fr_1fr_1.5fr_40px] gap-4 px-6 py-2 text-xs font-semibold text-gray-500 uppercase">
                            <div>Status / WO</div>
                            <div>Equipment</div>
                            <div>Due Date</div>
                            <div>Tasks</div>
                            <div>Assignee</div>
                            <div></div>
                        </div>

                        {{-- #Active Work Order Rows --}}
                        @foreach ($activeWorkOrders as $wo)
                            @include('components.maintenance.schedule.schedule-row')
                        @endforeach

                    @endif

                    {{-- Resolved Work Orders Container --}}
                    @if ($resolvedWorkOrders->isNotEmpty())

                        <div class="border-t border-[#e4e7ec] pt-6 space-y-4">
                            <div class="text-sm font-semibold text-[#344053] flex items-center gap-2">
                                <i class="fa-regular fa-circle-check text-[#667084]"></i>
                                Resolved Work Orders
                            </div>

                            {{-- #Resolved Column Headers --}}
                            <div
                                class="hidden lg:grid grid-cols-[1.25fr_2fr_1fr_1fr_1.5fr_40px] gap-4 px-6 py-2 text-xs font-semibold text-gray-500 uppercase">
                                <div>Status / WO</div>
                                <div>Equipment</div>
                                <div>Completed Date</div>
                                <div>Summary</div>
                                <div>Completed By</div>
                                <div></div>
                            </div>

                            {{-- #Resolved Work Order Rows --}}
                            @foreach ($resolvedWorkOrders as $wo)
                                @include('components.maintenance.schedule.resolved-schedule-row')
                            @endforeach
                            
                        </div>
                    @endif

                {{-- ─── CATEGORY GROUPING ─────────────────────────────────────────── --}}
                @elseif ($group === 'category')

                    @foreach ($groups as $category => $data)

                        @php
                            $openCount = $data['active']->count();
                            $resolvedCount = $data['resolved']->count();
                            $ids = $data['active']->pluck('id')->toArray();
                        @endphp

                        {{-- #Outer Container --}}
                        <div class="bg-white shadow rounded-lg mb-6">
                            
                            {{-- ##Header --}}
                            <div class="px-6 py-4 flex items-center justify-between border-b">
                                <h3 class="text-lg font-semibold flex items-center gap-2">
                                    <i class="fa-solid fa-box text-gray-600"></i>
                                    {{ $category }}
                                    
                                    {{-- ###Group Summary --}}
                                    <span class="text-sm text-gray-500">
                                        ({{ $openCount }} open{{ $resolvedCount ? ", {$resolvedCount} resolved" : '' }})
                                    </span>
                                </h3>

                                {{-- ###Launch Flow --}}
                                <button onclick="launchFlow({{ Js::from($ids) }}, {{ Js::from($category) }})"
                                    class="text-sm text-purple-600 hover:underline">
                                    Start Flow
                                </button>
                            </div>

                            {{-- ##Body --}}
                            <div class="px-6 py-4 space-y-4">
                                
                                {{-- Active Work Orders Container --}}
                                @if ($openCount)

                                    {{-- ###Active Column Headers --}}
                                    <div
                                        class="hidden lg:grid grid-cols-[1.25fr_2fr_1fr_1fr_1.5fr_40px] gap-4 text-xs font-semibold text-gray-500 uppercase">
                                        <div>Status / WO</div>
                                        <div>Equipment</div>
                                        <div>Due Date</div>
                                        <div>Tasks</div>
                                        <div>Assignee</div>
                                        <div></div>
                                    </div>

                                    {{-- ###Active Work Order Rows --}}
                                    @foreach ($data['active'] as $wo)
                                        @include('components.maintenance.schedule.schedule-row')
                                    @endforeach

                                @endif

                                {{-- Resolved Work Orders Container --}}
                                @if ($resolvedCount)

                                    <details class="border-t pt-4">
                                        <summary class="cursor-pointer text-sm font-medium text-gray-700 flex items-center gap-2">
                                            <i class="fa-regular fa-circle-check text-green-600"></i>
                                            Resolved ({{ $resolvedCount }})
                                        </summary>
                                        <div class="mt-4">
                                            
                                            {{-- ###Resolved Column Headers --}}
                                            <div
                                                class="hidden lg:grid grid-cols-[1.25fr_2fr_1fr_1fr_1.5fr_40px] gap-4 text-xs font-semibold text-gray-500 uppercase">
                                                <div>Status / WO</div>
                                                <div>Equipment</div>
                                                <div>Completed Date</div>
                                                <div>Summary</div>
                                                <div>Completed By</div>
                                                <div></div>
                                            </div>

                                            {{-- ###Resolved Work Order Rows --}}
                                            @foreach ($data['resolved'] as $wo)
                                                @include('components.maintenance.schedule.resolved-schedule-row')
                                            @endforeach

                                        </div>
                                    </details>

                                @endif

                            </div>
                        </div>

                    @endforeach

                {{-- ─── LOCATION GROUPING ─────────────────────────────────────────── --}}
                @elseif ($group === 'location')

                    @foreach ($groups as $deck => $locs)

                        {{-- #Outer Container --}}
                        <div class="bg-white shadow rounded-lg mb-6">
                            
                            {{-- ##Deck Header --}}
                            <div class="px-6 py-4 border-b flex items-center gap-2 text-lg font-semibold text-gray-800">
                                <i class="fa-solid fa-layer-group"></i>
                                {{ $deck }}
                            </div>

                            {{-- ##Location Containers --}}
                            <div class="divide-y">
                                
                                @foreach ($locs as $locationName => $data)

                                    @php
                                        $openCount = $data['active']->count();
                                        $resolvedCount = $data['resolved']->count();
                                        $ids = $data['active']->pluck('id')->toArray();
                                    @endphp

                                    <details @if ($loop->first) open @endif class="group">
                                        
                                        {{-- ###Location Summary --}}
                                        <summary class="px-6 py-3 flex items-center justify-between cursor-pointer group-hover:bg-gray-50">
                                            <div class="flex items-center gap-2">
                                                <i class="fa-solid fa-location-dot text-gray-600"></i>
                                                <span class="font-medium">{{ $locationName }}</span>
                                                <span class="text-sm text-gray-500">
                                                    ({{ $openCount }} open{{ $resolvedCount ? ", {$resolvedCount} resolved" : '' }})
                                                </span>
                                            </div>

                                            {{-- ####Launch Flow --}}
                                            <button onclick="launchFlow({{ Js::from($ids) }}, {{ Js::from($locationName) }})"
                                                class="text-sm text-purple-600 hover:underline">
                                                Start Flow
                                            </button>
                                        </summary>

                                        {{-- ###Body --}}
                                        <div class="px-6 py-4 space-y-4 bg-gray-50">

                                            {{-- Active Work Orders Container --}}
                                            @if ($openCount)

                                                {{-- ####Active Column Headers --}}
                                                <div
                                                    class="hidden lg:grid grid-cols-[1.25fr_2fr_1fr_1fr_1.5fr_40px] gap-4 text-xs font-semibold text-gray-500 uppercase">
                                                    <div>Status / WO</div>
                                                    <div>Equipment</div>
                                                    <div>Due Date</div>
                                                    <div>Tasks</div>
                                                    <div>Assignee</div>
                                                    <div></div>
                                                </div>

                                                {{-- ####Active Work Order Rows --}}
                                                @foreach ($data['active'] as $wo)
                                                    @include('components.maintenance.schedule.schedule-row')
                                                @endforeach

                                            @endif

                                            {{-- Resolved Work Orders Container --}}
                                            @if ($resolvedCount)

                                                <div class="border-t pt-4">
                                                    <div class="flex items-center gap-2 text-sm font-medium text-gray-700 mb-2">
                                                        <i class="fa-regular fa-circle-check text-green-600"></i>
                                                        Resolved ({{ $resolvedCount }})
                                                    </div>

                                                    {{-- ####Resolved Column Headers --}}
                                                    <div
                                                        class="hidden lg:grid grid-cols-[1.25fr_2fr_1fr_1fr_1.5fr_40px] gap-4 text-xs font-semibold text-gray-500 uppercase">
                                                        <div>Status / WO</div>
                                                        <div>Equipment</div>
                                                        <div>Completed Date</div>
                                                        <div>Summary</div>
                                                        <div>Completed By</div>
                                                        <div></div>
                                                    </div>

                                                    {{-- ####Resolved Work Order Rows --}}
                                                    @foreach ($data['resolved'] as $wo)
                                                        @include('components.maintenance.schedule.resolved-schedule-row')
                                                    @endforeach

                                                </div>

                                            @endif

                                        </div>
                                    </details>

                                @endforeach

                            </div>
                        </div>

                    @endforeach

                @endif
                {{-- ─── END LOCATION GROUPING ─────────────────────────────────────────── --}}

            </div>
        
        @endif
        {{-- ─── END WORK ORDER CONDITIONAL FOR SELECTED INTERVAL ──────────────────────── --}}

    @endif
    {{-- ─── END VESSEL LEVEL WORK ORDER CONDITIONAL ───────────────────────────────────── --}}


    {{-- Slide-In Flow Modal --}}
    <div id="flow-slideout-wrapper" class="fixed inset-0 z-50 flex justify-end pointer-events-none overflow-hidden">

        {{-- Overlay --}}
        <div onclick="closeFlowModal()" class="hidden absolute inset-0 bg-black bg-opacity-30 transition-opacity duration-300 pointer-events-auto"
            id="flow-slideout-overlay"></div>

        {{-- Panel --}}
        <div id="flow-slideout-panel"
            class="w-full max-w-5xl bg-white shadow-xl transform translate-x-full transition-transform duration-300 pointer-events-auto overflow-y-auto h-full">
            {{-- Dynamic Content Goes Here --}}
        </div>
    </div>


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

            if (prevButton) {
                const atFirst = currentIndex === 0;
                prevButton.disabled = atFirst;
                prevButton.classList.toggle('opacity-50', atFirst);
                prevButton.classList.toggle('cursor-not-allowed', atFirst);
            }

            if (nextButton) {
                const atLast = currentIndex === totalSteps - 1;
                nextButton.disabled = atLast;
                nextButton.classList.toggle('opacity-50', atLast);
                nextButton.classList.toggle('cursor-not-allowed', atLast);
            }
        }



        function launchFlow(selectedIds, groupName) {
            const params = new URLSearchParams({
                frequency: '{{ $frequency }}',
                date: '{{ $date->toDateString() }}',
                group: '{{ $group }}',
                groupName: groupName,
                dateRangeLabel: '{{ $formattedRange }}',
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

                        completeBtn.className = 'px-3 py-1.5 text-sm font-medium rounded-lg bg-[#ebfdf2] text-[#027947] hover:bg-[#d0fadf]';
                        flagBtn.className = 'px-3 py-1.5 text-sm font-medium rounded-lg bg-[#fef3f2] text-[#b42318] hover:bg-[#fee4e2]';
                        completeBtn.disabled = false;
                        flagBtn.disabled = false;

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

            const allResolved = Array.from(taskButtons).every(btn => {
                const container = btn.closest('.flex');
                return container.querySelector('button[disabled]');
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

        function toggleAssigneeDropdown(id = null) {
            const dropdown = id ?
                document.getElementById(`assignee-options-${id}`) :
                document.getElementById('assignee-options');

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
                .then(() => loadWorkOrderAt(currentIndex))
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
                const formData = new FormData(completeForm);

                fetch(completeForm.action, {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                            'Accept': 'application/json'
                        },
                        body: formData
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.status === 'success') {
                            loadNextWorkOrder();
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

        // For initial render - preloaded modal
        document.addEventListener('DOMContentLoaded', () => {
            checkWorkOrderCompletable();
            bindCompletionForm();
        });
    </script>

@endsection
