@extends('v2.layouts.app')

@section('title', 'Maintenance Category')

@section('content')

    @include('v2.components.maintenance.header', [
        'activeTab' => 'index',
        'breadcrumbs' => [
            ['label' => 'Maintenance', 'icon' => asset('assets/media/icons/sidebar-solid-wrench-scredriver.svg')],
            ['label' => 'Index', 'url' => route('maintenance.index')],
            ['label' => $category->name]
        ]
    ])

    <div class="flex flex-col gap-4 sm:gap-8 px-3 sm:px-6 lg:px-8 py-4 sm:py-6">
        {{-- Header --}}
        <div class="">        
            {{-- System Messages --}}
            @if (session('success'))
                <div class="mb-6 p-4 bg-green-100 border border-green-300 text-green-800 rounded-lg text-sm">
                    {{ session('success') }}
                </div>
            @endif
        
            {{-- Header --}}
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                <div>
                    <div class="flex space-x-2">
                        <div class="w-8 h-8 bg-[#f9f5ff] border rounded-md flex items-center justify-center mr-1">
                            <i class="fa-solid hover:text-[#7e56d8] {{ $category->icon }} text-[#6840c6]"></i>
                        </div>
                        <h1 class="text-2xl font-semibold text-[#0f1728]">Maintenance Index for <span
                                class=" font-bold text-[#6840c6]">{{ $category->name }}</span></h1>
                        <button onclick="window.location='{{ route('maintenance.edit', $category) }}'" class="text-[#667084] hover:text-[#7e56d8]">
                            <i class="fa-solid fa-edit"></i>
                        </button>
                    </div>
                    <p class="text-[#475466]">Manage maintenance requirements and view equipment manifest.</p>
                </div>
            </div>
        </div>
        
        @include ('components.maintenance.detail-stat-cards')
        
        {{-- Intervals --}}
        <div class="bg-white border rounded-lg shadow">
            <div class="px-4 py-3 border-b border-gray-200">
                <h3 class="text-lg font-medium text-gray-900">Maintenance Intervals</h3>
            </div>
            <div class="p-4">
                @if($category->intervals->count() > 0)
                    <div class="grid gap-4">
                        @foreach($category->intervals as $interval)
                            <div class="border rounded-lg p-4 hover:bg-gray-50">
                                <div class="flex items-center justify-between">
                                    <div class="flex items-center space-x-3">
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                            {{ $interval->interval }}
                                        </span>
                                        <span class="text-sm text-gray-600">{{ $interval->tasks_count }} tasks</span>
                                    </div>
                                    <div class="flex space-x-2">
                                        <a href="{{ route('maintenance.intervals.show', [$category, $interval]) }}" 
                                           class="text-blue-600 hover:text-blue-800 text-sm font-medium">
                                            View Details
                                        </a>
                                        <a href="{{ route('maintenance.intervals.edit', [$category, $interval]) }}" 
                                           class="text-gray-600 hover:text-gray-800 text-sm">
                                            Edit
                                        </a>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="text-center py-8 text-gray-500">
                        <i class="fa-solid fa-calendar-days text-4xl mb-4"></i>
                        <p>No maintenance intervals defined for this category.</p>
                        <a href="{{ route('maintenance.intervals.create', $category) }}" 
                           class="mt-4 inline-flex items-center px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">
                            <i class="fa-solid fa-plus mr-2"></i>
                            Add Interval
                        </a>
                    </div>
                @endif
            </div>
        </div>

        {{-- Equipment --}}
        <div class="bg-white border rounded-lg shadow">
            <div class="px-4 py-3 border-b border-gray-200">
                <h3 class="text-lg font-medium text-gray-900">Equipment ({{ $category->equipment_count }})</h3>
            </div>
            <div class="p-4">
                @if($category->equipment->count() > 0)
                    <div class="grid gap-4">
                        @foreach($category->equipment as $equipment)
                            <div class="border rounded-lg p-4 hover:bg-gray-50">
                                <div class="flex items-center justify-between">
                                    <div>
                                        <h4 class="font-medium text-gray-900">{{ $equipment->name }}</h4>
                                        <p class="text-sm text-gray-600">
                                            {{ $equipment->deck->name ?? 'No deck' }} - {{ $equipment->location->name ?? 'No location' }}
                                        </p>
                                    </div>
                                    <div class="flex space-x-2">
                                        <a href="{{ route('equipment.show', $equipment) }}" 
                                           class="text-blue-600 hover:text-blue-800 text-sm font-medium">
                                            View
                                        </a>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="text-center py-8 text-gray-500">
                        <i class="fa-solid fa-box text-4xl mb-4"></i>
                        <p>No equipment assigned to this category.</p>
                    </div>
                @endif
            </div>
        </div>
    </div>
@endsection
