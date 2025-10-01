<div class="grid grid-cols-1 gap-6 lg:grid-cols-3">
    {{-- Two-thirds content: compliance and tasks --}}
    <div class="col-span-1 space-y-6 lg:col-span-2">
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
