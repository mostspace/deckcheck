{{--Stat Cards--}}
<div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">     
    {{--Card 1: Total Equipment--}}
    <div class="bg-white border border-slate-200 rounded-lg relative shadow-sm hover:bg-slate-50 transition">
        <div class="h-full flex flex-col justify-between">
            <div class="w-full flex flex-col gap-3 p-3 sm:p-4">
                <div class="flex justify-between items-center">
                <h3 class="text-sm sm:text-md font-bold text-slate-800">Total equipment</h3>
                <button class="p-1 hover:bg-slate-100 rounded-md transition-colors">
                    <img src="./assets/media/icons/arrow-narrow-up-right.svg" class="h-4 w-4 sm:h-6 sm:w-6 text-slate-800" alt="View details" />
                </button>
                </div>
            <div class="flex items-center gap-2">
                <div class="text-2xl sm:text-3xl font-bold text-slate-800">{{ $totalEquipment }}</div>
                <span class="px-2 py-1 bg-slate-200 text-slate-800 text-xs rounded-full font-medium">+12%</span>
            </div>
            </div>
            <div class="w-full border-t border-slate-200 p-3 sm:p-4">
            <p class="text-xs sm:text-sm text-slate-500">From previous month</p>
            </div>
        </div>
    </div>

    {{--Card 2: Maintenance Items--}}
    <div class="bg-white border border-slate-200 p-3 sm:p-4 rounded-lg relative hover:bg-slate-50 transition">
        <div class="h-full flex flex-col justify-between">
            <div class="text-2xl sm:text-3xl font-bold text-slate-800 flex items-start">+12<span class="text-[16px] sm:text-[20px] ml-1 -mt-1">%</span></div>
            <div class="flex justify-between items-end">
                <div>
                <p class="text-sm sm:text-md font-semibold text-slate-800 mb-1">Maintenance items <span class="text-slate-500 text-xs">(8)</span></p>
                <p class="text-xs sm:text-sm text-slate-500">Compared to previous month</p>
                </div>
                <button class="p-1 hover:bg-slate-100 rounded-md transition-colors">
                <img src="./assets/media/icons/arrow-narrow-up-right.svg" class="h-4 w-4 sm:h-6 sm:w-6 text-slate-800" alt="View details" />
                </button>
            </div>
        </div>
    </div>
    
    {{--Card 3: Upcoming Requirements--}}
    <div class="bg-white border border-slate-200 p-3 sm:p-4 rounded-lg relative shadow-sm sm:col-span-2 lg:col-span-1 hover:bg-slate-50 transition">
        <div class="h-full flex flex-col justify-between">
            <div class="flex justify-between items-center gap-1">
                <h3 class="text-sm sm:text-md font-bold text-slate-800">Upcoming requirements</h3>
                <div class="relative">
                <button id="timeframeDropdown" class="flex items-center gap-1 sm:gap-2 text-xs text-slate-500 hover:text-slate-700 transition-colors duration-200">
                    <span id="selectedTimeframe" class="hidden sm:inline">Past 30 days</span>
                    <span class="sm:hidden">30d</span>
                    <img src="./assets/media/icons/chevron-down.svg" class="h-3 w-3 text-slate-800 transition-transform duration-200" alt="Dropdown" />
                </button>
                
                <!-- Dropdown Menu -->
                <div id="timeframeMenu" class="absolute right-0 top-full mt-1 w-28 sm:w-32 bg-white border border-slate-200 rounded-lg shadow-lg opacity-0 invisible transform scale-95 transition-all duration-200 ease-out z-10">
                    <div class="py-1">
                    <button class="w-full text-left px-2 sm:px-3 py-2 text-xs text-slate-700 hover:bg-slate-50 transition-colors duration-150" data-value="30">
                        Past 30 days
                    </button>
                    <button class="w-full text-left px-2 sm:px-3 py-2 text-xs text-slate-700 hover:bg-slate-50 transition-colors duration-150" data-value="7">
                        Past 7 days
                    </button>
                    </div>
                </div>
                </div>
            </div>
            <div class="flex flex-col">
            <div class="text-2xl sm:text-3xl font-bold text-slate-800">16</div>
            <div class="flex items-center gap-2">
                <div class="w-3 h-3 sm:w-4 sm:h-4 bg-slate-100 rounded flex items-center justify-center">
                <img src="./assets/media/icons/arrow-narrow-down.svg" class="h-2 w-2 sm:h-3 sm:w-3 text-slate-800" alt="Decrease" />
                </div>
                <span class="text-xs sm:text-sm font-medium text-slate-800">- 2,4%</span>
            </div>
            </div>
        </div>
    </div>
</div>