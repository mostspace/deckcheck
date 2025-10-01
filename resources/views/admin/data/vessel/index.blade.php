@extends('layouts.admin')

@section('title', 'Vessels')

@section('content')
    <main id="main-vessels">
        <!-- Header -->
        <div class="mb-6">
            <div class="text-navy-400 mb-2 flex items-center text-sm">
                <span>Data Management</span>
                <i class="fa-solid fa-chevron-right mx-2"></i>
                <span class="text-white">Vessels</span>
            </div>
            <h1 class="mb-2 text-2xl font-semibold">Vessel Management</h1>
            <p class="text-navy-300">Monitor and manage all vessels in the system</p>
        </div>

        <!-- Flash Messages -->
        @if (session('success'))
            <div class="relative mb-6 rounded border border-green-500 bg-green-600 px-4 py-3 text-white" role="alert">
                <span class="block sm:inline">{{ session('success') }}</span>
                <button type="button" class="absolute bottom-0 right-0 top-0 px-4 py-3"
                    onclick="this.parentElement.remove()">
                    <i class="fa-solid fa-times"></i>
                </button>
            </div>
        @endif

        @if (session('info'))
            <div class="relative mb-6 rounded border border-blue-500 bg-blue-600 px-4 py-3 text-white" role="alert">
                <span class="block sm:inline">{{ session('info') }}</span>
                <button type="button" class="absolute bottom-0 right-0 top-0 px-4 py-3"
                    onclick="this.parentElement.remove()">
                    <i class="fa-solid fa-times"></i>
                </button>
            </div>
        @endif

        @if (session('error'))
            <div class="relative mb-6 rounded border border-red-500 bg-red-600 px-4 py-3 text-white" role="alert">
                <span class="block sm:inline">{{ session('error') }}</span>
                <button type="button" class="absolute bottom-0 right-0 top-0 px-4 py-3"
                    onclick="this.parentElement.remove()">
                    <i class="fa-solid fa-times"></i>
                </button>
            </div>
        @endif

        <!-- Charts -->
        <div id="vessel-analytics" class="mb-8 grid grid-cols-1 gap-6 lg:grid-cols-2">
            <div class="bg-dark-800 border-dark-700 rounded-lg border p-6">
                <h3 class="mb-4 flex items-center text-lg font-semibold">
                    <i class="fa-solid fa-ship mr-2 text-blue-400"></i>
                    Vessel Activity (30 Days)
                </h3>
                <div id="vessel-activity-chart" class="h-48"></div>
            </div>

            <div class="bg-dark-800 border-dark-700 rounded-lg border p-6">
                <h3 class="mb-4 flex items-center text-lg font-semibold">
                    <i class="fa-solid fa-users mr-2 text-green-400"></i>
                    Average Users per Vessel
                </h3>
                <div class="mb-4 text-center">
                    <div class="text-4xl font-bold text-green-400">
                        {{ $overallAvgUsers ?? '—' }}
                    </div>
                    <div class="text-navy-400 text-sm">Average users per vessel</div>
                </div>

                <div id="users-per-vessel-chart" class="h-32"></div>
            </div>
        </div>

        <!-- Table Section -->
        <div id="vessel-table-section" class="bg-dark-800 border-dark-700 rounded-lg border">
            <div class="border-dark-700 border-b p-6">
                <div class="flex flex-col gap-4 lg:flex-row lg:items-center lg:justify-between">
                    <h2 class="text-lg font-semibold">Active Vessels</h2>
                    <div class="flex flex-wrap gap-3">
                        <div class="flex items-center space-x-2">
                            <button class="bg-dark-600 hover:bg-dark-500 rounded px-3 py-2 text-sm transition-colors">
                                <i class="fa-solid fa-filter mr-1"></i>
                                Filter
                            </button>
                        </div>
                        <div class="flex gap-2">
                            <a href="{{ route('admin.vessels.create') }}"
                                class="rounded bg-blue-600 px-4 py-2 text-sm text-white transition-colors hover:bg-blue-500">
                                <i class="fa-solid fa-plus mr-1"></i>
                                New Vessel
                            </a>
                            <button class="rounded bg-green-600 px-4 py-2 text-sm transition-colors hover:bg-green-500">
                                <i class="fa-solid fa-download mr-1"></i>
                                Export
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Vessel Table -->
            <div id="vessel-table" class="overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-dark-700">
                        <tr>
                            <th class="p-4 text-left text-sm font-medium">Name</th>
                            <th class="p-4 text-left text-sm font-medium">Size</th>
                            <th class="p-4 text-left text-sm font-medium">Owner</th>
                            <th class="p-4 text-left text-sm font-medium">Users</th>
                            <th class="p-4 text-left text-sm font-medium">Subscription</th>
                            <th class="p-4 text-left text-sm font-medium">Action</th>
                        </tr>
                    </thead>
                    <tbody class="divide-dark-700 text-navy-300 divide-y text-sm">
                        @foreach ($vessels as $vessel)
                            <tr class="hover:bg-dark-700/50">
                                <td class="p-4">
                                    <div class="flex items-center">
                                        @php
                                            $countryCode = strtolower($vessel->flag);
                                        @endphp

                                        <img src="https://flagcdn.com/h20/{{ $countryCode }}.png" alt="{{ $vessel->flag }}"
                                            class="mr-2 h-4 w-5 rounded-sm object-cover"
                                            onerror="this.style.display='none'">

                                        <span class="font-medium text-white">{{ $vessel->name }}</span>
                                    </div>
                                </td>
                                <td class="p-4">{{ $vessel->vessel_size ? $vessel->vessel_size . 'm' : '—' }}</td>
                                <td class="p-4">{{ optional($vessel->owner)->full_name ?? '—' }}</td>
                                <td class="p-4">{{ $vessel->users_count }}</td>
                                <td class="p-4">
                                    <span class="rounded bg-green-900/30 px-2 py-1 text-xs text-green-400">Active</span>
                                </td>
                                <td class="p-4">
                                    <div class="flex space-x-2">
                                        <a href="{{ route('admin.vessels.show', $vessel) }}"
                                            class="rounded bg-blue-600 px-3 py-1 text-xs text-white transition-colors hover:bg-blue-500">
                                            View Vessel
                                        </a>

                                        @if (in_array(auth()->user()->system_role, ['superadmin', 'staff', 'dev']))
                                            <form action="{{ route('vessel.switch') }}" method="POST" class="inline">
                                                @csrf
                                                <input type="hidden" name="vessel_id" value="{{ $vessel->id }}">
                                                <button type="submit"
                                                    class="rounded-lg bg-blue-600 px-3 py-1.5 text-sm font-medium text-white hover:bg-blue-700">
                                                    <i class="fa-solid fa-sign-in-alt mr-1"></i> Enter Vessel
                                                </button>
                                            </form>
                                        @endif
                                    </div>
                                </td>

                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            {{--
            <!-- Pagination (Static for now) -->
            <div class="p-4 border-t border-dark-700 flex items-center justify-between">
                <div class="text-sm text-navy-400">Showing {{ $vessels->count() }} vessels</div>
                <div class="flex items-center space-x-2">
                    <button class="px-3 py-1 bg-dark-700 rounded text-sm hover:bg-dark-600 transition-colors">Previous</button>
                    <button class="px-3 py-1 bg-blue-600 rounded text-sm text-white">1</button>
                    <button class="px-3 py-1 bg-dark-700 rounded text-sm hover:bg-dark-600 transition-colors">2</button>
                    <button class="px-3 py-1 bg-dark-700 rounded text-sm hover:bg-dark-600 transition-colors">3</button>
                    <button class="px-3 py-1 bg-dark-700 rounded text-sm hover:bg-dark-600 transition-colors">Next</button>
                </div>
            </div> --}}
        </div>
    </main>
