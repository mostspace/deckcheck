@extends('v2.layouts.app')

@section('title', 'Consumables')

@section('content')

    @include('v2.components.nav.header-routing', [
        'activeTab' => 'consumables',
        'context' => 'inventory',
        'breadcrumbs' => [
            ['label' => 'Inventory', 'icon' => asset('assets/media/icons/sidebar-solid-archive-box.svg')],
            ['label' => 'Consumables']
        ]
    ])

    <div class="px-3 py-4 sm:px-6 sm:py-6 lg:px-8">
        {{-- Header --}}
        <div class="mb-6">
            <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
                <div>
                    <h1 class="text-2xl font-semibold text-[#0f1728]">Consumables</h1>
                    <p class="text-[#475466]">Manage and track vessel consumables inventory.</p>
                </div>
            </div>
        </div>

        {{-- Consumables content will go here --}}
        <div class="rounded-lg border bg-white p-6 shadow">
            <p class="text-gray-500">Consumables management interface coming soon...</p>
        </div>
    </div>
@endsection
