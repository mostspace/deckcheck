@extends('layouts.app')

@section('title', 'Complete Work Orders')

@section('content')
    <div id="work-order-flow" class="fixed inset-0 z-50 overflow-auto bg-white">
        <div class="relative mx-auto max-w-6xl px-4 py-6">

            <div class="bg-white px-6 pb-4 pt-6">
                <div class="flex flex-wrap items-center justify-between gap-4">
                    <div>
                        <p class="text-sm font-medium uppercase tracking-wide text-[#6840c6]">
                            {{ strtoupper($frequency) }} Maintenance Flow
                        </p>

                        <h1 class="text-l mt-1 font-semibold text-gray-900">

                            <i class="fa-solid fa-calendar-days mr-1 text-[#6840c6]"></i>{{ $dateRangeLabel }}
                        </h1>

                        <h1 class="mt-3 text-xl font-semibold text-gray-900">
                            {{ $groupName }}
                        </h1>

                        <p class="text-sm text-gray-500">
                            {{ $workOrders->count() }} work orders to complete
                        </p>
                    </div>

                    {{-- Exit Button --}}
                    <button onclick="closeFlowModal()"
                        class="inline-flex items-center rounded-md border border-gray-300 px-3 py-2 text-sm font-medium text-gray-600 hover:bg-gray-100 hover:text-gray-800">
                        <i class="fa-solid fa-arrow-right-from-bracket mr-2"></i> Exit
                    </button>
                </div>

                {{-- Progress Row --}}
                @php
                    $index = $workOrders->search(fn($wo) => $wo->id === $currentWorkOrder->id);
                    $count = $workOrders->count();
                    $progress = $count > 0 ? (($index + 1) / $count) * 100 : 0;
                @endphp

                <div class="mb-1 mt-3 text-sm text-gray-500">
                    Step <span id="flow-step-num">{{ $currentIndex + 1 }}</span> of {{ $workOrders->count() }}
                </div>
                <div class="h-1 w-full rounded-full bg-gray-100">
                    <div id="flow-progress-bar" class="h-1 rounded-full bg-purple-600 transition-all duration-300"
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
                    'count' => $count
                ])
            </div>

        </div>
    </div>

    {{-- IDs passed to JS --}}
    <span id="flow-work-order-ids" class="hidden">{{ $workOrders->pluck('id')->toJson() }}</span>
    <span id="flow-current-id" class="hidden">{{ $currentWorkOrder->id }}</span>

@endsection
