@extends('v2.layouts.app')

@section('title', 'New Task')

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
            ['label' => 'New Task', 'active' => true]
        ],
        'actions' => [
            [
                'type' => 'link',
                'label' => 'Back to Interval',
                'url' => route('maintenance.intervals.show', [$interval->category, $interval]),
                'icon' => 'fas fa-arrow-left',
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
                <h1 class="text-2xl font-semibold text-[#0f1728]">New Task</h1>
                <p class="text-[#475466]">Define a new task for <span
                        class="{{ frequency_label_class($interval->interval) }} font-semibold uppercase">{{ ucfirst($interval->interval) }}</span>
                    {{ $interval->description }}</p>
            </div>

            <form id="task-form"
                action="{{ route('maintenance.intervals.tasks.store', ['category' => $interval->category, 'interval' => $interval]) }}"
                method="POST" class="space-y-6">
                @csrf

                {{-- Description --}}
                <div>
                    <label for="description" class="mb-2 block text-sm font-medium text-[#344053]">Description</label>
                    <input id="description" name="description" value="{{ old('description') }}"
                        class="w-full rounded-lg border border-[#cfd4dc] bg-white px-3.5 py-2.5 text-[#0f1728] focus:border-[#6840c6] focus:outline-none focus:ring-2 focus:ring-[#6840c6]"
                        placeholder="Enter description…">
                </div>

                {{-- Instructions --}}
                <div>
                    <label for="instructions" class="mb-2 block text-sm font-medium text-[#344053]">Instructions</label>
                    <textarea id="instructions" name="instructions" rows="4"
                        class="w-full rounded-lg border border-[#cfd4dc] bg-white px-3.5 py-2.5 text-[#0f1728] focus:border-[#6840c6] focus:outline-none focus:ring-2 focus:ring-[#6840c6]"
                        placeholder="Enter instructions…">{{ old('instructions') }}</textarea>
                </div>

                {{-- Applicable To --}}
                @php
                    // Radio default
                    $applicableTo = old('applicable_to', 'All Equipment');

                    $keyLabels = [
                        'manufacturer' => 'Manufacturer',
                        'model' => 'Model',
                        'deck_id' => 'Deck',
                        'location_id' => 'Location'
                    ];

                    // JSON data for JS
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
                                    value="{{ $opt['value'] }}" class="mt-1 h-4 w-4 text-[#6840c6] focus:ring-[#6840c6]"
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
                                    {{ in_array($eq->id, old('specific_equipment', [])) ? 'checked' : '' }}>
                                <div class="text-sm text-[#0f1728]">
                                    <span class="font-medium">{{ $eq->name }}</span>
                                    <p class="text-xs text-[#667084]">
                                        {{ $eq->location->name ?? '—' }} • Serial: {{ $eq->serial_number ?? '—' }}
                                    </p>
                                </div>
                            </label>
                        @endforeach
                    </div>
                </div>

                {{-- Conditional Logic --}}
                <div id="condition-builder" class="{{ $applicableTo !== 'Conditional' ? 'hidden' : '' }} mt-6 space-y-4">
                    <label class="block text-sm font-medium text-[#344053]">Define Matching Conditions</label>

                    {{-- Container for dynamic rows --}}
                    <div id="condition-rows" class="space-y-2"></div>

                    <button type="button" id="add-condition" class="text-sm text-[#6840c6] hover:underline">
                        + Add Condition
                    </button>

                    {{-- Template: no name attributes here --}}
                    <template id="condition-template">
                        <div class="condition-row flex items-center gap-3">
                            <select class="condition-key w-1/3 rounded-lg border border-[#cfd4dc] px-3 py-2">
                                <option value="">Select field…</option>
                                @foreach ($staticConditions as $key => $vals)
                                    <option value="{{ $key }}">
                                        {{ $keyLabels[$key] ?? ucfirst(str_replace('_id', '', $key)) }}</option>
                                @endforeach
                                @foreach ($dynamicConditions as $key => $vals)
                                    <option value="{{ $key }}">{{ ucfirst($key) }}</option>
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

                {{-- Form buttons --}}
                <div class="flex justify-end space-x-4">
                    <a href="{{ route('maintenance.intervals.show', ['category' => $interval->category, 'interval' => $interval]) }}"
                        class="rounded-lg border border-[#cfd4dc] bg-white px-4 py-2.5 text-sm font-medium text-[#344053] hover:bg-[#f9fafb]">
                        Cancel
                    </a>
                    <button type="submit"
                        class="rounded-lg bg-[#7e56d8] px-4 py-2.5 text-sm font-medium text-white hover:bg-[#6840c6]">
                        <i class="fa-solid fa-plus mr-2"></i> Save Task
                    </button>
                </div>
            </form>
        </div>
    </div>

@endsection

@push('scripts')
    <script>
        // 1) Strip incomplete rows right before submit
        document.addEventListener('DOMContentLoaded', () => {
            document.getElementById('task-form').addEventListener('submit', () => {
                document.querySelectorAll('.condition-row').forEach(row => {
                    const k = row.querySelector('.condition-key').value.trim();
                    const v = row.querySelector('.condition-value').value.trim();
                    if (!k || !v) row.remove();
                });
            });
        });

        // 2) Toggle sections and auto-add first row for Conditional
        document.addEventListener('DOMContentLoaded', () => {
            const radios = document.querySelectorAll('input[name="applicable_to"]');
            const spec = document.getElementById('specific-equipment-section');
            const cond = document.getElementById('condition-builder');

            function toggle(val) {
                spec.classList.toggle('hidden', val !== 'Specific Equipment');
                cond.classList.toggle('hidden', val !== 'Conditional');
                // if showing conditional and no rows exist, add one
                if (val === 'Conditional' && !cond.querySelector('.condition-row')) {
                    document.getElementById('add-condition').click();
                }
            }
            radios.forEach(r => r.addEventListener('change', e => toggle(e.target.value)));
            radios.forEach(r => {
                if (r.checked) toggle(r.value);
            });
        });

        // 3) Condition builder logic
        document.addEventListener('DOMContentLoaded', () => {
            const container = document.getElementById('condition-rows');
            const template = document.getElementById('condition-template').content;
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

                keySel.addEventListener('change', () => {
                    const opts = valueMap[keySel.value] || {};
                    valSel.innerHTML = '<option value="">Select value…</option>';
                    for (const v in opts) {
                        const op = document.createElement('option');
                        op.value = v;
                        op.textContent = opts[v];
                        valSel.appendChild(op);
                    }
                });

                row.querySelector('.remove-condition').addEventListener('click', () => {
                    row.remove();
                    updateIndices();
                });
            }

            addBtn.addEventListener('click', () => {
                const clone = template.cloneNode(true);
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
