{{-- Overlay --}}
<div id="basic-modal-overlay" class="hidden fixed inset-0 bg-black bg-opacity-70 z-40"></div>


<div id="basic-modal" class="fixed inset-0 flex items-center justify-center p-4 z-50 hidden">
    <div class="bg-white rounded-lg shadow-xl w-full max-w-xl overflow-hidden">

        {{-- Header --}}
        <div class="flex items-center justify-between px-6 py-4 border-b">
            <h3 class="text-xl font-semibold text-gray-900"><span class="text-[#6840c6] font-bold">Edit</span> {{ $equipment->name }}</h3>
            <button id="close-basic-modal" class="text-gray-400 hover:text-gray-600">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>

        {{-- Form --}}
        <form method="POST" action="{{ route('equipment.updateBasic', $equipment) }}" enctype="multipart/form-data" class="px-6 py-5 space-y-4">
            @csrf @method('PUT')

            {{-- Show validation errors --}}
            @if ($errors->any())
                <div class="mb-4 p-4 bg-red-100 text-red-700 rounded">
                    <ul class="list-disc list-inside">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">

                <!-- Name -->
                <div class="md:col-span-2">
                    <label for="modal_name" class="block text-sm font-medium text-gray-700">
                        Name
                    </label>
                    <input id="modal_name" name="name" type="text" value="{{ old('name', $equipment->name) }}" required
                        class="mt-1 block w-full px-3 py-2 border rounded-md focus:outline-none focus:ring-2 focus:ring-indigo-200">
                </div>

                {{-- Deck --}}
                <div>
                    <label for="modal_deck_id" class="block text-sm font-medium text-gray-700">Deck</label>
                    <select id="modal_deck_id" name="deck_id" class="mt-1 block w-full px-3 py-2 border rounded-md focus:ring-indigo-200">
                        <option value="">— Select deck —</option>
                        @foreach ($decks as $deck)
                            <option value="{{ $deck->id }}" @selected(old('deck_id', $equipment->deck_id) == $deck->id)>
                                {{ $deck->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                {{-- Location --}}
                <div>
                    <label for="modal_location_id" class="block text-sm font-medium text-gray-700">Location</label>
                    <select id="modal_location_id" name="location_id" required
                        class="mt-1 block w-full px-3 py-2 border rounded-md focus:ring-indigo-200" @if (!$equipment->location_id) disabled @endif>
                        @if ($equipment->location_id || old('location_id'))
                            @foreach ($locations as $loc)
                                <option value="{{ $loc->id }}" @selected(old('location_id', $equipment->location_id) == $loc->id)>
                                    {{ $loc->name }}
                                </option>
                            @endforeach
                        @else
                            <option value="">Select deck first</option>
                        @endif
                    </select>
                </div>

                {{-- Status --}}
                <div>
                    <label for="status" class="block text-sm font-medium text-gray-700">
                        Status
                    </label>
                    <select id="status" name="status" class="mt-1 block w-full px-3 py-2 border rounded-md focus:ring-indigo-200">
                        <option value="">— Select —</option>
                        @foreach (['In Service', 'Out of Service', 'Inoperable', 'Archived', 'Unknown'] as $st)
                            <option value="{{ $st }}" @selected(old('status', $equipment->status) === $st)>
                                {{ $st }}
                            </option>
                        @endforeach
                    </select>
                </div>


                <!-- Hero Photo -->
                <div class="md:col-span-2">
                    <label for="hero_photo" class="block text-sm font-medium text-gray-700">
                        Change Photo
                    </label>
                    <input id="hero_photo" name="hero_photo" type="file" accept="image/*"
                        class="block w-full text-gray-70 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-medium file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100">
                </div>

            </div>

            <!-- Actions -->
            <div class="mt-6 border-t pt-4 flex justify-end space-x-3">
                <button type="button" id="close-basic-modal"
                    class="px-4 py-2.5 bg-white text-[#344053] border border-[#cfd4dc] rounded-lg text-sm font-medium hover:bg-[#f9fafb] transition-colors">
                    Cancel
                </button>
                <button type="submit" class="px-3 py-2 bg-[#6840c6] text-white rounded-lg text-sm hover:bg-[#5a35a8] flex items-center">
                    Save
                </button>
            </div>

        </form>

    </div>
</div>
