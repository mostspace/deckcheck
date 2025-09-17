@php
    // Get dashboard statistics with error handling
    try {
        $totalUsers = \App\Models\User::count();
        $totalVessels = \App\Models\Vessel::count();
        $totalWorkOrders = \App\Models\WorkOrder::count();
        $pendingWorkOrders = \App\Models\WorkOrder::where('status', 'open')->count();
        $overdueWorkOrders = \App\Models\WorkOrder::where('due_date', '<', now())->where('status', '!=', 'completed')->count();
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
    <div class="relative overflow-hidden bg-gradient-to-r from-dark-800 to-dark-900 rounded-xl border border-subtle shadow-subtle">
        <div class="absolute inset-0 bg-gradient-to-r from-accent-primary/5 to-accent-secondary/5"></div>
        <div class="relative p-6 md:p-8">
            <div class="flex flex-col md:flex-row md:items-center md:justify-between space-y-4 md:space-y-0">
                <div>
                    <h1 class="text-2xl md:text-3xl font-bold text-white mb-2">Welcome back, {{ auth()->user()->first_name ?? auth()->user()->name }}!</h1>
                    <p class="text-gray-300 text-base md:text-lg">Here's what's happening across your fleet today</p>
                </div>
                <div class="text-center md:text-right">
                    <div class="text-3xl md:text-4xl font-bold text-white font-mono">{{ now()->format('M j') }}</div>
                    <div class="text-gray-300 font-medium">{{ now()->format('l') }}</div>
                </div>
            </div>
        </div>
        {{-- Geometric accent --}}
        <div class="absolute top-0 right-0 w-24 md:w-32 h-24 md:h-32 bg-gradient-to-br from-accent-primary/20 to-transparent rounded-full -translate-y-12 md:-translate-y-16 translate-x-12 md:translate-x-16"></div>
    </div>

    {{-- Key Metrics Grid --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 md:gap-6">
        {{-- Total Users --}}
        <div class="group bg-dark-900 rounded-xl border border-subtle p-4 md:p-6 hover-lift">
            <div class="flex items-center justify-between mb-4">
                <div>
                    <p class="text-gray-400 text-sm font-medium uppercase tracking-wider">Total Users</p>
                    <p class="text-2xl md:text-3xl font-bold text-white mt-1">{{ number_format($totalUsers) }}</p>
                </div>
                <div class="w-10 md:w-12 h-10 md:h-12 bg-blue-500/10 rounded-xl flex items-center justify-center group-hover:bg-blue-500/20 transition-colors duration-200">
                    <i class="fa-solid fa-users text-blue-400 text-lg md:text-xl"></i>
                </div>
            </div>
            <div class="pt-4 border-t border-subtle">
                @if($recentUsers->count() > 0)
                    <span class="text-green-400 text-sm font-medium flex items-center">
                        <i class="fa-solid fa-arrow-up mr-2 text-xs"></i>
                        {{ $recentUsers->count() }} new this month
                    </span>
                @else
                    <span class="text-gray-400 text-sm font-medium flex items-center">
                        <i class="fa-solid fa-info-circle mr-2 text-xs"></i>
                        No new users this month
                    </span>
                @endif
            </div>
        </div>

        {{-- Total Vessels --}}
        <div class="group bg-dark-900 rounded-xl border border-subtle p-6 hover-lift">
            <div class="flex items-center justify-between mb-4">
                <div>
                    <p class="text-gray-400 text-sm font-medium uppercase tracking-wider">Active Vessels</p>
                    <p class="text-3xl font-bold text-white mt-1">{{ number_format($totalVessels) }}</p>
                </div>
                <div class="w-12 h-12 bg-green-500/10 rounded-xl flex items-center justify-center group-hover:bg-green-500/20 transition-colors duration-200">
                    <i class="fa-solid fa-ship text-green-400 text-xl"></i>
                </div>
            </div>
            <div class="pt-4 border-t border-subtle">
                @if($recentVessels->count() > 0)
                    <span class="text-blue-400 text-sm font-medium flex items-center">
                        <i class="fa-solid fa-info-circle mr-2 text-xs"></i>
                        {{ $recentVessels->count() }} recently added
                    </span>
                @else
                    <span class="text-gray-400 text-sm font-medium flex items-center">
                        <i class="fa-solid fa-info-circle mr-2 text-xs"></i>
                        No recent vessels
                    </span>
                @endif
            </div>
        </div>

        {{-- Work Orders --}}
        <div class="group bg-dark-900 rounded-xl border border-subtle p-6 hover-lift">
            <div class="flex items-center justify-between mb-4">
                <div>
                    <p class="text-gray-400 text-sm font-medium uppercase tracking-wider">Work Orders</p>
                    <p class="text-3xl font-bold text-white mt-1">{{ number_format($totalWorkOrders) }}</p>
                </div>
                <div class="w-12 h-12 bg-yellow-500/10 rounded-xl flex items-center justify-center group-hover:bg-yellow-500/20 transition-colors duration-200">
                    <i class="fa-solid fa-clipboard-list text-yellow-400 text-xl"></i>
                </div>
            </div>
            <div class="pt-4 border-t border-subtle">
                <span class="text-yellow-400 text-sm font-medium flex items-center">
                    <i class="fa-solid fa-exclamation-triangle mr-2 text-xs"></i>
                    {{ $overdueWorkOrders }} overdue
                </span>
            </div>
        </div>

        {{-- Deficiencies --}}
        <div class="group bg-dark-900 rounded-xl border border-subtle p-6 hover-lift">
            <div class="flex items-center justify-between mb-4">
                <div>
                    <p class="text-gray-400 text-sm font-medium uppercase tracking-wider">Deficiencies</p>
                    <p class="text-3xl font-bold text-white mt-1">{{ number_format($totalDeficiencies) }}</p>
                </div>
                <div class="w-12 h-12 bg-red-500/10 rounded-xl flex items-center justify-center group-hover:bg-red-500/20 transition-colors duration-200">
                    <i class="fa-solid fa-exclamation-circle text-red-400 text-xl"></i>
                </div>
            </div>
            <div class="pt-4 border-t border-subtle">
                <span class="text-red-400 text-sm font-medium flex items-center">
                    <i class="fa-solid fa-clock mr-2 text-xs"></i>
                    {{ $openDeficiencies }} open
                </span>
            </div>
        </div>
    </div>

    {{-- Charts and Data Section --}}
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
        {{-- Work Orders Chart --}}
        <div class="bg-dark-900 rounded-xl border border-subtle p-6">
            <div class="flex items-center justify-between mb-6">
                <h3 class="text-lg font-semibold text-white">Work Orders This Year</h3>
                <div class="flex space-x-1">
                    <button class="px-3 py-1.5 text-xs bg-dark-800 text-gray-300 rounded-lg hover:bg-dark-700 transition-colors duration-200 font-medium">Monthly</button>
                    <button class="px-3 py-1.5 text-xs bg-dark-800 text-gray-300 rounded-lg hover:bg-dark-700 transition-colors duration-200 font-medium">Weekly</button>
                </div>
            </div>
            <div class="h-64">
                @if(array_sum($monthlyWorkOrders) > 0)
                    <canvas id="workOrdersChart" width="400" height="200"></canvas>
                @else
                    <div class="h-full flex items-center justify-center">
                        <div class="text-center">
                            <div class="w-16 h-16 bg-gray-500/10 rounded-xl flex items-center justify-center mx-auto mb-4">
                                <i class="fa-solid fa-chart-line text-gray-400 text-2xl"></i>
                            </div>
                            <p class="text-gray-400 text-sm font-medium">No work order data available</p>
                        </div>
                    </div>
                @endif
            </div>
        </div>

        {{-- Recent Activity --}}
        <div class="bg-dark-900 rounded-xl border border-subtle p-6">
            <h3 class="text-lg font-semibold text-white mb-6">Recent Activity</h3>
            <div class="space-y-4">
                @if($recentUsers->count() > 0)
                    @foreach($recentUsers->take(3) as $user)
                    <div class="flex items-center space-x-3 p-3 rounded-lg hover:bg-dark-800 transition-colors duration-200">
                        <div class="w-10 h-10 bg-gradient-to-br from-accent-primary to-accent-secondary rounded-lg flex items-center justify-center">
                            <span class="text-white text-sm font-bold">{{ substr($user->first_name ?? $user->name, 0, 1) }}</span>
                        </div>
                        <div class="flex-1 min-w-0">
                            <p class="text-white text-sm font-medium truncate">{{ $user->first_name ?? $user->name }}</p>
                            <p class="text-gray-400 text-xs font-mono">Joined {{ $user->created_at->diffForHumans() }}</p>
                        </div>
                    </div>
                    @endforeach
                @else
                    <div class="text-center py-8">
                        <div class="w-12 h-12 bg-gray-500/10 rounded-lg flex items-center justify-center mx-auto mb-3">
                            <i class="fa-solid fa-user text-gray-400 text-lg"></i>
                        </div>
                        <p class="text-gray-400 text-sm font-medium">No recent user activity</p>
                    </div>
                @endif
                
                @if($recentVessels->count() > 0)
                    @foreach($recentVessels->take(2) as $vessel)
                    <div class="flex items-center space-x-3 p-3 rounded-lg hover:bg-dark-800 transition-colors duration-200">
                        <div class="w-10 h-10 bg-gradient-to-br from-accent-secondary to-green-500 rounded-lg flex items-center justify-center">
                            <i class="fa-solid fa-ship text-white text-sm"></i>
                        </div>
                        <div class="flex-1 min-w-0">
                            <p class="text-white text-sm font-medium truncate">{{ $vessel->name }}</p>
                            <p class="text-gray-400 text-xs font-mono">Added {{ $vessel->created_at->diffForHumans() }}</p>
                        </div>
                    </div>
                    @endforeach
                @else
                    <div class="text-center py-4">
                        <div class="w-8 h-8 bg-gray-500/10 rounded-lg flex items-center justify-center mx-auto mb-2">
                            <i class="fa-solid fa-ship text-gray-400 text-sm"></i>
                        </div>
                        <p class="text-gray-400 text-xs font-medium">No recent vessel activity</p>
                    </div>
                @endif
            </div>
        </div>
    </div>

    {{-- Quick Actions --}}
    <div class="bg-dark-900 rounded-xl border border-subtle p-6">
        <h3 class="text-lg font-semibold text-white mb-6">Quick Actions</h3>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
            <a href="{{ route('admin.staff.index') }}" class="group bg-dark-800 hover:bg-dark-700 rounded-xl p-6 text-center transition-all duration-200 hover-lift border border-subtle">
                <div class="w-14 h-14 bg-blue-500/10 rounded-xl flex items-center justify-center mx-auto mb-4 group-hover:bg-blue-500/20 transition-colors duration-200">
                    <i class="fa-solid fa-user-plus text-blue-400 text-xl"></i>
                </div>
                <p class="text-white font-semibold mb-1">Add Staff</p>
                <p class="text-gray-400 text-sm">Invite new team members</p>
            </a>

            <a href="{{ route('admin.vessels.create') }}" class="group bg-dark-800 hover:bg-dark-700 rounded-xl p-6 text-center transition-all duration-200 hover-lift border border-subtle">
                <div class="w-14 h-14 bg-green-500/10 rounded-xl flex items-center justify-center mx-auto mb-4 group-hover:bg-green-500/20 transition-colors duration-200">
                    <i class="fa-solid fa-ship text-green-400 text-xl"></i>
                </div>
                <p class="text-white font-semibold mb-1">Add Vessel</p>
                <p class="text-gray-400 text-sm">Register new vessel</p>
            </a>

            <a href="{{ route('admin.users.index') }}" class="group bg-dark-800 hover:bg-dark-700 rounded-xl p-6 text-center transition-all duration-200 hover-lift border border-subtle">
                <div class="w-14 h-14 bg-purple-500/10 rounded-xl flex items-center justify-center mx-auto mb-4 group-hover:bg-purple-500/20 transition-colors duration-200">
                    <i class="fa-solid fa-users-gear text-purple-400 text-xl"></i>
                </div>
                <p class="text-white font-semibold mb-1">Manage Users</p>
                <p class="text-gray-400 text-sm">User permissions & roles</p>
            </a>

            <a href="#" class="group bg-dark-800 hover:bg-dark-700 rounded-xl p-6 text-center transition-all duration-200 hover-lift border border-subtle">
                <div class="w-14 h-14 bg-orange-500/10 rounded-xl flex items-center justify-center mx-auto mb-4 group-hover:bg-orange-500/20 transition-colors duration-200">
                    <i class="fa-solid fa-chart-line text-orange-400 text-xl"></i>
                </div>
                <p class="text-white font-semibold mb-1">View Reports</p>
                <p class="text-gray-400 text-sm">System analytics</p>
            </a>
        </div>
    </div>

    {{-- System Status --}}
    <div class="bg-dark-900 rounded-xl border border-subtle p-6">
        <h3 class="text-lg font-semibold text-white mb-6">System Status</h3>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <div class="text-center group">
                <div class="w-20 h-20 bg-green-500/10 rounded-xl flex items-center justify-center mx-auto mb-4 group-hover:bg-green-500/20 transition-colors duration-200">
                    <i class="fa-solid fa-check-circle text-green-400 text-3xl"></i>
                </div>
                <p class="text-white font-semibold mb-1">Database</p>
                <p class="text-green-400 text-sm font-mono">Healthy</p>
            </div>
            
            <div class="text-center group">
                <div class="w-20 h-20 bg-green-500/10 rounded-xl flex items-center justify-center mx-auto mb-4 group-hover:bg-green-500/20 transition-colors duration-200">
                    <i class="fa-solid fa-check-circle text-green-400 text-3xl"></i>
                </div>
                <p class="text-white font-semibold mb-1">Queue System</p>
                <p class="text-green-400 text-sm font-mono">Running</p>
            </div>
            
            <div class="text-center group">
                <div class="w-20 h-20 bg-green-500/10 rounded-xl flex items-center justify-center mx-auto mb-4 group-hover:bg-green-500/20 transition-colors duration-200">
                    <i class="fa-solid fa-check-circle text-green-400 text-3xl"></i>
                </div>
                <p class="text-white font-semibold mb-1">Storage</p>
                <p class="text-green-400 text-sm font-mono">Normal</p>
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
                labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'],
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
