@php
    $assignedUser = $workOrder->assignee;
    $users = $availableUsers ?? collect();
@endphp

{{-- System Message --}}
@if (session('error'))
    <div class="mb-4 rounded bg-red-100 p-3 text-sm text-red-700">
        {{ session('error') }}
    </div>
@endif

{{-- Tasks and Completion --}}
<div class="rounded-lg border border-[#e4e7ec] bg-white shadow-sm" data-status="{{ $workOrder->status }}">

    @include('components.maintenance.work-order-summary', [
        'workOrder' => $workOrder,
        'withButton' => false
    ])

    <div class="mb-12 px-6 pb-6">
        {{-- #Completion Form --}}
        @if ($workOrder->tasks->count())
            <div class="mt-6 border-t border-[#e4e7ec] pt-6">
                <form id="complete-form" action="{{ route('work-orders.complete', $workOrder) }}" method="POST">
                    @csrf

                    <label class="mb-2 block text-base font-bold text-[#344053]">Comments / Observations</label>
                    <textarea name="notes"
                        class="w-full resize-none rounded-lg border border-[#cfd4dc] bg-white px-3.5 py-2.5 text-sm text-[#0f1728] shadow-sm focus:outline-none focus:ring-2 focus:ring-[#6840c6]"
                        rows="4" placeholder="Enter any final notes about this record...">{{ old('notes', $workOrder->notes) }}</textarea>

                    {{-- Navigation Buttons --}}
                    <div class="mt-6 flex justify-end gap-2">
                        <button id="prev-button" type="button" onclick="loadPrevWorkOrder()"
                            class="rounded-lg border border-[#344053] px-4 py-2 text-sm font-semibold text-[#344053] hover:bg-[#e4e7ec]">
                            <i class="fa-solid fa-chevron-left mr-2"></i> Previous
                        </button>

                        <button id="next-button" type="button" onclick="loadNextWorkOrder()"
                            class="rounded-lg border border-[#344053] px-4 py-2 text-sm font-semibold text-[#344053] hover:bg-[#e4e7ec]">
                            Next <i class="fa-solid fa-chevron-right ml-2"></i>
                        </button>

                        <button type="submit" id="complete-work-order-button" data-action="next"
                            class="ml-4 cursor-pointer rounded-lg bg-[#6840c6] px-4 py-2 text-sm font-semibold text-white hover:bg-[#5a35a8]"
                            disabled>
                            <i class="fa-solid fa-check mr-2"></i> Complete & Next
                        </button>
                    </div>
                </form>
            </div>
        @endif
    </div>

</div>
