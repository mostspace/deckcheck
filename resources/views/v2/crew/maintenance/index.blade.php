@extends('v2.layouts.app')

@section('title', 'Maintenance Index')

@section('content')

    @php
        $intervalTypes = [
            'Daily',
            'Bi-Weekly',
            'Weekly',
            'Monthly',
            'Quarterly',
            'Bi-Annually',
            'Annual',
            '2-Yearly',
            '3-Yearly',
            '5-Yearly',
            '6-Yearly',
            '10-Yearly',
            '12-Yearly',
        ];
    @endphp

    @include('v2.components.navigation.page-header', [
        'tabs' => [
            ['id' => 'index', 'label' => 'Index', 'icon' => 'tab-index.svg', 'active' => true],
            ['id' => 'schedule', 'label' => 'Schedule', 'icon' => 'tab-schedule.svg', 'active' => false],
            ['id' => 'deficiencies', 'label' => 'Deficiencies', 'icon' => 'tab-deficiencies.svg', 'active' => false]
        ]
    ])
    
    @include('v2.components.navigation.sub-header', [
        'pageName' => 'Maintenance',
        'pageIcon' => asset('assets/media/icons/sidebar-solid-wrench-scredriver.svg'),
        'activeTab' => 'Index'
    ])

    <div class="px-3 sm:px-6 lg:px-8 py-4 sm:py-6">

        {{-- Index Tab Panel --}}
        <div id="panel-index" class="tab-panel" role="tabpanel" aria-labelledby="tab-index">
            <div class="mb-6">
                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                    <div>
                        <h1 class="text-2xl font-semibold text-[#0f1728]">Maintenance Index</h1>
                        <p class="text-[#475466]">Overview of equipment maintenance requirements.</p>
                    </div>
                </div>
            </div>
        
            @include ('components.maintenance.stat-cards')
        
            {{-- Maintenance Index --}}
            <div id="maintenance-table" class="bg-white rounded-lg border border-[#e4e7ec] shadow-sm overflow-hidden">
        
                {{-- Header & Controls --}}
                <div class="flex items-center justify-between px-6 py-4 border-b border-[#e4e7ec]">
                    <h2 class="text-lg font-semibold text-[#0f1728]">Maintenance Requirements</h2>
                    <div class="flex items-center space-x-2">
        
                        {{-- Search --}}
                        <div class="relative">
                            <input id="category-search" type="text" placeholder="Search by name..."
                                class="pl-9 pr-4 py-2 border border-[#e4e7ec] rounded-lg text-sm focus:outline-none focus:ring-1 focus:ring-[#6840c6] focus:border-[#6840c6]">
                            <i class="fa-solid fa-search absolute left-3 top-1/2 transform -translate-y-1/2 text-[#667084]"></i>
                        </div>
        
                        <button onclick="window.location='{{ route('maintenance.create') }}'"
                            class="px-3 py-2 bg-[#6840c6] text-white rounded-lg text-sm hover:bg-[#5a35a8] flex items-center">
                            <i class="fa-solid fa-plus mr-2"></i>
                            Add New
                        </button>
        
                    </div>
                </div>
        
                {{-- Table --}}
                <div class="overflow-x-auto">
                    <table class="w-full">
        
                        <thead>
                            <tr class="bg-[#f8f9fb] text-[#475466] text-xs uppercase">
                                <th class="px-6 py-3 text-left font-medium">
                                    <button data-sort-key="name" type="button"
                                        class="sort-button flex items-center text-xs font-medium text-[#6840c6] hover:text-[#7e56d8] uppercase tracking-wider">
                                        Category
                                        <i class="fa-solid fa-sort ml-1 text-xs transition-colors text-[#475466]"></i>
                                    </button>
                                </th>
        
                                <th class="px-6 py-3 text-left font-medium">
                                    <button data-sort-key="type" type="button"
                                        class="sort-button flex items-center text-xs font-medium text-[#6840c6] hover:text-[#7e56d8] uppercase tracking-wider">
                                        Type
                                        <i class="fa-solid fa-sort ml-1 text-xs transition-colors text-[#475466]"></i>
                                    </button>
                                </th>
        
                                <th class="px-6 py-3 text-left font-medium">Affected Equipment</th>
                                <th class="px-6 py-3 text-left font-medium">Intervals</th>
                                <th class="px-6 py-3 text-left font-medium">Actions</th>
                            </tr>
                        </thead>
        
                        {{-- Category Loop --}}
                        <tbody class="divide-y divide-[#e4e7ec]" id="category-list">
        
                            @forelse ($categories as $category)
        
                                <tr class="hover:bg-[#f9f5ff]" data-name="{{ strtolower($category->name) }}" data-type="{{ strtolower($category->type) }}">
                                    <td class="px-6 py-4">
                                        <div class="flex items-center">
                                            <div class="flex items-center name">
                                                <div class="w-8 h-8 bg-[#f9f5ff] rounded-md flex items-center justify-center mr-3">
                                                    <i class="text-[#6840c6] fa-solid {{ $category->icon }}"></i>
                                                </div>
                                                <span class="text-sm text-[#344053]">{{ $category->name ?? '—' }}</span>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 text-sm font-bold text-[#344053]">{{ $category->type }}</td>
                                    <td class="px-6 py-4">
                                        <span class="text-sm text-[#6840c6]">{{ $category->equipment_count }}</span>
                                    </td>
        
                                    {{-- Interval Requirements --}}
                                    <td class="px-6 py-4">
        
                                        @foreach ($intervalTypes as $freq)
                                            @php
                                                $count = $category->intervals->where('interval', $freq)->count();
                                            @endphp
        
                                            @if ($count > 0)
                                                <span
                                                    class="inline-flex items-center px-2 py-1 text-xs font-medium rounded-full {{ frequency_label_class($freq) }}">
                                                    {{ $freq }}: {{ $count }}
                                                </span>
                                            @endif
                                        @endforeach
                                    </td>
        
                                    {{-- Actions --}}
                                    <td class="px-6 py-4">
                                        <div class="flex space-x-2">
        
                                            {{-- View Category --}}
                                            <button onclick="window.location='{{ route('maintenance.show', $category) }}'"
                                                class="p-2 text-sm text-[#667084] hover:text-[#344053] hover:bg-[#f8f9fb] rounded">
                                                <i class="fa-solid fa-eye"></i>
                                            </button>
        
                                        </div>
                                    </td>
                                </tr>
        
                            @empty
                                <tr>
                                    <td colspan="5" class="px-6 py-4 text-center text-sm text-[#667084]">No requirements defined for this vessel.</td>
                                </tr>
                            @endforelse
        
                        </tbody>
        
                    </table>
                </div>
        
            </div>
        </div>

        {{-- Schedule Tab Panel --}}
        <div id="panel-schedule" class="tab-panel hidden" role="tabpanel" aria-labelledby="tab-schedule">
            @include('v2.crew.maintenance.schedule.index')
        </div>

        {{-- Deficiencies Tab Panel --}}
        <div id="panel-deficiencies" class="tab-panel hidden" role="tabpanel" aria-labelledby="tab-deficiencies">
            @include('v2.crew.maintenance.deficiencies.index')
        </div>
    </div>

    {{-- Search Filtering --}}
    <script>
        document.addEventListener('DOMContentLoaded', function() {

            // Search filtering logic
            const searchInput = document.getElementById('category-search');
            const rows = document.querySelectorAll('#category-list tr');

            searchInput.addEventListener('input', function() {
                const query = this.value.toLowerCase();

                rows.forEach(row => {
                    const category = row.children[0]?.textContent.toLowerCase() || '';

                    const match = category.includes(query);
                    row.style.display = match ? '' : 'none';
                });
            });
        });
    </script>

    {{-- Column Sort --}}
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const buttons = document.querySelectorAll('.sort-button');
            const tableBody = document.getElementById('category-list');
            let currentSortKey = null;
            let ascending = true;

            buttons.forEach(button => {
                button.addEventListener('click', () => {
                    const sortKey = button.dataset.sortKey;
                    ascending = (currentSortKey === sortKey) ? !ascending : true;
                    currentSortKey = sortKey;

                    const rows = Array.from(tableBody.querySelectorAll('tr'));

                    rows.sort((a, b) => {
                        let aVal = a.dataset[sortKey] || '';
                        let bVal = b.dataset[sortKey] || '';

                        aVal = aVal.toLowerCase();
                        bVal = bVal.toLowerCase();

                        return (aVal > bVal ? 1 : aVal < bVal ? -1 : 0) * (ascending ? 1 : -1);
                    });

                    rows.forEach(row => tableBody.appendChild(row));

                    // Reset all sort icons
                    buttons.forEach(btn => {
                        const icon = btn.querySelector('svg');
                        if (icon) {
                            icon.classList.remove('fa-arrow-up-short-wide', 'fa-arrow-down-wide-short');
                            icon.classList.add('fa-sort');
                            icon.classList.remove('text-[#6840c6]');
                            icon.classList.add('text-[#475466]');
                        }
                    });

                    // Update clicked button's icon
                    const icon = button.querySelector('svg');
                    if (icon) {
                        icon.classList.remove('fa-sort', 'text-[#475466]');
                        icon.classList.add(
                            ascending ? 'fa-arrow-up-short-wide' : 'fa-arrow-down-wide-short',
                            'text-[#6840c6]'
                        );
                    } else {
                        console.warn('Icon not found in clicked sort button:', button);
                    }
                });
            });
        });
    </script>
