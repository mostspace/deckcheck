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

    <div class="px-3 py-4 sm:px-6 sm:py-6 lg:px-8">
        {{-- Navigation Cards --}}
        <div class="mb-8 grid grid-cols-1 gap-6 md:grid-cols-2">
            {{-- Equipment Card --}}
            <a href="{{ route('inventory.index') }}"
                class="group block rounded-lg border border-gray-200 bg-white p-6 shadow-sm transition-shadow duration-200 hover:shadow-md">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="flex h-12 w-12 items-center justify-center rounded-lg bg-[#f3e8ff]">
                            <i class="fa-solid fa-tools text-xl text-[#6840c6]"></i>
                        </div>
                    </div>
                    <div class="ml-4">
                        <h3 class="text-lg font-semibold text-gray-900 transition-colors group-hover:text-[#6840c6]">
                            Equipment
                        </h3>
                        <p class="text-sm text-gray-500">
                            Manage and track vessel equipment inventory
                        </p>
                    </div>
                    <div class="ml-auto">
                        <i class="fa-solid fa-arrow-right text-gray-400 transition-colors group-hover:text-[#6840c6]"></i>
                    </div>
                </div>
            </a>

            {{-- Consumables Card --}}
            <a href="{{ route('inventory.consumables') }}"
                class="group block rounded-lg border border-gray-200 bg-white p-6 shadow-sm transition-shadow duration-200 hover:shadow-md">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="flex h-12 w-12 items-center justify-center rounded-lg bg-[#f0f9ff]">
                            <i class="fa-solid fa-box text-xl text-[#0ea5e9]"></i>
                        </div>
                    </div>
                    <div class="ml-4">
                        <h3 class="text-lg font-semibold text-gray-900 transition-colors group-hover:text-[#0ea5e9]">
                            Consumables
                        </h3>
                        <p class="text-sm text-gray-500">
                            Manage and track vessel consumables inventory
                        </p>
                    </div>
                    <div class="ml-auto">
                        <i class="fa-solid fa-arrow-right text-gray-400 transition-colors group-hover:text-[#0ea5e9]"></i>
                    </div>
                </div>
            </a>
        </div>

        {{-- Quick Stats --}}
        <div class="grid grid-cols-1 gap-6 md:grid-cols-3">
            @include('components.equipment.operational-card')
            @include('components.equipment.action-needed-card')
            @include('components.equipment.out-of-service-card')
        </div>
    </div>
@endsection
