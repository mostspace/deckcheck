{{-- All Reports Tab Content --}}
<div class="mb-6">
    <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
        <div>
            <h1 class="text-2xl font-semibold text-[#0f1728]">All Reports</h1>
            <p class="text-[#475466]">Browse and access all available reports for this vessel.</p>
        </div>
    </div>
</div>

{{-- All Reports Grid --}}
<div class="grid grid-cols-1 gap-6 md:grid-cols-2 lg:grid-cols-3">
    <div class="rounded-lg border border-[#e4e7ec] bg-white p-6 shadow-sm transition-shadow hover:shadow-md">
        <div class="mb-4 flex items-center">
            <div class="mr-3 flex h-10 w-10 items-center justify-center rounded-lg bg-[#f0f9ff]">
                <i class="fa-solid fa-cog text-lg text-[#0ea5e9]"></i>
            </div>
            <h3 class="text-lg font-semibold text-[#0f1728]">Equipment Inventory</h3>
        </div>
        <p class="mb-4 text-[#475466]">Complete equipment inventory with specifications and status</p>
        <div class="flex items-center justify-between">
            <span class="text-sm text-[#667084]">Last updated: 2 hours ago</span>
            <button
                class="rounded-lg bg-primary-500 px-3 py-1.5 text-sm font-medium text-slate-800 transition-colors hover:bg-primary-600">View</button>
        </div>
    </div>

    <div class="rounded-lg border border-[#e4e7ec] bg-white p-6 shadow-sm transition-shadow hover:shadow-md">
        <div class="mb-4 flex items-center">
            <div class="mr-3 flex h-10 w-10 items-center justify-center rounded-lg bg-[#fef2f2]">
                <i class="fa-solid fa-exclamation-triangle text-lg text-[#ef4444]"></i>
            </div>
            <h3 class="text-lg font-semibold text-[#0f1728]">Deficiencies Report</h3>
        </div>
        <p class="mb-4 text-[#475466]">All deficiencies with status, age, and resolution tracking</p>
        <div class="flex items-center justify-between">
            <span class="text-sm text-[#667084]">Last updated: 1 hour ago</span>
            <button
                class="rounded-lg bg-primary-500 px-3 py-1.5 text-sm font-medium text-slate-800 transition-colors hover:bg-primary-600">View</button>
        </div>
    </div>

    <div class="rounded-lg border border-[#e4e7ec] bg-white p-6 shadow-sm transition-shadow hover:shadow-md">
        <div class="mb-4 flex items-center">
            <div class="mr-3 flex h-10 w-10 items-center justify-center rounded-lg bg-[#fef3c7]">
                <i class="fa-solid fa-tasks text-lg text-[#f59e0b]"></i>
            </div>
            <h3 class="text-lg font-semibold text-[#0f1728]">Work Orders Summary</h3>
        </div>
        <p class="mb-4 text-[#475466]">Summary of all work orders with completion status</p>
        <div class="flex items-center justify-between">
            <span class="text-sm text-[#667084]">Last updated: 30 min ago</span>
            <button
                class="rounded-lg bg-primary-500 px-3 py-1.5 text-sm font-medium text-slate-800 transition-colors hover:bg-primary-600">View</button>
        </div>
    </div>

    <div class="rounded-lg border border-[#e4e7ec] bg-white p-6 shadow-sm transition-shadow hover:shadow-md">
        <div class="mb-4 flex items-center">
            <div class="mr-3 flex h-10 w-10 items-center justify-center rounded-lg bg-[#f0fdf4]">
                <i class="fa-solid fa-calendar text-lg text-[#22c55e]"></i>
            </div>
            <h3 class="text-lg font-semibold text-[#0f1728]">Maintenance Schedule</h3>
        </div>
        <p class="mb-4 text-[#475466]">Upcoming maintenance schedule by equipment category</p>
        <div class="flex items-center justify-between">
            <span class="text-sm text-[#667084]">Last updated: 4 hours ago</span>
            <button
                class="rounded-lg bg-primary-500 px-3 py-1.5 text-sm font-medium text-slate-800 transition-colors hover:bg-primary-600">View</button>
        </div>
    </div>
</div>
