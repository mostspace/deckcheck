<div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-6">
                    
    
    {{--Total Units--}}
    <div id="total-units" class="bg-white p-5 rounded-lg border border-[#e4e7ec] shadow-sm">                
        <div class="flex justify-between items-start mb-4">
            <h3 class="text-[#475466] text-sm font-medium">Total Units</h3>
            <div class="p-2 bg-[#fef2f2] rounded-md">
                <i class="fa-solid fa-fire-extinguisher text-[#dc2626]"></i>
            </div>
        </div>
        <div class="flex items-end">
            <span class="text-3xl font-bold text-[#0f1728]">{{ $category->equipment_count }}</span>
        </div>
        <p class="text-[#475466] text-sm mt-1">Across all decks</p>
    </div>
                    
    {{--Active Requirements--}}               
    <div id="maintenance-tasks" class="bg-white p-5 rounded-lg border border-[#e4e7ec] shadow-sm">
        <div class="flex justify-between items-start mb-4">
            <h3 class="text-[#475466] text-sm font-medium">Maintenance Tasks</h3>
            <div class="p-2 bg-[#f9f5ff] rounded-md">
                <i class="fa-solid fa-wrench text-[#6840c6]"></i>
            </div>
        </div>
        <div class="flex items-end">
            <span class="text-3xl font-bold text-[#0f1728]">##</span>
        </div>
        <p class="text-[#475466] text-sm mt-1">Active requirements</p>
    </div>

    {{--Overdue Items--}}   
    <div id="overdue-items" class="bg-white p-5 rounded-lg border border-[#e4e7ec] shadow-sm">
        <div class="flex justify-between items-start mb-4">
            <h3 class="text-[#475466] text-sm font-medium">Overdue Items</h3>
            <div class="p-2 bg-[#fef2f2] rounded-md">
                <i class="fa-solid fa-triangle-exclamation text-[#dc2626]"></i>
            </div>
        </div>
        <div class="flex items-end">
            <span class="text-3xl font-bold text-[#dc2626]">##</span>
        </div>
        <p class="text-[#475466] text-sm mt-1">Require attention</p>
    </div>

    {{--Compliance Rate--}}   
    <div id="compliance-rate" class="bg-white p-5 rounded-lg border border-[#e4e7ec] shadow-sm">
        <div class="flex justify-between items-start mb-4">
            <h3 class="text-[#475466] text-sm font-medium">Compliance Rate</h3>
            <div class="p-2 bg-[#f0fdf4] rounded-md">
                <i class="fa-solid fa-check-circle text-[#16a34a]"></i>
            </div>
        </div>
        <div class="flex items-end">
            <span class="text-3xl font-bold text-[#16a34a]">##%</span>
        </div>
        <p class="text-[#475466] text-sm mt-1">Current period</p>
    </div>

</div>