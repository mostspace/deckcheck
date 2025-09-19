@extends('v2.layouts.app')

@section('title', 'Inventory')

@section('content')

    @include('v2.components.navigation.header', [
        'tabs' => [
            ['id' => 'equipment', 'label' => 'Equipment', 'active' => true],
            ['id' => 'consumables', 'label' => 'Consumables', 'active' => false]
        ],
        'pageName' => 'Inventory',
        'pageIcon' => asset('assets/media/icons/sidebar-solid-archive-box.svg'),
        'activeTab' => 'Equipment'
    ])

    <div class="px-3 sm:px-6 lg:px-8 py-4 sm:py-6">
        {{-- Equipment Tab Panel --}}
        <div id="panel-equipment" class="tab-panel" role="tabpanel" aria-labelledby="tab-equipment">
            @include('v2.crew.inventory.equipment.index')
        </div>
        
        {{-- Consumables Tab Panel --}}
        <div id="panel-consumables" class="tab-panel hidden" role="tabpanel" aria-labelledby="tab-consumables">
            @include('v2.crew.inventory.consumables.index')
        </div>
    </div>
@endsection