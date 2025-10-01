{{-- #System Messages --}}
@if (session('success'))
    <div class="mb-6 rounded-lg border border-green-300 bg-green-100 p-4 text-sm text-green-800">
        {{ session('success') }}
    </div>
@endif

{{-- Header --}}
<div class="mb-6">
    {{-- #Title Block --}}
    <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
        <div>
            <h1 class="text-2xl font-semibold text-[#0f1728]">Maintenance Index</h1>
            <p class="text-[#475466]">Overview of equipment maintenance requirements.</p>
        </div>
    </div>
</div>

@include ('v2.sections.crew.maintenance.deficiencies.dashboard')

@include ('v2.sections.crew.maintenance.deficiencies.open-deficiencies')
