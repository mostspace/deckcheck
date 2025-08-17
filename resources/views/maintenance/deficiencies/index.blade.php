@extends('layouts.app')

@section('title', 'Deficiencies')

@section('content')

    {{-- #System Messages --}}
    @if (session('success'))
        <div class="mb-6 p-4 bg-green-100 border border-green-300 text-green-800 rounded-lg text-sm">
            {{ session('success') }}
        </div>
    @endif

    {{-- #Header --}}
    <div class="mb-6">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <h1 class="text-2xl font-semibold text-[#0f1728]">Maintenance Index</h1>
                <p class="text-[#475466]">Overview of equipment maintenance requirements.</p>
            </div>
        </div>
    </div>

    {{-- #Dashboard --}}
    <div class="mb-8" id="deficiencies-hero">
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

            {{-- ##Chart --}}
            <div class="lg:col-span-2">
                <div class="bg-white rounded-lg border border-[#e4e7ec] shadow-sm p-6">
                    <div class="mb-6">
                        <div class="flex items-center justify-between mb-2">
                            <div>
                                <h2 class="text-lg font-semibold text-[#0f1728]">Deficiency Age Distribution</h2>
                                <p class="text-sm text-[#475466]">Current open deficiencies grouped by age</p>
                            </div>
                            <div class="text-right">
                                <div class="text-3xl font-bold text-[#0f1728]">{{ array_sum($chartData['data']) }}</div>
                                <div class="text-sm text-[#475466]">Total Open</div>
                                <div class="text-xs text-[#667084] mt-1">Updated {{ now()->format('M j, g:i A') }}</div>
                            </div>
                        </div>
                    </div>
                    <div class="h-[300px] relative flex items-center justify-center">
                        <canvas id="deficiencyPieChart" width="300" height="300"></canvas>
                        @if(array_sum($chartData['data']) > 0)
                            <div class="absolute inset-0 flex items-center justify-center">
                                <div class="text-center">
                                    <div class="text-2xl font-semibold text-[#0f1728]">{{ array_sum($chartData['data']) }}</div>
                                    <div class="text-sm text-[#475466]">Total Open</div>
                                </div>
                            </div>
                        @else
                            <div class="text-center text-[#667084]">
                                <div class="text-lg font-medium">No Open Deficiencies</div>
                                <div class="text-sm">All caught up!</div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            {{-- ##Cards --}}
            <div class="space-y-4">

                {{-- ### <30 Days --}}
                <div class="bg-white rounded-lg border border-[#e4e7ec] shadow-sm p-6 hover:shadow-md transition-shadow duration-200 cursor-pointer" onclick="filterByAge('under_30')">
                    <div class="flex items-center justify-between mb-3">
                        <h3 class="text-sm font-medium text-[#344053]">&lt; 30 Days</h3>
                        <div class="w-3 h-3 bg-[#12b76a] rounded-full"></div>
                    </div>
                    <div class="text-2xl font-semibold text-[#0f1728] mb-1">{{ $ageDistribution['under_30_days'] }}</div>
                    <div class="text-sm text-[#475466]">Recent deficiencies</div>
                </div>

                {{-- ### 30-90 Days --}}
                <div class="bg-white rounded-lg border border-[#e4e7ec] shadow-sm p-6 hover:shadow-md transition-shadow duration-200 cursor-pointer" onclick="filterByAge('30_to_90')">
                    <div class="flex items-center justify-between mb-3">
                        <h3 class="text-sm font-medium text-[#344053]">30-90 Days</h3>
                        <div class="w-3 h-3 bg-[#f79009] rounded-full"></div>
                    </div>
                    <div class="text-2xl font-semibold text-[#0f1728] mb-1">{{ $ageDistribution['30_to_90_days'] }}</div>
                    <div class="text-sm text-[#475466]">Aging deficiencies</div>
                </div>

                {{-- ### >90 Days --}}
                <div class="bg-white rounded-lg border border-[#e4e7ec] shadow-sm p-6 hover:shadow-md transition-shadow duration-200 cursor-pointer" onclick="filterByAge('over_90')">
                    <div class="flex items-center justify-between mb-3">
                        <h3 class="text-sm font-medium text-[#344053]">&gt; 90 Days</h3>
                        <div class="w-3 h-3 bg-[#f04438] rounded-full"></div>
                    </div>
                    <div class="text-2xl font-semibold text-[#0f1728] mb-1">{{ $ageDistribution['over_90_days'] }}</div>
                    <div class="text-sm text-[#475466]">Critical deficiencies</div>
                </div>
            </div>
        </div>
    </div>

    {{-- #Open Deficiencies --}}
    <div class="bg-white rounded-lg border border-[#e4e7ec] shadow-sm overflow-x-auto">
        <table class="w-full text-sm">
            <thead class="bg-[#f8f9fb] text-[#475466] uppercase text-xs border-b border-[#e4e7ec]">
                <tr>
                    <th class="text-left px-6 py-3">ID</th>
                    <th class="text-left px-6 py-3">Equipment</th>
                    <th class="text-left px-6 py-3">Subject</th>
                    <th class="text-left px-6 py-3">Priority</th>
                    <th class="text-left px-6 py-3">Status</th>
                    <th class="text-left px-6 py-3">Opened</th>
                    <th class="text-left px-6 py-3">Actions</th>
                </tr>
            </thead>

            <tbody class="text-[#344053]">

                {{-- ##Deficiencies Conditional --}}
                @if ($deficiencies->count())
                    {{-- ###Deficiencies Loop --}}
                    @foreach ($deficiencies as $deficiency)
                        <tr class="border-b border-[#e4e7ec] hover:bg-[#f9fafb]">
                            <td class="px-6 py-4 font-mono text-xs text-[#6941c6]">#{{ $deficiency->display_id }}</td>
                            <td class="px-6 py-4">
                                {{ $deficiency->equipment->name ?? '—' }}
                            </td>
                            <td class="px-6 py-4">
                                {{ $deficiency->subject }}
                            </td>
                            <td class="px-6 py-4">
                                {!! priority_badge($deficiency->priority) !!}
                            </td>
                            <td class="px-6 py-4">
                                {!! status_badge($deficiency->status) !!}
                            </td>
                            <td class="px-6 py-4 text-xs text-[#667084]">
                                {{ $deficiency->created_at->format('M j, Y') }}
                            </td>
                            <td class="px-6 py-4">
                                <a href="{{ route('deficiencies.show', $deficiency) }}" class="text-sm font-medium text-[#6941c6] hover:underline">
                                    View →
                                </a>
                            </td>
                        </tr>
                    @endforeach
                @else
                    {{-- ###No Deficiencies Message --}}
                    <tr>
                        <td colspan="7" class="px-6 py-12 text-center">
                            <div class="flex flex-col items-center justify-center text-[#667084]">
                                <div class="w-16 h-16 bg-[#f8f9fb] rounded-full flex items-center justify-center mb-4">
                                    <i class="fa-solid fa-clipboard-list text-2xl text-[#c7c9d1]"></i>
                                </div>
                                <div class="text-lg font-medium text-[#344053] mb-2">No Deficiencies Found</div>
                                <div class="text-sm">No deficiencies have been logged yet for this vessel.</div>
                            </div>
                        </td>
                    </tr>
                @endif

            </tbody>
        </table>
    </div>

@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    const ctx = document.getElementById('deficiencyPieChart').getContext('2d');
    let chart;
    
    // Only create chart if there's data
    @if(array_sum($chartData['data']) > 0)
        chart = new Chart(ctx, {
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
        const rows = document.querySelectorAll('tbody tr');
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
        table.parentNode.insertBefore(filterMessage, table);
    }

    // Clear filter
    window.clearFilter = function() {
        const rows = document.querySelectorAll('tbody tr');
        rows.forEach(row => {
            row.style.display = '';
        });
        
        const filterMessage = document.querySelector('.filter-message');
        if (filterMessage) {
            filterMessage.remove();
        }
    };
});
</script>
@endpush
