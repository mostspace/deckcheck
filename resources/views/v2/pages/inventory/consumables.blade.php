@extends('v2.layouts.app')

@section('title', 'Consumables')

@section('content')

    @include('v2.components.maintenance.header', [
        'activeTab' => 'consumables',
        'context' => 'inventory',
        'breadcrumbs' => [
            ['label' => 'Inventory', 'icon' => asset('assets/media/icons/sidebar-solid-archive-box.svg')],
            ['label' => 'Consumables']
        ]
    ])

    <div class="px-3 sm:px-6 lg:px-8 py-4 sm:py-6">
        {{-- Header --}}
        <div class="mb-6">
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                <div>
                    <h1 class="text-2xl font-semibold text-[#0f1728]">Consumables</h1>
                    <p class="text-[#475466]">Manage and track vessel consumables inventory.</p>
                </div>
            </div>
        </div>
        
        {{-- Consumables content will go here --}}
        <div class="bg-white border rounded-lg shadow p-6">
            <p class="text-gray-500">Consumables management interface coming soon...</p>
        </div>
    </div>
@endsection
