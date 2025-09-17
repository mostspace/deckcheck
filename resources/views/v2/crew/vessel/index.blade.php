@extends('v2.layouts.app')

@section('title', 'Vessel Management')

@section('content')

    @include('v2.components.navigation.page-header', [
        'tabs' => [
            ['id' => 'overview', 'label' => 'Overview', 'short' => 'Ov', 'icon' => 'tab-overview.svg', 'active' => true],
            ['id' => 'crew', 'label' => 'Crew', 'short' => 'Crew', 'icon' => 'tab-crew.svg', 'active' => false],
            ['id' => 'specifications', 'label' => 'Specifications', 'short' => 'Spec', 'icon' => 'tab-specifications.svg', 'active' => false],
            ['id' => 'documents', 'label' => 'Documents', 'short' => 'Doc', 'icon' => 'tab-documents.svg', 'active' => false]
        ]
    ])
    
    @include('v2.components.navigation.sub-header', [
        'breadcrumbs' => [
            ['label' => 'Vessel', 'short' => 'Vessel', 'icon' => 'sidebar-solid-boat.svg'],
            ['label' => 'Overview']
        ]
    ])

    <div class="p-6">
        <h2 class="text-2xl font-bold">Vessel Management Page</h2>
        <p>This is the vessel management page with dynamic tabs for Overview, Crew, Specifications, and Documents.</p>
        
        <div id="panel-overview" class="mt-4 block">
            <h3>Overview Content</h3>
            <p>Details about vessel overview.</p>
        </div>
        <div id="panel-crew" class="mt-4 hidden">
            <h3>Crew Content</h3>
            <p>Details about vessel crew.</p>
        </div>
        <div id="panel-specifications" class="mt-4 hidden">
            <h3>Specifications Content</h3>
            <p>Details about vessel specifications.</p>
        </div>
        <div id="panel-documents" class="mt-4 hidden">
            <h3>Documents Content</h3>
            <p>Details about vessel documents.</p>
        </div>
    </div>
@endsection
