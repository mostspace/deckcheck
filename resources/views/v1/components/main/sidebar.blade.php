<aside id="sidebar" class="z-20 flex hidden w-[280px] flex-shrink-0 flex-col border-r border-[#e4e7ec] bg-white">
    <!-- Header -->
    <div class="flex h-16 items-center border-b border-[#e4e7ec] px-4">
        <div class="flex items-center">
            <div class="flex h-10 w-10 items-center justify-center rounded-lg bg-[#6840c6] text-xl font-bold text-white">
                D</div>
            <span class="ml-3 font-semibold text-[#0f1728]">DeckCheck</span>
        </div>
    </div>

    <!-- Navigation -->
    <nav class="mt-6 flex-1">
        <ul class="space-y-1 px-4">

            {{-- Dashboard --}}
            <li>
                <a href="{{ route('dashboard') }}" @class([
                    'flex items-center px-3 py-2.5 text-md rounded-lg transition',
                    // ACTIVE
                    'bg-[#f9f5ff] text-[#6840c6]' => request()->routeIs('dashboard'),
                    // INACTIVE
                    'text-[#667084] hover:text-[#344053] hover:bg-[#f8f9fb]' => !request()->routeIs(
                        'dashboard'
                    )
                ])>
                    <i class="fa-solid fa-gauge mr-3 w-5"></i>
                    Dashboard
                </a>
            </li>

            {{-- Vessel --}}
            <li x-data="{ open: {{ request()->routeIs('vessel.*') ? 'true' : 'false' }} }" class="space-y-1">
                <a href="{{ route('vessel.index') }}" @click.prevent="open = !open"
                    @keydown.escape.window="open = false" role="button"
                    class="text-md flex cursor-pointer select-none items-center rounded-lg px-3 py-2.5 transition"
                    :class="{
                        'bg-[#f9f5ff] text-[#6840c6]': open,
                        'text-[#667084] hover:text-[#344053] hover:bg-[#f8f9fb]': !open
                    }">
                    <span class="flex items-center">
                        <i class="fa-solid fa-ship mr-3 w-5"></i>
                        Vessel
                    </span>
                </a>

                <ul x-show="open" x-collapse class="ml-8 mt-2 space-y-1 overflow-hidden">
                    <li>
                        <a href="{{ route('vessel.index') }}" @class([
                            'block px-3 py-2 text-sm rounded-lg transition font-light',
                            'text-[#6840c6]' => request()->routeIs('vessel.index'),
                            'text-[#475466] hover:bg-[#f8f9fb]' => !request()->routeIs('vessel.index')
                        ])>
                            Vessel Information
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('vessel.crew') }}" @class([
                            'block px-3 py-2 text-sm rounded-lg transition font-light',
                            'text-[#6840c6]' => request()->routeIs(['vessel.crew', 'vessel.crew.*']),
                            'text-[#475466] hover:bg-[#f8f9fb]' => !request()->routeIs('vessel.crew')
                        ])>
                            Crew
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('vessel.deckplan') }}" @class([
                            'block px-3 py-2 text-sm rounded-lg transition font-light',
                            'text-[#6840c6]' => request()->routeIs([
                                'vessel.deckplan',
                                'vessel.decks.*',
                                'locations.*'
                            ]),
                            'text-[#475466] hover:bg-[#f8f9fb]' => !request()->routeIs(
                                'vessel.deckplan'
                            )
                        ])>
                            Deck Plan
                        </a>
                    </li>
                </ul>
            </li>

            {{-- Maintenance --}}
            <li x-data="{ open: {{ request()->routeIs('maintenance.*') ? 'true' : 'false' }} }" class="space-y-1">
                <a href="#" @click.prevent="open = !open" @keydown.escape.window="open = false" role="button"
                    class="text-md flex cursor-pointer select-none items-center justify-between rounded-lg px-3 py-2.5 transition"
                    :class="{
                        'bg-[#f9f5ff] text-[#6840c6]': open,
                        'text-[#667084] hover:text-[#344053] hover:bg-[#f8f9fb]': !open
                    }">
                    <span class="flex items-center">
                        <i class="fa-solid fa-wrench mr-3 w-5"></i>
                        Maintenance
                    </span>
                </a>

                <ul x-show="open" x-collapse class="ml-8 mt-2 space-y-1 overflow-hidden">
                    <li>
                        <a href="{{ route('maintenance.index') }}" @class([
                            'block px-3 py-2 text-sm font-light rounded-lg transition',
                            'text-[#6840c6]' => request()->routeIs('maintenance.*'),
                            'text-[#475466] hover:bg-[#f8f9fb]' => !request()->routeIs(
                                'maintenance.index'
                            )
                        ])>
                            Index
                        </a>
                    </li>
                    <li>
                        <a href="#" @class([
                            'block px-3 py-2 text-sm font-light rounded-lg transition',
                            'text-[#6840c6]' => request()->routeIs('maintenance.schedule'),
                            'text-[#475466] hover:bg-[#f8f9fb]' => !request()->routeIs(
                                'maintenance.schedule'
                            )
                        ])>
                            Schedule
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('deficiencies.index') }}" @class([
                            'block px-3 py-2 text-sm font-light rounded-lg transition',
                            'text-[#6840c6]' => request()->routeIs('deficiencies.*'),
                            'text-[#475466] hover:bg-[#f8f9fb]' => !request()->routeIs(
                                'deficiencies.index'
                            )
                        ])>
                            Deficiencies
                        </a>
                    </li>
                </ul>
            </li>

            {{-- Inventory --}}
            <li x-data="{ open: {{ request()->routeIs('equipment.*') ? 'true' : 'false' }} }" class="space-y-1"> {{-- Need to figure out how to add conditions to this --}}
                <a href="#" @click.prevent="open = !open" @keydown.escape.window="open = false" role="button"
                    class="text-md flex cursor-pointer select-none items-center justify-between rounded-lg px-3 py-2.5 transition"
                    :class="{
                        'bg-[#f9f5ff] text-[#6840c6]': open,
                        'text-[#667084] hover:text-[#344053] hover:bg-[#f8f9fb]': !open
                    }">
                    <span class="flex items-center">
                        <i class="fa-solid fa-boxes-stacked mr-3 w-5"></i>
                        Inventory
                    </span>
                </a>

                <ul x-show="open" x-collapse class="ml-8 mt-2 space-y-1 overflow-hidden">
                    <li>
                        <a href="{{ route('inventory.index') }}" @class([
                            'block px-3 py-2 text-sm font-light rounded-lg transition',
                            'text-[#6840c6]' => request()->routeIs('equipment.*'),
                            'text-[#475466] hover:bg-[#f8f9fb]' => !request()->routeIs(
                                'inventory.index'
                            )
                        ])>
                            Equipment
                        </a>
                    </li>
                    <li>
                        <a href="#" @class([
                            'block px-3 py-2 text-sm font-light rounded-lg transition',
                            'text-[#6840c6]' => request()->routeIs('equipment.consumables'),
                            'text-[#475466] hover:bg-[#f8f9fb]' => !request()->routeIs(
                                'equipment.consumables'
                            ),
                            {{-- Not correct --}}
                        ])>
                            Consumables
                        </a>
                    </li>
                </ul>
            </li>

            {{-- Reports --}}
            <li x-data="{ open: {{ request()->routeIs('files.*') ? 'true' : 'false' }} }" class="space-y-1">
                <a href="#" @click.prevent="open = !open" @keydown.escape.window="open = false" role="button"
                    class="text-md flex cursor-pointer select-none items-center justify-between rounded-lg px-3 py-2.5 transition"
                    :class="{
                        'bg-[#f9f5ff] text-[#6840c6]': open,
                        'text-[#667084] hover:text-[#344053] hover:bg-[#f8f9fb]': !open
                    }">
                    <span class="flex items-center">
                        <i class="fa-solid fa-file-lines mr-3 w-5"></i>
                        Reports
                    </span>
                </a>

                <ul x-show="open" x-collapse class="ml-8 mt-2 space-y-1 overflow-hidden">
                    <li>
                        <a href="#" @class([
                            'block px-3 py-2 text-sm font-light rounded-lg transition',
                            'text-[#6840c6]' => request()->routeIs('files.index'),
                            'text-[#475466] hover:bg-[#f8f9fb]' => !request()->routeIs('files.index')
                        ])>
                            All Reports
                        </a>
                    </li>
                    <li>
                        <a href="#" @class([
                            'block px-3 py-2 text-sm font-light rounded-lg transition',
                            'text-[#6840c6]' => request()->routeIs('files.saved'),
                            'text-[#475466] hover:bg-[#f8f9fb]' => !request()->routeIs('files.saved')
                        ])>
                            My Reports
                        </a>
                    </li>
                </ul>
            </li>

            {{-- Support --}}
            <li x-data="{ open: {{ request()->routeIs('support.*') ? 'true' : 'false' }} }" class="space-y-1">
                <a href="#" @click.prevent="open = !open" @keydown.escape.window="open = false" role="button"
                    class="text-md flex cursor-pointer select-none items-center justify-between rounded-lg px-3 py-2.5 transition"
                    :class="{
                        'bg-[#f9f5ff] text-[#6840c6]': open,
                        'text-[#667084] hover:text-[#344053] hover:bg-[#f8f9fb]': !open
                    }">
                    <span class="flex items-center">
                        <i class="fa-solid fa-life-ring mr-3 w-5"></i>
                        Support
                    </span>
                </a>
            </li>

            {{-- Divider Line --}}
            <div class="mx-4 my-6 border-t border-[#e4e7ec]"></div>

            {{-- Account Settings --}}
            <li x-data="{ open: {{ request()->routeIs('settings.*') ? 'true' : 'false' }} }" class="space-y-1">
                <a href="#" @click.prevent="open = !open" @keydown.escape.window="open = false" role="button"
                    class="text-md flex cursor-pointer select-none items-center justify-between rounded-lg px-3 py-5 transition"
                    :class="{
                        'bg-[#f9f5ff] text-[#6840c6]': open,
                        'text-[#667084] hover:text-[#344053] hover:bg-[#f8f9fb]': !open
                    }">
                    <span class="flex items-center">
                        <i class="fa-solid fa-gear mr-3 w-5"></i>
                        Account Settings
                    </span>
                </a>
            </li>

            {{-- Divider Line --}}
            <div class="mt-6 border-t border-[#e4e7ec] pt-4">

                {{-- User Actions --}}
                <div class="relative rounded-lg border border-[#e4e7ec]">
                    <button id="user-menu-button"
                        class="text-md flex w-full cursor-pointer items-center rounded-lg px-3 py-2.5 font-medium text-[#344053] hover:bg-[#f8f9fb]">
                        <img
                            src="{{ $user->profile_pic ? Storage::url($user->profile_pic) : asset('images/placeholders/user.png') }}">
                        <div class="flex-1 text-left">
                            <p class="text-sm font-medium text-[#0f1728]">{{ $user->first_name }}
                                {{ $user->last_name }}</p>
                            <p class="text-xs text-[#475466]">First Officer</p>
                        </div>
                        <i class="fa-solid fa-sign-out-alt text-xs text-[#667084]"></i>
                    </button>
                </div>
            </div>
    </nav>

    <!-- Sidebar Toggle -->
    <div class="border-t border-[#e4e7ec] p-4">
        <button id="toggle-sidebar"
            class="flex w-full items-center justify-center rounded-lg p-2 text-[#667084] hover:bg-[#f8f9fb] hover:text-[#344053]">
            <i class="fa-solid fa-angles-left"></i>
        </button>
    </div>
</aside>
