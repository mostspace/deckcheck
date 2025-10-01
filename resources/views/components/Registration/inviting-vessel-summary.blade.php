<!-- Vessel Info -->
<div class="border-b border-[#e4e7ec] bg-[#f9f5ff] p-6" id="selected-yacht">
    <div class="flex items-center justify-between">
        <div class="flex items-center gap-4">
            <div class="flex h-16 w-16 items-center justify-center rounded-lg bg-[#f4ebff]">
                <i class="fa-solid fa-ship text-2xl text-[#6840c6]"></i>
            </div>
            <div>
                <div class="text-sm text-[#475466]">Invitation Pending From</div>
                <div class="text-lg font-semibold text-[#0f1728]">{{ $invitation->boarding->vessel->type }}
                    {{ $invitation->boarding->vessel->name }}</div>
                <div class="text-sm text-[#475466]">{{ $invitation->boarding->vessel->vessel_size }}m
                    {{ $invitation->boarding->vessel->vessel_make }} • {{ $invitation->boarding->vessel->build_year }}
                </div>
            </div>
        </div>
    </div>
</div>
