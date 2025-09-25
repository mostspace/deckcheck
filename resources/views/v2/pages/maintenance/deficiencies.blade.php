@extends('v2.layouts.app')

@section('title', 'Deficiencies')

@section('content')

    {{-- Enhanced Maintenance Header --}}
    @include('v2.components.maintenance.header', [
        'activeTab' => 'deficiencies',
        'breadcrumbs' => [
            ['label' => 'Maintenance', 'icon' => asset('assets/media/icons/sidebar-solid-wrench-scredriver.svg')],
            ['label' => 'Deficiencies']
        ],
        'actions' => [
            [
                'type' => 'link',
                'label' => 'Create Deficiency',
                'url' => '#', // Add route when available
                'icon' => 'fas fa-plus',
                'class' => 'bg-primary-500 text-slate-800 hover:bg-primary-600'
            ]
        ]
    ])

    <div class="px-3 sm:px-6 lg:px-8 py-4 sm:py-6">
        {{-- System Messages --}}
        @if (session('success'))
            <div class="mb-6 p-4 bg-green-100 border border-green-300 text-green-800 rounded-lg text-sm">
                {{ session('success') }}
            </div>
        @endif

        {{-- Header --}}
        <div class="mb-6">
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                <div>
                    <h1 class="text-2xl font-semibold text-[#0f1728]">Deficiencies</h1>
                    <p class="text-[#475466]">Track and manage equipment deficiencies and issues.</p>
                </div>
            </div>
        </div>

        @include('v2.sections.crew.maintenance.deficiencies.dashboard')
        @include('v2.sections.crew.maintenance.deficiencies.open-deficiencies')
    </div>

    {{-- Include Chart.js for deficiency charts --}}
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
                        const deficiencyTable = document.querySelector('table tbody');
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
                        const table = document.querySelector('table');
                        if (table) {
                            table.parentNode.insertBefore(filterMessage, table);
                        }
                    }

                    // Clear filter
                    window.clearFilter = function() {
                        const deficiencyTable = document.querySelector('table tbody');
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
    @endpush

@endsection
