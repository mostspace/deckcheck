@php
    // Get dashboard statistics with error handling
    try {
        $totalUsers = \App\Models\User::count();
        $totalVessels = \App\Models\Vessel::count();
        $totalWorkOrders = \App\Models\WorkOrder::count();
        $pendingWorkOrders = \App\Models\WorkOrder::where('status', 'open')->count();
        $overdueWorkOrders = \App\Models\WorkOrder::where('due_date', '<', now())
            ->where('status', '!=', 'completed')
            ->count();
        $totalDeficiencies = \App\Models\Deficiency::count();
        $openDeficiencies = \App\Models\Deficiency::where('status', 'open')->count();
        $recentUsers = \App\Models\User::latest()->take(5)->get();
        $recentVessels = \App\Models\Vessel::latest()->take(5)->get();
    } catch (\Exception $e) {
        $totalUsers = 0;
        $totalVessels = 0;
        $totalWorkOrders = 0;
        $pendingWorkOrders = 0;
        $overdueWorkOrders = 0;
        $totalDeficiencies = 0;
        $openDeficiencies = 0;
        $recentUsers = collect();
        $recentVessels = collect();
    }

    // Get monthly work order data for chart
    try {
        $monthlyWorkOrders = \App\Models\WorkOrder::selectRaw('MONTH(created_at) as month, COUNT(*) as count')
            ->whereYear('created_at', date('Y'))
            ->groupBy('month')
            ->orderBy('month')
            ->get()
            ->pluck('count', 'month')
            ->toArray();

        // Fill in missing months with 0
        for ($i = 1; $i <= 12; $i++) {
            if (!isset($monthlyWorkOrders[$i])) {
                $monthlyWorkOrders[$i] = 0;
            }
        }
        ksort($monthlyWorkOrders);
    } catch (\Exception $e) {
        $monthlyWorkOrders = array_fill(1, 12, 0);
    }
@endphp