@endsection

@section('scripts')
    <script>
        Highcharts.chart('vessel-activity-chart', {
            chart: {
                type: 'line',
                backgroundColor: 'transparent'
            },
            title: {
                text: null
            },
            xAxis: {
                categories: ['Week 1', 'Week 2', 'Week 3', 'Week 4'],
                labels: {
                    style: {
                        color: '#9fb3c8'
                    }
                }
            },
            yAxis: {
                title: {
                    text: null
                },
                labels: {
                    style: {
                        color: '#9fb3c8'
                    }
                }
            },
            legend: {
                itemStyle: {
                    color: '#9fb3c8'
                }
            },
            plotOptions: {
                line: {
                    marker: {
                        enabled: true,
                        radius: 4
                    }
                }
            },
            series: [{
                    name: 'Active Vessels',
                    data: [247, 251, 245, 247],
                    color: '#60a5fa'
                },
                {
                    name: 'Daily Activity',
                    data: [189, 195, 187, 192],
                    color: '#34d399'
                }
            ],
            credits: {
                enabled: false
            }
        });
    </script>

    <script>
        const usersPerVesselData = @json($avgUsersPerRange->values()->map(fn($val) => $val ?? ['name' => 'No Data'])->all());
        const usersPerVesselLabels = @json($avgUsersPerRange->keys());

        Highcharts.chart('users-per-vessel-chart', {
            chart: {
                type: 'column',
                backgroundColor: 'transparent',
                height: 120
            },
            title: {
                text: null
            },
            xAxis: {
                categories: usersPerVesselLabels,
                labels: {
                    style: {
                        color: '#9fb3c8'
                    }
                }
            },
            yAxis: {
                title: {
                    text: null
                },
                labels: {
                    style: {
                        color: '#9fb3c8'
                    }
                },
                min: 0
            },
            legend: {
                enabled: false
            },
            tooltip: {
                formatter: function() {
                    return this.y === null ?
                        'No data' :
                        `<strong>${this.y}</strong> users per vessel`;
                }
            },
            plotOptions: {
                column: {
                    borderRadius: 3,
                    color: '#34d399'
                }
            },
            series: [{
                name: 'Avg Users',
                data: usersPerVesselData,
            }],
            credits: {
                enabled: false
            }
        });
    </script>

@endsection
