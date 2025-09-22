@extends('v2.layouts.app')

@section('title', 'Maintenance New Category')

@section('content')

    @include('v2.components.nav.header', [
        'tabs' => [],
        'pageName' => 'Maintenance',
        'pageIcon' => asset('assets/media/icons/sidebar-solid-wrench-scredriver.svg'),
        'activeTab' => 'Index',
        'breadcrumbs' => [
            ['label' => 'Maintenance', 'url' => route('maintenance.index')],
            ['label' => 'Index', 'url' => route('maintenance.index')],
            ['label' => $breadcrumbLabel ?? 'New Category', 'url' => null]
        ],
        'showTopHeader' => false
    ])

    <div class="flex flex-col gap-4 sm:gap-8 px-3 sm:px-6 lg:px-8 py-4 sm:py-6">
        <div class="">
            <h1 class="text-2xl font-semibold text-[#0f1728]">
                {{ $title ?? 'New Record' }}
            </h1>
            @isset($subtitle)
                <p class="text-[#475466]">{!! $subtitle !!}</p>
            @endisset
        </div>

        <div>
            <section class="bg-white rounded-lg border border-[#e4e7ec] p-6">
                
                @if ($errors->any())
                    <div class="mb-6 p-4 bg-red-100 border border-red-300 text-red-700 rounded-lg">
                        <ul class="list-disc pl-5 space-y-1 text-sm">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                @yield('form')
            </section>
        </div>
    </div>
@endsection