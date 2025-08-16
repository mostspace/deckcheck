<div class="flex flex-wrap gap-3">
    @if(auth()->user() && in_array(auth()->user()->system_role, ['superadmin', 'staff', 'dev']))
        <a href="{{ route('admin.vessels.index') }}" class="px-4 py-2.5 bg-blue-600 rounded-lg shadow-[0px_1px_2px_0px_rgba(16,24,40,0.05)] border border-blue-600 justify-center items-center gap-2 flex overflow-hidden hover:bg-blue-700 transition-colors">
            <i class="fa-solid fa-exchange-alt text-white"></i>
            <div class="text-white text-sm leading-tight">Switch Vessel</div>
        </a>
    @endif

    <button class="px-4 py-2.5 bg-white rounded-lg shadow-[0px_1px_2px_0px_rgba(16,24,40,0.05)] border border-[#cfd4dc] justify-center items-center gap-2 flex overflow-hidden hover:bg-[#f8f9fb] transition-colors">
        <i class="fa-solid fa-plus text-[#344053]"></i>
        <div class="text-[#344053] text-sm leading-tight">Add Equipment</div>
    </button>

    <button class="px-4 py-2.5 bg-white rounded-lg shadow-[0px_1px_2px_0px_rgba(16,24,40,0.05)] border border-[#cfd4dc] justify-center items-center gap-2 flex overflow-hidden hover:bg-[#f8f9fb] transition-colors">
        <i class="fa-solid fa-file-lines text-[#344053]"></i>
        <div class="text-[#344053] text-sm leading-tight">Generate Report</div>
    </button>

    <button class="px-4 py-2.5 bg-[#7e56d8] rounded-lg shadow-[0px_1px_2px_0px_rgba(16,24,40,0.05)] border border-[#7e56d8] justify-center items-center gap-2 flex overflow-hidden hover:bg-[#6840c6] transition-colors">
        <i class="fa-solid fa-triangle-exclamation text-white"></i>
        <div class="text-white text-sm leading-tight">Log Deficiency</div>
    </button>
</div>
