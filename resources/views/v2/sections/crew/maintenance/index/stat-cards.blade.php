{{-- Stat Cards --}}
<div class="grid grid-cols-1 gap-4 sm:gap-6 md:grid-cols-3">
    {{-- Card 1: Total Equipment --}}
    <div class="relative rounded-lg border border-slate-200 bg-white shadow-sm transition hover:bg-slate-50">
        <div class="flex h-full flex-col justify-between">
            <div class="flex w-full flex-col gap-3 p-3 sm:p-4">
                <div class="flex items-center justify-between">
                    <h3 class="sm:text-md text-sm font-bold text-slate-800">Total equipment</h3>
                    <button class="rounded-md p-1 transition-colors hover:bg-slate-100">
                        <img src="./assets/media/icons/arrow-narrow-up-right.svg"
                            class="h-4 w-4 text-slate-800 sm:h-6 sm:w-6" alt="View details" />
                    </button>
                </div>
                <div class="flex items-center gap-2">
                    <div class="text-2xl font-bold text-slate-800 sm:text-3xl">{{ $totalEquipment }}</div>
                    <span class="rounded-full bg-slate-200 px-2 py-1 text-xs font-medium text-slate-800">+12%</span>
                </div>
            </div>
            <div class="w-full border-t border-slate-200 p-3 sm:p-4">
                <p class="text-xs text-slate-500 sm:text-sm">From previous month</p>
            </div>
        </div>
    </div>

    {{-- Card 2: Maintenance Items --}}
    <div class="relative rounded-lg border border-slate-200 bg-white p-3 transition hover:bg-slate-50 sm:p-4">
        <div class="flex h-full flex-col justify-between">
            <div class="flex items-start text-2xl font-bold text-slate-800 sm:text-3xl">+12<span
                    class="-mt-1 ml-1 text-[16px] sm:text-[20px]">%</span></div>
            <div class="flex items-end justify-between">
                <div>
                    <p class="sm:text-md mb-1 text-sm font-semibold text-slate-800">Maintenance items <span
                            class="text-xs text-slate-500">(8)</span></p>
                    <p class="text-xs text-slate-500 sm:text-sm">Compared to previous month</p>
                </div>
                <button class="rounded-md p-1 transition-colors hover:bg-slate-100">
                    <img src="./assets/media/icons/arrow-narrow-up-right.svg"
                        class="h-4 w-4 text-slate-800 sm:h-6 sm:w-6" alt="View details" />
                </button>
            </div>
        </div>
    </div>

    {{-- Card 3: Upcoming Requirements --}}
    <div
        class="relative rounded-lg border border-slate-200 bg-white p-3 shadow-sm transition hover:bg-slate-50 sm:col-span-2 sm:p-4 lg:col-span-1">
        <div class="flex h-full flex-col justify-between">
            <div class="flex items-center justify-between gap-1">
                <h3 class="sm:text-md text-sm font-bold text-slate-800">Upcoming requirements</h3>
                <div class="relative">
                    <button id="timeframeDropdown"
                        class="flex items-center gap-1 text-xs text-slate-500 transition-colors duration-200 hover:text-slate-700 sm:gap-2">
                        <span id="selectedTimeframe" class="hidden sm:inline">Past 30 days</span>
                        <span class="sm:hidden">30d</span>
                        <img src="./assets/media/icons/chevron-down.svg"
                            class="h-3 w-3 text-slate-800 transition-transform duration-200" alt="Dropdown" />
                    </button>

                    <!-- Dropdown Menu -->
                    <div id="timeframeMenu"
                        class="invisible absolute right-0 top-full z-10 mt-1 w-28 scale-95 transform rounded-lg border border-slate-200 bg-white opacity-0 shadow-lg transition-all duration-200 ease-out sm:w-32">
                        <div class="py-1">
                            <button
                                class="w-full px-2 py-2 text-left text-xs text-slate-700 transition-colors duration-150 hover:bg-slate-50 sm:px-3"
                                data-value="30">
                                Past 30 days
                            </button>
                            <button
                                class="w-full px-2 py-2 text-left text-xs text-slate-700 transition-colors duration-150 hover:bg-slate-50 sm:px-3"
                                data-value="7">
                                Past 7 days
                            </button>
                        </div>
                    </div>
                </div>
            </div>
            <div class="flex flex-col">
                <div class="text-2xl font-bold text-slate-800 sm:text-3xl">16</div>
                <div class="flex items-center gap-2">
                    <div class="flex h-3 w-3 items-center justify-center rounded bg-slate-100 sm:h-4 sm:w-4">
                        <img src="./assets/media/icons/arrow-narrow-down.svg"
                            class="h-2 w-2 text-slate-800 sm:h-3 sm:w-3" alt="Decrease" />
                    </div>
                    <span class="text-xs font-medium text-slate-800 sm:text-sm">- 2,4%</span>
                </div>
            </div>
        </div>
    </div>
</div>
