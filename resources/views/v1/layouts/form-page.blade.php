@extends('layouts.app')

@section('content')
    <div class="mb-6">
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
@endsection