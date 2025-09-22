{{-- Status Cards --}}
<div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
    @include ('v2.sections.crew.maintenance.manifest.status-cards.operational-card')
    @include ('v2.sections.crew.maintenance.manifest.status-cards.action-needed-card')
    @include ('v2.sections.crew.maintenance.manifest.status-cards.out-of-service-card')
</div>