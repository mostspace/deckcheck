{{-- #System Messages --}}
@if (session('success'))
    <div class="mb-6 p-4 bg-green-100 border border-green-300 text-green-800 rounded-lg text-sm">
        {{ session('success') }}
    </div>
@endif

{{-- Header --}}
<div class="mb-6">

    {{-- #Title Block --}}
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h1 class="text-2xl font-semibold text-[#0f1728]">Manifest</h1>
            <p class="text-[#475466]">Manifest content goes here.</p>
        </div>
    </div>

</div>