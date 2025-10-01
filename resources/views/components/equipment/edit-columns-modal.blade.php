{{-- Edit Columns Modal --}}
<div id="column-modal-overlay" class="fixed inset-0 z-40 hidden bg-black bg-opacity-70"></div>

<div id="column-modal" class="fixed inset-0 z-50 flex hidden items-center justify-center">
    <div class="relative w-full max-w-lg space-y-4 rounded-xl bg-white p-6 shadow-xl">
        <h3 class="text-lg font-semibold text-gray-900">Edit Visible Columns</h3>

        <form action="{{ route('equipment.columns.update') }}" method="POST">
            @csrf

            {{-- Column Checklist UI --}}
            <div class="max-h-[400px] space-y-6 overflow-y-auto pr-1">

                {{-- Standard Fields --}}
                <div>
                    <h4 class="mb-2 text-xs font-semibold uppercase tracking-wide text-gray-500">Standard Fields</h4>
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
                        <h4 class="mb-2 text-xs font-semibold uppercase tracking-wide text-gray-500">Custom Attributes
                        </h4>
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
            <div class="mt-4 flex items-center justify-between gap-2">
                {{-- Restore to Default --}}
                <button type="submit" name="reset" value="true"
                    class="rounded-lg bg-gray-100 px-4 py-2 text-sm text-gray-700 hover:bg-gray-200">
                    Restore Default
                </button>

                <div class="flex gap-2">
                    <button type="button" onclick="toggleColumnModal(false)"
                        class="rounded-lg bg-gray-100 px-4 py-2 text-sm text-gray-700">
                        Cancel
                    </button>
                    <button type="submit"
                        class="rounded-lg bg-[#6840c6] px-4 py-2 text-sm font-medium text-white hover:bg-[#52379e]">
                        Apply
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>
