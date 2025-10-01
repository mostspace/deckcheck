{{-- My Reports Tab Content --}}
<div class="mb-6">
    <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
        <div>
            <h1 class="text-2xl font-semibold text-[#0f1728]">My Reports</h1>
            <p class="text-[#475466]">Your custom reports and saved report configurations.</p>
        </div>
        <x-v2.components.ui.button type="button" icon="fa-solid fa-plus">
            Create Report
        </x-v2.components.ui.button>
    </div>
</div>

{{-- My Reports List --}}
<div class="space-y-4">
    <div class="rounded-lg border border-[#e4e7ec] bg-white p-6 shadow-sm">
        <div class="flex items-center justify-between">
            <div class="flex items-center">
                <div class="mr-4 flex h-10 w-10 items-center justify-center rounded-lg bg-[#f9f5ff]">
                    <i class="fa-solid fa-chart-line text-lg text-[#6840c6]"></i>
                </div>
                <div>
                    <h3 class="text-lg font-semibold text-[#0f1728]">Weekly Equipment Status</h3>
                    <p class="text-[#475466]">Custom report for weekly equipment status review</p>
                </div>
            </div>
            <div class="flex items-center gap-2">
                <span class="text-sm text-[#667084]">Last run: 1 day ago</span>
                <button
                    class="rounded-lg bg-primary-500 px-3 py-1.5 text-sm font-medium text-slate-800 transition-colors hover:bg-primary-600">Run</button>
                <button
                    class="rounded-lg bg-primary-500 px-3 py-1.5 text-sm font-medium text-slate-800 transition-colors hover:bg-primary-600">Edit</button>
            </div>
        </div>
    </div>

    <div class="rounded-lg border border-[#e4e7ec] bg-white p-6 shadow-sm">
        <div class="flex items-center justify-between">
            <div class="flex items-center">
                <div class="mr-4 flex h-10 w-10 items-center justify-center rounded-lg bg-[#fef2f2]">
                    <i class="fa-solid fa-exclamation-triangle text-lg text-[#ef4444]"></i>
                </div>
                <div>
                    <h3 class="text-lg font-semibold text-[#0f1728]">Monthly Deficiencies Summary</h3>
                    <p class="text-[#475466]">Monthly summary of all deficiencies and their status</p>
                </div>
            </div>
            <div class="flex items-center gap-2">
                <span class="text-sm text-[#667084]">Last run: 1 week ago</span>
                <button
                    class="rounded-lg bg-primary-500 px-3 py-1.5 text-sm font-medium text-slate-800 transition-colors hover:bg-primary-600">Run</button>
                <button
                    class="rounded-lg bg-primary-500 px-3 py-1.5 text-sm font-medium text-slate-800 transition-colors hover:bg-primary-600">Edit</button>
            </div>
        </div>
    </div>
</div>
