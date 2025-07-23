@php
    $assignedUser = $workOrder->assignee;
    $users = $availableUsers ?? collect();
@endphp

{{-- System Message --}}
@if (session('error'))
    <div class="mb-4 p-3 bg-red-100 text-red-700 text-sm rounded">
        {{ session('error') }}
    </div>
@endif

{{-- Tasks and Completion --}}
<div class=" bg-white rounded-lg border border-[#e4e7ec] shadow-sm">

    @include('components.maintenance.work-order-summary', [
        'workOrder' => $workOrder,
        'withButton' => false,
    ])

    <div class="mb-12 px-6 pb-6">
        {{-- #Completion Form --}}
        @if ($workOrder->tasks->count())
            <div class="border-t border-[#e4e7ec] pt-6 mt-6">
                <form id="complete-form" action="{{ route('work-orders.complete', $workOrder) }}" method="POST">
                    @csrf

                    <label class="block text-base font-bold text-[#344053] mb-2">Comments / Observations</label>
                    <textarea name="notes"
                        class="w-full px-3.5 py-2.5 bg-white rounded-lg shadow-sm border border-[#cfd4dc] text-[#0f1728] text-sm resize-none focus:outline-none focus:ring-2 focus:ring-[#6840c6]"
                        rows="4" placeholder="Enter any final notes about this record...">{{ old('notes', $workOrder->notes) }}</textarea>

                    {{-- Navigation Buttons --}}
                    <div class="mt-6 flex justify-end gap-2">
                        <button id="prev-button" type="button" onclick="loadPrevWorkOrder()"
                            class="px-4 py-2 rounded-lg text-sm font-semibold border border-[#344053] text-[#344053] hover:bg-[#e4e7ec]">
                            <i class="fa-solid fa-chevron-left mr-2"></i> Previous
                        </button>

                        <button id="next-button" type="button" onclick="loadNextWorkOrder()"
                            class="px-4 py-2 rounded-lg text-sm font-semibold border border-[#344053] text-[#344053] hover:bg-[#e4e7ec]">
                            Next <i class="fa-solid fa-chevron-right ml-2"></i>
                        </button>

                        <button type="submit" id="complete-work-order-button"
                            class="px-4 py-2 ml-4 rounded-lg text-sm font-semibold bg-[#6840c6] text-white hover:bg-[#5a35a8] cursor-pointer"
                            disabled>
                            <i class="fa-solid fa-check mr-2"></i> Complete & Next
                        </button>
                    </div>
                </form>
            </div>
        @endif
    </div>

</div>