@endsection

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const ctx = document.getElementById('deficiencyPieChart');
            if (ctx) {
                const chartCtx = ctx.getContext('2d');
                let chart;
                
                // Only create chart if there's data
                @if(isset($chartData) && array_sum($chartData['data']) > 0)
                    chart = new Chart(chartCtx, {
                        type: 'doughnut',
                        data: {
                            labels: @json($chartData['labels']),
                            datasets: [{
                                data: @json($chartData['data']),
                                backgroundColor: @json($chartData['colors']),
                                borderWidth: 0,
                                cutout: '70%'
                            }]
                        },
                        options: {
                            responsive: true,
                            maintainAspectRatio: false,
                            plugins: {
                                legend: {
                                    display: false
                                },
                                tooltip: {
                                    callbacks: {
                                        label: function(context) {
                                            const total = context.dataset.data.reduce((a, b) => a + b, 0);
                                            const percentage = ((context.parsed / total) * 100).toFixed(1);
                                            return `${context.label}: ${context.parsed} (${percentage}%)`;
                                        }
                                    }
                                }
                            },
                            onClick: function(event, elements) {
                                if (elements.length > 0) {
                                    const index = elements[0].index;
                                    const ageRanges = ['under_30', '30_to_90', 'over_90'];
                                    filterByAge(ageRanges[index]);
                                }
                            }
                        }
                    });
                @endif

                // Filter deficiencies by age
                window.filterByAge = function(ageRange) {
                    const deficiencyTable = document.querySelector('#panel-deficiencies table tbody');
                    if (!deficiencyTable) return;
                    
                    const rows = deficiencyTable.querySelectorAll('tr');
                    let count = 0;
                    
                    rows.forEach(row => {
                        const dateCell = row.querySelector('td:nth-child(6)'); // Opened column
                        if (dateCell) {
                            const dateText = dateCell.textContent.trim();
                            const date = new Date(dateText);
                            const daysOpen = Math.floor((Date.now() - date.getTime()) / (1000 * 60 * 60 * 24));
                            
                            let shouldShow = false;
                            switch(ageRange) {
                                case 'under_30':
                                    shouldShow = daysOpen < 30;
                                    break;
                                case '30_to_90':
                                    shouldShow = daysOpen >= 30 && daysOpen <= 90;
                                    break;
                                case 'over_90':
                                    shouldShow = daysOpen > 90;
                                    break;
                            }
                            
                            if (shouldShow) {
                                row.style.display = '';
                                count++;
                            } else {
                                row.style.display = 'none';
                            }
                        }
                    });
                    
                    // Show filter message
                    showFilterMessage(ageRange, count);
                };

                // Show filter message
                function showFilterMessage(ageRange, count) {
                    let message = '';
                    switch(ageRange) {
                        case 'under_30':
                            message = `Showing ${count} recent deficiencies (< 30 days)`;
                            break;
                        case '30_to_90':
                            message = `Showing ${count} aging deficiencies (30-90 days)`;
                            break;
                        case 'over_90':
                            message = `Showing ${count} critical deficiencies (> 90 days)`;
                            break;
                    }
                    
                    // Remove existing filter message
                    const existingMessage = document.querySelector('.filter-message');
                    if (existingMessage) {
                        existingMessage.remove();
                    }
                    
                    // Add new filter message
                    const filterMessage = document.createElement('div');
                    filterMessage.className = 'filter-message mb-4 p-3 bg-blue-50 border border-blue-200 text-blue-800 rounded-lg text-sm flex items-center justify-between';
                    filterMessage.innerHTML = `
                        <span>${message}</span>
                        <button onclick="clearFilter()" class="text-blue-600 hover:text-blue-800 font-medium">Clear Filter</button>
                    `;
                    
                    // Insert before the table
                    const table = document.querySelector('#panel-deficiencies table');
                    if (table) {
                        table.parentNode.insertBefore(filterMessage, table);
                    }
                }

                // Clear filter
                window.clearFilter = function() {
                    const deficiencyTable = document.querySelector('#panel-deficiencies table tbody');
                    if (!deficiencyTable) return;
                    
                    const rows = deficiencyTable.querySelectorAll('tr');
                    rows.forEach(row => {
                        row.style.display = '';
                    });
                    
                    const filterMessage = document.querySelector('.filter-message');
                    if (filterMessage) {
                        filterMessage.remove();
                    }
                 };
             }
         });
     </script>

     {{-- Schedule JavaScript --}}
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
                             'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
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