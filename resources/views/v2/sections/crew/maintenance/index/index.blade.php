<div class="flex h-full flex-col gap-4 overflow-y-auto bg-white sm:gap-6">
    <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
        <div>
            <h1 class="text-2xl font-semibold text-[#0f1728]">Maintenance Index</h1>
            <p class="text-[#475466]">Overview of equipment maintenance requirements.</p>
        </div>
    </div>

    <div class="flex flex-col gap-4 sm:gap-6">
        @include ('v2.sections.crew.maintenance.index.stat-cards')

        @include ('v2.sections.crew.maintenance.index.maintenance-table')
    </div>
</div>
