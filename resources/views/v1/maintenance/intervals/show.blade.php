@extends('layouts.app')

@section('title', 'Interval Details')

@section('content')

    {{-- Header --}}
    <div class="mb-6">

        {{-- Breadcrumb --}}
        <div class="mb-4">
            <nav class="flex items-center space-x-2 text-sm">
                <a href="{{ route('maintenance.index') }}">
                    <span class="text-[#6840c6] hover:text-[#5a35a8] cursor-pointer">Maintenance Index</span>
                </a>
                <i class="fa-solid fa-chevron-right text-[#667084] text-xs"></i>
                <a href="{{ route('maintenance.show', $category) }}">
                    <span class="text-[#6840c6] hover:text-[#5a35a8] cursor-pointer">{{ $category->name }}</span>
                </a>
                <i class="fa-solid fa-chevron-right text-[#667084] text-xs"></i>
                <span class="font-semibold uppercase {{ frequency_label_class($interval->interval) }}">{{ ucfirst($interval->interval) }}</span>
                <span class="text-[#475466]">{{ $interval->description }}</span>
            </nav>
        </div>

        {{-- System Messages --}}
        @if (session('warning'))
            <div class="mb-4 p-4 bg-yellow-100 border-l-4 border-yellow-500 text-yellow-700">
                {{ session('warning') }}
            </div>
        @endif
        
        @if (session('success'))
            <div class="mb-6 p-4 bg-green-100 border border-green-300 text-green-800 rounded-lg text-sm">
                {{ session('success') }}
            </div>
        @endif

        

        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-6">
            <div>
                <div class="flex items-center space-x-2">
                    <h1 class="text-2xl font-semibold text-[#0f1728]">{{ $interval->description }}</h1>
                    <button onclick="window.location='{{ route('intervals.edit', [$category, $interval]) }}'" class="text-[#6840c6] hover:text-[#7e56d8]">
                        <i class="fa-solid fa-edit"></i>
                    </button>
                </div>
                <p class="text-[#475466]">Requirement overview and task configuration.</p>
            </div>
        </div>

        {{-- Interval Detail --}}
        <div class="grid grid-cols-6 gap-6">
            <div>
                <label class="block text-sm font-medium text-[#344053] mb-2">Interval</label>
                <span class="inline-flex px-2 py-1 text-xs font-medium rounded-full {{ frequency_label_class($interval->interval) }}">
                    {{ ucfirst($interval->interval) }}
                </span>
            </div>
            <div>
                <label class="block text-sm font-medium text-[#344053] mb-2">Facilitated By</label>
                <div>
                    <span class="px-2 py-1 rounded text-xs font-medium {{ facilitator_label_class($interval->facilitator) }}">
                        {{ ucfirst($interval->facilitator) }}
                    </span>
                </div>
            </div>
        </div>

    </div>

    {{-- Tasks Table --}}
    <section id="tasks-section" class="bg-white rounded-lg border border-[#e4e7ec]">

        {{-- Header & Controls --}}
        <div class="px-6 py-4 border-b border-[#e4e7ec] flex items-center justify-between">
            <div>
                <h2 class="text-lg font-semibold text-[#0f1728]">Tasks</h2>
                <p class="text-sm text-[#475466] mt-1">Define specific tasks and conditions for this requirement</p>
            </div>
            <button onclick="window.location='{{ route('maintenance.intervals.tasks.create', $interval) }}'"
                class="px-4 py-2 bg-[#7e56d8] text-white rounded-lg hover:bg-[#6840c6] flex items-center gap-2">
                <i class="fa-solid fa-plus"></i>
                Add Task
            </button>
        </div>

        {{-- Table --}}
        <div class="overflow-x-auto">
            <table class="w-full">

                <thead class="bg-[#f8f9fb]">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-[#7e56d8] uppercase tracking-wider w-1/12">Order</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-[#7e56d8] uppercase tracking-wider w-1/4">Description</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-[#7e56d8] uppercase tracking-wider w-2/5">Instructions</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-[#7e56d8] uppercase tracking-wider w-1/4">Applicable To</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-[#7e56d8] uppercase tracking-wider w-16">Actions</th>
                    </tr>
                </thead>

                <tbody class="divide-y divide-[#e4e7ec]" id="tasks-list">

                    {{-- Tasks Loop --}}
                    @if ($interval->tasks->isNotEmpty())

                        @foreach ($interval->tasks as $task)
                            <tr data-id="{{ $task->id }}" class="hover:bg-[#f9f5ff]">
                                <td class="px-6 py-4 ">
                                    <i class="fa-solid fa-grip-vertical text-[#667084] cursor-move"></i>
                                </td>
                                <td class="px-6 py-4 text-sm text-[#0f1728]">
                                    <div class="font-medium">{{ $task->description }}</div>
                                </td>
                                <td class="px-6 py-4 text-sm text-[#0f1728]">
                                    <div class="text-sm">{{ $task->instructions }}</div>
                                </td>
                                <td class="px-6 py-4 text-sm text-[#0f1728]">
                                    <span class="inline-flex px-2 py-1 text-xs font-medium rounded-full">
                                        <span
                                            class="inline-flex px-2 py-1 text-xs font-medium rounded-full {{ applicable_to_label_class($task->applicable_to) }}">
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
                                            class="text-[#667084] hover:text-[#344053] hover:bg-[#f8f9fb] rounded-lg">
                                            <i class="fa-solid fa-edit"></i>
                                        </button>

                                        {{-- Delete Task --}}
                                        <form
                                            action="{{ route('maintenance.intervals.tasks.destroy', ['category' => $category, 'interval' => $interval, 'task' => $task]) }}"
                                            method="POST"
                                            onsubmit="return confirm('Are you sure you want to delete this task? This action cannot be undone.');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="p-2 text-[#667084] hover:text-[#f04438] hover:bg-[#fef3f2] rounded-lg">
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
                                    class="font-semibold uppercase {{ frequency_label_class($interval->interval) }}">{{ ucfirst($interval->interval) }}</span>

                                <span class="text-[#000000] font-semibold uppercase">{{ $interval->description }}.</span>
                            </td>
                        </tr>
                    @endif

                </tbody>

            </table>
        </div>

    </section>

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
