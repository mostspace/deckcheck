@php
    use Illuminate\Support\Str;
    $user = auth()->user() ?? (object) ['profile_pic' => null];
    $routeName = request()->route()->getName();
@endphp

<div class="flex">

    <!-- Primary Nav -->
    <aside id="sidebar-rail"
        class="fixed left-0 top-0 z-40 flex h-screen w-14 flex-col border-r border-[#e4e7ec] bg-[#6840c6] opacity-0 transition-opacity duration-300">

        <!-- Logo -->
        <a href="{{ route('dashboard') }}">
            <div class="flex h-16 items-center justify-center border-b border-[#e4e7ec]">
                <div
                    class="flex h-10 w-10 items-center justify-center rounded-lg bg-[#6840c6] text-xl font-bold text-white">
                    D</div>
            </div>
        </a>

        <!-- Nav Icons -->
        <nav class="flex flex-1 flex-col justify-between py-4">
            <div class="flex flex-col items-center space-y-2">
                @php
                    $primaryNav = [
                        ['key' => 'dashboard', 'icon' => 'fa-gauge', 'label' => 'Dashboard', 'match' => ['dashboard']],
                        ['key' => 'vessel', 'icon' => 'fa-ship', 'label' => 'Vessel', 'match' => ['vessel']],
                        [
                            'key' => 'maintenance',
                            'icon' => 'fa-wrench',
                            'label' => 'Maintenance',
                            'match' => ['maintenance', 'schedule', 'deficiencies']
                        ],
                        [
                            'key' => 'inventory',
                            'icon' => 'fa-boxes-stacked',
                            'label' => 'Inventory',
                            'match' => ['equipment']
                        ],
                        ['key' => 'reports', 'icon' => 'fa-file-lines', 'label' => 'Reports', 'match' => ['reports']],
                        ['key' => 'support', 'icon' => 'fa-life-ring', 'label' => 'Support', 'match' => ['support']]
                    ];
                @endphp

                @foreach ($primaryNav as $item)
                    @php
                        $isActive = collect($item['match'])->contains(
                            fn($prefix) => Str::startsWith($routeName, $prefix)
                        );
                    @endphp
                    <button
                        class="sidebar-icon relative flex h-12 min-h-[3rem] w-12 min-w-[3rem] shrink-0 items-center justify-center rounded-lg text-white transition hover:text-[#dcd6f4]"
                        data-section="{{ $item['key'] }}" aria-label="{{ $item['label'] }}" type="button">
                        @if ($isActive)
                            <span class="absolute left-0 top-0 h-full w-1 rounded-r bg-white"></span>
                        @endif
                        <i class="fa-solid {{ $item['icon'] }} {{ $isActive ? 'text-white' : '' }} z-10 h-5 w-5"></i>
                    </button>
                @endforeach
            </div>

            <!-- Settings + Avatar -->
            <div class="mb-4 flex flex-col items-center space-y-4">
                @php
                    $settingsActive = Str::startsWith($routeName, 'settings');
                @endphp
                <button
                    class="sidebar-icon relative flex h-12 min-h-[3rem] w-12 min-w-[3rem] shrink-0 items-center justify-center rounded-lg text-white transition hover:text-[#dcd6f4]"
                    data-section="settings" aria-label="Settings" type="button">
                    @if ($settingsActive)
                        <span class="absolute left-0 top-0 h-full w-1 rounded-r bg-white"></span>
                    @endif
                    <i class="fa-solid fa-gear {{ $settingsActive ? 'text-white' : '' }} z-10 h-5 w-5"></i>
                </button>

                <button id="sidebar-user-btn">
                    <img src="{{ $user->profile_pic ? Storage::url($user->profile_pic) : asset('images/placeholders/user.png') }}"
                        alt="User avatar"
                        class="h-8 w-8 rounded-full border-2 border-white object-cover transition hover:ring-2 hover:ring-white" />
                </button>
            </div>
        </nav>
    </aside>

    <!-- Slide-out Secondary Nav -->
    <aside id="sidebar-slideout"
        class="invisible fixed left-14 top-0 z-30 flex h-screen w-0 flex-col overflow-hidden border-r border-[#e4e7ec] bg-white opacity-0 shadow-xl transition-all duration-300 ease-in-out">
        <div class="flex h-16 items-center border-b border-[#e4e7ec] px-6">
            <span id="slideout-title"
                class="overflow-hidden text-ellipsis whitespace-nowrap text-lg font-semibold text-[#6840c6]">Section</span>
            <button id="close-slideout" class="ml-auto text-[#6840c6] hover:text-[#0f1728]">
                <i class="fa-solid fa-xmark text-2xl"></i>
            </button>
        </div>
        <div id="slideout-content" class="w-56 flex-1 shrink-0 overflow-y-auto whitespace-nowrap px-6 py-4"></div>
    </aside>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const sidebarSections = {
            dashboard: {
                title: "Dashboard",
                links: [{
                    href: "{{ route('dashboard') }}",
                    label: "Overview",
                    active: {{ request()->routeIs('dashboard') ? 'true' : 'false' }}
                }]
            },
            vessel: {
                title: "Vessel",
                links: [{
                        href: "{{ route('vessel.index') }}",
                        label: "Vessel Information",
                        active: {{ request()->routeIs('vessel.index') ? 'true' : 'false' }}
                    },
                    {
                        href: "{{ route('vessel.crew') }}",
                        label: "Crew",
                        active: {{ request()->routeIs('vessel.crew') ? 'true' : 'false' }}
                    },
                    {
                        href: "{{ route('vessel.deckplan') }}",
                        label: "Deck Plan",
                        active: {{ request()->routeIs('vessel.deckplan') ? 'true' : 'false' }}
                    }
                ]
            },
            maintenance: {
                title: "Maintenance",
                links: [{
                        href: "{{ route('maintenance.index') }}",
                        label: "Index",
                        active: {{ request()->routeIs('maintenance.*') ? 'true' : 'false' }}
                    },
                    {
                        href: "{{ route('maintenance.schedule.index') }}",
                        label: "Schedule",
                        active: {{ request()->routeIs('maintenance.schedule*') ? 'true' : 'false' }}
                    },
                    {
                        href: "{{ route('deficiencies.index') }}",
                        label: "Deficiencies",
                        active: {{ request()->routeIs('deficiencies.*') ? 'true' : 'false' }}
                    }
                ]
            },
            inventory: {
                title: "Inventory",
                links: [{
                        href: "{{ route('inventory.index') }}",
                        label: "Equipment",
                        active: {{ request()->routeIs('inventory.index') ? 'true' : 'false' }}
                    },
                    {
                        href: "#",
                        label: "Consumables",
                        active: false
                    }
                ]
            },
            reports: {
                title: "Reports",
                links: [{
                        href: "#",
                        label: "All Reports",
                        active: false
                    },
                    {
                        href: "#",
                        label: "My Reports",
                        active: false
                    }
                ]
            },
            support: {
                title: "Support",
                links: [{
                    href: "#",
                    label: "Help Center",
                    active: false
                }]
            },
            settings: {
                title: "Account Settings",
                links: [{
                    href: "#",
                    label: "Profile",
                    active: false
                }]
            },
        };

        const sidebarSlideout = document.getElementById('sidebar-slideout');
        const sidebarRail = document.getElementById('sidebar-rail');
        const slideoutTitle = document.getElementById('slideout-title');
        const slideoutContent = document.getElementById('slideout-content');
        const closeBtn = document.getElementById('close-slideout');
        const mainContent = document.getElementById('main-content');

        let currentOpenSection = null;

        function openSidebar(section) {
            sidebarSlideout.classList.remove('w-0', 'opacity-0', 'invisible');
            sidebarSlideout.classList.add('w-56', 'opacity-100', 'visible');
            mainContent.classList.add('ml-72');

            slideoutTitle.textContent = sidebarSections[section].title;
            let html = '<ul class="space-y-1">';
            sidebarSections[section].links.forEach(link => {
                html += `<li>
                    <a href="${link.href}" class="block px-4 py-2.5 rounded-lg transition font-medium text-md
                        ${link.active ? 'bg-[#f9f5ff] text-[#6840c6]' : 'text-[#344053] hover:bg-[#f8f9fb] hover:text-[#6840c6]'}">
                        ${link.label}
                    </a>
                </li>`;
            });
            html += '</ul>';
            slideoutContent.innerHTML = html;
        }

        function closeSidebar() {
            sidebarSlideout.classList.remove('w-56', 'opacity-100', 'visible');
            sidebarSlideout.classList.add('w-0', 'opacity-0', 'invisible');
            mainContent.classList.remove('ml-72');
        }

        document.querySelectorAll('.sidebar-icon').forEach(btn => {
            btn.addEventListener('click', function() {
                const section = this.getAttribute('data-section');
                if (!sidebarSections[section]) return;

                if (currentOpenSection === section) {
                    closeSidebar();
                    currentOpenSection = null;
                } else {
                    openSidebar(section);
                    currentOpenSection = section;
                }
            });
        });

        closeBtn.addEventListener('click', () => {
            closeSidebar();
            currentOpenSection = null;
        });

        document.addEventListener('keydown', e => {
            if (e.key === 'Escape') {
                closeSidebar();
                currentOpenSection = null;
            }
        });

        closeSidebar();
    });

    window.addEventListener('load', () => {
        document.getElementById('sidebar-rail')?.classList.remove('opacity-0');
    });
</script>
