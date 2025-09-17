@extends('v2.layouts.app')

@section('title', 'Inventory Index')

@section('content')

    @include('v2.components.navigation.page-header', [
        'tabs' => [
            ['id' => 'equipment', 'label' => 'Equipment', 'icon' => 'tab-equipment.svg', 'active' => true],
            ['id' => 'consumables', 'label' => 'Consumables', 'icon' => 'tab-consumables.svg', 'active' => false]
        ]
    ])
    
    @include('v2.components.navigation.sub-header', [
        'breadcrumbs' => [
            ['label' => 'Inventory', 'short' => 'Inv', 'icon' => 'sidebar-solid-archive-box.svg'],
            ['label' => 'Equipment']
        ]
    ])

    <div class="p-6">
        <h2 class="text-2xl font-bold">Inventory Page</h2>
        <p>This is the inventory page with dynamic tabs for Equipment and Consumables.</p>
        
        <div id="panel-equipment" class="mt-4 block">
            <h3>Equipment Content</h3>
            <p>Details about equipment inventory.</p>
        </div>
        <div id="panel-consumables" class="mt-4 hidden">
            <h3>Consumables Content</h3>
            <p>Details about consumables inventory.</p>
        </div>
    </div>
@endsection
