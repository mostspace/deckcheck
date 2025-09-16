@extends('v2.layouts.crew')

@section('title', 'Maintenance')

@section('page-title', 'Maintenance Management')
@section('page-description', 'Manage equipment categories, intervals, and maintenance schedules')

@section('page-actions')
    <x-v2-ui-button variant="primary" icon="fas fa-plus" href="{{ route('maintenance.create') }}">
        Add Category
    </x-v2-ui-button>
@endsection

@section('crew-tabs')
    <a href="{{ route('maintenance.index') }}" 
       class="whitespace-nowrap border-b-2 border-blue-500 py-2 px-1 text-sm font-medium text-blue-600">
        Categories
    </a>
    <a href="{{ route('schedule.index') }}" 
       class="whitespace-nowrap border-b-2 border-transparent py-2 px-1 text-sm font-medium text-gray-500 hover:border-gray-300 hover:text-gray-700">
        Schedule
    </a>
    <a href="{{ route('deficiencies.index') }}" 
       class="whitespace-nowrap border-b-2 border-transparent py-2 px-1 text-sm font-medium text-gray-500 hover:border-gray-300 hover:text-gray-700">
        Deficiencies
    </a>
@endsection

@section('crew-content')
    <!-- Statistics Cards -->
    <div class="grid grid-cols-1 gap-5 sm:grid-cols-2 lg:grid-cols-4">
        <x-v2-ui-card>
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <i class="fas fa-tools text-2xl text-blue-600"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-500">Total Categories</p>
                    <p class="text-2xl font-semibold text-gray-900">{{ $categories->count() }}</p>
                </div>
            </div>
        </x-v2-ui-card>

        <x-v2-ui-card>
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <i class="fas fa-boxes text-2xl text-green-600"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-500">Total Equipment</p>
                    <p class="text-2xl font-semibold text-gray-900">{{ $totalEquipment }}</p>
                </div>
            </div>
        </x-v2-ui-card>

        <x-v2-ui-card>
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <i class="fas fa-calendar-alt text-2xl text-yellow-600"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-500">Active Intervals</p>
                    <p class="text-2xl font-semibold text-gray-900">{{ $activeIntervals ?? 0 }}</p>
                </div>
            </div>
        </x-v2-ui-card>

        <x-v2-ui-card>
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <i class="fas fa-exclamation-triangle text-2xl text-red-600"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-500">Open Deficiencies</p>
                    <p class="text-2xl font-semibold text-gray-900">{{ $openDeficiencies ?? 0 }}</p>
                </div>
            </div>
        </x-v2-ui-card>
    </div>

    <!-- Categories Grid -->
    <div class="grid grid-cols-1 gap-6 sm:grid-cols-2 lg:grid-cols-3">
        @forelse($categories as $category)
            <x-v2-ui-card>
                <div class="flex items-center justify-between">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <i class="{{ $category->icon ?? 'fas fa-tools' }} text-2xl text-gray-600"></i>
                        </div>
                        <div class="ml-4">
                            <h3 class="text-lg font-medium text-gray-900">{{ $category->name }}</h3>
                            <p class="text-sm text-gray-500">{{ $category->equipment->count() }} equipment items</p>
                        </div>
                    </div>
                    <div class="flex items-center space-x-2">
                        <x-v2-ui-button variant="outline" size="sm" href="{{ route('maintenance.show', $category) }}">
                            <i class="fas fa-eye"></i>
                        </x-v2-ui-button>
                        <x-v2-ui-button variant="outline" size="sm" href="{{ route('maintenance.edit', $category) }}">
                            <i class="fas fa-edit"></i>
                        </x-v2-ui-button>
                    </div>
                </div>
                
                @if($category->intervals->count() > 0)
                    <div class="mt-4">
                        <p class="text-sm font-medium text-gray-700 mb-2">Intervals:</p>
                        <div class="space-y-1">
                            @foreach($category->intervals->take(3) as $interval)
                                <div class="flex items-center justify-between text-sm">
                                    <span class="text-gray-600">{{ $interval->description }}</span>
                                    <span class="text-gray-400">{{ $interval->interval }}</span>
                                </div>
                            @endforeach
                            @if($category->intervals->count() > 3)
                                <p class="text-xs text-gray-500">+{{ $category->intervals->count() - 3 }} more</p>
                            @endif
                        </div>
                    </div>
                @endif
            </x-v2-ui-card>
        @empty
            <div class="col-span-full">
                <x-v2-ui-card>
                    <div class="text-center py-12">
                        <i class="fas fa-tools text-4xl text-gray-400 mb-4"></i>
                        <h3 class="text-lg font-medium text-gray-900 mb-2">No categories yet</h3>
                        <p class="text-gray-500 mb-4">Get started by creating your first equipment category.</p>
                        <x-v2-ui-button variant="primary" href="{{ route('maintenance.create') }}">
                            <i class="fas fa-plus mr-2"></i>
                            Create Category
                        </x-v2-ui-button>
                    </div>
                </x-v2-ui-card>
            </div>
        @endforelse
    </div>
@endsection
