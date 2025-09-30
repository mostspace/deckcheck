@extends('v2.layouts.app')

@php
    use Illuminate\Support\Facades\Storage;
@endphp

@section('title', 'Edit Task')

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
            [
                'label' => $task->description,
                'url' => route('maintenance.intervals.tasks.show', [$interval->category, $interval, $task])
            ],
            ['label' => 'Edit', 'active' => true]
        ],
        'actions' => [
            [
                'type' => 'link',
                'label' => 'View Task',
                'url' => route('maintenance.intervals.tasks.show', [$interval->category, $interval, $task]),
                'icon' => 'fas fa-eye',
                'class' => 'bg-slate-500 text-white hover:bg-slate-600'
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

        @if (session('error'))
            <div class="mb-6 rounded-lg border border-red-300 bg-red-100 p-4 text-sm text-red-800">
                {{ session('error') }}
            </div>
        @endif

        {{-- Form --}}
        <div class="rounded-lg border border-[#e4e7ec] bg-white p-6 shadow-sm">
            <div class="mb-6">
                <h1 class="text-2xl font-semibold text-[#0f1728]">Edit Task</h1>
                <p class="text-[#475466]">Update task for <span
                        class="{{ frequency_label_class($interval->interval) }} font-semibold uppercase">{{ ucfirst($interval->interval) }}</span>
                    {{ $interval->description }}</p>
            </div>

            <form id="task-form"
                action="{{ route('maintenance.intervals.tasks.update', ['category' => $interval->category, 'interval' => $interval, 'task' => $task]) }}"
                method="POST" class="space-y-6">
                @csrf
                @method('PUT')

                {{-- Description --}}
                <div>
                    <label for="description" class="mb-2 block text-sm font-medium text-[#344053]">Description</label>
                    <input id="description" name="description" value="{{ old('description', $task->description) }}"
                        class="w-full rounded-lg border border-[#cfd4dc] bg-white px-3.5 py-2.5 text-[#0f1728] focus:border-[#6840c6] focus:outline-none focus:ring-2 focus:ring-[#6840c6]"
                        placeholder="Enter description…">
                </div>

                {{-- Instructions --}}
                <div>
                    <label for="instructions" class="mb-2 block text-sm font-medium text-[#344053]">Instructions</label>
                    <textarea id="instructions" name="instructions" rows="4"
                        class="w-full rounded-lg border border-[#cfd4dc] bg-white px-3.5 py-2.5 text-[#0f1728] focus:border-[#6840c6] focus:outline-none focus:ring-2 focus:ring-[#6840c6]"
                        placeholder="Enter instructions…">{{ old('instructions', $task->instructions) }}</textarea>
                </div>

                {{-- Applicable To --}}
                @php
                    $applicableTo = old('applicable_to', $task->applicable_to);

                    $keyLabels = [
                        'manufacturer' => 'Manufacturer',
                        'model' => 'Model',
                        'deck_id' => 'Deck',
                        'location_id' => 'Location'
                    ];

                    // Build out the old/DB conditions
                    $rawConditions = old('applicability_conditions', $conditions ?? []);
                    $submittedConditions = collect($rawConditions)
                        ->filter(fn($r) => isset($r['key'], $r['value']) && ($r['key'] !== '' || $r['value'] !== ''))
                        ->values()
                        ->toArray();

                    // Value‐map for JS
                    $valueMap = collect($staticConditionsJson)->merge($dynamicConditionsJson);

                    $options = [
                        'All Equipment' => [
                            'value' => 'All Equipment',
                            'desc' => 'This task applies to all fire extinguishers regardless of type or location',
                            'id' => 'all-equipment'
                        ],
                        'Specific Equipment' => [
                            'value' => 'Specific Equipment',
                            'desc' => 'Select individual equipment items from inventory',
                            'id' => 'specific-equipment'
                        ],
                        'Conditional' => [
                            'value' => 'Conditional',
                            'desc' => 'This task applies only to equipment meeting specific conditions',
                            'id' => 'conditional'
                        ]
                    ];
                @endphp
                <div>
                    <label class="mb-3 block text-sm font-medium text-[#344053]">Applicable To *</label>
                    <div class="space-y-3">
                        @foreach ($options as $label => $opt)
                            <div class="flex items-start gap-3">
                                <input type="radio" id="{{ $opt['id'] }}" name="applicable_to"
                                    value="{{ $opt['value'] }}" class="mt-1 h-4 w-4 focus:ring-[#6840c6]"
                                    {{ $applicableTo === $opt['value'] ? 'checked' : '' }}>
                                <div class="flex-1">
                                    <label for="{{ $opt['id'] }}"
                                        class="cursor-pointer text-sm font-medium text-[#0f1728]">
                                        {{ $label }}
                                    </label>
                                    <p class="mt-1 text-xs text-[#475466]">{{ $opt['desc'] }}</p>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>

                {{-- Specific Equipment --}}
                <div id="specific-equipment-section"
                    class="{{ $applicableTo !== 'Specific Equipment' ? 'hidden' : '' }} mt-6 space-y-4">
                    <label class="block text-sm font-medium text-[#344053]">Select Specific Equipment</label>
                    <div class="space-y-2">
                        @foreach ($interval->category->equipment as $eq)
                            <label
                                class="flex items-center gap-2 rounded-lg border border-[#e4e7ec] bg-white px-4 py-3 shadow-sm">
                                <input type="checkbox" name="specific_equipment[]" value="{{ $eq->id }}"
                                    class="focus:ring-[#6840c6]"
                                    {{ in_array($eq->id, old('specific_equipment', $selectedSpecific)) ? 'checked' : '' }}>
                                <div class="flex items-center rounded-md border border-[#e4e7ec] bg-[#f9fafb] p-2 shadow-sm transition hover:bg-[#f3f4f6]"
                                    style="min-width:300px; max-width:100%;">
                                    {{-- Hero Image --}}
                                    <div
                                        class="h-20 w-20 flex-shrink-0 overflow-hidden rounded-md border border-[#e4e7ec] bg-white">
                                        <img src="{{ Storage::url($eq->hero_photo) }}"
                                            alt="Hero Photo for {{ $eq->name }}" class="h-full w-full object-cover">
                                    </div>
                                    {{-- Info --}}
                                    <div class="ml-4 flex min-w-0 flex-col">
                                        {{-- Name + Icon --}}
                                        <div class="mb-1 flex items-center gap-2">
                                            <span
                                                class="flex h-7 w-7 items-center justify-center rounded-md border border-[#d6bbfb] bg-[#f3ebff] text-[#6840c6]">
                                                <i
                                                    class="fa-solid {{ $eq->category->icon ?? 'fa-screwdriver-wrench' }}"></i>
                                            </span>
                                            <span
                                                class="truncate text-base font-semibold text-[#0f1728]">{{ $eq->name ?? 'Unnamed' }}</span>
                                        </div>
                                        {{-- Location --}}
                                        <div class="mb-1 flex items-center gap-1 text-xs text-[#667084]">
                                            <i class="fa-solid fa-location-dot text-[#6840c6]"></i>
                                            <span class="font-medium text-[#344053]">{{ $eq->deck->name }}</span>
                                            <span>/ {{ $eq->location->name }}</span>
                                        </div>
                                        {{-- Internal Info --}}
                                        <div class="flex items-center gap-2 text-xs text-[#475467]">
                                            <span>{{ $eq->internal_id }}</span>
                                            <span class="text-[#d0d5dd]">•</span>
                                            <span>S/N: {{ $eq->serial_number }}</span>
                                        </div>
                                    </div>
                                </div>
                            </label>
                        @endforeach
                    </div>
                </div>

                {{-- Conditional Logic --}}
                <div id="condition-builder" class="{{ $applicableTo !== 'Conditional' ? 'hidden' : '' }} mt-6 space-y-4">
                    <label class="block text-sm font-medium text-[#344053]">Define Matching Conditions</label>

                    <div id="condition-rows" class="space-y-2">
                        @foreach ($submittedConditions as $i => $cond)
                            @php
                                $selKey = $cond['key'];
                                $selValue = $cond['value'];
                                $avail = $staticConditions[$selKey] ?? ($dynamicConditions[$selKey] ?? []);
                            @endphp
                            <div class="condition-row flex items-center gap-3">
                                <select name="applicability_conditions[{{ $i }}][key]"
                                    class="condition-key w-1/3 rounded-lg border border-[#cfd4dc] px-3 py-2">
                                    <option value="">Select field…</option>
                                    @foreach ($staticConditions as $k => $vals)
                                        <option value="{{ $k }}" {{ $selKey === $k ? 'selected' : '' }}>
                                            {{ $keyLabels[$k] ?? ucfirst(str_replace('_id', '', $k)) }}
                                        </option>
                                    @endforeach
                                    @foreach ($dynamicConditions as $k => $vals)
                                        <option value="{{ $k }}" {{ $selKey === $k ? 'selected' : '' }}>
                                            {{ ucfirst($k) }}</option>
                                    @endforeach
                                </select>

                                <select name="applicability_conditions[{{ $i }}][value]"
                                    class="condition-value w-1/3 rounded-lg border border-[#cfd4dc] px-3 py-2"
                                    data-selected="{{ $selValue }}">
                                    <option value="">Select value…</option>
                                    @if ($selKey && $avail)
                                        @foreach ($avail as $opt)
                                            <option value="{{ $opt['value'] }}"
                                                {{ $selValue == $opt['value'] ? 'selected' : '' }}>
                                                {{ $opt['label'] }}
                                            </option>
                                        @endforeach
                                    @endif
                                </select>

                                <button type="button" class="remove-condition text-sm text-red-600 hover:underline">
                                    Remove
                                </button>
                            </div>
                        @endforeach
                    </div>

                    <button type="button" id="add-condition" class="text-sm text-[#6840c6] hover:underline">
                        + Add Condition
                    </button>

                    <template id="condition-template">
                        <div class="condition-row flex items-center gap-3">
                            <select class="condition-key w-1/3 rounded-lg border border-[#cfd4dc] px-3 py-2">
                                <option value="">Select field…</option>
                                @foreach ($staticConditions as $k => $vals)
                                    <option value="{{ $k }}">
                                        {{ $keyLabels[$k] ?? ucfirst(str_replace('_id', '', $k)) }}</option>
                                @endforeach
                                @foreach ($dynamicConditions as $k => $vals)
                                    <option value="{{ $k }}">{{ ucfirst($k) }}</option>
                                @endforeach
                            </select>
                            <select class="condition-value w-1/3 rounded-lg border border-[#cfd4dc] px-3 py-2">
                                <option value="">Select value…</option>
                            </select>
                            <button type="button" class="remove-condition text-sm text-red-600 hover:underline">
                                Remove
                            </button>
                        </div>
                    </template>
                </div>

                {{-- Form Buttons --}}
                <div class="flex justify-end space-x-4">
                    <a href="{{ route('maintenance.intervals.show', ['category' => $interval->category, 'interval' => $interval]) }}"
                        class="rounded-lg border border-[#cfd4dc] bg-white px-4 py-2.5 text-sm font-medium text-[#344053] hover:bg-[#f9fafb]">
                        Cancel
                    </a>
                    <button type="submit"
                        class="rounded-lg bg-[#7e56d8] px-4 py-2.5 text-sm font-medium text-white hover:bg-[#6840c6]">
                        <i class="fa-solid fa-floppy-disk mr-2"></i> Save Changes
                    </button>
                </div>
            </form>
        </div>
    </div>

@endsection

@push('scripts')
    <script>
        // 1) strip incomplete rows before submit
        document.getElementById('task-form').addEventListener('submit', () => {
            document.querySelectorAll('.condition-row').forEach(row => {
                const k = row.querySelector('.condition-key').value.trim();
                const v = row.querySelector('.condition-value').value.trim();
                if (!k || !v) row.remove();
            });
        });

        // 2) toggle and auto-add first row if needed
        document.querySelectorAll('input[name="applicable_to"]').forEach(radio => {
            const spec = document.getElementById('specific-equipment-section');
            const cond = document.getElementById('condition-builder');

            function toggle(val) {
                spec.classList.toggle('hidden', val !== 'Specific Equipment');
                cond.classList.toggle('hidden', val !== 'Conditional');
                if (val === 'Conditional' && !cond.querySelector('.condition-row')) {
                    document.getElementById('add-condition').click();
                }
            }
            radio.addEventListener('change', e => toggle(e.target.value));
            if (radio.checked) toggle(radio.value);
        });

        // 3) condition builder logic
        document.addEventListener('DOMContentLoaded', () => {
            const container = document.getElementById('condition-rows');
            const tmpl = document.getElementById('condition-template').content;
            const addBtn = document.getElementById('add-condition');
            const valueMap = @json($valueMap);

            function updateIndices() {
                container.querySelectorAll('.condition-row').forEach((row, i) => {
                    row.querySelector('.condition-key').name = `applicability_conditions[${i}][key]`;
                    row.querySelector('.condition-value').name = `applicability_conditions[${i}][value]`;
                });
            }

            function bindRow(row) {
                const keySel = row.querySelector('.condition-key');
                const valSel = row.querySelector('.condition-value');
                const preset = valSel.dataset.selected || '';

                keySel.addEventListener('change', () => {
                    valSel.innerHTML = '<option value="">Select value…</option>';
                    for (const [val, label] of Object.entries(valueMap[keySel.value] || {})) {
                        const op = document.createElement('option');
                        op.value = val;
                        op.textContent = label;
                        if (val === preset) op.selected = true;
                        valSel.appendChild(op);
                    }
                });

                row.querySelector('.remove-condition').addEventListener('click', () => {
                    row.remove();
                    updateIndices();
                });

                // trigger initial population for edit
                if (keySel.value) keySel.dispatchEvent(new Event('change'));
            }

            // bind existing
            container.querySelectorAll('.condition-row').forEach(bindRow);

            // add new
            addBtn.addEventListener('click', () => {
                const clone = tmpl.cloneNode(true);
                const row = clone.querySelector('.condition-row');
                bindRow(row);
                container.appendChild(row);
                updateIndices();
            });
        });
    </script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const form = document.getElementById('task-form');
            const builder = document.getElementById('condition-builder');
            if (!form || !builder) return;

            // Error banner (one per builder)
            let errorContainer = document.createElement('div');
            errorContainer.id = 'condition-error';
            errorContainer.className =
                'hidden mb-4 text-sm text-red-700 bg-red-100 border border-red-300 rounded-lg p-3';
            errorContainer.innerText =
                'Please complete at least one valid condition row, or remove incomplete ones.';
            builder.prepend(errorContainer);

            form.addEventListener('submit', function(e) {
                // Only validate if conditional section is shown
                if (builder.classList.contains('hidden')) return;

                let isValid = true;
                let firstErrorRow = null;
                let hasCompleteRow = false;

                builder.querySelectorAll('.condition-row').forEach(row => {
                    const keySel = row.querySelector('.condition-key');
                    const valSel = row.querySelector('.condition-value');
                    const hasKey = keySel.value.trim() !== '';
                    const hasValue = valSel.value.trim() !== '';

                    // mark errors for half-filled rows
                    if ((hasKey && !hasValue) || (!hasKey && hasValue)) {
                        isValid = false;
                        row.classList.add('border-red-500', 'bg-red-50');
                        if (!firstErrorRow) firstErrorRow = row;
                    } else {
                        row.classList.remove('border-red-500', 'bg-red-50');
                    }

                    // detect at least one fully-complete row
                    if (hasKey && hasValue) {
                        hasCompleteRow = true;
                    }
                });

                // require at least one complete row
                if (!hasCompleteRow) {
                    isValid = false;
                }

                if (!isValid) {
                    e.preventDefault();
                    errorContainer.classList.remove('hidden');
                    const target = firstErrorRow || builder;
                    target.scrollIntoView({
                        behavior: 'smooth',
                        block: 'center'
                    });
                } else {
                    errorContainer.classList.add('hidden');
                }
            });
        });
    </script>
@endpush
