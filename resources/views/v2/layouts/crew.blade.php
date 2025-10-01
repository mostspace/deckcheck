@extends('v2.layouts.app')

@section('content')
    <div class="space-y-6">
        <!-- Crew Module Header -->
        <div class="border-b border-gray-200 pb-4">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-2xl font-bold text-gray-900">@yield('page-title', 'Crew Operations')</h1>
                    <p class="mt-1 text-sm text-gray-500">@yield('page-description', 'Manage vessel operations and maintenance')</p>
                </div>
                <div class="flex items-center space-x-3">
                    @yield('page-actions')
                </div>
            </div>
        </div>

        <!-- Crew Navigation Tabs -->
        @hasSection('crew-tabs')
            <div class="border-b border-gray-200">
                <nav class="-mb-px flex space-x-8" aria-label="Tabs">
                    @yield('crew-tabs')
                </nav>
            </div>
        @endif

        <!-- Crew Content -->
        <div class="space-y-6">
            @yield('crew-content')
        </div>
    </div>
@endsection
