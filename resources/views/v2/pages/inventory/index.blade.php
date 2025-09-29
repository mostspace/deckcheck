@extends('v2.layouts.app')

@section('title', 'Inventory')

@section('content')

    @include('v2.components.nav.header-routing', [
        'activeTab' => 'index',
        'context' => 'inventory',
        'breadcrumbs' => [
            ['label' => 'Inventory', 'icon' => asset('assets/media/icons/sidebar-solid-archive-box.svg')],
            ['label' => 'Overview']
        ]
    ])

    <div class="px-3 sm:px-6 lg:px-8 py-4 sm:py-6">
        {{-- Navigation Cards --}}
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
            {{-- Equipment Card --}}
            <a href="{{ route('inventory.index') }}" 
               class="group block p-6 bg-white border border-gray-200 rounded-lg shadow-sm hover:shadow-md transition-shadow duration-200">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="w-12 h-12 bg-[#f3e8ff] rounded-lg flex items-center justify-center">
                            <i class="text-[#6840c6] fa-solid fa-tools text-xl"></i>
                        </div>
                    </div>
                    <div class="ml-4">
                        <h3 class="text-lg font-semibold text-gray-900 group-hover:text-[#6840c6] transition-colors">
                            Equipment
                        </h3>
                        <p class="text-sm text-gray-500">
                            Manage and track vessel equipment inventory
                        </p>
                    </div>
                    <div class="ml-auto">
                        <i class="fa-solid fa-arrow-right text-gray-400 group-hover:text-[#6840c6] transition-colors"></i>
                    </div>
                </div>
            </a>

            {{-- Consumables Card --}}
            <a href="{{ route('inventory.consumables') }}" 
               class="group block p-6 bg-white border border-gray-200 rounded-lg shadow-sm hover:shadow-md transition-shadow duration-200">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="w-12 h-12 bg-[#f0f9ff] rounded-lg flex items-center justify-center">
                            <i class="text-[#0ea5e9] fa-solid fa-box text-xl"></i>
                        </div>
                    </div>
                    <div class="ml-4">
                        <h3 class="text-lg font-semibold text-gray-900 group-hover:text-[#0ea5e9] transition-colors">
                            Consumables
                        </h3>
                        <p class="text-sm text-gray-500">
                            Manage and track vessel consumables inventory
                        </p>
                    </div>
                    <div class="ml-auto">
                        <i class="fa-solid fa-arrow-right text-gray-400 group-hover:text-[#0ea5e9] transition-colors"></i>
                    </div>
                </div>
            </a>
        </div>

        {{-- Quick Stats --}}
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            @include('components.equipment.operational-card')
            @include('components.equipment.action-needed-card')
            @include('components.equipment.out-of-service-card')
        </div>
    </div>
@endsection