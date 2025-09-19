@extends('v2.layouts.app')

@section('title', 'Reports')

@section('content')

    @include('v2.components.navigation.page-header', [
        'tabs' => [
            ['id' => 'all_reports', 'label' => 'All Reports', 'active' => true],
            ['id' => 'my_reports', 'label' => 'My Reports', 'active' => false]
        ]
    ])
    
    @include('v2.components.navigation.sub-header', [
        'pageName' => 'Files',
        'pageIcon' => asset('assets/media/icons/sidebar-solid-folder.svg'),
        'activeTab' => 'All Reports'
    ])

    <div class="px-3 sm:px-6 lg:px-8 py-4 sm:py-6">
        
        {{-- All Reports Tab Panel --}}
        <div id="panel-all_reports" class="tab-panel" role="tabpanel" aria-labelledby="tab-all_reports">
            @include('v2.crew.files.all-reports.index')
        </div>

        {{-- My Reports Tab Panel --}}
        <div id="panel-my_reports" class="tab-panel hidden" role="tabpanel" aria-labelledby="tab-my_reports">
            @include('v2.crew.files.my-reports.index')
        </div>
        
    </div>
@endsection
