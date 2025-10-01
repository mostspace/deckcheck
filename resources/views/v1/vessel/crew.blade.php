@extends('layouts.app')

@section('title', 'Crew')

@section('content')

    {{-- Header --}}
    <div class="mb-6">
        <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <h1 class="text-2xl font-semibold text-[#0f1728]">Crew Management</h1>
                <p class="text-[#475466]">Manage crew certifications, training records, and compliance tracking</p>
            </div>
        </div>
    </div>

    {{-- System Messages --}}
    @if (session('success'))
        <div class="mb-6 rounded-lg border border-green-300 bg-green-100 p-4 text-sm text-green-800">
            {{ session('success') }}
        </div>
    @endif

    {{-- Search & Filters --}}
    <div class="mb-6 flex items-center gap-4">

        {{-- Search --}}
        <div class="max-w-md flex-1">
            <div class="relative">
                <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3">
                    <i class="fa-solid fa-search text-[#667084]"></i>
                </div>
                <input type="text" placeholder="Search crew members..."
                    class="w-full rounded-lg border border-[#cfd4dc] bg-white py-2.5 pl-10 pr-4 text-[#0f1728] placeholder-[#667084] shadow-[0px_1px_2px_0px_rgba(16,24,40,0.05)] focus:border-transparent focus:outline-none focus:ring-2 focus:ring-[#6840c6]">
            </div>
        </div>

        <div class="flex items-center gap-3">

            {{-- Department Select --}}
            <div class="relative">
                <select
                    class="appearance-none rounded-lg border border-[#cfd4dc] bg-white px-4 py-2.5 pl-3 pr-10 text-sm text-[#344053] shadow-[0px_1px_2px_0px_rgba(16,24,40,0.05)] focus:border-transparent focus:outline-none focus:ring-2 focus:ring-[#6840c6]">
                    <option>All Departments</option>
                    <option>Engineering</option>
                    <option>Deck</option>
                    <option>Interior</option>
                    <option>Galley</option>
                </select>
                <div class="pointer-events-none absolute inset-y-0 right-3 flex items-center">
                    <i class="fa-solid fa-chevron-down text-xs text-[#667084]"></i>
                </div>
            </div>

            {{-- Create Button --}}
            @php
                $boarding = currentUserBoarding();
            @endphp

            @if (in_array(currentUserBoarding()?->access_level, ['owner', 'admin']))
                <button id="openInviteModal"
                    class="rounded-lg border border-[#7e56d8] bg-[#7e56d8] px-4 py-2.5 text-sm font-medium text-white shadow transition-colors hover:bg-[#6840c6]">
                    <i class="fa-solid fa-plus mr-2"></i>
                    Add Crew Member
                </button>
            @endif

        </div>
    </div>

    {{-- Crew Table --}}
    <div class="overflow-hidden rounded-lg border border-[#e4e7ec] bg-white shadow-[0px_1px_2px_0px_rgba(16,24,40,0.06)]">
        <div class="overflow-x-auto">
            <table class="w-full">

                {{-- Table Header --}}
                <thead class="border-b border-[#e4e7ec] bg-[#f8f9fb]">
                    <tr>
                        <th class="px-6 py-3 text-left">
                            <button id="sort-name" type="button"
                                class="flex items-center text-xs font-medium uppercase tracking-wider text-[#475466] hover:text-[#6840c6]">
                                Crew Member
                                <i id="sort-icon"
                                    class="fa-solid fa-sort ml-1 text-xs text-[#475466] transition-colors"></i>
                            </button>
                        </th>
                        <th class="px-6 py-3 text-left">
                            <span class="text-xs font-medium uppercase tracking-wider text-[#7e56d8]">Department</span>
                        </th>
                        <th class="px-6 py-3 text-left">
                            <span class="text-xs font-medium uppercase tracking-wider text-[#7e56d8]">Position</span>
                        </th>
                        <th class="px-6 py-3 text-left">
                            <span class="text-xs font-medium uppercase tracking-wider text-[#7e56d8]">Join Date</span>
                        </th>
                        <th class="px-6 py-3 text-left">
                            <span class="text-xs font-medium uppercase tracking-wider text-[#7e56d8]">Details</span>
                        </th>
                    </tr>
                </thead>

                {{-- Crew Loop --}}
                <tbody id="user-table-body" class="divide-y divide-[#e4e7ec]">
                    @forelse($users as $user)
                        <tr class="transition-colors hover:bg-[#f8f9fb]">
                            <td class="px-6 py-4">
                                <div class="flex items-center">
                                    <img class="h-10 w-10 rounded-full"
                                        src="{{ $user->profile_pic ? Storage::url($user->profile_pic) : asset('images/placeholders/user.png') }}"
                                        alt="Hero Photo for {{ $user->first_name }}">
                                    <div class="ml-4">
                                        <div class="name text-sm font-medium text-[#0f1728]"
                                            data-name="{{ strtolower($user->name) }}">{{ $user->first_name }}
                                            {{ $user->last_name }}
                                        </div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <span
                                    class="rounded-2xl bg-[#ebfdf2] px-2 py-0.5 text-center text-xs text-[#027947]">{{ $user->department ?? 'N/A' }}</span>
                            </td>
                            <td class="px-6 py-4 text-sm text-[#475466]">{{ $user->position ?? 'N/A' }}</td>
                            <td class="px-6 py-4 text-sm text-[#475466]">{{ $user->created_at->format('F j, Y') }}</td>
                            <td class="px-6 py-4">
                                <div class="flex items-center gap-2">
                                    <a href="{{ route('vessel.crew.show', $user) }}"
                                        class="text-[#667084] hover:text-[#344053]">
                                        <i class="fa-solid fa-eye"></i>
                                    </a>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-6 py-4 text-center text-sm text-[#667084]">No Users Assigned to
                                this Vessel.</td>
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

                const rows = Array.from(tableBody.querySelectorAll('tr')).filter(row => row.querySelector(
                    '.name'));

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

@endsection
