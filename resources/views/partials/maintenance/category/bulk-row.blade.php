<tr class="hover:bg-gray-50">
    {{-- NAME --}}
    <td class="whitespace-nowrap px-6 py-4">
        <input type="text" name="equipment[{{ $index }}][name]"
            class="w-full border-b border-gray-300 bg-transparent py-2 text-gray-800 placeholder-gray-400 focus:border-purple-500 focus:ring-0"
            placeholder="e.g. Portable Pump" required>
    </td>

    {{-- DECK --}}
    <td class="whitespace-nowrap px-6 py-4">
        <select name="equipment[{{ $index }}][deck_id]"
            class="deck-select w-full border-b border-gray-300 py-2 focus:border-purple-500 focus:ring-0" required>

            <option value="">Select deck</option>
            @foreach ($decks as $deck)
                <option value="{{ $deck->id }}">{{ $deck->name }}</option>
            @endforeach
        </select>
    </td>

    {{-- LOCATION --}}
    <td class="whitespace-nowrap px-6 py-4">
        <select name="equipment[{{ $index }}][location_id]"
            class="location-select w-full border-b border-gray-300 py-2 focus:border-purple-500 focus:ring-0" disabled
            required>

            <option value="">Select deck first</option>
        </select>
    </td>

    {{-- INTERNAL ID --}}
    <td class="whitespace-nowrap px-6 py-4">
        <input type="text" name="equipment[{{ $index }}][internal_id]"
            class="w-full border-b border-gray-300 bg-transparent py-2 text-gray-800 placeholder-gray-400 focus:border-purple-500 focus:ring-0"
            placeholder="Optional">
    </td>

    {{-- SERIAL # --}}
    <td class="whitespace-nowrap px-6 py-4">
        <input type="text" name="equipment[{{ $index }}][serial_number]"
            class="w-full border-b border-gray-300 bg-transparent py-2 text-gray-800 placeholder-gray-400 focus:border-purple-500 focus:ring-0"
            placeholder="Optional">
    </td>

    {{-- Manufacturer --}}
    <td class="whitespace-nowrap px-6 py-4">
        <input type="text" name="equipment[{{ $index }}][manufacturer]"
            class="w-full border-b border-gray-300 bg-transparent py-2 text-gray-800 placeholder-gray-400 focus:border-purple-500 focus:ring-0"
            placeholder="Optional">
    </td>

    {{-- Model --}}
    <td class="whitespace-nowrap px-6 py-4">
        <input type="text" name="equipment[{{ $index }}][model]"
            class="w-full border-b border-gray-300 bg-transparent py-2 text-gray-800 placeholder-gray-400 focus:border-purple-500 focus:ring-0"
            placeholder="Optional">
    </td>

    {{-- STATUS --}}
    <td class="whitespace-nowrap px-6 py-4">
        <select name="equipment[{{ $index }}][status]"
            class="w-full border-b border-gray-300 bg-transparent py-2 text-gray-800 focus:border-purple-500 focus:ring-0">
            <option value="In Service" selected>In Service</option>
            <option value="Out of Service">Out of Service</option>
            <option value="Removed">Inoperable</option>
            <option value="Removed">Archived</option>
        </select>
    </td>

    {{-- REMOVE --}}
    <td class="whitespace-nowrap px-6 py-4 text-center">
        <button type="button" class="remove-row text-red-500 hover:text-red-700 focus:outline-none">&times;</button>
    </td>
</tr>
