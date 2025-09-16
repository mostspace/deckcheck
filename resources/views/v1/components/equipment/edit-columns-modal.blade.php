{{-- Edit Columns Modal --}}
<div id="column-modal-overlay" class="fixed inset-0 bg-black bg-opacity-70 z-40 hidden"></div>

<div id="column-modal" class="fixed z-50 inset-0 flex items-center justify-center hidden">
    <div class="bg-white rounded-xl shadow-xl max-w-lg w-full p-6 space-y-4 relative">
        <h3 class="text-lg font-semibold text-gray-900">Edit Visible Columns</h3>

        <form action="{{ route('equipment.columns.update') }}" method="POST">
            @csrf

            {{-- Column Checklist UI --}}
            <div class="space-y-6 max-h-[400px] overflow-y-auto pr-1">

                {{-- Standard Fields --}}
                <div>
                    <h4 class="text-xs font-semibold text-gray-500 uppercase tracking-wide mb-2">Standard Fields</h4>
                    <div class="grid grid-cols-2 gap-4">
                        @foreach ($staticFields as $field => $label)
                            @if (!in_array($field, ['category', 'name']))
                                <label class="flex items-center gap-2">
                                    <input type="checkbox" name="columns[]" value="{{ $field }}"
                                        {{ in_array($field, session('visible_columns', $defaultColumns)) ? 'checked' : '' }}>
                                    <span class="text-sm text-gray-700">{{ $label }}</span>
                                </label>
                            @endif
                        @endforeach
                    </div>
                </div>

                {{-- Custom Attributes --}}
                @if ($attributeKeys->isNotEmpty())
                    <hr class="border-gray-200">

                    <div>
                        <h4 class="text-xs font-semibold text-gray-500 uppercase tracking-wide mb-2">Custom Attributes</h4>
                        <div class="grid grid-cols-2 gap-4">
                            @foreach ($attributeKeys as $attr)
                                <label class="flex items-center gap-2">
                                    <input type="checkbox" name="columns[]" value="{{ $attr }}"
                                        {{ in_array($attr, session('visible_columns', $defaultColumns)) ? 'checked' : '' }}>
                                    <span class="text-sm text-gray-700">{{ $attr }}</span>
                                </label>
                            @endforeach
                        </div>
                    </div>
                @endif

            </div>

            {{-- Action Buttons --}}
            <div class="mt-4 flex justify-between items-center gap-2">
                {{-- Restore to Default --}}
                <button type="submit" name="reset" value="true"
                    class="px-4 py-2 bg-gray-100 text-gray-700 rounded-lg text-sm hover:bg-gray-200">
                    Restore Default
                </button>

                <div class="flex gap-2">
                    <button type="button" onclick="toggleColumnModal(false)"
                        class="px-4 py-2 bg-gray-100 text-gray-700 rounded-lg text-sm">
                        Cancel
                    </button>
                    <button type="submit"
                        class="px-4 py-2 bg-[#6840c6] text-white rounded-lg text-sm font-medium hover:bg-[#52379e]">
                        Apply
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>