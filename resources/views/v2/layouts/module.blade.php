@extends('v2.layouts.app')

@section('content')
<div class="space-y-6">
    <!-- Module Header -->
    <div class="border-b border-gray-200 pb-4">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-bold text-gray-900">@yield('module-title', 'Module')</h1>
                <p class="mt-1 text-sm text-gray-500">@yield('module-description', 'Module description')</p>
            </div>
            <div class="flex items-center space-x-3">
                @yield('module-actions')
            </div>
        </div>
    </div>

    <!-- Module Navigation Tabs -->
    @hasSection('module-tabs')
        <div class="border-b border-gray-200">
            <nav class="-mb-px flex space-x-8" aria-label="Tabs">
                @yield('module-tabs')
            </nav>
        </div>
    @endif

    <!-- Module Content -->
    <div class="space-y-6">
        @yield('module-content')
    </div>
</div>
@endsection
