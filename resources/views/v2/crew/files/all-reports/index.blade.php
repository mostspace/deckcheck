{{-- All Reports Tab Content --}}
<div class="mb-6">
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h1 class="text-2xl font-semibold text-[#0f1728]">All Reports</h1>
            <p class="text-[#475466]">Browse and access all available reports for this vessel.</p>
        </div>
    </div>
</div>

{{-- All Reports Grid --}}
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
    <div class="bg-white rounded-lg border border-[#e4e7ec] shadow-sm p-6 hover:shadow-md transition-shadow">
        <div class="flex items-center mb-4">
            <div class="w-10 h-10 bg-[#f0f9ff] rounded-lg flex items-center justify-center mr-3">
                <i class="text-[#0ea5e9] fa-solid fa-cog text-lg"></i>
            </div>
            <h3 class="text-lg font-semibold text-[#0f1728]">Equipment Inventory</h3>
        </div>
        <p class="text-[#475466] mb-4">Complete equipment inventory with specifications and status</p>
        <div class="flex items-center justify-between">
            <span class="text-sm text-[#667084]">Last updated: 2 hours ago</span>
            <button class="px-3 py-1.5 bg-primary-500 text-slate-800 rounded-lg text-sm font-medium hover:bg-primary-600 transition-colors">View</button>
        </div>
    </div>

    <div class="bg-white rounded-lg border border-[#e4e7ec] shadow-sm p-6 hover:shadow-md transition-shadow">
        <div class="flex items-center mb-4">
            <div class="w-10 h-10 bg-[#fef2f2] rounded-lg flex items-center justify-center mr-3">
                <i class="text-[#ef4444] fa-solid fa-exclamation-triangle text-lg"></i>
            </div>
            <h3 class="text-lg font-semibold text-[#0f1728]">Deficiencies Report</h3>
        </div>
        <p class="text-[#475466] mb-4">All deficiencies with status, age, and resolution tracking</p>
        <div class="flex items-center justify-between">
            <span class="text-sm text-[#667084]">Last updated: 1 hour ago</span>
            <button class="px-3 py-1.5 bg-primary-500 text-slate-800 rounded-lg text-sm font-medium hover:bg-primary-600 transition-colors">View</button>
        </div>
    </div>

    <div class="bg-white rounded-lg border border-[#e4e7ec] shadow-sm p-6 hover:shadow-md transition-shadow">
        <div class="flex items-center mb-4">
            <div class="w-10 h-10 bg-[#fef3c7] rounded-lg flex items-center justify-center mr-3">
                <i class="text-[#f59e0b] fa-solid fa-tasks text-lg"></i>
            </div>
            <h3 class="text-lg font-semibold text-[#0f1728]">Work Orders Summary</h3>
        </div>
        <p class="text-[#475466] mb-4">Summary of all work orders with completion status</p>
        <div class="flex items-center justify-between">
            <span class="text-sm text-[#667084]">Last updated: 30 min ago</span>
            <button class="px-3 py-1.5 bg-primary-500 text-slate-800 rounded-lg text-sm font-medium hover:bg-primary-600 transition-colors">View</button>
        </div>
    </div>

    <div class="bg-white rounded-lg border border-[#e4e7ec] shadow-sm p-6 hover:shadow-md transition-shadow">
        <div class="flex items-center mb-4">
            <div class="w-10 h-10 bg-[#f0fdf4] rounded-lg flex items-center justify-center mr-3">
                <i class="text-[#22c55e] fa-solid fa-calendar text-lg"></i>
            </div>
            <h3 class="text-lg font-semibold text-[#0f1728]">Maintenance Schedule</h3>
        </div>
        <p class="text-[#475466] mb-4">Upcoming maintenance schedule by equipment category</p>
        <div class="flex items-center justify-between">
            <span class="text-sm text-[#667084]">Last updated: 4 hours ago</span>
            <button class="px-3 py-1.5 bg-primary-500 text-slate-800 rounded-lg text-sm font-medium hover:bg-primary-600 transition-colors">View</button>
        </div>
    </div>
</div>
