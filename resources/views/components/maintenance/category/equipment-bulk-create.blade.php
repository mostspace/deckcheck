{{-- equipment-bulk-create.blade.php --}}
<div id="equipmentBulkModal"
    class="fixed inset-0 z-50 flex translate-x-full transform flex-col bg-white transition-transform duration-300 ease-in-out">
    {{-- HEADER --}}
    <header class="flex items-center justify-between border-b px-6 py-4">
        <div class="flex items-center space-x-2">
            {{-- Main Action --}}
            <h2 class="text-2xl font-semibold text-[#0f1728]">
                Add Equipment
            </h2>

            {{-- Arrow Separator --}}
            <i class="fa-solid fa-arrow-right text-gray-400"></i>

            {{-- Category Icon + Name --}}
            <div class="flex items-center space-x-1">
                <div class="flex h-8 w-8 items-center justify-center rounded-md border border-[#e4e7ec] bg-[#f9f5ff]">
                    <i class="fa-solid {{ $category->icon }} text-[#6840c6] hover:text-[#7e56d8]"></i>
                </div>
                <span class="text-2xl font-semibold text-[#6840c6]">
                    {{ $category->name }}
                </span>
            </div>
        </div>

        {{-- Close --}}
        <button id="closeBulkModal" class="text-gray-500 hover:text-gray-800">
            <i class="fa-solid fa-xmark fa-lg"></i>
        </button>
    </header>

    {{-- FORM --}}
    <form id="bulkForm" action="{{ route('equipment.bulk-store') }}" method="POST"
        class="flex flex-1 flex-col overflow-hidden">
        @csrf
        <input type="hidden" name="category_id" value="{{ $category->id }}">

        {{-- TABLE BODY (scrollable) --}}
        <div class="flex-1 overflow-y-auto px-6 py-4">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="sticky top-0 border-b border-gray-200 bg-white">
                        <tr>
                            <th
                                class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-500">
                                Name</th>
                            <th
                                class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-500">
                                Deck</th>
                            <th
                                class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-500">
                                Location</th>
                            <th
                                class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-500">
                                Internal ID</th>
                            <th
                                class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-500">
                                Serial #</th>
                            <th
                                class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-500">
                                Manufacturer</th>
                            <th
                                class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-500">
                                Model</th>
                            <th
                                class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-500">
                                Status</th>
                            <th class="w-12"></th>
                        </tr>
                    </thead>
                    <tbody id="bulkRowsContainer" class="divide-y divide-gray-100 bg-white">
                        @include('partials.maintenance.category.bulk-row', [
                            'index' => 0,
                            'decks' => $decks
                        ])
                    </tbody>
                </table>
            </div>
        </div>

        {{-- FOOTER --}}
        <footer class="flex flex-shrink-0 items-center justify-between border-t bg-white px-6 py-4">
            <button type="button" id="cancelBulkModal"
                class="rounded-lg border border-gray-300 px-4 py-2 text-sm text-gray-700 transition hover:bg-gray-100">
                Cancel
            </button>

            <div class="space-x-3">
                <button type="button" id="addBulkRow" class="text-sm text-purple-600 hover:underline">
                    + Add Another Row
                </button>
                <button type="submit"
                    class="rounded-lg bg-purple-600 px-5 py-2 text-sm text-white transition hover:bg-purple-700">
                    Save Equipment
                </button>
            </div>
        </footer>
    </form>
</div>
