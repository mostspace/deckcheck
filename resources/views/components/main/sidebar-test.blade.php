@php
    $user = auth()->user() ?? (object) ['profile_pic' => 'profile.jpg'];
@endphp

<div class="flex min-h-screen">

    <!-- Sidebar Rail (always w-16) -->
    <aside id="sidebar-rail" class="bg-[#6840c6] w-14 flex flex-col h-full border-r border-[#e4e7ec]">
        <a href="{{ route('dashboard') }}">
            <div class="h-16 flex items-center justify-center border-b border-[#e4e7ec]">
                <div class="w-10 h-10 rounded-lg bg-[#6840c6] flex items-center justify-center text-white font-bold text-xl">D</div>
            </div>
        </a>
        <nav class="flex-1 mt-6 flex flex-col items-center space-y-2">

            <!-- Primary Nav -->
            @php
                $primaryNav = [
                    ['key' => 'dashboard', 'icon' => 'fa-gauge', 'label' => 'Dashboard'],
                    ['key' => 'vessel', 'icon' => 'fa-ship', 'label' => 'Vessel'],
                    ['key' => 'maintenance', 'icon' => 'fa-wrench', 'label' => 'Maintenance'],
                    ['key' => 'inventory', 'icon' => 'fa-boxes-stacked', 'label' => 'Inventory'],
                    ['key' => 'reports', 'icon' => 'fa-file-lines', 'label' => 'Reports'],
                    ['key' => 'support', 'icon' => 'fa-life-ring', 'label' => 'Support'],
                    ['key' => 'settings', 'icon' => 'fa-gear', 'label' => 'Settings'],
                ];
            @endphp
            @foreach ($primaryNav as $item)
                <button
                    class="sidebar-icon flex items-center justify-center w-12 h-12 rounded-lg transition text-[#f9f5ff] hover:bg-[#f8f9fb] hover:text-[#6840c6]"
                    data-section="{{ $item['key'] }}" aria-label="{{ $item['label'] }}" type="button">
                    <i class="fa-solid {{ $item['icon'] }} w-5 h-5"></i>
                </button>
            @endforeach

            <!-- User avatar at the bottom -->
            <div class="mt-auto mb-4">
                <button id="sidebar-user-btn" class="block">
                    <img src="{{ Storage::url($user->profile_pic) }}" alt="User avatar"
                        class="w-8 h-8 rounded-full border-2 border-white hover:ring-2 hover:ring-[#6840c6] transition" />
                </button>
            </div>
        </nav>
    </aside>

    <!-- Slide-out Sidebar (w-0 when closed, w-56 when open) -->
    <aside id="sidebar-slideout" class="transition-width bg-white border-r border-[#e4e7ec] shadow-xl h-full w-0 overflow-hidden flex flex-col">
        <div class="h-16 flex items-center px-6 border-b border-[#e4e7ec]">
            <span id="slideout-title" class="font-semibold text-[#6840c6] text-lg">Section</span>
            <button id="close-slideout" class="ml-auto text-[#6840c6] hover:text-[#0f1728]">
                <i class="fa-solid fa-xmark text-2xl"></i>
            </button>
        </div>
        <nav id="slideout-content" class="flex-1 py-4 px-6 overflow-y-auto"></nav>
    </aside>

</div>


<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Use Blade to echo route checks and URIs in production!
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
                        href: "{{ route('schedule.index') }}",
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
                        href: "{{ route('equipment.index') }}",
                        label: "Equipment",
                        active: {{ request()->routeIs('equipment.*') ? 'true' : 'false' }}
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
        const slideoutTitle = document.getElementById('slideout-title');
        const slideoutContent = document.getElementById('slideout-content');
        const closeBtn = document.getElementById('close-slideout');
        const mainContent = document.getElementById('main-content');

        function openSidebar(section) {
            sidebarSlideout.classList.remove('w-0');
            sidebarSlideout.classList.add('w-56'); // 14rem = 224px
            mainContent.classList.remove('ml-16');
            // Fill out secondary nav
            slideoutTitle.textContent = sidebarSections[section].title;
            let html = '<ul class="space-y-1">';
            sidebarSections[section].links.forEach(link => {
                html += `<li>
                <a href="${link.href}" class="block px-4 py-2.5 rounded-lg transition font-medium text-md
                    ${link.active ? 'bg-[#f9f5ff] text-[#6840c6]' : 'text-[#344053] hover:bg-[#f8f9fb] hover:text-[#6840c6]'}"
                >${link.label}</a>
            </li>`;
            });
            html += '</ul>';
            slideoutContent.innerHTML = html;
        }

        function closeSidebar() {
            sidebarSlideout.classList.remove('w-56');
            sidebarSlideout.classList.add('w-0');
            mainContent.classList.remove('ml-72');

        }

        // Sidebar icon click
        document.querySelectorAll('.sidebar-icon').forEach(btn => {
            btn.addEventListener('click', function() {
                const section = this.getAttribute('data-section');
                if (!sidebarSections[section]) return;
                openSidebar(section);
            });
        });

        closeBtn.addEventListener('click', closeSidebar);

        // ESC to close
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') closeSidebar();
        });

        // Default: collapsed
        closeSidebar();
    });
</script>

