@extends('v2.layouts.app')

@section('title', 'Interval Details')

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
            ['label' => $category->name, 'url' => route('maintenance.show', $category)],
            ['label' => ucfirst($interval->interval) . ' ' . $interval->description, 'active' => true]
        ],
        'actions' => [
            [
                'type' => 'link',
                'label' => 'Edit Interval',
                'url' => route('maintenance.intervals.edit', [$category, $interval]),
                'icon' => 'fas fa-edit',
                'class' => 'bg-primary-500 text-slate-800 hover:bg-primary-600'
            ]
        ]
    ])

    <div class="px-3 py-4 sm:px-6 sm:py-6 lg:px-8">
        {{-- System Messages --}}
        @if (session('warning'))
            <div class="mb-4 rounded-lg border-l-4 border-yellow-500 bg-yellow-100 p-4 text-yellow-700">
                {{ session('warning') }}
            </div>
        @endif

        @if (session('success'))
            <div class="mb-6 rounded-lg border border-green-300 bg-green-100 p-4 text-sm text-green-800">
                {{ session('success') }}
            </div>
        @endif

        {{-- Header --}}
        <div class="mb-6">
            <div class="mb-6 flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
                <div>
                    <h1 class="text-2xl font-semibold text-[#0f1728]">{{ $interval->description }}</h1>
                    <p class="text-[#475466]">Requirement overview and task configuration.</p>
                </div>
            </div>

            {{-- Interval Detail --}}
            <div class="grid grid-cols-6 gap-6">
                <div>
                    <label class="mb-2 block text-sm font-medium text-[#344053]">Interval</label>
                    <span
                        class="{{ frequency_label_class($interval->interval) }} inline-flex rounded-full px-2 py-1 text-xs font-medium">
                        {{ ucfirst($interval->interval) }}
                    </span>
                </div>
                <div>
                    <label class="mb-2 block text-sm font-medium text-[#344053]">Facilitated By</label>
                    <div>
                        <span
                            class="{{ facilitator_label_class($interval->facilitator) }} rounded px-2 py-1 text-xs font-medium">
                            {{ ucfirst($interval->facilitator) }}
                        </span>
                    </div>
                </div>
            </div>
        </div>

        {{-- Tasks Table --}}
        <section id="tasks-section" class="rounded-lg border border-[#e4e7ec] bg-white">
            {{-- Header & Controls --}}
            <div class="flex items-center justify-between border-b border-[#e4e7ec] px-6 py-4">
                <div>
                    <h2 class="text-lg font-semibold text-[#0f1728]">Tasks</h2>
                    <p class="mt-1 text-sm text-[#475466]">Define specific tasks and conditions for this requirement</p>
                </div>
                <button
                    onclick="window.location='{{ route('maintenance.intervals.tasks.create', [$category, $interval]) }}'"
                    class="flex items-center gap-2 rounded-lg bg-[#7e56d8] px-4 py-2 text-white hover:bg-[#6840c6]">
                    <i class="fa-solid fa-plus"></i>
                    Add Task
                </button>
            </div>

            {{-- Table --}}
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-[#f8f9fb]">
                        <tr>
                            <th
                                class="w-1/12 px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-[#7e56d8]">
                                Order</th>
                            <th
                                class="w-1/4 px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-[#7e56d8]">
                                Description</th>
                            <th
                                class="w-2/5 px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-[#7e56d8]">
                                Instructions</th>
                            <th
                                class="w-1/4 px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-[#7e56d8]">
                                Applicable To</th>
                            <th
                                class="w-16 px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-[#7e56d8]">
                                Actions</th>
                        </tr>
                    </thead>

                    <tbody class="divide-y divide-[#e4e7ec]" id="tasks-list">
                        {{-- Tasks Loop --}}
                        @if ($interval->tasks->isNotEmpty())
                            @foreach ($interval->tasks as $task)
                                <tr data-id="{{ $task->id }}" class="hover:bg-[#f9f5ff]">
                                    <td class="px-6 py-4">
                                        <i class="fa-solid fa-grip-vertical cursor-move text-[#667084]"></i>
                                    </td>
                                    <td class="px-6 py-4 text-sm text-[#0f1728]">
                                        <div class="font-medium">{{ $task->description }}</div>
                                    </td>
                                    <td class="px-6 py-4 text-sm text-[#0f1728]">
                                        <div class="text-sm">{{ $task->instructions }}</div>
                                    </td>
                                    <td class="px-6 py-4 text-sm text-[#0f1728]">
                                        <span class="inline-flex rounded-full px-2 py-1 text-xs font-medium">
                                            <span
                                                class="{{ applicable_to_label_class($task->applicable_to) }} inline-flex rounded-full px-2 py-1 text-xs font-medium">
                                                {{ $task->applicable_to }}
                                            </span>
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 text-sm font-medium">
                                        {{-- Actions --}}
                                        <div class="flex items-center gap-2">
                                            {{-- Edit Task --}}
                                            <button
                                                onclick="window.location='{{ route('maintenance.intervals.tasks.edit', ['category' => $category, 'interval' => $interval, 'task' => $task]) }}'"
                                                class="rounded-lg text-[#667084] hover:bg-[#f8f9fb] hover:text-[#344053]">
                                                <i class="fa-solid fa-edit"></i>
                                            </button>

                                            {{-- Delete Task --}}
                                            <form
                                                action="{{ route('maintenance.intervals.tasks.destroy', ['category' => $category, 'interval' => $interval, 'task' => $task]) }}"
                                                method="POST"
                                                onsubmit="return confirm('Are you sure you want to delete this task? This action cannot be undone.');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit"
                                                    class="rounded-lg p-2 text-[#667084] hover:bg-[#fef3f2] hover:text-[#f04438]">
                                                    <i class="fa-solid fa-trash"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        @else
                            <tr>
                                <td colspan="5" class="px-6 py-4 text-center text-sm text-[#667084]">
                                    No tasks defined for
                                    <span
                                        class="{{ frequency_label_class($interval->interval) }} font-semibold uppercase">{{ ucfirst($interval->interval) }}</span>

                                    <span
                                        class="font-semibold uppercase text-[#000000]">{{ $interval->description }}.</span>
                                </td>
                            </tr>
                        @endif
                    </tbody>
                </table>
            </div>
        </section>
    </div>

    {{-- Sortable --}}
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            new Sortable(document.querySelector('#tasks-list'), {
                animation: 150,
                onEnd: function(evt) {
                    // grab the new order of IDs
                    const order = Array.from(
                        document.querySelectorAll('#tasks-list tr')
                    ).map((tr, idx) => ({
                        id: tr.dataset.id,
                        display_order: idx + 1
                    }));

                    // send it to your backend
                    fetch(@json(route('tasks.reorder', $interval->id)), {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': @json(csrf_token())
                            },
                            body: JSON.stringify({
                                order
                            })
                        })
                        .then(res => {
                            if (!res.ok) throw new Error('Reorder failed');
                        })
                        .catch(console.error);
                }
            });
        });
    </script>

@endsection
