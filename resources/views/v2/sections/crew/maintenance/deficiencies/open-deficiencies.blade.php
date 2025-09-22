{{-- #Open Deficiencies --}}
<div class="bg-white rounded-lg border border-[#e4e7ec] shadow-sm overflow-x-auto">
    <table class="w-full text-sm">
        <thead class="bg-[#f8f9fb] text-[#475466] uppercase text-xs border-b border-[#e4e7ec]">
            <tr>
                <th class="text-left px-6 py-3">ID</th>
                <th class="text-left px-6 py-3">Equipment</th>
                <th class="text-left px-6 py-3">Subject</th>
                <th class="text-left px-6 py-3">Priority</th>
                <th class="text-left px-6 py-3">Status</th>
                <th class="text-left px-6 py-3">Opened</th>
                <th class="text-left px-6 py-3">Actions</th>
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
                            <a href="{{ route('deficiencies.show', $deficiency) }}" class="text-sm font-medium text-[#6941c6] hover:underline">
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
                            <div class="w-16 h-16 bg-[#f8f9fb] rounded-full flex items-center justify-center mb-4">
                                <i class="fa-solid fa-clipboard-list text-2xl text-[#c7c9d1]"></i>
                            </div>
                            <div class="text-lg font-medium text-[#344053] mb-2">No Deficiencies Found</div>
                            <div class="text-sm">No deficiencies have been logged yet for this vessel.</div>
                        </div>
                    </td>
                </tr>
            @endif

        </tbody>
    </table>
</div>