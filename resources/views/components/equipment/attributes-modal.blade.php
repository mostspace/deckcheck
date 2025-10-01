{{-- Overlay --}}
<div id="attributes-modal-overlay" class="fixed inset-0 z-40 hidden bg-black bg-opacity-70"></div>

<div id="attributes-modal" class="fixed inset-0 z-50 flex hidden items-center justify-center p-4">
    <div class="w-full max-w-xl overflow-hidden rounded-lg bg-white shadow-xl">

        {{-- #Header --}}
        <div class="flex items-center justify-between border-b px-6 py-4">
            <h3 class="text-xl font-semibold text-gray-900"><span class="font-bold text-[#6840c6]">Edit</span> Attributes
            </h3>
            <button id="close-attributes-modal" class="text-gray-400 hover:text-gray-600">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24"
                    stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>

        {{-- #Form --}}
        <div class="px-6 py-5">
            <form method="POST" action="{{ route('equipment.attributes.update', $equipment) }}">
                @csrf @method('PUT')

                {{-- #Existing Attributes --}}
                <div id="attribute-list" class="mb-6 space-y-3">
                    @foreach ($equipment->attributes_json ?? [] as $key => $value)
                        <div class="attribute-row flex items-center gap-3">
                            <span class="w-1/3 break-words font-medium capitalize text-gray-700">
                                {{ $key }}
                            </span>
                            <input type="text" name="attributes_json[{{ $key }}]"
                                value="{{ old('attributes_json.' . $key, $value) }}"
                                class="w-2/3 rounded-md border px-3 py-2 focus:outline-none focus:ring-2 focus:ring-indigo-200">
                            <button type="button" class="remove-attribute rounded p-1 text-red-500 hover:bg-red-100">
                                &times;
                            </button>
                        </div>
                    @endforeach

                </div>

                {{-- #Add New --}}
                <div class="mt-6 grid grid-cols-1 items-end gap-3 border-t pt-4 md:grid-cols-3">
                    <div>
                        <label for="new-attr-key" class="sr-only">New Key</label>
                        <input id="new-attr-key" type="text" placeholder="New Attribute"
                            class="w-full rounded-md border px-3 py-2 focus:outline-none focus:ring-2 focus:ring-indigo-200">
                    </div>
                    <div>
                        <label for="new-attr-value" class="sr-only">New Value</label>
                        <input id="new-attr-value" type="text" placeholder="Value"
                            class="w-full rounded-md border px-3 py-2 focus:outline-none focus:ring-2 focus:ring-indigo-200">
                    </div>
                    <button id="add-attribute" type="button"
                        class="flex w-full items-center rounded-md bg-[#7e56d8] px-4 py-2 text-white hover:bg-[#6840c6] md:w-auto">
                        <i class="fa-solid fa-plus mr-2"></i>
                        Add New
                    </button>
                </div>

                {{-- #Actions --}}
                <div class="mt-6 flex justify-end space-x-3 border-t pt-4">
                    <button type="button" id="close-attributes-modal"
                        class="rounded-lg border border-[#cfd4dc] bg-white px-4 py-2.5 text-sm font-medium text-[#344053] transition-colors hover:bg-[#f9fafb]">
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
</div>
