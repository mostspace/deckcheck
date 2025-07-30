<!-- Vessel Info -->
<div class="p-6 bg-[#f9f5ff] border-b border-[#e4e7ec]" id="selected-yacht">
    <div class="flex items-center justify-between">
        <div class="flex items-center gap-4">
            <div class="w-16 h-16 rounded-lg bg-[#f4ebff] flex items-center justify-center">
                <i class="fa-solid fa-ship text-[#6840c6] text-2xl"></i>
            </div>
            <div>
                <div class="text-sm text-[#475466]">Invitation Pending From</div>
                <div class="text-lg font-semibold text-[#0f1728]">{{ $invitation->boarding->vessel->type }}
                    {{ $invitation->boarding->vessel->name }}</div>
                <div class="text-sm text-[#475466]">{{ $invitation->boarding->vessel->vessel_size }}m
                    {{ $invitation->boarding->vessel->vessel_make }} • {{ $invitation->boarding->vessel->build_year }}</div>
            </div>
        </div>
    </div>
</div>
