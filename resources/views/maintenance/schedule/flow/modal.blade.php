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
            <div class="mb-6">
                <h1 class="text-2xl font-semibold text-[#0f1728]">
                    <h1 class="text-2xl font-semibold text-[#0f1728]">
                        {{ strtoupper($frequency) }} Flow – {{ $groupName }} {{ $dateRangeLabel }}
                    </h1>

                </h1>
                <p class="text-sm text-[#667084]">Complete all work orders in sequence.</p>

                {{-- Progress Bar --}}
                @php
                    $index = $workOrders->search(fn($wo) => $wo->id === $currentWorkOrder->id);
                    $count = $workOrders->count();
                    $progress = $count > 0 ? (($index + 1) / $count) * 100 : 0;
                @endphp

                <div class="text-sm text-gray-500 mb-1">
                    Step <span id="flow-step-num">{{ $currentIndex + 1 }}</span> of {{ $workOrders->count() }}
                </div>
                <div class="w-full h-1 bg-gray-100 rounded-full">
                    <div id="flow-progress-bar" class="h-1 bg-purple-600 rounded-full transition-all duration-300"
                        style="width: {{ round((($currentIndex + 1) / $workOrders->count()) * 100) }}%;">
                    </div>
                </div>

            </div>

            {{-- Dynamic Work Order Container --}}
            <div id="work-order-container">
                @include('maintenance.schedule.flow.partials.work-order', [
                    'workOrder' => $currentWorkOrder,
                    'availableUsers' => $currentWorkOrder->equipmentInterval->equipment->vessel->users,
                    'index' => $index,
                    'count' => $count,
                ])
            </div>
        </div>
    </div>

    {{-- IDs passed to JS --}}
    <span id="flow-work-order-ids" class="hidden">{{ $workOrders->pluck('id')->toJson() }}</span>
    <span id="flow-current-id" class="hidden">{{ $currentWorkOrder->id }}</span>


@endsection
