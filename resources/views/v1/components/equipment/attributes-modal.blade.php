{{-- Overlay --}}
<div id="attributes-modal-overlay" class="hidden fixed inset-0 bg-black bg-opacity-70 z-40"></div>


<div id="attributes-modal" class="fixed inset-0 flex items-center justify-center p-4 z-50 hidden">
    <div class="bg-white rounded-lg shadow-xl w-full max-w-xl overflow-hidden">

        {{-- #Header --}}
        <div class="flex items-center justify-between px-6 py-4 border-b">
            <h3 class="text-xl font-semibold text-gray-900"><span class="text-[#6840c6] font-bold">Edit</span> Attributes</h3>
            <button id="close-attributes-modal" class="text-gray-400 hover:text-gray-600">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>

        {{-- #Form --}}
        <div class="px-6 py-5">
            <form method="POST" action="{{ route('equipment.attributes.update', $equipment) }}">
                @csrf @method('PUT')

               {{-- #Existing Attributes --}}
                <div id="attribute-list" class="space-y-3 mb-6">
                    @foreach ($equipment->attributes_json ?? [] as $key => $value)
                        <div class="flex items-center gap-3 attribute-row">
                            <span class="w-1/3 text-gray-700 font-medium capitalize break-words">
                                {{ $key }}
                            </span>
                            <input type="text" name="attributes_json[{{ $key }}]" value="{{ old('attributes_json.' . $key, $value) }}"
                                class="w-2/3 px-3 py-2 border rounded-md focus:outline-none focus:ring-2 focus:ring-indigo-200">
                            <button type="button" class="p-1 text-red-500 hover:bg-red-100 rounded remove-attribute">
                                &times;
                            </button>
                        </div>
                    @endforeach

                </div>

                {{-- #Add New --}}
                <div class="mt-6 border-t pt-4 grid grid-cols-1 md:grid-cols-3 gap-3 items-end">
                    <div>
                        <label for="new-attr-key" class="sr-only">New Key</label>
                        <input id="new-attr-key" type="text" placeholder="New Attribute"
                            class="w-full px-3 py-2 border rounded-md focus:outline-none focus:ring-2 focus:ring-indigo-200">
                    </div>
                    <div>
                        <label for="new-attr-value" class="sr-only">New Value</label>
                        <input id="new-attr-value" type="text" placeholder="Value"
                            class="w-full px-3 py-2 border rounded-md focus:outline-none focus:ring-2 focus:ring-indigo-200">
                    </div>
                    <button id="add-attribute" type="button"
                        class="w-full md:w-auto px-4 py-2 bg-[#7e56d8] hover:bg-[#6840c6] text-white rounded-md flex items-center"> 
                        <i class="fa-solid fa-plus mr-2"></i>
                            Add New
                    </button>
                </div>

                {{-- #Actions --}}
                <div class="mt-6 border-t pt-4 flex justify-end space-x-3">
                    <button type="button" id="close-attributes-modal" class="px-4 py-2.5 bg-white text-[#344053] border border-[#cfd4dc] rounded-lg text-sm font-medium hover:bg-[#f9fafb] transition-colors">
                        Cancel
                    </button>
                    <button type="submit" class="px-3 py-2 bg-[#6840c6] text-white rounded-lg text-sm hover:bg-[#5a35a8] flex items-center">
                        Save
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
