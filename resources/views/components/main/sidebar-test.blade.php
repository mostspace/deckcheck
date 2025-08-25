@php
    use Illuminate\Support\Str;
    $user = auth()->user() ?? (object) ['profile_pic' => null];
    $routeName = request()->route()->getName();
@endphp

<div class="flex">

    <!-- Primary Nav -->
    <aside id="sidebar-rail"
        class="fixed top-0 left-0 h-screen w-14 bg-[#6840c6] border-r border-[#e4e7ec] flex flex-col z-40 opacity-0 transition-opacity duration-300">

        <!-- Logo -->
        <a href="{{ route('dashboard') }}">
            <div class="h-16 flex items-center justify-center border-b border-[#e4e7ec]">
                <div class="w-10 h-10 rounded-lg bg-[#6840c6] text-white font-bold text-xl flex items-center justify-center">D</div>
            </div>
        </a>

        <!-- Nav Icons -->
        <nav class="flex-1 flex flex-col justify-between py-4">
            <div class="flex flex-col items-center space-y-2">
                @php
                    $primaryNav = [
                        ['key' => 'dashboard', 'icon' => 'fa-gauge', 'label' => 'Dashboard', 'match' => ['dashboard']],
                        ['key' => 'vessel', 'icon' => 'fa-ship', 'label' => 'Vessel', 'match' => ['vessel']],
                        ['key' => 'maintenance', 'icon' => 'fa-wrench', 'label' => 'Maintenance', 'match' => ['maintenance', 'schedule', 'deficiencies']],
                        ['key' => 'inventory', 'icon' => 'fa-boxes-stacked', 'label' => 'Inventory', 'match' => ['equipment']],
                        ['key' => 'reports', 'icon' => 'fa-file-lines', 'label' => 'Reports', 'match' => ['reports']],
                        ['key' => 'support', 'icon' => 'fa-life-ring', 'label' => 'Support', 'match' => ['support']],
                    ];
                @endphp

                @foreach ($primaryNav as $item)
                    @php
                        $isActive = collect($item['match'])->contains(fn($prefix) => Str::startsWith($routeName, $prefix));
                    @endphp
                    <button
                        class="relative sidebar-icon flex items-center justify-center w-12 h-12 min-w-[3rem] min-h-[3rem] rounded-lg text-white transition hover:text-[#dcd6f4] shrink-0"
                        data-section="{{ $item['key'] }}"
                        aria-label="{{ $item['label'] }}"
                        type="button"
                    >
                        @if($isActive)
                            <span class="absolute left-0 top-0 h-full w-1 bg-white rounded-r"></span>
                        @endif
                        <i class="fa-solid {{ $item['icon'] }} w-5 h-5 z-10 {{ $isActive ? 'text-white' : '' }}"></i>
                    </button>
                @endforeach
            </div>

            <!-- Settings + Avatar -->
            <div class="flex flex-col items-center space-y-4 mb-4">
                @php
                    $settingsActive = Str::startsWith($routeName, 'settings');
                @endphp
                <button
                    class="relative sidebar-icon flex items-center justify-center w-12 h-12 min-w-[3rem] min-h-[3rem] rounded-lg text-white transition hover:text-[#dcd6f4] shrink-0"
                    data-section="settings"
                    aria-label="Settings"
                    type="button"
                >
                    @if($settingsActive)
                        <span class="absolute left-0 top-0 h-full w-1 bg-white rounded-r"></span>
                    @endif
                    <i class="fa-solid fa-gear w-5 h-5 z-10 {{ $settingsActive ? 'text-white' : '' }}"></i>
                </button>

                <button id="sidebar-user-btn">
                    <img src="{{ $user->profile_pic ? Storage::url($user->profile_pic) : asset('images/placeholders/user.png') }}"
                         alt="User avatar"
                         class="w-8 h-8 rounded-full border-2 border-white hover:ring-2 hover:ring-white object-cover transition" />
                </button>
            </div>
        </nav>
    </aside>

    <!-- Slide-out Secondary Nav -->
    <aside id="sidebar-slideout"
        class="fixed top-0 left-14 h-screen w-0 transition-all duration-300 ease-in-out bg-white border-r border-[#e4e7ec] shadow-xl z-30 overflow-hidden flex flex-col opacity-0 invisible">
        <div class="h-16 flex items-center px-6 border-b border-[#e4e7ec]">
            <span id="slideout-title" class="font-semibold text-[#6840c6] text-lg whitespace-nowrap overflow-hidden text-ellipsis">Section</span>
            <button id="close-slideout" class="ml-auto text-[#6840c6] hover:text-[#0f1728]">
                <i class="fa-solid fa-xmark text-2xl"></i>
            </button>
        </div>
        <div id="slideout-content" class="flex-1 overflow-y-auto px-6 py-4 w-56 shrink-0 whitespace-nowrap"></div>
    </aside>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const sidebarSections = {
            dashboard: {
                title: "Dashboard",
                links: [
                    { href: "{{ route('dashboard') }}", label: "Overview", active: {{ request()->routeIs('dashboard') ? 'true' : 'false' }} }
                ]
            },
            vessel: {
                title: "Vessel",
                links: [
                    { href: "{{ route('vessel.index') }}", label: "Vessel Information", active: {{ request()->routeIs('vessel.index') ? 'true' : 'false' }} },
                    { href: "{{ route('vessel.crew') }}", label: "Crew", active: {{ request()->routeIs('vessel.crew') ? 'true' : 'false' }} },
                    { href: "{{ route('vessel.deckplan') }}", label: "Deck Plan", active: {{ request()->routeIs('vessel.deckplan') ? 'true' : 'false' }} }
                ]
            },
            maintenance: {
                title: "Maintenance",
                links: [
                    { href: "{{ route('maintenance.index') }}", label: "Index", active: {{ request()->routeIs('maintenance.*') ? 'true' : 'false' }} },
                    { href: "{{ route('schedule.index') }}", label: "Schedule", active: {{ request()->routeIs('maintenance.schedule*') ? 'true' : 'false' }} },
                    { href: "{{ route('deficiencies.index') }}", label: "Deficiencies", active: {{ request()->routeIs('deficiencies.*') ? 'true' : 'false' }} }
                ]
            },
            inventory: {
                title: "Inventory",
                links: [
                    { href: "{{ route('equipment.index') }}", label: "Equipment", active: {{ request()->routeIs('equipment.*') ? 'true' : 'false' }} },
                    { href: "#", label: "Consumables", active: false }
                ]
            },
            reports: {
                title: "Reports",
                links: [
                    { href: "#", label: "All Reports", active: false },
                    { href: "#", label: "My Reports", active: false }
                ]
            },
            support: {
                title: "Support",
                links: [
                    { href: "#", label: "Help Center", active: false }
                ]
            },
            settings: {
                title: "Account Settings",
                links: [
                    { href: "#", label: "Profile", active: false }
                ]
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
            btn.addEventListener('click', function () {
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
