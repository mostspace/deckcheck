{{-- #Open Deficiencies --}}
<div class="overflow-x-auto rounded-lg border border-[#e4e7ec] bg-white shadow-sm">
    <table class="w-full text-sm">
        <thead class="border-b border-[#e4e7ec] bg-[#f8f9fb] text-xs uppercase text-[#475466]">
            <tr>
                <th class="px-6 py-3 text-left">ID</th>
                <th class="px-6 py-3 text-left">Equipment</th>
                <th class="px-6 py-3 text-left">Subject</th>
                <th class="px-6 py-3 text-left">Priority</th>
                <th class="px-6 py-3 text-left">Status</th>
                <th class="px-6 py-3 text-left">Opened</th>
                <th class="px-6 py-3 text-left">Actions</th>
            </tr>
        </thead>

        <tbody class="text-[#344053]">

            {{-- ##Deficiencies Conditional --}}
            @if ($deficiencies->count())
                {{-- ###Deficiencies Loop --}}
                @foreach ($deficiencies as $deficiency)
                    <tr class="border-b border-[#e4e7ec] hover:bg-[#f9fafb]">
                        <td class="px-6 py-4 font-mono text-xs text-[#6941c6]">#{{ $deficiency->display_id }}</td>
                        <td class="px-6 py-4">
                            {{ $deficiency->equipment->name ?? '—' }}
                        </td>
                        <td class="px-6 py-4">
                            {{ $deficiency->subject }}
                        </td>
                        <td class="px-6 py-4">
                            {!! priority_badge($deficiency->priority) !!}
                        </td>
                        <td class="px-6 py-4">
                            {!! status_badge($deficiency->status) !!}
                        </td>
                        <td class="px-6 py-4 text-xs text-[#667084]">
                            {{ $deficiency->created_at->format('M j, Y') }}
                        </td>
                        <td class="px-6 py-4">
                            <a href="{{ route('deficiencies.show', $deficiency) }}"
                                class="text-sm font-medium text-[#6941c6] hover:underline">
                                View →
                            </a>
                        </td>
                    </tr>
                @endforeach
            @else
                {{-- ###No Deficiencies Message --}}
                <tr>
                    <td colspan="7" class="px-6 py-12 text-center">
                        <div class="flex flex-col items-center justify-center text-[#667084]">
                            <div class="mb-4 flex h-16 w-16 items-center justify-center rounded-full bg-[#f8f9fb]">
                                <i class="fa-solid fa-clipboard-list text-2xl text-[#c7c9d1]"></i>
                            </div>
                            <div class="mb-2 text-lg font-medium text-[#344053]">No Deficiencies Found</div>
                            <div class="text-sm">No deficiencies have been logged yet for this vessel.</div>
                        </div>
                    </td>
                </tr>
            @endif

        </tbody>
    </table>
</div>
