@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
    @include('components.dash.announcement')

    <div class="mb-6">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <h1 class="text-2xl font-semibold text-[#0f1728]">Dashboard</h1>
                <p class="text-[#475466]">M/Y Serenity - Maintenance Overview</p>
            </div>

            @include('components.dash.quick-actions')
        </div>
    </div>

    @include('components.dash.dashboard-grid')
@endsection