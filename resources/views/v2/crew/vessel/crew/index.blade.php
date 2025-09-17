{{-- Header --}}
<div class="mb-6">
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h1 class="text-2xl font-semibold text-[#0f1728]">Crew Management</h1>
            <p class="text-[#475466]">Manage crew certifications, training records, and compliance tracking</p>
        </div>
    </div>
</div>

{{-- System Messages --}}
@if (session('success'))
    <div class="mb-6 p-4 bg-green-100 border border-green-300 text-green-800 rounded-lg text-sm">
        {{ session('success') }}
    </div>
@endif

{{-- Search & Filters --}}
<div class="mb-6 flex items-center gap-4">

    {{-- Search --}}
    <div class="flex-1 max-w-md">
        <div class="relative">
            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                <i class="fa-solid fa-search text-[#667084]"></i>
            </div>
            <input type="text" placeholder="Search crew members..."
                class="w-full pl-10 pr-4 py-2.5 bg-white rounded-lg shadow-[0px_1px_2px_0px_rgba(16,24,40,0.05)] border border-[#cfd4dc] text-[#0f1728] placeholder-[#667084] focus:outline-none focus:ring-2 focus:ring-[#6840c6] focus:border-transparent">
        </div>
    </div>

    <div class="flex items-center gap-3">

        {{-- Department Select --}}
        <div class="relative">
            <select
                class="appearance-none pr-10 pl-3 px-4 py-2.5 bg-white rounded-lg shadow-[0px_1px_2px_0px_rgba(16,24,40,0.05)] border border-[#cfd4dc] text-[#344053] text-sm focus:outline-none focus:ring-2 focus:ring-[#6840c6] focus:border-transparent">
                <option>All Departments</option>
                <option>Engineering</option>
                <option>Deck</option>
                <option>Interior</option>
                <option>Galley</option>
            </select>
            <div class="pointer-events-none absolute inset-y-0 right-3 flex items-center">
                <i class="fa-solid fa-chevron-down text-[#667084] text-xs"></i>
            </div>
        </div>

        {{-- Create Button --}}
        @php
            $boarding = currentUserBoarding();
        @endphp

        @if (in_array(currentUserBoarding()?->access_level, ['owner', 'admin']))
            <button id="openInviteModal"
                class="px-4 py-2.5 bg-[#7e56d8] rounded-lg shadow border border-[#7e56d8] text-white text-sm font-medium hover:bg-[#6840c6] transition-colors">
                <i class="fa-solid fa-plus mr-2"></i>
                Add Crew Member
            </button>
        @endif

    </div>
</div>

{{-- Crew Table --}}
<div class="bg-white rounded-lg shadow-[0px_1px_2px_0px_rgba(16,24,40,0.06)] border border-[#e4e7ec] overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full">

            {{-- Table Header --}}
            <thead class="bg-[#f8f9fb] border-b border-[#e4e7ec]">
                <tr>
                    <th class="px-6 py-3 text-left">
                        <button id="sort-name" type="button"
                            class="flex items-center text-xs font-medium text-[#475466] hover:text-[#6840c6] uppercase tracking-wider">
                            Crew Member
                            <i id="sort-icon" class="fa-solid fa-sort ml-1 text-xs transition-colors text-[#475466]"></i>
                        </button>
                    </th>
                    <th class="px-6 py-3 text-left">
                        <span class="text-xs font-medium text-[#7e56d8] uppercase tracking-wider">Department</span>
                    </th>
                    <th class="px-6 py-3 text-left">
                        <span class="text-xs font-medium text-[#7e56d8] uppercase tracking-wider">Position</span>
                    </th>
                    <th class="px-6 py-3 text-left">
                        <span class="text-xs font-medium text-[#7e56d8] uppercase tracking-wider">Join Date</span>
                    </th>
                    <th class="px-6 py-3 text-left">
                        <span class="text-xs font-medium text-[#7e56d8] uppercase tracking-wider">Details</span>
                    </th>
                </tr>
            </thead>

            {{-- Crew Loop --}}
            <tbody id="user-table-body" class="divide-y divide-[#e4e7ec]">
                @forelse($users as $user)
                    <tr class="hover:bg-[#f8f9fb] transition-colors">
                        <td class="px-6 py-4">
                            <div class="flex items-center">
                                <img class="w-10 h-10 rounded-full" src="{{ $user->profile_pic ? Storage::url($user->profile_pic) : asset('images/placeholders/user.png') }}"
                                    alt="Hero Photo for {{ $user->first_name }}">
                                <div class="ml-4">
                                    <div class="text-sm font-medium text-[#0f1728] name" data-name="{{ strtolower($user->name) }}">{{ $user->first_name }} {{ $user->last_name }}
                                    </div>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            <span
                                class="px-2 py-0.5 bg-[#ebfdf2] rounded-2xl text-center text-[#027947] text-xs">{{ $user->department ?? 'N/A' }}</span>
                        </td>
                        <td class="px-6 py-4 text-sm text-[#475466]">{{ $user->position ?? 'N/A' }}</td>
                        <td class="px-6 py-4 text-sm text-[#475466]">{{ $user->created_at->format('F j, Y') }}</td>
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-2">
                                <a href="{{ route('vessel.crew.show', $user) }}" class="text-[#667084] hover:text-[#344053]">
                                    <i class="fa-solid fa-eye"></i>
                                </a>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="px-6 py-4 text-center text-sm text-[#667084]">No Users Assigned to this Vessel.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

@include('components.vessel.crew.invite-user-modal')


{{-- Sort By Crew Member Name --}}
<script>
    document.addEventListener('DOMContentLoaded', () => {
        const sortButton = document.getElementById('sort-name');
        const tableBody = document.getElementById('user-table-body');

        let ascending = null;

        sortButton.addEventListener('click', () => {
            ascending = ascending === null ? true : !ascending;

            const rows = Array.from(tableBody.querySelectorAll('tr')).filter(row => row.querySelector('.name'));

            rows.sort((a, b) => {
                const aName = a.querySelector('.name').dataset.name;
                const bName = b.querySelector('.name').dataset.name;
                return aName.localeCompare(bName) * (ascending ? 1 : -1);
            });

            rows.forEach(row => tableBody.appendChild(row));

            // Replace icon with directional indicator
            const oldIcon = document.getElementById('sort-icon');
            const newIcon = document.createElement('i');
            newIcon.id = 'sort-icon';
            newIcon.className = `fa-solid ${
                ascending ? 'fa-arrow-up-short-wide' : 'fa-arrow-down-wide-short'
            } ml-1 text-xs text-[#6840c6] transition-colors`;
            sortButton.replaceChild(newIcon, oldIcon);
        });
    });
</script>

{{-- Invite Modal --}}
<script>
    const inviteModal = document.getElementById('inviteUserModal');

    function openInviteModal() {
        inviteModal.classList.remove('translate-x-full');
    }

    function closeInviteModal() {
        inviteModal.classList.add('translate-x-full');
    }

    document.getElementById('openInviteModal')
        .addEventListener('click', openInviteModal);
    document.getElementById('closeInviteModal')
        .addEventListener('click', closeInviteModal);
    document.getElementById('cancelInviteModal')
        .addEventListener('click', closeInviteModal);

    document.addEventListener('keydown', e => {
        if (e.key === 'Escape') closeInviteModal();
    });
</script>