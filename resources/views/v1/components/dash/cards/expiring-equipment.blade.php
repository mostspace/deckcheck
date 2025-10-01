<div id="expiring-equipment-card" class="rounded-lg border border-[#e4e7ec] bg-white p-5 shadow-sm">
    <div class="mb-4 flex items-center justify-between">
        <h2 class="text-lg font-semibold text-[#0f1728]">Equipment Expiring (Next 30 Days)</h2>
        <button class="text-sm font-medium text-[#6840c6] hover:underline">View All</button>
    </div>
    <div class="grid grid-cols-1 gap-4 md:grid-cols-3">
        {{-- Example equipment cards, can be looped dynamically --}}
        <div class="cursor-pointer rounded-lg border border-[#e4e7ec] p-4 hover:border-[#6840c6]">
            <div class="mb-2 flex justify-between">
                <div class="rounded-md bg-[#f9f5ff] p-2"><i class="fa-solid fa-fire-extinguisher text-[#6840c6]"></i>
                </div>
                <span class="rounded-full bg-[#fef3f2] px-2 py-1 text-xs font-medium text-[#f04438]">7 days</span>
            </div>
            <h3 class="font-medium text-[#0f1728]">Fire Extinguisher</h3>
            <p class="text-xs text-[#475466]">ID: FE-103</p>
            <p class="text-xs text-[#475466]">Location: Crew Quarters</p>
            <p class="mt-2 text-xs font-medium text-[#f04438]">Expires: June 12, 2024</p>
        </div>
    </div>
</div>
