<div id="expiring-equipment-card" class="bg-white rounded-lg shadow-sm border border-[#e4e7ec] p-5">
    <div class="flex items-center justify-between mb-4">
        <h2 class="text-lg font-semibold text-[#0f1728]">Equipment Expiring (Next 30 Days)</h2>
        <button class="text-[#6840c6] text-sm font-medium hover:underline">View All</button>
    </div>
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
        {{-- Example equipment cards, can be looped dynamically --}}
        <div class="border border-[#e4e7ec] rounded-lg p-4 hover:border-[#6840c6] cursor-pointer">
            <div class="flex justify-between mb-2">
                <div class="p-2 bg-[#f9f5ff] rounded-md"><i class="fa-solid fa-fire-extinguisher text-[#6840c6]"></i></div>
                <span class="text-xs font-medium text-[#f04438] bg-[#fef3f2] px-2 py-1 rounded-full">7 days</span>
            </div>
            <h3 class="font-medium text-[#0f1728]">Fire Extinguisher</h3>
            <p class="text-xs text-[#475466]">ID: FE-103</p>
            <p class="text-xs text-[#475466]">Location: Crew Quarters</p>
            <p class="text-xs font-medium text-[#f04438] mt-2">Expires: June 12, 2024</p>
        </div>
    </div>
</div>