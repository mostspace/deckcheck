@extends('v2.layouts.app')

@section('title', 'Work Order')

@section('content')

    @include('v2.components.nav.header-routing', [
        'activeTab' => 'manifest',
        'context' => 'maintenance',
        'enableRefererBreadcrumbs' => true,
        'refererContext' => ['interval' => $workOrder->equipmentInterval]
    ])

    <div class="px-3 py-4 sm:px-6 sm:py-6 lg:px-8">

        @php
            $assignedUser = $workOrder->assignee;
            $users = $workOrder->equipmentInterval->equipment->vessel->users;
        @endphp

        {{-- #System Messages --}}
        @if (session('error'))
            <div class="mb-4 rounded bg-red-100 p-3 text-sm text-red-700">
                {{ session('error') }}
            </div>
        @endif

        {{-- #Main --}}
        <div class="mb-6" id="record-details">
            <div class="rounded-lg border border-[#e4e7ec] bg-white shadow-sm">

                {{-- ##Work Order Summary --}}

                @include('components.maintenance.work-order-summary', [
                    'workOrder' => $workOrder,
                    'withButton' => true
                ])
            </div>
        </div>

    </div>
    </div>

    {{-- Task Status Handling --}}
    <script>
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
                .then(response => {
                    if (!response.ok) throw new Error('Network response was not ok.');
                    return response.json();
                })
                .then(data => {
                    if (data.success) {
                        const container = button.closest('.flex');
                        const completeBtn = container.querySelector('button[data-status="completed"]');
                        const flagBtn = container.querySelector('button[data-status="flagged"]');

                        // Reset both buttons
                        completeBtn.className =
                            'px-3 py-1.5 text-sm font-medium rounded-lg bg-[#ebfdf2] text-[#027947] hover:bg-[#d0fadf]';
                        flagBtn.className =
                            'px-3 py-1.5 text-sm font-medium rounded-lg bg-[#fef3f2] text-[#b42318] hover:bg-[#fee4e2]';
                        completeBtn.disabled = false;
                        flagBtn.disabled = false;

                        // Apply selected styling
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
                        alert('Update failed.');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('There was a problem updating the task.');
                });
        }


        function checkWorkOrderCompletable() {
            const taskCards = document.querySelectorAll('[data-task-id]');
            const completeButton = document.getElementById('complete-work-order-button');

            const allResolved = Array.from(taskCards).every(card => {
                const btnContainer = card.closest('.flex');
                return btnContainer.querySelector(
                    'button[disabled]'); // At least one button in each group is disabled
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

        // Run on page load
        document.addEventListener('DOMContentLoaded', checkWorkOrderCompletable);
    </script>

    {{-- AJAX User Assignment Update --}}
    <script>
        function toggleAssigneeDropdown() {
            const dropdown = document.getElementById('assignee-options');
            dropdown.classList.toggle('hidden');
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
                .then(() => window.location.reload())
                .catch(error => {
                    console.error('Error assigning user:', error);
                    alert('Failed to assign user.');
                });
        }
    </script>

    {{-- Toggle Instructions on Completed View --}}
    <script>
        function toggleInstructions(id) {
            const element = document.getElementById(`instructions-${id}`);
            if (element) {
                element.classList.toggle('hidden');
            }
        }
    </script>

    {{-- Open Scheduled Task --}}
    <script>
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
                        window.location.reload();
                    } else {
                        alert('Failed to open work order.');
                    }
                })
                .catch(error => {
                    console.error(error);
                    alert('There was a problem updating the work order.');
                });
        }
    </script>
    </div>
@endsection
