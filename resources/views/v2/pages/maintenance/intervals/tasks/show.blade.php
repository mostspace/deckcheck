@extends('v2.layouts.app')

@section('title', 'Task Details')

@section('content')

    {{-- Enhanced Maintenance Header --}}
    @include('v2.components.nav.header-routing', [
        'activeTab' => 'index',
        'breadcrumbs' => [
            [
                'label' => 'Maintenance',
                'icon' => asset('assets/media/icons/sidebar-solid-wrench-scredriver.svg'),
                'url' => route('maintenance.index')
            ],
            ['label' => $interval->category->name, 'url' => route('maintenance.show', $interval->category)],
            [
                'label' => ucfirst($interval->interval) . ' ' . $interval->description,
                'url' => route('maintenance.intervals.show', [$interval->category, $interval])
            ],
            ['label' => $task->description, 'active' => true]
        ],
        'actions' => [
            [
                'type' => 'link',
                'label' => 'Edit Task',
                'url' => route('maintenance.intervals.tasks.edit', [$interval->category, $interval, $task]),
                'icon' => 'fas fa-edit',
                'class' => 'bg-primary-500 text-slate-800 hover:bg-primary-600'
            ]
        ]
    ])

    <div class="px-3 py-4 sm:px-6 sm:py-6 lg:px-8">
        {{-- System Messages --}}
        @if (session('success'))
            <div class="mb-6 rounded-lg border border-green-300 bg-green-100 p-4 text-sm text-green-800">
                {{ session('success') }}
            </div>
        @endif

        {{-- Task Details --}}
        <div class="rounded-lg border border-[#e4e7ec] bg-white p-6 shadow-sm">
            <div class="mb-6">
                <h1 class="text-2xl font-semibold text-[#0f1728]">{{ $task->description }}</h1>
                <p class="text-[#475466]">Task details and configuration</p>
            </div>

            {{-- Task Information --}}
            <div class="grid grid-cols-1 gap-6 md:grid-cols-2">
                <div>
                    <label class="mb-2 block text-sm font-medium text-[#344053]">Description</label>
                    <p class="text-[#0f1728]">{{ $task->description }}</p>
                </div>

                <div>
                    <label class="mb-2 block text-sm font-medium text-[#344053]">Instructions</label>
                    <p class="text-[#0f1728]">{{ $task->instructions }}</p>
                </div>

                <div>
                    <label class="mb-2 block text-sm font-medium text-[#344053]">Applicable To</label>
                    <span
                        class="{{ applicable_to_label_class($task->applicable_to) }} inline-flex rounded-full px-2 py-1 text-xs font-medium">
                        {{ $task->applicable_to }}
                    </span>
                </div>

                <div>
                    <label class="mb-2 block text-sm font-medium text-[#344053]">Interval</label>
                    <span
                        class="{{ frequency_label_class($interval->interval) }} inline-flex rounded-full px-2 py-1 text-xs font-medium">
                        {{ ucfirst($interval->interval) }}
                    </span>
                </div>
            </div>

            {{-- Specific Equipment (if applicable) --}}
            @if ($task->applicable_to === 'Specific Equipment' && $task->applicableEquipment->isNotEmpty())
                <div class="mt-6">
                    <h3 class="mb-4 text-lg font-semibold text-[#0f1728]">Selected Equipment</h3>
                    <div class="grid grid-cols-1 gap-4 md:grid-cols-2">
                        @foreach ($task->applicableEquipment as $applicable)
                            <div class="rounded-lg border border-[#e4e7ec] bg-white p-4 shadow-sm">
                                <div class="flex items-center gap-3">
                                    <div
                                        class="flex h-10 w-10 items-center justify-center rounded-md border border-[#d6bbfb] bg-[#f3ebff] text-[#6840c6]">
                                        <i
                                            class="fa-solid {{ $applicable->equipment->category->icon ?? 'fa-screwdriver-wrench' }}"></i>
                                    </div>
                                    <div class="min-w-0 flex-1">
                                        <h4 class="truncate font-semibold text-[#0f1728]">
                                            {{ $applicable->equipment->name ?? 'Unnamed' }}</h4>
                                        <p class="text-sm text-[#667084]">{{ $applicable->equipment->deck->name }} /
                                            {{ $applicable->equipment->location->name }}</p>
                                        <p class="text-xs text-[#475467]">S/N: {{ $applicable->equipment->serial_number }}
                                        </p>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif

            {{-- Conditional Logic (if applicable) --}}
            @if ($task->applicable_to === 'Conditional' && $task->applicability_conditions)
                <div class="mt-6">
                    <h3 class="mb-4 text-lg font-semibold text-[#0f1728]">Matching Conditions</h3>
                    <div class="space-y-2">
                        @foreach (json_decode($task->applicability_conditions, true) as $condition)
                            <div class="flex items-center gap-3 rounded-lg border border-[#e4e7ec] bg-[#f8f9fb] p-3">
                                <span
                                    class="text-sm font-medium text-[#344053]">{{ ucfirst(str_replace('_', ' ', $condition['key'])) }}</span>
                                <span class="text-[#667084]">=</span>
                                <span class="text-sm text-[#0f1728]">{{ $condition['value'] }}</span>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif
        </div>

        {{-- Actions --}}
        <div class="mt-6 flex justify-end space-x-4">
            <a href="{{ route('maintenance.intervals.show', [$interval->category, $interval]) }}"
                class="rounded-lg border border-[#cfd4dc] bg-white px-4 py-2.5 text-sm font-medium text-[#344053] transition-colors hover:bg-[#f9fafb]">
                Back to Interval
            </a>
            <a href="{{ route('maintenance.intervals.tasks.edit', [$interval->category, $interval, $task]) }}"
                class="rounded-lg border border-[#7e56d8] bg-[#7e56d8] px-4 py-2.5 text-sm font-medium text-white transition-colors hover:bg-[#6840c6]">
                <i class="fa-solid fa-edit mr-2"></i>
                Edit Task
            </a>
        </div>
    </div>

@endsection
