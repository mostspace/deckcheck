{{-- Overlay --}}
<div id="edit-equipment-overlay" class="fixed inset-0 z-40 hidden bg-black bg-opacity-70"></div>

<div id="edit-equipment-modal" class="fixed inset-0 z-50 flex hidden items-center justify-center p-4">
    <div class="w-full max-w-2xl overflow-hidden rounded-lg bg-white shadow-xl">

        {{-- Header --}}
        <div class="flex items-center justify-between border-b px-6 py-4">
            <h3 class="text-xl font-semibold text-gray-900"><span class="font-bold text-[#6840c6]">Edit</span> Equipment
                Data</h3>
            <button class="close-edit-equipment text-gray-500 hover:text-gray-700">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24"
                    stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>

        {{-- Form --}}
        <form method="POST" action="{{ route('equipment.updateData', $equipment) }}" class="space-y-4 px-6 py-5">
            @csrf @method('PUT')

            <div class="grid grid-cols-1 gap-4 md:grid-cols-2">
                <!-- Manufacturer -->
                <div>
                    <label for="manufacturer" class="block text-sm font-medium text-gray-700">
                        Manufacturer
                    </label>
                    <input id="manufacturer" name="manufacturer" type="text"
                        value="{{ old('manufacturer', $equipment->manufacturer) }}"
                        class="mt-1 block w-full rounded-md border px-3 py-2 focus:outline-none focus:ring-2 focus:ring-indigo-200">
                </div>

                <!-- Model / Part # -->
                <div>
                    <label for="model" class="block text-sm font-medium text-gray-700">
                        Model / Part #
                    </label>
                    <input id="model" name="model" type="text" value="{{ old('model', $equipment->model) }}"
                        class="mt-1 block w-full rounded-md border px-3 py-2 focus:outline-none focus:ring-2 focus:ring-indigo-200">
                </div>

                <!-- Serial Number -->
                <div>
                    <label for="serial_number" class="block text-sm font-medium text-gray-700">
                        Serial Number
                    </label>
                    <input id="serial_number" name="serial_number" type="text"
                        value="{{ old('serial_number', $equipment->serial_number) }}"
                        class="mt-1 block w-full rounded-md border px-3 py-2 focus:outline-none focus:ring-2 focus:ring-indigo-200">
                </div>

                <!-- Custom ID (internal_id) -->
                <div>
                    <label for="internal_id" class="block text-sm font-medium text-gray-700">
                        Custom ID
                    </label>
                    <input id="internal_id" name="internal_id" type="text"
                        value="{{ old('internal_id', $equipment->internal_id) }}"
                        class="mt-1 block w-full rounded-md border px-3 py-2 focus:outline-none focus:ring-2 focus:ring-indigo-200">
                </div>

                <!-- Purchase Date -->
                <div>
                    <label for="purchase_date" class="block text-sm font-medium text-gray-700">
                        Purchase Date
                    </label>
                    <input id="purchase_date" name="purchase_date" type="date"
                        value="{{ old('purchase_date', $equipment->purchase_date?->toDateString()) }}"
                        class="mt-1 block w-full rounded-md border px-3 py-2 focus:outline-none focus:ring-2 focus:ring-indigo-200">
                </div>

                <!-- Manufacturing Date -->
                <div>
                    <label for="manufacturing_date" class="block text-sm font-medium text-gray-700">
                        Manufacturing Date
                    </label>
                    <input id="manufacturing_date" name="manufacturing_date" type="date"
                        value="{{ old('manufacturing_date', $equipment->manufacturing_date?->toDateString()) }}"
                        class="mt-1 block w-full rounded-md border px-3 py-2 focus:outline-none focus:ring-2 focus:ring-indigo-200">
                </div>

                <!-- In Service Date -->
                <div>
                    <label for="in_service" class="block text-sm font-medium text-gray-700">
                        In Service Date
                    </label>
                    <input id="in_service" name="in_service" type="date"
                        value="{{ old('in_service', $equipment->in_service?->toDateString()) }}"
                        class="mt-1 block w-full rounded-md border px-3 py-2 focus:outline-none focus:ring-2 focus:ring-indigo-200">
                </div>

                <!-- Expiration Date -->
                <div>
                    <label for="expiry_date" class="block text-sm font-medium text-gray-700">
                        Expiration Date
                    </label>
                    <input id="expiry_date" name="expiry_date" type="date"
                        value="{{ old('expiry_date', $equipment->expiry_date?->toDateString()) }}"
                        class="mt-1 block w-full rounded-md border px-3 py-2 focus:outline-none focus:ring-2 focus:ring-indigo-200">
                </div>
            </div>

            <!-- Actions -->
            <div class="mt-6 flex justify-end space-x-3 border-t pt-4">
                <button type="button"
                    class="close-edit-equipment rounded-lg border border-[#cfd4dc] bg-white px-4 py-2.5 text-sm font-medium text-[#344053] transition-colors hover:bg-[#f9fafb]">
                    Cancel
                </button>
                <button type="submit"
                    class="flex items-center rounded-lg bg-[#6840c6] px-3 py-2 text-sm text-white hover:bg-[#5a35a8]">
                    Save
                </button>
            </div>
        </form>
    </div>
</div>
