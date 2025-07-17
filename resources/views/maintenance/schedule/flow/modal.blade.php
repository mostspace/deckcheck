@extends('layouts.app')

@section('title', 'Complete Work Orders')

@section('content')
    <div id="work-order-flow" class="fixed inset-0 z-50 bg-white overflow-auto">
        <div class="max-w-6xl mx-auto py-6 px-4 relative">


            <div class="px-6 pt-6 pb-4 bg-white">
                <div class="flex items-center justify-between flex-wrap gap-4">
                    <div>
                        <p class="text-sm font-medium text-[#6840c6] tracking-wide uppercase">
                            {{ strtoupper($frequency) }} Maintenance Flow
                        </p>

                        <h1 class="text-l font-semibold text-gray-900 mt-1">
                        
                            <i class="fa-solid fa-calendar-days text-[#6840c6] mr-1"></i>{{ $dateRangeLabel }}
                        </h1>

                        <h1 class="text-xl font-semibold text-gray-900 mt-3">
                            {{ $groupName }}
                        </h1>

                        <p class="text-sm text-gray-500">
                            {{ $workOrders->count() }} work orders to complete
                        </p>
                    </div>

                    {{-- Exit Button --}}
                    <button onclick="closeFlowModal()"
                        class="inline-flex items-center px-3 py-2 text-sm font-medium border border-gray-300 rounded-md text-gray-600 hover:bg-gray-100 hover:text-gray-800">
                        <i class="fa-solid fa-arrow-right-from-bracket mr-2"></i> Exit
                    </button>
                </div>

                {{-- Progress Row --}}
                @php
                    $index = $workOrders->search(fn($wo) => $wo->id === $currentWorkOrder->id);
                    $count = $workOrders->count();
                    $progress = $count > 0 ? (($index + 1) / $count) * 100 : 0;
                @endphp

                <div class="text-sm text-gray-500 mb-1 mt-3">
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
