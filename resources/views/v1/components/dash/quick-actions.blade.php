<div class="flex flex-wrap gap-3">
    @if (auth()->user() && in_array(auth()->user()->system_role, ['superadmin', 'staff', 'dev']))
        <a href="{{ route('admin.vessels.index') }}"
            class="flex items-center justify-center gap-2 overflow-hidden rounded-lg border border-blue-600 bg-blue-600 px-4 py-2.5 shadow-[0px_1px_2px_0px_rgba(16,24,40,0.05)] transition-colors hover:bg-blue-700">
            <i class="fa-solid fa-exchange-alt text-white"></i>
            <div class="text-sm leading-tight text-white">Switch Vessel</div>
        </a>
    @endif

    <button
        class="flex items-center justify-center gap-2 overflow-hidden rounded-lg border border-[#cfd4dc] bg-white px-4 py-2.5 shadow-[0px_1px_2px_0px_rgba(16,24,40,0.05)] transition-colors hover:bg-[#f8f9fb]">
        <i class="fa-solid fa-plus text-[#344053]"></i>
        <div class="text-sm leading-tight text-[#344053]">Add Equipment</div>
    </button>

    <button
        class="flex items-center justify-center gap-2 overflow-hidden rounded-lg border border-[#cfd4dc] bg-white px-4 py-2.5 shadow-[0px_1px_2px_0px_rgba(16,24,40,0.05)] transition-colors hover:bg-[#f8f9fb]">
        <i class="fa-solid fa-file-lines text-[#344053]"></i>
        <div class="text-sm leading-tight text-[#344053]">Generate Report</div>
    </button>

    <button
        class="flex items-center justify-center gap-2 overflow-hidden rounded-lg border border-[#7e56d8] bg-[#7e56d8] px-4 py-2.5 shadow-[0px_1px_2px_0px_rgba(16,24,40,0.05)] transition-colors hover:bg-[#6840c6]">
        <i class="fa-solid fa-triangle-exclamation text-white"></i>
        <div class="text-sm leading-tight text-white">Log Deficiency</div>
    </button>
</div>
