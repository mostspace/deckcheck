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

    <div class="flex flex-col gap-4 px-3 py-4 sm:gap-8 sm:px-6 sm:py-6 lg:px-8">
        <div class="">
            <h1 class="text-2xl font-semibold text-[#0f1728]">
                {{ $title ?? 'New Record' }}
            </h1>
            @isset($subtitle)
                <p class="text-[#475466]">{!! $subtitle !!}</p>
            @endisset
        </div>

        <div>
            <section class="rounded-lg border border-[#e4e7ec] bg-white p-6">

                @if ($errors->any())
                    <div class="mb-6 rounded-lg border border-red-300 bg-red-100 p-4 text-red-700">
                        <ul class="list-disc space-y-1 pl-5 text-sm">
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
