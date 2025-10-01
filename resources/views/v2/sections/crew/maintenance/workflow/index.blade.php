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

    $navParams = ['frequency' => $frequency, 'assigned' => request('assigned'), 'group' => $group];
@endphp

{{-- Workflow content starts here --}}
{{-- Interval Navigation --}}
<div class="mb-6 rounded-lg border border-[#e4e7ec] bg-white">

    {{-- #Frequency & Range Control --}}
    <div class="border-b border-[#e4e7ec] px-6 py-4">
        <div class="flex items-center justify-between">

            {{-- ##Frequency Tabs --}}
            <nav class="flex space-x-1">
                @foreach ($visibleFrequencies as $freq)
                    @php
                        $tabDate = match ($freq) {
                            'daily', 'weekly', 'bi-weekly' => now()->toDateString(),
                            'monthly', 'quarterly', 'bi-annually' => now()->startOfMonth()->toDateString(),
                            default => now()->startOfYear()->toDateString()
                        };
                    @endphp

                    @php
                        $queryParams = ['frequency' => $freq, 'date' => $tabDate];
                        if (request('assigned')) {
                            $queryParams['assigned'] = '1';
                        }
                    @endphp

                    <a href="{{ route('maintenance.schedule.index', $queryParams) }}"
                        class="{{ $frequency === $freq ? 'bg-[#f9f5ff] text-[#6840c6]' : 'text-[#667084] hover:text-[#344053] hover:bg-[#f8f9fb]' }} rounded-lg px-4 py-2 text-sm font-medium transition-colors">
                        {{ ucfirst($freq) }}
                    </a>
                @endforeach
            </nav>

            {{-- ##Date Navigation --}}
            <div class="flex items-center gap-2">
                <a href="{{ route('maintenance.schedule.index', array_merge($navParams, ['date' => $prevDate->toDateString()])) }}"
                    class="rounded-lg p-2 text-[#667084] transition-colors hover:bg-[#f8f9fb] hover:text-[#344053]">
                    <i class="fa-solid fa-chevron-left"></i>
                </a>

                <div
                    class="rounded-lg border border-[#cfd4dc] bg-white px-3 py-2 text-sm text-[#6840c6] shadow-[0px_1px_2px_0px_rgba(16,24,40,0.05)]">
                    <i class="fa-regular fa-calendar-days pr-2 text-gray-400"></i>
                    {{ $formattedRange }}
                </div>

                <a href="{{ route('maintenance.schedule.index', array_merge($navParams, ['date' => $nextDate->toDateString()])) }}"
                    class="rounded-lg p-2 text-[#667084] transition-colors hover:bg-[#f8f9fb] hover:text-[#344053]">
                    <i class="fa-solid fa-chevron-right"></i>
                </a>
            </div>

        </div>
    </div>

    {{-- #Filters and Controls (static for now) --}}
    <div class="px-6 py-4">
        <div class="flex flex-col items-start justify-between gap-4 lg:flex-row lg:items-center">

            {{-- ##Group By Buttons --}}
            <div class="flex items-center gap-3">
                <span class="text-sm font-medium text-[#344053]">Group By:</span>
                @php
                    $group = request('group', 'date');
                @endphp

                @foreach (['date' => 'None', 'category' => 'Category', 'location' => 'Location'] as $key => $label)
                    <a href="{{ route('maintenance.schedule.index', array_merge(request()->query(), ['group' => $key])) }}"
                        class="{{ $group === $key ? 'text-[#6840c6] border-[#6840c6] bg-[#f9f5ff]' : 'text-[#344053] border-[#d0d5dd] hover:bg-[#f8f9fb]' }} rounded-md border px-4 py-2 text-sm font-medium">
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

                <label class="flex cursor-pointer items-center gap-2">
                    <input type="checkbox" name="assigned" onchange="this.form.submit()" class="sr-only"
                        {{ request('assigned') ? 'checked' : '' }}>
                    @php
                        $isAssignedActive = request()->boolean('assigned');
                    @endphp

                    {{-- ###Toggle w/ Active/Inactive Styles --}}
                    <div
                        class="{{ $isAssignedActive ? 'bg-[#6840c6]' : 'bg-[#e4e7ec]' }} relative h-6 w-10 rounded-full transition-colors duration-200">
                        <div
                            class="{{ $isAssignedActive ? 'translate-x-4' : '' }} absolute left-1 top-1 h-4 w-4 rounded-full bg-white shadow-sm transition-transform duration-200">
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
    <div
        class="flex flex-col items-center justify-center space-y-3 rounded-lg border border-[#e4e7ec] p-8 text-center text-sm text-[#475466]">
        <i class="fa-regular fa-calendar-xmark text-3xl text-[#d0d5dd]"></i>
        <p class="font-medium text-[#344053]">No work orders scheduled for this interval</p>
        <p class="max-w-sm text-xs text-[#667084]">
            You haven't scheduled any maintenance tasks for this frequency yet.
            Use the "Schedule Task" button to get started.
        </p>
    </div>
@else
    <div class="space-y-6 rounded-lg bg-white p-6 shadow">

        {{-- ─── DATE GROUPING ("All" tab) ─────────────────────────────────── --}}
        @if ($group === 'date')

            {{-- Active Work Orders Container --}}
            @if ($activeWorkOrders->isNotEmpty())

                {{-- #Active Column Headers --}}
                <div
                    class="hidden grid-cols-[1.25fr_2fr_1fr_1fr_1.5fr_40px] gap-4 px-6 py-2 text-xs font-semibold uppercase text-gray-500 lg:grid">
                    <div>Status / WO</div>
                    <div>Equipment</div>
                    <div>Due Date</div>
                    <div>Tasks</div>
                    <div>Assignee</div>
                    <div></div>
                </div>

                {{-- #Active Work Order Rows --}}
                @foreach ($activeWorkOrders as $wo)
                    @include('components.maintenance.schedule.schedule-row', ['showFlowCta' => true])
                @endforeach

            @endif

            {{-- Resolved Work Orders Container --}}
            @if ($resolvedWorkOrders->isNotEmpty())

                <div class="space-y-4 border-t border-[#e4e7ec] pt-6">
                    <div class="flex items-center gap-2 text-sm font-semibold text-[#344053]">
                        <i class="fa-regular fa-circle-check text-[#667084]"></i>
                        Resolved Work Orders
                    </div>

                    {{-- #Resolved Column Headers --}}
                    <div
                        class="hidden grid-cols-[1.25fr_2fr_1fr_1fr_1.5fr_40px] gap-4 px-6 py-2 text-xs font-semibold uppercase text-gray-500 lg:grid">
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
                <div class="mb-6 rounded-lg bg-white shadow">

                    {{-- ##Header --}}
                    <div class="flex items-center justify-between border-b px-6 py-4">
                        <h3 class="flex items-center gap-2 text-lg font-semibold">
                            <i class="fa-solid fa-box text-gray-600"></i>
                            {{ $category }}

                            {{-- ###Group Summary --}}
                            <span class="text-sm text-gray-500">
                                ({{ $openCount }} open{{ $resolvedCount ? ", {$resolvedCount} resolved" : '' }})
                            </span>
                        </h3>

                        {{-- ###Launch Flow --}}
                        @if ($openCount)
                            <button onclick="launchFlow({{ Js::from($ids) }}, {{ Js::from($category) }})"
                                class="flex items-center gap-1.5 text-sm text-purple-600 hover:underline">
                                <i class="fa-solid fa-circle-play"></i> Start
                            </button>
                        @elseif ($resolvedCount)
                            <span class="flex items-center gap-1.5 text-sm italic text-gray-400">
                                <i class="fa-regular fa-circle-check"></i> Complete
                            </span>
                        @endif
                    </div>

                    {{-- ##Body --}}
                    <div class="space-y-4 px-6 py-4">

                        {{-- Active Work Orders Container --}}
                        @if ($openCount)
                            {{-- ###Active Column Headers --}}
                            <div
                                class="hidden grid-cols-[1.25fr_2fr_1fr_1fr_1.5fr_40px] gap-4 text-xs font-semibold uppercase text-gray-500 lg:grid">
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
                                <summary
                                    class="flex cursor-pointer items-center gap-2 text-sm font-medium text-gray-700">
                                    <i class="fa-regular fa-circle-check text-green-600"></i>
                                    Resolved ({{ $resolvedCount }})
                                </summary>
                                <div class="mt-4">

                                    {{-- ###Resolved Column Headers --}}
                                    <div
                                        class="hidden grid-cols-[1.25fr_2fr_1fr_1fr_1.5fr_40px] gap-4 text-xs font-semibold uppercase text-gray-500 lg:grid">
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
                <div class="mb-6 rounded-lg bg-white shadow">

                    {{-- ##Deck Header --}}
                    <div class="flex items-center gap-2 border-b px-6 py-4 text-lg font-semibold text-gray-800">
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
                                <summary
                                    class="flex cursor-pointer items-center justify-between px-6 py-3 group-hover:bg-gray-50">
                                    <div class="flex items-center gap-2">
                                        <i class="fa-solid fa-location-dot text-gray-600"></i>
                                        <span class="font-medium">{{ $locationName }}</span>
                                        <span class="text-sm text-gray-500">
                                            ({{ $openCount }}
                                            open{{ $resolvedCount ? ", {$resolvedCount} resolved" : '' }})
                                        </span>
                                    </div>

                                    {{-- ####Launch Flow --}}
                                    @if ($openCount)
                                        <button
                                            onclick="launchFlow({{ Js::from($ids) }}, {{ Js::from($locationName) }})"
                                            class="flex items-center gap-1.5 text-sm text-purple-600 hover:underline">
                                            <i class="fa-solid fa-circle-play"></i> Start
                                        </button>
                                    @elseif ($resolvedCount)
                                        <span class="flex items-center gap-1.5 text-sm italic text-gray-400">
                                            <i class="fa-regular fa-circle-check"></i> Complete
                                        </span>
                                    @endif

                                </summary>

                                {{-- ###Body --}}
                                <div class="space-y-4 bg-gray-50 px-6 py-4">

                                    {{-- Active Work Orders Container --}}
                                    @if ($openCount)
                                        {{-- ####Active Column Headers --}}
                                        <div
                                            class="hidden grid-cols-[1.25fr_2fr_1fr_1fr_1.5fr_40px] gap-4 text-xs font-semibold uppercase text-gray-500 lg:grid">
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
                                            <div class="mb-2 flex items-center gap-2 text-sm font-medium text-gray-700">
                                                <i class="fa-regular fa-circle-check text-green-600"></i>
                                                Resolved ({{ $resolvedCount }})
                                            </div>

                                            {{-- ####Resolved Column Headers --}}
                                            <div
                                                class="hidden grid-cols-[1.25fr_2fr_1fr_1fr_1.5fr_40px] gap-4 text-xs font-semibold uppercase text-gray-500 lg:grid">
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
{{-- ─── END VESSEL LEVEL WORK ORDER CONDITIONAL ───────────────────────────────────── --}}

{{-- Modal structure is now in the main schedule page --}}