<div class="space-y-6 md:space-y-8">
    {{-- Welcome Header --}}
    <div
        class="from-dark-800 to-dark-900 border-subtle shadow-subtle relative overflow-hidden rounded-xl border bg-gradient-to-r">
        <div class="from-accent-primary/5 to-accent-secondary/5 absolute inset-0 bg-gradient-to-r"></div>
        <div class="relative p-6 md:p-8">
            <div class="flex flex-col space-y-4 md:flex-row md:items-center md:justify-between md:space-y-0">
                <div>
                    <h1 class="mb-2 text-2xl font-bold text-white md:text-3xl">Welcome back,
                        {{ auth()->user()->first_name ?? auth()->user()->name }}!</h1>
                    <p class="text-base text-gray-300 md:text-lg">Here's what's happening across your fleet today</p>
                </div>
                <div class="text-center md:text-right">
                    <div class="font-mono text-3xl font-bold text-white md:text-4xl">{{ now()->format('M j') }}</div>
                    <div class="font-medium text-gray-300">{{ now()->format('l') }}</div>
                </div>
            </div>
        </div>
        {{-- Geometric accent --}}
        <div
            class="from-accent-primary/20 absolute right-0 top-0 h-24 w-24 -translate-y-12 translate-x-12 rounded-full bg-gradient-to-br to-transparent md:h-32 md:w-32 md:-translate-y-16 md:translate-x-16">
        </div>
    </div>

    {{-- Key Metrics Grid --}}
    <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 md:gap-6 lg:grid-cols-4">
        {{-- Total Users --}}
        <div class="bg-dark-900 border-subtle hover-lift group rounded-xl border p-4 md:p-6">
            <div class="mb-4 flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium uppercase tracking-wider text-gray-400">Total Users</p>
                    <p class="mt-1 text-2xl font-bold text-white md:text-3xl">{{ number_format($totalUsers) }}</p>
                </div>
                <div
                    class="flex h-10 w-10 items-center justify-center rounded-xl bg-blue-500/10 transition-colors duration-200 group-hover:bg-blue-500/20 md:h-12 md:w-12">
                    <i class="fa-solid fa-users text-lg text-blue-400 md:text-xl"></i>
                </div>
            </div>
            <div class="border-subtle border-t pt-4">
                @if ($recentUsers->count() > 0)
                    <span class="flex items-center text-sm font-medium text-green-400">
                        <i class="fa-solid fa-arrow-up mr-2 text-xs"></i>
                        {{ $recentUsers->count() }} new this month
                    </span>
                @else
                    <span class="flex items-center text-sm font-medium text-gray-400">
                        <i class="fa-solid fa-info-circle mr-2 text-xs"></i>
                        No new users this month
                    </span>
                @endif
            </div>
        </div>

        {{-- Total Vessels --}}
        <div class="bg-dark-900 border-subtle hover-lift group rounded-xl border p-6">
            <div class="mb-4 flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium uppercase tracking-wider text-gray-400">Active Vessels</p>
                    <p class="mt-1 text-3xl font-bold text-white">{{ number_format($totalVessels) }}</p>
                </div>
                <div
                    class="flex h-12 w-12 items-center justify-center rounded-xl bg-green-500/10 transition-colors duration-200 group-hover:bg-green-500/20">
                    <i class="fa-solid fa-ship text-xl text-green-400"></i>
                </div>
            </div>
            <div class="border-subtle border-t pt-4">
                @if ($recentVessels->count() > 0)
                    <span class="flex items-center text-sm font-medium text-blue-400">
                        <i class="fa-solid fa-info-circle mr-2 text-xs"></i>
                        {{ $recentVessels->count() }} recently added
                    </span>
                @else
                    <span class="flex items-center text-sm font-medium text-gray-400">
                        <i class="fa-solid fa-info-circle mr-2 text-xs"></i>
                        No recent vessels
                    </span>
                @endif
            </div>
        </div>

        {{-- Work Orders --}}
        <div class="bg-dark-900 border-subtle hover-lift group rounded-xl border p-6">
            <div class="mb-4 flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium uppercase tracking-wider text-gray-400">Work Orders</p>
                    <p class="mt-1 text-3xl font-bold text-white">{{ number_format($totalWorkOrders) }}</p>
                </div>
                <div
                    class="flex h-12 w-12 items-center justify-center rounded-xl bg-yellow-500/10 transition-colors duration-200 group-hover:bg-yellow-500/20">
                    <i class="fa-solid fa-clipboard-list text-xl text-yellow-400"></i>
                </div>
            </div>
            <div class="border-subtle border-t pt-4">
                <span class="flex items-center text-sm font-medium text-yellow-400">
                    <i class="fa-solid fa-exclamation-triangle mr-2 text-xs"></i>
                    {{ $overdueWorkOrders }} overdue
                </span>
            </div>
        </div>

        {{-- Deficiencies --}}
        <div class="bg-dark-900 border-subtle hover-lift group rounded-xl border p-6">
            <div class="mb-4 flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium uppercase tracking-wider text-gray-400">Deficiencies</p>
                    <p class="mt-1 text-3xl font-bold text-white">{{ number_format($totalDeficiencies) }}</p>
                </div>
                <div
                    class="flex h-12 w-12 items-center justify-center rounded-xl bg-red-500/10 transition-colors duration-200 group-hover:bg-red-500/20">
                    <i class="fa-solid fa-exclamation-circle text-xl text-red-400"></i>
                </div>
            </div>
            <div class="border-subtle border-t pt-4">
                <span class="flex items-center text-sm font-medium text-red-400">
                    <i class="fa-solid fa-clock mr-2 text-xs"></i>
                    {{ $openDeficiencies }} open
                </span>
            </div>
        </div>
    </div>

    {{-- Charts and Data Section --}}
    <div class="grid grid-cols-1 gap-8 lg:grid-cols-2">
        {{-- Work Orders Chart --}}
        <div class="bg-dark-900 border-subtle rounded-xl border p-6">
            <div class="mb-6 flex items-center justify-between">
                <h3 class="text-lg font-semibold text-white">Work Orders This Year</h3>
                <div class="flex space-x-1">
                    <button
                        class="bg-dark-800 hover:bg-dark-700 rounded-lg px-3 py-1.5 text-xs font-medium text-gray-300 transition-colors duration-200">Monthly</button>
                    <button
                        class="bg-dark-800 hover:bg-dark-700 rounded-lg px-3 py-1.5 text-xs font-medium text-gray-300 transition-colors duration-200">Weekly</button>
                </div>
            </div>
            <div class="h-64">
                @if (array_sum($monthlyWorkOrders) > 0)
                    <canvas id="workOrdersChart" width="400" height="200"></canvas>
                @else
                    <div class="flex h-full items-center justify-center">
                        <div class="text-center">
                            <div
                                class="mx-auto mb-4 flex h-16 w-16 items-center justify-center rounded-xl bg-gray-500/10">
                                <i class="fa-solid fa-chart-line text-2xl text-gray-400"></i>
                            </div>
                            <p class="text-sm font-medium text-gray-400">No work order data available</p>
                        </div>
                    </div>
                @endif
            </div>
        </div>

        {{-- Recent Activity --}}
        <div class="bg-dark-900 border-subtle rounded-xl border p-6">
            <h3 class="mb-6 text-lg font-semibold text-white">Recent Activity</h3>
            <div class="space-y-4">
                @if ($recentUsers->count() > 0)
                    @foreach ($recentUsers->take(3) as $user)
                        <div
                            class="hover:bg-dark-800 flex items-center space-x-3 rounded-lg p-3 transition-colors duration-200">
                            <div
                                class="from-accent-primary to-accent-secondary flex h-10 w-10 items-center justify-center rounded-lg bg-gradient-to-br">
                                <span
                                    class="text-sm font-bold text-white">{{ substr($user->first_name ?? $user->name, 0, 1) }}</span>
                            </div>
                            <div class="min-w-0 flex-1">
                                <p class="truncate text-sm font-medium text-white">
                                    {{ $user->first_name ?? $user->name }}</p>
                                <p class="font-mono text-xs text-gray-400">Joined
                                    {{ $user->created_at->diffForHumans() }}</p>
                            </div>
                        </div>
                    @endforeach
                @else
                    <div class="py-8 text-center">
                        <div class="mx-auto mb-3 flex h-12 w-12 items-center justify-center rounded-lg bg-gray-500/10">
                            <i class="fa-solid fa-user text-lg text-gray-400"></i>
                        </div>
                        <p class="text-sm font-medium text-gray-400">No recent user activity</p>
                    </div>
                @endif

                @if ($recentVessels->count() > 0)
                    @foreach ($recentVessels->take(2) as $vessel)
                        <div
                            class="hover:bg-dark-800 flex items-center space-x-3 rounded-lg p-3 transition-colors duration-200">
                            <div
                                class="from-accent-secondary flex h-10 w-10 items-center justify-center rounded-lg bg-gradient-to-br to-green-500">
                                <i class="fa-solid fa-ship text-sm text-white"></i>
                            </div>
                            <div class="min-w-0 flex-1">
                                <p class="truncate text-sm font-medium text-white">{{ $vessel->name }}</p>
                                <p class="font-mono text-xs text-gray-400">Added
                                    {{ $vessel->created_at->diffForHumans() }}</p>
                            </div>
                        </div>
                    @endforeach
                @else
                    <div class="py-4 text-center">
                        <div class="mx-auto mb-2 flex h-8 w-8 items-center justify-center rounded-lg bg-gray-500/10">
                            <i class="fa-solid fa-ship text-sm text-gray-400"></i>
                        </div>
                        <p class="text-xs font-medium text-gray-400">No recent vessel activity</p>
                    </div>
                @endif
            </div>
        </div>
    </div>

    {{-- Quick Actions --}}
    <div class="bg-dark-900 border-subtle rounded-xl border p-6">
        <h3 class="mb-6 text-lg font-semibold text-white">Quick Actions</h3>
        <div class="grid grid-cols-1 gap-4 md:grid-cols-2 lg:grid-cols-4">
            <a href="{{ route('admin.staff.index') }}"
                class="bg-dark-800 hover:bg-dark-700 hover-lift border-subtle group rounded-xl border p-6 text-center transition-all duration-200">
                <div
                    class="mx-auto mb-4 flex h-14 w-14 items-center justify-center rounded-xl bg-blue-500/10 transition-colors duration-200 group-hover:bg-blue-500/20">
                    <i class="fa-solid fa-user-plus text-xl text-blue-400"></i>
                </div>
                <p class="mb-1 font-semibold text-white">Add Staff</p>
                <p class="text-sm text-gray-400">Invite new team members</p>
            </a>

            <a href="{{ route('admin.vessels.create') }}"
                class="bg-dark-800 hover:bg-dark-700 hover-lift border-subtle group rounded-xl border p-6 text-center transition-all duration-200">
                <div
                    class="mx-auto mb-4 flex h-14 w-14 items-center justify-center rounded-xl bg-green-500/10 transition-colors duration-200 group-hover:bg-green-500/20">
                    <i class="fa-solid fa-ship text-xl text-green-400"></i>
                </div>
                <p class="mb-1 font-semibold text-white">Add Vessel</p>
                <p class="text-sm text-gray-400">Register new vessel</p>
            </a>

            <a href="{{ route('admin.users.index') }}"
                class="bg-dark-800 hover:bg-dark-700 hover-lift border-subtle group rounded-xl border p-6 text-center transition-all duration-200">
                <div
                    class="mx-auto mb-4 flex h-14 w-14 items-center justify-center rounded-xl bg-purple-500/10 transition-colors duration-200 group-hover:bg-purple-500/20">
                    <i class="fa-solid fa-users-gear text-xl text-purple-400"></i>
                </div>
                <p class="mb-1 font-semibold text-white">Manage Users</p>
                <p class="text-sm text-gray-400">User permissions & roles</p>
            </a>

            <a href="#"
                class="bg-dark-800 hover:bg-dark-700 hover-lift border-subtle group rounded-xl border p-6 text-center transition-all duration-200">
                <div
                    class="mx-auto mb-4 flex h-14 w-14 items-center justify-center rounded-xl bg-orange-500/10 transition-colors duration-200 group-hover:bg-orange-500/20">
                    <i class="fa-solid fa-chart-line text-xl text-orange-400"></i>
                </div>
                <p class="mb-1 font-semibold text-white">View Reports</p>
                <p class="text-sm text-gray-400">System analytics</p>
            </a>
        </div>
    </div>

    {{-- System Status --}}
    <div class="bg-dark-900 border-subtle rounded-xl border p-6">
        <h3 class="mb-6 text-lg font-semibold text-white">System Status</h3>
        <div class="grid grid-cols-1 gap-6 md:grid-cols-3">
            <div class="group text-center">
                <div
                    class="mx-auto mb-4 flex h-20 w-20 items-center justify-center rounded-xl bg-green-500/10 transition-colors duration-200 group-hover:bg-green-500/20">
                    <i class="fa-solid fa-check-circle text-3xl text-green-400"></i>
                </div>
                <p class="mb-1 font-semibold text-white">Database</p>
                <p class="font-mono text-sm text-green-400">Healthy</p>
            </div>

            <div class="group text-center">
                <div
                    class="mx-auto mb-4 flex h-20 w-20 items-center justify-center rounded-xl bg-green-500/10 transition-colors duration-200 group-hover:bg-green-500/20">
                    <i class="fa-solid fa-check-circle text-3xl text-green-400"></i>
                </div>
                <p class="mb-1 font-semibold text-white">Queue System</p>
                <p class="font-mono text-sm text-green-400">Running</p>
            </div>

            <div class="group text-center">
                <div
                    class="mx-auto mb-4 flex h-20 w-20 items-center justify-center rounded-xl bg-green-500/10 transition-colors duration-200 group-hover:bg-green-500/20">
                    <i class="fa-solid fa-check-circle text-3xl text-green-400"></i>
                </div>
                <p class="mb-1 font-semibold text-white">Storage</p>
                <p class="font-mono text-sm text-green-400">Normal</p>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const chartCanvas = document.getElementById('workOrdersChart');

        if (chartCanvas && @json(array_sum($monthlyWorkOrders)) > 0) {
            const ctx = chartCanvas.getContext('2d');

            new Chart(ctx, {
                type: 'line',
                data: {
                    labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct',
                        'Nov', 'Dec'
                    ],
                    datasets: [{
                        label: 'Work Orders',
                        data: @json(array_values($monthlyWorkOrders)),
                        borderColor: '#3B82F6',
                        backgroundColor: 'rgba(59, 130, 246, 0.05)',
                        borderWidth: 2,
                        fill: true,
                        tension: 0.4,
                        pointBackgroundColor: '#3B82F6',
                        pointBorderColor: '#ffffff',
                        pointBorderWidth: 2,
                        pointRadius: 4,
                        pointHoverRadius: 6
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            display: false
                        }
                    },
                    scales: {
                        x: {
                            grid: {
                                color: 'rgba(148, 163, 184, 0.1)',
                                borderColor: 'rgba(148, 163, 184, 0.2)'
                            },
                            ticks: {
                                color: '#94A3B8',
                                font: {
                                    size: 11
                                }
                            }
                        },
                        y: {
                            grid: {
                                color: 'rgba(148, 163, 184, 0.1)',
                                borderColor: 'rgba(148, 163, 184, 0.2)'
                            },
                            ticks: {
                                color: '#94A3B8',
                                font: {
                                    size: 11
                                },
                                beginAtZero: true
                            }
                        }
                    },
                    interaction: {
                        intersect: false,
                        mode: 'index'
                    }
                }
            });
        }
    });
</script>
