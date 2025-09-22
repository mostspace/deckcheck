{{-- My Reports Tab Content --}}
<div class="mb-6">
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h1 class="text-2xl font-semibold text-[#0f1728]">My Reports</h1>
            <p class="text-[#475466]">Your custom reports and saved report configurations.</p>
        </div>
        <button class="px-4 py-2 bg-[#6840c6] text-white rounded-lg text-sm font-medium hover:bg-[#5a35a8] transition-colors">
            <i class="fa-solid fa-plus mr-2"></i>
            Create Report
        </button>
    </div>
</div>

{{-- My Reports List --}}
<div class="space-y-4">
    <div class="bg-white rounded-lg border border-[#e4e7ec] shadow-sm p-6">
        <div class="flex items-center justify-between">
            <div class="flex items-center">
                <div class="w-10 h-10 bg-[#f9f5ff] rounded-lg flex items-center justify-center mr-4">
                    <i class="text-[#6840c6] fa-solid fa-chart-line text-lg"></i>
                </div>
                <div>
                    <h3 class="text-lg font-semibold text-[#0f1728]">Weekly Equipment Status</h3>
                    <p class="text-[#475466]">Custom report for weekly equipment status review</p>
                </div>
            </div>
            <div class="flex items-center gap-2">
                <span class="text-sm text-[#667084]">Last run: 1 day ago</span>
                <button class="px-3 py-1.5 bg-primary-500 text-slate-800 rounded-lg text-sm font-medium hover:bg-primary-600 transition-colors">Run</button>
                <button class="px-3 py-1.5 bg-primary-500 text-slate-800 rounded-lg text-sm font-medium hover:bg-primary-600 transition-colors">Edit</button>
            </div>
        </div>
    </div>

    <div class="bg-white rounded-lg border border-[#e4e7ec] shadow-sm p-6">
        <div class="flex items-center justify-between">
            <div class="flex items-center">
                <div class="w-10 h-10 bg-[#fef2f2] rounded-lg flex items-center justify-center mr-4">
                    <i class="text-[#ef4444] fa-solid fa-exclamation-triangle text-lg"></i>
                </div>
                <div>
                    <h3 class="text-lg font-semibold text-[#0f1728]">Monthly Deficiencies Summary</h3>
                    <p class="text-[#475466]">Monthly summary of all deficiencies and their status</p>
                </div>
            </div>
            <div class="flex items-center gap-2">
                <span class="text-sm text-[#667084]">Last run: 1 week ago</span>
                <button class="px-3 py-1.5 bg-primary-500 text-slate-800 rounded-lg text-sm font-medium hover:bg-primary-600 transition-colors">Run</button>
                <button class="px-3 py-1.5 bg-primary-500 text-slate-800 rounded-lg text-sm font-medium hover:bg-primary-600 transition-colors">Edit</button>
            </div>
        </div>
    </div>
</div>
