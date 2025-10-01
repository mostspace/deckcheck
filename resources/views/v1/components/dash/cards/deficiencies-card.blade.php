<div id="deficiencies-card" class="rounded-lg border border-[#e4e7ec] bg-white p-5 shadow-sm">
    <div class="mb-4 flex items-center justify-between">
        <h2 class="text-lg font-semibold text-[#0f1728]">Open Deficiencies</h2>
        <button class="text-sm font-medium text-[#6840c6] hover:underline">View All</button>
    </div>
    <div class="mb-4">
        <div class="mb-1 flex justify-between text-sm text-[#475466]">
            <span>Total Open</span><span class="font-medium text-[#0f1728]">12</span>
        </div>
        <div class="h-2 overflow-hidden rounded-full bg-gray-100">
            <div class="flex h-full">
                <div class="h-full bg-[#f04438]" style="width: 25%"></div>
                <div class="h-full bg-[#f79009]" style="width: 33%"></div>
                <div class="h-full bg-[#12b669]" style="width: 42%"></div>
            </div>
        </div>
        <div class="mt-1 flex justify-between text-xs text-[#475466]">
            <span>> 60 Days</span>
            <span>30-60 Days</span>
            <span>
                < 30 Days</span>
        </div>
    </div>
    {{-- Example deficiency entries --}}
    <div class="space-y-3 text-sm">
        <div class="flex justify-between border-b border-[#e4e7ec] pb-2">
            <div><span class="mr-2 inline-block h-2 w-2 rounded-full bg-[#f04438]"></span>Main Engine Coolant
                Leak<br><span class="text-xs text-[#475466]">Engine Room - ME-01</span></div>
            <div class="text-xs font-medium text-[#f04438]">45 days</div>
        </div>
    </div>
</div>
