@extends('v2.layouts.app')

@section('title', 'Interval Detail')

@section('content')

    @include('v2.components.nav.header-routing', [
        'activeTab' => 'equipment',
        'context' => 'inventory',
        'enableRefererBreadcrumbs' => true,
        'refererContext' => ['interval' => $interval]
    ])

    <div class="px-3 sm:px-6 lg:px-8 py-4 sm:py-6">




    {{-- System Messages --}}
    @if (session('success'))
        <div class="mb-6 p-4 bg-green-100 border border-green-300 text-green-800 rounded-lg text-sm">
            {{ session('success') }}
        </div>
    @endif

    {{-- #Hero --}}
    <div class="mb-6 flex flex-col lg:flex-row gap-6">
        {{-- #Hero Photo --}}
        <div class="flex-shrink-0">
            <div class="w-48 h-[149.8px] bg-[#f8f9fb] rounded-lg border border-[#e4e7ec] flex items-center justify-center overflow-hidden">
                <img src="{{ $interval->equipment->hero_photo ? Storage::url($interval->equipment->hero_photo) : asset('images/placeholders/placeholder.png') }}"
                    alt="Hero Photo for {{ $interval->equipment->name }}" class="w-full h-full object-cover">
            </div>
        </div>

        <div class="flex-1">
            <div>

                {{-- #Title --}}
                <div class="flex gap-2">

                    {{-- ##Category Icon --}}
                    <div class="w-8 h-8 bg-[#f9f5ff] border rounded-md flex items-center justify-center mr-1">
                        <a href="{{ route('maintenance.show', $interval->equipment->category) }}">
                            <i class="fa-solid hover:text-[#7e56d8] {{ $interval->equipment->category->icon }} text-[#6840c6]"></i>
                        </a>
                    </div>

                    {{-- ##Equipment Name --}}
                    <h1 class="text-2xl font-semibold text-[#0f1728] mb-2">{{ $interval->equipment->name ?? 'Unamed' }}</h1>

                </div>

                {{-- #Location --}}
                <div class="flex items-center gap-1 mb-4">
                    <i class="fa-solid fa-location-dot text-sm text-[#6840c6]"></i>
                    <span class="text-sm font-bold text-[#484f5d]">{{ $interval->equipment->deck->name }}</span>
                    <span class="text-sm text-[#667084]">/ {{ $interval->equipment->location->name }}</span>

                    {{-- ##Location Help --}}
                    <button id="location-description-modal-open" class="text-sm text-[#667084] hover:text-[#6840c6]">
                        <i class="fa-solid fa-circle-info"></i>
                    </button>
                </div>

                {{-- #Status Cards --}}
                <div class="flex flex-col sm:flex-row gap-3">

                    {{-- ##Status --}}
                    <div class="border rounded-lg p-4 min-w-[140px] {{ status_label_class($interval->equipment->status) }}">
                        <div class="flex items-center gap-2 mb-1">
                            <i class="{{ status_label_icon($interval->equipment->status) }}"></i>
                            <span class="text-sm font-medium">{{ $interval->equipment->status ?? 'Unknown Status' }}</span>
                        </div>
                        <p class="text-xs text-[#475466]">Commissioned
                            {{ $interval->equipment->in_service ? $interval->equipment->in_service->format('F j, Y') : '—' }}</p>
                    </div>

                    {{-- ##Compliance --}}
                    <div class="bg-[#fef3f2] border border-[#fecdca] rounded-lg p-4 min-w-[140px]">
                        <div class="flex items-center gap-2 mb-1">
                            <i class="text-[#b42318] fa-solid fa-triangle-exclamation"></i>
                            <span class="text-sm font-medium text-[#b42318]">## Overdue</span>
                        </div>
                        <p class="text-xs text-[#475466]">Test</p>
                    </div>

                    {{-- ##Scheduled --}}
                    <div class="bg-[#fffaeb] border border-[#fed7aa] rounded-lg p-4 min-w-[140px]">
                        <div class="flex items-center gap-2 mb-1">
                            <i class="text-[#dc6803] fa-solid fa-clock"></i>
                            <span class="text-sm font-medium text-[#dc6803]">## Upcoming</span>
                        </div>
                        <p class="text-xs text-[#475466]">Test</p>
                    </div>

                </div>
            </div>
        </div>
    </div>

    {{-- #Equipment Data --}}
    <div class="bg-white rounded-lg border border-[#e4e7ec] shadow-sm">
        <div class="flex items-center justify-between px-6 py-4 border-b border-[#e4e7ec]">
            <h2 class="text-lg font-semibold text-[#0f1728]">Quick Reference</h2>
        </div>

        {{-- #Data --}}
        <div class="p-6">
            <div class="space-y-4">
                <div class="grid grid-cols-4 gap-4">
                    <div>
                        <label class="block text-sm font-light text-[#667084] mb-1">Manufacturer</label>
                        <p class="text-sm font-bold text-[#344053]">{{ $interval->equipment->manufacturer ?? '—' }}</p>
                    </div>
                    <div>
                        <label class="block text-sm font-light text-[#667084] mb-1">Model / Part #</label>
                        <p class="text-sm font-bold text-[#344053]">{{ $interval->equipment->model ?? '—' }}</p>
                    </div>
                    <div>
                        <label class="block text-sm font-light text-[#667084] mb-1">Serial Number</label>
                        <p class="text-sm font-bold text-[#344053]">{{ $interval->equipment->serial_number ?? '—' }}</p>
                    </div>
                    <div>
                        <label class="block text-sm font-light text-[#667084] mb-1">Custom ID</label>
                        <p class="text-sm font-bold text-[#344053]">{{ $interval->equipment->internal_id ?? '—' }}</p>
                    </div>
                </div>
                <div class="grid grid-cols-4 gap-4">
                    <div>
                        <label class="block text-sm font-light text-[#667084] mb-1">Purchase Date</label>
                        <p class="text-sm font-bold text-[#344053]">
                            {{ $interval->equipment->purchase_date ? $interval->equipment->purchase_date->format('F j, Y') : '—' }}
                        </p>
                    </div>
                    <div>
                        <label class="block text-sm font-light text-[#667084] mb-1">Manufacturing Date</label>
                        <p class="text-sm font-bold text-[#344053]">
                            {{ $interval->equipment->manufacturing_date ? $interval->equipment->manufacturing_date->format('F j, Y') : '—' }}
                        </p>
                    </div>
                    <div>
                        <label class="block text-sm font-light text-[#667084] mb-1">In Service Date</label>
                        <p class="text-sm font-bold text-[#344053]">
                            {{ $interval->equipment->in_service ? $interval->equipment->in_service->format('F j, Y') : '—' }}
                        </p>
                    </div>
                    <div>
                        <label class="block text-sm font-light text-[#667084] mb-1">End of Life</label>
                        <p class="text-sm font-bold text-[#b42318]">
                            {{ $interval->equipment->expiry_date ? $interval->equipment->expiry_date->format('F j, Y') : 'Not Applicable' }}
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-1 gap-6 mb-6">
        <div class="bg-white rounded-lg border border-[#e4e7ec] shadow-sm mt-6">

            {{-- Overview --}}
            <div class="p-6 mb-6">

                {{-- #Title Block --}}
                <h1 class="text-2xl font-semibold text-[#0f1728] mb-2 ">
                    <span class="{{ frequency_label_class($interval->frequency) }} uppercase">{{ $interval->frequency }}</span>
                    {{ $interval->description }}
                </h1>

                {{-- #Details --}}
                <div class="bg-white rounded-lg border border-[#e4e7ec] shadow-sm">

                    {{-- ##Subtitle --}}
                    <div class="px-6 py-4 border-b border-[#e4e7ec]">
                        <h2 class="text-lg font-semibold text-[#0f1728]">Requirement Overview</h2>
                    </div>

                    {{-- ##Attributes --}}
                    <div class="p-6">
                        <div class="grid grid-cols-2 lg:grid-cols-2 gap-6">
                            <div>
                                <label class="block text-sm font-medium text-[#667084] mb-1">Interval Type</label>
                                <div class="flex items-center">
                                    <span
                                        class="px-2 py-1 text-xs font-medium rounded-full uppercase {{ frequency_label_class($interval->frequency) }}">{{ $interval->frequency }}</span>
                                </div>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-[#667084] mb-1">Facilitator</label>
                                <div class="flex items-center">
                                    <span
                                        class="px-2 py-1 text-xs font-medium rounded capitalize {{ facilitator_label_class($interval->facilitator) }}">{{ $interval->facilitator }}</span>
                                </div>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-[#667084] mb-1">Last Performed</label>
                                <p class="text-sm text-[#344053]">
                                    {!! last_completed_badge($interval->last_completed_at) !!}
                                </p>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-[#667084] mb-1">Next Due</label>
                                <p class="text-sm text-[#344053]">
                                    {!! next_due_badge($interval->next_due_date) !!}
                                </p>
                            </div>

                        </div>
                    </div>
                </div>
            </div>

            {{-- Work Orders --}}
            <div class="">
                <table class="w-full">
                    <thead class="bg-[#f8f9fb]">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-[#475466] uppercase tracking-wider border-b border-[#e4e7ec]">ID
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-[#475466] uppercase tracking-wider border-b border-[#e4e7ec]">Status
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-[#475466] uppercase tracking-wider border-b border-[#e4e7ec]">Tasks
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-[#475466] uppercase tracking-wider border-b border-[#e4e7ec]">Due
                                Date
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-[#475466] uppercase tracking-wider border-b border-[#e4e7ec]">
                                Assignee
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-[#475466] uppercase tracking-wider border-b border-[#e4e7ec]"></th>
                        </tr>
                    </thead>


                    <tbody class="divide-y divide-[#e4e7ec]">

                        {{-- #Work Orders Loop --}}
                        @forelse ($interval->workOrders as $workOrder)
                            <tr>
                                <td class="px-6 py-4 text-sm text-[#344053]">
                                    WO-{{ str_pad($workOrder->id, 6, '0', STR_PAD_LEFT) }}
                                </td>
                                <td class="px-6 py-4 text-sm">
                                    <span class="px-2 py-1 text-xs font-medium rounded-full">
                                        {!! status_badge($workOrder->status) !!}
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-sm text-[#344053]">
                                    {{ $workOrder->tasks->count() }}
                                </td>
                                <td class="px-6 py-4">
                                    {!! work_order_due_badge($workOrder) !!}
                                </td>
                                
                                {{-- ##Assignee --}}
                                <td class="text-sm text-[#344053]">
                                    <div class="relative inline-block text-left" id="assignee-dropdown-{{ $workOrder->id }}">
                                        {{-- Trigger --}}
                                        <button onclick="toggleAssigneeDropdown({{ $workOrder->id }})"
                                            class="flex items-center gap-2 px-3 py-2 border border-[#e4e7ec] rounded-lg shadow-sm bg-white text-sm font-medium text-[#344053]">
                                            @if ($workOrder->assignee)
                                                <img src="{{ $workOrder->assignee->profile_pic ? Storage::url($workOrder->assignee->profile_pic) : asset('images/placeholders/user.png') }}"
                                                    class="w-5 h-5 rounded-full" alt="Avatar">
                                                {{ $workOrder->assignee->first_name }} {{ $workOrder->assignee->last_name }}
                                            @else
                                                <img src="{{ asset('images/placeholders/user.png') }}" class="w-5 h-5 rounded-full"
                                                    alt="Avatar">
                                                Unassigned
                                            @endif
                                            <i class="fa-solid fa-chevron-down ml-1 text-xs"></i>
                                        </button>

                                        {{-- Dropdown --}}
                                        <div id="assignee-options-{{ $workOrder->id }}"
                                            class="hidden absolute z-10 mt-2 w-48 bg-white rounded-lg shadow-lg border border-[#e4e7ec]">
                                            <ul class="py-2">
                                                @foreach ($users->sortBy('first_name') as $user)
                                                    <li>
                                                        <button onclick="assignUser({{ $workOrder->id }}, {{ $user->id }})"
                                                            class="flex items-center w-full px-4 py-2 text-sm text-[#344053] hover:bg-[#f9fafb]">
                                                            <img src="{{ $user->profile_pic ? Storage::url($user->profile_pic) : asset('images/placeholders/user.png') }}"
                                                                class="w-5 h-5 rounded-full mr-2" alt="Avatar">
                                                            {{ $user->first_name }} {{ $user->last_name }}
                                                        </button>
                                                    </li>
                                                @endforeach
                                                <li>
                                                    <button onclick="assignUser({{ $workOrder->id }}, null)"
                                                        class="flex items-center w-full px-4 py-2 text-sm text-[#b42318] hover:bg-[#fef3f2]">
                                                        <i class="fa-solid fa-xmark mr-2"></i> Clear
                                                    </button>
                                                </li>
                                            </ul>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 text-sm font-medium">
                                    <button onclick="window.location='{{ route('work-orders.show', $workOrder) }}'"
                                        class="p-2 text-[#667084] hover:text-[#344053] hover:bg-[#f8f9fb] rounded-lg transition-colors">
                                        <i class="fa-solid fa-eye"></i>
                                    </button>
                                </td>
                            </tr>

                        @empty

                            <tr>
                                <td colspan="6" class="px-6 py-4 text-sm text-[#667084] italic">No work orders yet for this interval.</td>
                            </tr>
                        @endforelse

                    </tbody>
                </table>
            </div>


        </div>
    </div>


    {{-- #Location Information Modal --}}
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const openBtn = document.getElementById('location-description-modal-open');
            const closeBtn = document.getElementById('location-description-modal-close');
            const overlay = document.getElementById('location-description-modal-overlay');
            const modal = document.getElementById('location-description-modal');

            // Show modal
            function showModal() {
                overlay.classList.remove('hidden');
                modal.classList.remove('hidden');
            }

            // Hide modal
            function closeModal() {
                overlay.classList.add('hidden');
                modal.classList.add('hidden');
            }

            // Wire up events
            openBtn.addEventListener('click', showModal);
            closeBtn.addEventListener('click', closeModal);
            overlay.addEventListener('click', closeModal);

            // Also close when clicking outside the content (i.e. on the modal wrapper itself)
            modal.addEventListener('click', (e) => {
                if (e.target === modal) {
                    closeModal();
                }
            });

            // Close on ESC
            document.addEventListener('keydown', (e) => {
                if (e.key === 'Escape' && !modal.classList.contains('hidden')) {
                    closeModal();
                }
            });
        });
    </script>

    {{-- AJAX User Assignment Update --}}
    <script>
        function toggleAssigneeDropdown(id) {
            document.getElementById(`assignee-options-${id}`).classList.toggle('hidden');
        }

        function assignUser(workOrderId, userId) {
            fetch(`/work-orders/${workOrderId}/assign`, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Content-Type': 'application/json',
                    },
                    body: JSON.stringify({
                        assigned_to: userId
                    }),
                })
                .then(response => {
                    if (!response.ok) throw new Error('Failed to assign user.');
                    location.reload();
                })
                .catch(error => {
                    console.error(error);
                    alert('There was an error assigning this user.');
                });
        }
    </script>
    </div>
@endsection
