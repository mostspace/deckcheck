{{-- Stat Cards --}}
<div class="mb-6 grid grid-cols-1 gap-6 md:grid-cols-3">

    {{-- Card 1: Total Equipment --}}
    <div id="total-equipment" class="rounded-lg border border-[#e4e7ec] bg-white p-5 shadow-sm">
        <div class="mb-4 flex items-start justify-between">
            <h3 class="text-sm font-medium text-[#475466]">Total Equipment</h3>
            <div class="rounded-md bg-[#f9f5ff] p-2">
                <i class="fa-solid fa-ship text-[#6840c6]"></i>
            </div>
        </div>
        <div class="flex items-end">
            <span class="text-3xl font-bold text-[#0f1728]">{{ $totalEquipment }}</span>
            <span class="ml-2 flex items-center text-sm text-[#12b669]">
                <i class="fa-solid fa-arrow-up mr-1"></i>
                0.0%
            </span>
        </div>
        <p class="mt-1 text-sm text-[#475466]">From previous month</p>
    </div>

    {{-- Card 2: Maintenance Items --}}
    <div id="maintenance-items" class="rounded-lg border border-[#e4e7ec] bg-white p-5 shadow-sm">
        <div class="mb-4 flex items-start justify-between">
            <h3 class="text-sm font-medium text-[#475466]">Maintenance Items</h3>
            <div class="rounded-md bg-[#f9f5ff] p-2">
                <i class="fa-solid fa-wrench text-[#6840c6]"></i>
            </div>
        </div>
        <div class="flex items-end">
            <span class="text-3xl font-bold text-[#0f1728]">##</span>
            <span class="ml-2 flex items-center text-sm text-[#12b669]">
                <i class="fa-solid fa-arrow-up mr-1"></i>
                0.0%
            </span>
        </div>
        <p class="mt-1 text-sm text-[#475466]">From previous month</p>
    </div>

    {{-- Card 3: Upcoming Requirements --}}
    <div id="upcoming-requirements" class="rounded-lg border border-[#e4e7ec] bg-white p-5 shadow-sm">
        <div class="mb-4 flex items-start justify-between">
            <h3 class="text-sm font-medium text-[#475466]">Upcoming Requirements</h3>
            <div class="rounded-md bg-[#f9f5ff] p-2">
                <i class="fa-solid fa-calendar text-[#6840c6]"></i>
            </div>
        </div>
        <div class="flex items-end">
            <span class="text-3xl font-bold text-[#0f1728]">##</span>
            <span class="ml-2 flex items-center text-sm text-[#f04438]">
                <i class="fa-solid fa-arrow-down mr-1"></i>
                0.0%
            </span>
        </div>
        <p class="mt-1 text-sm text-[#475466]">Due in next 30 days</p>
    </div>

</div>
