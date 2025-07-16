@extends('layouts.app')

@section('title', 'Complete Work Orders')

@section('content')
    <div id="work-order-flow" class="fixed inset-0 z-50 bg-white overflow-auto">
        <div class="max-w-6xl mx-auto py-6 px-4 relative">

            {{-- Close Button --}}
            <button onclick="closeFlowModal()" class="absolute top-6 right-6 text-[#667084] hover:text-[#344053] text-sm font-medium">
                <i class="fa-solid fa-xmark mr-1"></i> Close
            </button>

            {{-- Header --}}
            <div class="mb-6 flex justify-between items-center">
                <div>
                    <h1 class="text-2xl font-semibold text-[#0f1728]">Maintenance Flow</h1>
                    <p class="text-sm text-[#667084]">Complete all work orders in sequence.</p>
                </div>

                {{-- Navigation Controls --}}
                <div class="flex gap-2">
                    <button id="prev-button" onclick="loadPrevWorkOrder()" class="btn btn-sm bg-[#f2f4f7] hover:bg-[#e4e7ec]">
                        <i class="fa-solid fa-chevron-left mr-1"></i> Previous
                    </button>
                    <button id="next-button" onclick="loadNextWorkOrder()" class="btn btn-sm bg-[#6840c6] text-white hover:bg-[#5a35a8]">
                        Next <i class="fa-solid fa-chevron-right ml-1"></i>
                    </button>
                </div>
            </div>

            {{-- Dynamic Work Order Container --}}
            <div id="work-order-container">
                @include('maintenance.schedule.flow.partials.work-order', [
                    'workOrder' => $currentWorkOrder,
                    'availableUsers' => $currentWorkOrder->equipmentInterval->equipment->vessel->users,
                ])
            </div>
        </div>
    </div>

    <span id="flow-work-order-ids" class="hidden">{{ $workOrders->pluck('id')->toJson() }}</span>
    <span id="flow-current-id" class="hidden">{{ $currentWorkOrder->id }}</span>

@endsection
