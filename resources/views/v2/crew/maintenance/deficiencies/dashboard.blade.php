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