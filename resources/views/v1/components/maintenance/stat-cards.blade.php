{{--Stat Cards--}}
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
                    
                    {{--Card 1: Total Equipment--}}
                    <div id="total-equipment" class="bg-white p-5 rounded-lg border border-[#e4e7ec] shadow-sm">
                        <div class="flex justify-between items-start mb-4">
                            <h3 class="text-[#475466] text-sm font-medium">Total Equipment</h3>
                            <div class="p-2 bg-[#f9f5ff] rounded-md">
                                <i class="fa-solid fa-ship text-[#6840c6]"></i>
                            </div>
                        </div>
                        <div class="flex items-end">
                            <span class="text-3xl font-bold text-[#0f1728]">{{ $totalEquipment }}</span>
                            <span class="ml-2 text-sm text-[#12b669] flex items-center">
                                <i class="fa-solid fa-arrow-up mr-1"></i>
                                0.0%
                            </span>
                        </div>
                        <p class="text-[#475466] text-sm mt-1">From previous month</p>
                    </div>
                    
                    {{--Card 2: Maintenance Items--}}
                    <div id="maintenance-items" class="bg-white p-5 rounded-lg border border-[#e4e7ec] shadow-sm">
                        <div class="flex justify-between items-start mb-4">
                            <h3 class="text-[#475466] text-sm font-medium">Maintenance Items</h3>
                            <div class="p-2 bg-[#f9f5ff] rounded-md">
                                <i class="fa-solid fa-wrench text-[#6840c6]"></i>
                            </div>
                        </div>
                        <div class="flex items-end">
                            <span class="text-3xl font-bold text-[#0f1728]">##</span>
                            <span class="ml-2 text-sm text-[#12b669] flex items-center">
                                <i class="fa-solid fa-arrow-up mr-1"></i>
                                0.0%
                            </span>
                        </div>
                        <p class="text-[#475466] text-sm mt-1">From previous month</p>
                    </div>
                    
                    {{--Card 3: Upcoming Requirements--}}
                    <div id="upcoming-requirements" class="bg-white p-5 rounded-lg border border-[#e4e7ec] shadow-sm">
                        <div class="flex justify-between items-start mb-4">
                            <h3 class="text-[#475466] text-sm font-medium">Upcoming Requirements</h3>
                            <div class="p-2 bg-[#f9f5ff] rounded-md">
                                <i class="fa-solid fa-calendar text-[#6840c6]"></i>
                            </div>
                        </div>
                        <div class="flex items-end">
                            <span class="text-3xl font-bold text-[#0f1728]">##</span>
                            <span class="ml-2 text-sm text-[#f04438] flex items-center">
                                <i class="fa-solid fa-arrow-down mr-1"></i>
                                0.0%
                            </span>
                        </div>
                        <p class="text-[#475466] text-sm mt-1">Due in next 30 days</p>
                    </div>

    </div>