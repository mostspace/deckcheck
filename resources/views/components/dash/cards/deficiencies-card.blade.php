<div id="deficiencies-card" class="bg-white rounded-lg shadow-sm border border-[#e4e7ec] p-5">
    <div class="flex items-center justify-between mb-4">
        <h2 class="text-lg font-semibold text-[#0f1728]">Open Deficiencies</h2>
        <button class="text-[#6840c6] text-sm font-medium hover:underline">View All</button>
    </div>
    <div class="mb-4">
        <div class="flex justify-between text-sm text-[#475466] mb-1">
            <span>Total Open</span><span class="font-medium text-[#0f1728]">12</span>
        </div>
        <div class="h-2 bg-gray-100 rounded-full overflow-hidden">
            <div class="flex h-full">
                <div class="h-full bg-[#f04438]" style="width: 25%"></div>
                <div class="h-full bg-[#f79009]" style="width: 33%"></div>
                <div class="h-full bg-[#12b669]" style="width: 42%"></div>
            </div>
        </div>
        <div class="flex justify-between text-xs text-[#475466] mt-1">
            <span>> 60 Days</span>
            <span>30-60 Days</span>
            <span>< 30 Days</span>
        </div>
    </div>
    {{-- Example deficiency entries --}}
    <div class="space-y-3 text-sm">
        <div class="flex justify-between border-b border-[#e4e7ec] pb-2">
            <div><span class="inline-block w-2 h-2 bg-[#f04438] rounded-full mr-2"></span>Main Engine Coolant Leak<br><span class="text-xs text-[#475466]">Engine Room - ME-01</span></div>
            <div class="text-xs font-medium text-[#f04438]">45 days</div>
        </div>
    </div>
</div>