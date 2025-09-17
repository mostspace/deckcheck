@extends('v2.layouts.app')

@section('title', 'Reports')

@section('content')

    @include('v2.components.navigation.page-header', [
        'tabs' => [
            ['id' => 'analytics', 'label' => 'Analytics', 'icon' => 'tab-analytics.svg', 'active' => true],
            ['id' => 'exports', 'label' => 'Exports', 'icon' => 'tab-exports.svg', 'active' => false]
        ]
    ])
    
    @include('v2.components.navigation.sub-header', [
        'breadcrumbs' => [
            ['label' => 'Reports', 'short' => 'Rep', 'icon' => 'sidebar-solid-folders.svg'],
            ['label' => 'Analytics']
        ]
    ])

    <div class="p-6">
        <h2 class="text-2xl font-bold">Reports Page</h2>
        <p>This is the reports page with dynamic tabs for Analytics and Exports.</p>
        
        <div id="panel-analytics" class="mt-4 block">
            <h3>Analytics Content</h3>
            <p>Details about analytics and reporting.</p>
        </div>
        <div id="panel-exports" class="mt-4 hidden">
            <h3>Exports Content</h3>
            <p>Details about data exports.</p>
        </div>
    </div>
@endsection
