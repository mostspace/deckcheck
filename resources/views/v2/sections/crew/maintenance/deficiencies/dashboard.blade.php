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
