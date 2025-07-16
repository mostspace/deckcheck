<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    {{-- Two-thirds content: compliance and tasks --}}
    <div class="col-span-1 lg:col-span-2 space-y-6">
        @include('components.dash.cards.compliance-card')
        @include('components.dash.cards.upcoming-tasks')
        @include('components.dash.cards.expiring-equipment')
    </div>

    {{-- One-third content: deficiencies, notifications, chart --}}
    <div class="col-span-1 space-y-6">
        @include('components.dash.cards.deficiencies-card')
        @include('components.dash.cards.notifications-card')
        @include('components.dash.cards.chart-card')
    </div>
</div>
