@extends('layouts.app')

@section('title', 'Deficiencies')

@section('content')

    {{-- #System Messages --}}
    @if (session('success'))
        <div class="mb-6 rounded-lg border border-green-300 bg-green-100 p-4 text-sm text-green-800">
            {{ session('success') }}
        </div>
    @endif

    {{-- #Header --}}
    <div class="mb-6">
        <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <h1 class="text-2xl font-semibold text-[#0f1728]">Maintenance Index</h1>
                <p class="text-[#475466]">Overview of equipment maintenance requirements.</p>
            </div>
        </div>
    </div>

    {{-- #Dashboard --}}
    <div class="mb-8" id="deficiencies-hero">
        <div class="grid grid-cols-1 gap-6 lg:grid-cols-3">

            {{-- ##Chart --}}
            <div class="lg:col-span-2">
                <div class="rounded-lg border border-[#e4e7ec] bg-white p-6 shadow-sm">
                    <div class="mb-6">
                        <div class="mb-2 flex items-center justify-between">
                            <div>
                                <h2 class="text-lg font-semibold text-[#0f1728]">Deficiency Age Distribution</h2>
                                <p class="text-sm text-[#475466]">Current open deficiencies grouped by age</p>
                            </div>
                            <div class="text-right">
                                <div class="text-3xl font-bold text-[#0f1728]">{{ array_sum($chartData['data']) }}</div>
                                <div class="text-sm text-[#475466]">Total Open</div>
                                <div class="mt-1 text-xs text-[#667084]">Updated {{ now()->format('M j, g:i A') }}</div>
                            </div>
                        </div>
                    </div>
                    <div class="relative flex h-[300px] items-center justify-center">
                        <canvas id="deficiencyPieChart" width="300" height="300"></canvas>
                        @if (array_sum($chartData['data']) > 0)
                            <div class="absolute inset-0 flex items-center justify-center">
                                <div class="text-center">
                                    <div class="text-2xl font-semibold text-[#0f1728]">{{ array_sum($chartData['data']) }}
                                    </div>
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
                <div class="cursor-pointer rounded-lg border border-[#e4e7ec] bg-white p-6 shadow-sm transition-shadow duration-200 hover:shadow-md"
                    onclick="filterByAge('under_30')">
                    <div class="mb-3 flex items-center justify-between">
                        <h3 class="text-sm font-medium text-[#344053]">&lt; 30 Days</h3>
                        <div class="h-3 w-3 rounded-full bg-[#12b76a]"></div>
                    </div>
                    <div class="mb-1 text-2xl font-semibold text-[#0f1728]">{{ $ageDistribution['under_30_days'] }}</div>
                    <div class="text-sm text-[#475466]">Recent deficiencies</div>
                </div>

                {{-- ### 30-90 Days --}}
                <div class="cursor-pointer rounded-lg border border-[#e4e7ec] bg-white p-6 shadow-sm transition-shadow duration-200 hover:shadow-md"
                    onclick="filterByAge('30_to_90')">
                    <div class="mb-3 flex items-center justify-between">
                        <h3 class="text-sm font-medium text-[#344053]">30-90 Days</h3>
                        <div class="h-3 w-3 rounded-full bg-[#f79009]"></div>
                    </div>
                    <div class="mb-1 text-2xl font-semibold text-[#0f1728]">{{ $ageDistribution['30_to_90_days'] }}</div>
                    <div class="text-sm text-[#475466]">Aging deficiencies</div>
                </div>

                {{-- ### >90 Days --}}
                <div class="cursor-pointer rounded-lg border border-[#e4e7ec] bg-white p-6 shadow-sm transition-shadow duration-200 hover:shadow-md"
                    onclick="filterByAge('over_90')">
                    <div class="mb-3 flex items-center justify-between">
                        <h3 class="text-sm font-medium text-[#344053]">&gt; 90 Days</h3>
                        <div class="h-3 w-3 rounded-full bg-[#f04438]"></div>
                    </div>
                    <div class="mb-1 text-2xl font-semibold text-[#0f1728]">{{ $ageDistribution['over_90_days'] }}</div>
                    <div class="text-sm text-[#475466]">Critical deficiencies</div>
                </div>
            </div>
        </div>
    </div>

    {{-- #Open Deficiencies --}}
    <div class="overflow-x-auto rounded-lg border border-[#e4e7ec] bg-white shadow-sm">
        <table class="w-full text-sm">
            <thead class="border-b border-[#e4e7ec] bg-[#f8f9fb] text-xs uppercase text-[#475466]">
                <tr>
                    <th class="px-6 py-3 text-left">ID</th>
                    <th class="px-6 py-3 text-left">Equipment</th>
                    <th class="px-6 py-3 text-left">Subject</th>
                    <th class="px-6 py-3 text-left">Priority</th>
                    <th class="px-6 py-3 text-left">Status</th>
                    <th class="px-6 py-3 text-left">Opened</th>
                    <th class="px-6 py-3 text-left">Actions</th>
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
                                <a href="{{ route('deficiencies.show', $deficiency) }}"
                                    class="text-sm font-medium text-[#6941c6] hover:underline">
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
                                <div class="mb-4 flex h-16 w-16 items-center justify-center rounded-full bg-[#f8f9fb]">
                                    <i class="fa-solid fa-clipboard-list text-2xl text-[#c7c9d1]"></i>
                                </div>
                                <div class="mb-2 text-lg font-medium text-[#344053]">No Deficiencies Found</div>
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
            @if (array_sum($chartData['data']) > 0)
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
                        const daysOpen = Math.floor((Date.now() - date.getTime()) / (1000 * 60 * 60 *
                            24));

                        let shouldShow = false;
                        switch (ageRange) {
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
                switch (ageRange) {
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
                filterMessage.className =
                    'filter-message mb-4 p-3 bg-blue-50 border border-blue-200 text-blue-800 rounded-lg text-sm flex items-center justify-between';
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
