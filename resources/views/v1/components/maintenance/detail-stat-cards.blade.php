<div class="mb-6 grid grid-cols-1 gap-6 md:grid-cols-4">

    {{-- Total Units --}}
    <div id="total-units" class="rounded-lg border border-[#e4e7ec] bg-white p-5 shadow-sm">
        <div class="mb-4 flex items-start justify-between">
            <h3 class="text-sm font-medium text-[#475466]">Total Units</h3>
            <div class="rounded-md bg-[#fef2f2] p-2">
                <i class="fa-solid fa-fire-extinguisher text-[#dc2626]"></i>
            </div>
        </div>
        <div class="flex items-end">
            <span class="text-3xl font-bold text-[#0f1728]">{{ $category->equipment_count }}</span>
        </div>
        <p class="mt-1 text-sm text-[#475466]">Across all decks</p>
    </div>

    {{-- Active Requirements --}}
    <div id="maintenance-tasks" class="rounded-lg border border-[#e4e7ec] bg-white p-5 shadow-sm">
        <div class="mb-4 flex items-start justify-between">
            <h3 class="text-sm font-medium text-[#475466]">Maintenance Tasks</h3>
            <div class="rounded-md bg-[#f9f5ff] p-2">
                <i class="fa-solid fa-wrench text-[#6840c6]"></i>
            </div>
        </div>
        <div class="flex items-end">
            <span class="text-3xl font-bold text-[#0f1728]">##</span>
        </div>
        <p class="mt-1 text-sm text-[#475466]">Active requirements</p>
    </div>

    {{-- Overdue Items --}}
    <div id="overdue-items" class="rounded-lg border border-[#e4e7ec] bg-white p-5 shadow-sm">
        <div class="mb-4 flex items-start justify-between">
            <h3 class="text-sm font-medium text-[#475466]">Overdue Items</h3>
            <div class="rounded-md bg-[#fef2f2] p-2">
                <i class="fa-solid fa-triangle-exclamation text-[#dc2626]"></i>
            </div>
        </div>
        <div class="flex items-end">
            <span class="text-3xl font-bold text-[#dc2626]">##</span>
        </div>
        <p class="mt-1 text-sm text-[#475466]">Require attention</p>
    </div>

    {{-- Compliance Rate --}}
    <div id="compliance-rate" class="rounded-lg border border-[#e4e7ec] bg-white p-5 shadow-sm">
        <div class="mb-4 flex items-start justify-between">
            <h3 class="text-sm font-medium text-[#475466]">Compliance Rate</h3>
            <div class="rounded-md bg-[#f0fdf4] p-2">
                <i class="fa-solid fa-check-circle text-[#16a34a]"></i>
            </div>
        </div>
        <div class="flex items-end">
            <span class="text-3xl font-bold text-[#16a34a]">##%</span>
        </div>
        <p class="mt-1 text-sm text-[#475466]">Current period</p>
    </div>

</div>
