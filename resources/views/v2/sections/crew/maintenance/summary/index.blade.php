<div class="mb-6">
    <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
        <div>
            <h1 class="text-2xl font-semibold text-[#0f1728]">Dashboard</h1>
            @if (currentVessel())
                <p class="text-[#475466]">{{ currentVessel()->type ?? 'M/Y' }} {{ currentVessel()->name }} - Maintenance
                    Overview</p>
            @else
                <p class="text-[#475466]">Maintenance Overview</p>
            @endif
        </div>

        @include('components.dash.quick-actions')
    </div>
</div>

@include('components.dash.dashboard-grid')
