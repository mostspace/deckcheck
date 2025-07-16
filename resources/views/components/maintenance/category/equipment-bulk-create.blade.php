{{-- equipment-bulk-create.blade.php --}}
<div
  id="equipmentBulkModal"
  class="fixed inset-0 z-50 flex flex-col bg-white transform translate-x-full transition-transform duration-300 ease-in-out"
>
  {{-- HEADER --}}
<header class="flex items-center justify-between px-6 py-4 border-b">
  <div class="flex items-center space-x-2">
    {{-- Main Action --}}
    <h2 class="text-2xl font-semibold text-[#0f1728]">
      Add Equipment
    </h2>

    {{-- Arrow Separator --}}
    <i class="fa-solid fa-arrow-right text-gray-400"></i>

    {{-- Category Icon + Name --}}
    <div class="flex items-center space-x-1">
      <div class="w-8 h-8 bg-[#f9f5ff] border border-[#e4e7ec] rounded-md flex items-center justify-center">
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
  <form
    id="bulkForm"
    action="{{ route('equipment.bulk-store') }}"
    method="POST"
    class="flex-1 flex flex-col overflow-hidden"
  >
    @csrf
    <input type="hidden" name="category_id" value="{{ $category->id }}">

    {{-- TABLE BODY (scrollable) --}}
    <div class="flex-1 overflow-y-auto px-6 py-4">
      <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200">
          <thead class="bg-white border-b border-gray-200 sticky top-0">
            <tr>
              <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Name</th>
              <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Deck</th>
              <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Location</th>
              <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Internal ID</th>
              <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Serial #</th>
              <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Manufacturer</th>
              <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Model</th>
              <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Status</th>
              <th class="w-12"></th>
            </tr>
          </thead>
          <tbody id="bulkRowsContainer" class="bg-white divide-y divide-gray-100">
            @include('partials.maintenance.category.bulk-row', ['index' => 0, 'decks' => $decks])
          </tbody>
        </table>
      </div>
    </div>

    {{-- FOOTER --}}
    <footer class="flex-shrink-0 flex items-center justify-between px-6 py-4 border-t bg-white">
      <button
        type="button"
        id="cancelBulkModal"
        class="px-4 py-2 border border-gray-300 rounded-lg text-sm text-gray-700 hover:bg-gray-100 transition"
      >
        Cancel
      </button>

      <div class="space-x-3">
        <button
          type="button"
          id="addBulkRow"
          class="text-purple-600 hover:underline text-sm"
        >
          + Add Another Row
        </button>
        <button
          type="submit"
          class="px-5 py-2 bg-purple-600 text-white rounded-lg text-sm hover:bg-purple-700 transition"
        >
          Save Equipment
        </button>
      </div>
    </footer>
  </form>
</div>
