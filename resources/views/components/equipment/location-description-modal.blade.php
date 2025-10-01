{{-- Overlay --}}
<div id="location-description-modal-overlay" class="fixed inset-0 z-40 hidden bg-black bg-opacity-70"></div>

<div id="location-description-modal" class="fixed inset-0 z-50 flex hidden items-center justify-center p-4">
    <div class="w-full max-w-xl overflow-hidden rounded-lg bg-white shadow-xl">

        {{-- #Header --}}
        <div class="flex justify-between border-b px-6 py-4">
            <div class="flex items-center gap-2">
                <i class="fa-solid fa-location-dot text-sm text-[#6840c6]"></i>
                <span class="text-lg font-bold text-[#484f5d]">{{ $equipment->deck->name }} /</span>
                <span class="text-lg text-[#6840c6]">{{ $equipment->location->name }}</span>
            </div>
            <button id="location-description-modal-close" class="text-gray-400 hover:text-gray-600">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24"
                    stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>

        {{-- #Body --}}
        <div class="flex items-center justify-between px-6 py-4">

            @isset($equipment->location->description)
                <p class="text-md mb-4 font-light text-[#667084]">{{ $equipment->location->description }}</p>
            @else
                <p class="text-md mb-4 font-light text-[#667084]">No location description available</p>
            @endisset

        </div>
    </div>
</div>
