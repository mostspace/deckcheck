@props([
    'activeTab' => 'index',
    'context' => 'maintenance', // 'maintenance' or 'inventory'
    'breadcrumbs' => [],
    'actions' => [],
    'showAnnouncement' => true,
    'announcementText' => 'Some announcement or message',
    'enableRefererBreadcrumbs' => false, // Enable smart referer-based breadcrumbs
    'refererContext' => null // Additional context for referer logic (e.g., equipment, category)
])

@php
    use Illuminate\Support\Str;

    $user = auth()->user();
    $vessel = currentVessel();

    // Determine context based on explicit context parameter
    $isInventory = $context === 'inventory';

    if ($isInventory) {
        // Inventory tabs
        $tabs = [
            [
                'id' => 'equipment',
                'label' => 'Equipment',
                'route' => 'inventory.index',
                'active' => $activeTab === 'equipment'
            ],
            [
                'id' => 'consumables',
                'label' => 'Consumables',
                'route' => 'inventory.consumables',
                'active' => $activeTab === 'consumables'
            ]
        ];
    } else {
        // Maintenance tabs
        $tabs = [
            [
                'id' => 'summary',
                'label' => 'Summary',
                'icon' => 'tab-summary.svg',
                'route' => 'maintenance.summary',
                'active' => $activeTab === 'summary'
            ],
            [
                'id' => 'index',
                'label' => 'Index',
                'icon' => 'tab-index.svg',
                'route' => 'maintenance.index',
                'active' => $activeTab === 'index'
            ],
            [
                'id' => 'manifest',
                'label' => 'Manifest',
                'icon' => 'tab-manifest.svg',
                'route' => 'inventory.index',
                'active' => $activeTab === 'manifest'
            ],
            [
                'id' => 'deficiencies',
                'label' => 'Deficiencies',
                'icon' => 'tab-deficiencies.svg',
                'route' => 'deficiencies.index',
                'active' => $activeTab === 'deficiencies'
            ],
            [
                'id' => 'workflow',
                'label' => 'Workflow',
                'icon' => 'tab-workflow.svg',
                'route' => 'maintenance.schedule.index',
                'active' => $activeTab === 'workflow'
            ]
        ];
    }

    // Smart referer-based breadcrumbs
    if ($enableRefererBreadcrumbs && count($breadcrumbs) === 0) {
        // Get the Referer header
        $referer = request()->headers->get('referer', '');
        $refererPath = parse_url($referer, PHP_URL_PATH) ?: '';

        if ($isInventory && isset($refererContext['equipment'])) {
            $equipment = $refererContext['equipment'];

            // Route paths (relative)
            $maintenanceIndexPath = route('maintenance.index', [], false);
            $categoryShowPathPattern = route('maintenance.show', ['category' => $equipment->category->id], false);
            $equipmentIndexPath = route('inventory.index', [], false);

            if (Str::startsWith($refererPath, $maintenanceIndexPath)) {
                $breadcrumbs = [
                    [
                        'label' => 'Maintenance',
                        'icon' => asset('assets/media/icons/sidebar-solid-wrench-scredriver.svg'),
                        'url' => route('maintenance.index')
                    ],
                    ['label' => $equipment->category->name, 'url' => route('maintenance.show', $equipment->category)],
                    ['label' => $equipment->name, 'active' => true]
                ];
            } elseif (Str::startsWith($refererPath, $categoryShowPathPattern)) {
                $breadcrumbs = [
                    [
                        'label' => $equipment->category->name,
                        'url' => route('maintenance.showCategory', $equipment->category)
                    ],
                    ['label' => $equipment->name, 'active' => true]
                ];
            } elseif (Str::startsWith($refererPath, $equipmentIndexPath)) {
                $breadcrumbs = [
                    ['label' => 'Inventory', 'icon' => asset('assets/media/icons/sidebar-solid-archive-box.svg')],
                    ['label' => 'Equipment', 'url' => route('inventory.index')],
                    ['label' => $equipment->name, 'active' => true]
                ];
            } else {
                $breadcrumbs = [
                    ['label' => 'Inventory', 'icon' => asset('assets/media/icons/sidebar-solid-archive-box.svg')],
                    ['label' => 'Equipment', 'url' => route('inventory.index')],
                    ['label' => $equipment->name, 'active' => true]
                ];
            }
        }

        // Handle interval context for frequency badges
        if ($isInventory && isset($refererContext['interval'])) {
            $interval = $refererContext['interval'];
            $equipment = $interval->equipment;

            // Route paths (relative)
            $maintenanceIndexPath = route('maintenance.index', [], false);
            $categoryShowPathPattern = route('maintenance.show', ['category' => $equipment->category->id], false);
            $equipmentIndexPath = route('inventory.index', [], false);
            $intervalShowPath = route('equipment-intervals.show', $interval, false);

            if (Str::startsWith($refererPath, $maintenanceIndexPath)) {
                $breadcrumbs = [
                    [
                        'label' => 'Maintenance',
                        'icon' => asset('assets/media/icons/sidebar-solid-wrench-scredriver.svg'),
                        'url' => route('maintenance.index')
                    ],
                    ['label' => $equipment->category->name, 'url' => route('maintenance.show', $equipment->category)],
                    ['label' => $equipment->name, 'url' => route('equipment.show', $equipment)],
                    ['label' => ucfirst($interval->frequency) . ' ' . $interval->description, 'active' => true]
                ];
            } elseif (Str::startsWith($refererPath, $categoryShowPathPattern)) {
                $breadcrumbs = [
                    [
                        'label' => $equipment->category->name,
                        'url' => route('maintenance.showCategory', $equipment->category)
                    ],
                    ['label' => $equipment->name, 'url' => route('equipment.show', $equipment)],
                    ['label' => ucfirst($interval->frequency) . ' ' . $interval->description, 'active' => true]
                ];
            } elseif (Str::startsWith($refererPath, $equipmentIndexPath)) {
                $breadcrumbs = [
                    ['label' => 'Inventory', 'icon' => asset('assets/media/icons/sidebar-solid-archive-box.svg')],
                    ['label' => 'Equipment', 'url' => route('inventory.index')],
                    ['label' => $equipment->name, 'url' => route('equipment.show', $equipment)],
                    ['label' => ucfirst($interval->frequency) . ' ' . $interval->description, 'active' => true]
                ];
            } else {
                $breadcrumbs = [
                    ['label' => 'Inventory', 'icon' => asset('assets/media/icons/sidebar-solid-archive-box.svg')],
                    ['label' => 'Equipment', 'url' => route('inventory.index')],
                    ['label' => $equipment->name, 'url' => route('equipment.show', $equipment)],
                    ['label' => ucfirst($interval->frequency) . ' ' . $interval->description, 'active' => true]
                ];
            }
        }

        // Handle work order context - add "Record #X" to interval breadcrumbs
        if ($isInventory && isset($refererContext['interval']) && request()->routeIs('work-orders.show')) {
            // Add "Record #X" as the final breadcrumb
            if (isset($breadcrumbs) && count($breadcrumbs) > 0) {
                // Remove 'active' from the last breadcrumb and add URL
                $lastBreadcrumb = array_pop($breadcrumbs);
                if (isset($lastBreadcrumb['active']) && $lastBreadcrumb['active']) {
                    $lastBreadcrumb['url'] = route('equipment-intervals.show', $refererContext['interval']);
                    unset($lastBreadcrumb['active']);
                }
                $breadcrumbs[] = $lastBreadcrumb;

                // Get work order ID from the current request
                $workOrderId = request()->route('workOrder')->id ?? 'Unknown';
                $breadcrumbs[] = ['label' => 'Record #' . $workOrderId, 'active' => true];
            }
        }
    }
@endphp

<!-- Enhanced Maintenance Header -->
<header class="relative z-20 flex flex-col">
    <!-- Top Header with Announcement and User Info -->
    <div
        class="flex min-h-[4rem] flex-shrink-0 items-end justify-between gap-1 border-b bg-[#F8F8F6] px-2 pt-4 sm:gap-2 sm:px-3 sm:pt-4 md:px-6">
        <!-- Mobile hamburger -->
        <button id="btnOpenSidebar"
            class="flex-shrink-0 rounded-md p-1.5 hover:bg-slate-100 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-slate-400 sm:p-2 md:hidden"
            aria-label="Open menu">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 sm:h-5 sm:w-5" fill="none" viewBox="0 0 24 24"
                stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 7h16M4 12h16M4 17h16" />
            </svg>
        </button>

        <!-- Announcement Bar -->
        @if ($showAnnouncement)
            <div class="absolute left-4 right-4 top-2 sm:left-6 sm:right-auto">
                <div id="announcement"
                    class="relative flex min-w-0 max-w-[calc(100vw-8rem)] shrink items-center gap-1.5 overflow-hidden rounded-md border-primary-300 bg-accent-200/40 px-2 py-1 text-xs text-slate-900 transition-all duration-300 ease-out opacity-0 translate-y-2 sm:max-w-md sm:gap-2 sm:px-2.5 sm:text-sm">
                    <span class="absolute bottom-0 left-0 top-0 w-1 rounded-l bg-primary-500"></span>
                    <img src="{{ asset('assets/media/icons/pin-list.svg') }}" alt="pin list"
                        class="h-3 w-3 shrink-0 sm:h-4 sm:w-4" />
                    <span class="truncate text-xs">{{ $announcementText }}</span>
                    <button id="btnDismissAnnouncement" class="ml-1 flex-shrink-0 text-slate-500 hover:text-slate-700"
                        aria-label="Dismiss">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3 sm:h-3.5 sm:w-3.5" viewBox="0 0 24 24"
                            fill="none" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                d="m6 6 12 12M18 6 6 18" />
                        </svg>
                    </button>
                </div>
            </div>
        @endif

        <!-- Navigation Tabs -->
        <div class="no-scrollbar -mb-px flex max-w-full items-center gap-0.5 overflow-x-auto sm:gap-1" role="tablist"
            aria-label="{{ $isInventory ? 'Inventory' : 'Maintenance' }} tabs">
            @foreach ($tabs as $tab)
                <a href="{{ route($tab['route']) }}"
                    @if ($tab['active']) @if ($tab['id'] === 'workflow')
                           class="px-2 sm:px-3 py-1.5 rounded-t-md rounded-b-none text-xs sm:text-sm bg-primary-500 bg-opacity-50 hover:bg-primary-500 hover:bg-opacity-100 text-slate-900 border border-[#E4E4E4] border-b-transparent whitespace-nowrap flex items-center gap-1 sm:gap-2 flex-shrink-0"
                       @else
                           class="px-2 sm:px-3 py-1.5 rounded-t-md rounded-b-none text-xs sm:text-sm bg-white text-slate-900 border border-[#E4E4E4] border-b-transparent whitespace-nowrap flex items-center gap-1 sm:gap-2 flex-shrink-0" @endif
                @elseif($tab['id'] === 'workflow')
                    class="flex flex-shrink-0 items-center gap-1 whitespace-nowrap rounded-t-md border bg-primary-500 bg-opacity-50 px-2 py-1.5 text-xs text-slate-900 hover:bg-primary-500 hover:bg-opacity-100 sm:gap-2 sm:px-3 sm:text-sm"
                @else
                    class="flex flex-shrink-0 items-center gap-1 whitespace-nowrap rounded-t-md border px-2 py-1.5 text-xs text-slate-700 hover:bg-white sm:gap-2 sm:px-3 sm:text-sm"
                    @endif
                    >
                    @if (isset($tab['icon']) && !empty($tab['icon']))
                        @php
                            $iconName = $tab['icon'];
                            $solidIconName = str_replace('.svg', '-solid.svg', $iconName);
                        @endphp
                        @if ($tab['active'])
                            <img src="{{ asset('assets/media/icons/' . $solidIconName) }}"
                                class="tab-icon h-4 w-4 text-slate-900" alt="{{ $tab['label'] }}" />
                        @else
                            <img src="{{ asset('assets/media/icons/' . $iconName) }}"
                                class="tab-icon h-4 w-4 text-slate-500" alt="{{ $tab['label'] }}" />
                        @endif
                    @endif
                    <span class="tab-text">{{ $tab['label'] }}</span>
                </a>
            @endforeach
        </div>

        <!-- Right Toolbar with Notifications and User -->
        <div class="mb-4 flex items-center gap-2">
            <!-- Notifications -->
            <button class="icon-hover-btn h-5 w-5" aria-label="Notifications">
                <img src="{{ asset('assets/media/icons/bell.svg') }}"
                    class="icon-normal h-4 w-4 text-slate-900 sm:h-5 sm:w-5" alt="Notifications" />
                <img src="{{ asset('assets/media/icons/bell-solid.svg') }}"
                    class="icon-solid hidden h-4 w-4 text-slate-900 sm:h-5 sm:w-5" alt="Notifications" />
            </button>

            <div class="mx-1 h-4 w-px bg-[#E4E4E4] sm:mx-2 sm:h-5 md:mx-6"></div>

            <!-- Mobile: Just avatar button -->
            <button id="btnOpenProfileMobile"
                class="h-7 w-7 flex-shrink-0 overflow-hidden rounded-md border-2 border-primary-300 sm:hidden sm:h-8 sm:w-8">
                <img src="{{ $user->profile_pic ? Storage::url($user->profile_pic) : asset('assets/media/avatars/avatar2.png') }}"
                    alt="avatar" class="h-full w-full object-cover" />
            </button>

            <!-- Desktop: Full profile section -->
            <div class="hidden items-center gap-2 sm:flex lg:gap-3">
                <img src="{{ $user->profile_pic ? Storage::url($user->profile_pic) : asset('assets/media/avatars/avatar2.png') }}"
                    alt="avatar"
                    class="h-8 w-8 flex-shrink-0 rounded-lg border-2 border-primary-300 lg:h-10 lg:w-10" />
                <button
                    class="-mx-2 -my-1 min-w-0 rounded-md px-2 py-1 text-left leading-tight transition-colors hover:bg-slate-100">
                    <div class="flex items-center gap-1 text-xs font-medium lg:gap-2 lg:text-sm">
                        <span class="hidden lg:inline">{{ $vessel ? $vessel->name : 'Vessel Name' }}</span>
                        <span class="lg:hidden">Vessel</span>
                        <svg class="h-2.5 w-2.5 flex-shrink-0 lg:h-3 lg:w-3" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7">
                            </path>
                        </svg>
                    </div>
                    <div class="truncate text-xs text-slate-800" id="currentTime">Loading...</div>
                </button>
            </div>
        </div>
    </div>
</header>

<!-- Sub Header with Breadcrumbs and Actions -->
<div
    class="sticky top-0 z-10 flex flex-shrink-0 flex-col justify-between gap-3 border-b bg-white px-3 py-3 sm:flex-row sm:items-center sm:gap-2 sm:px-5 lg:px-8">
    <!-- Breadcrumbs -->
    <div class="flex items-center rounded-lg bg-white" id="breadcrumb-container">
        @if (count($breadcrumbs) > 0)
            @foreach ($breadcrumbs as $index => $crumb)
                @if ($index === 0)
                    @if (isset($crumb['url']) && $crumb['url'])
                        <a href="{{ $crumb['url'] }}"
                            class="shadow-soft z-10 inline-flex items-center gap-1 rounded-lg border border-primary-500 bg-accent-200/40 px-2 py-1.5 text-xs text-slate-900 transition-colors hover:bg-accent-200/60 sm:gap-2 sm:px-3">
                            @if (isset($crumb['icon']))
                                <img src="{{ $crumb['icon'] }}" alt="{{ $crumb['label'] }}" class="h-3 w-3" />
                            @endif
                            <span>{{ $crumb['label'] }}</span>
                        </a>
                    @else
                        <span
                            class="shadow-soft z-10 inline-flex items-center gap-1 rounded-lg border border-primary-500 bg-accent-200/40 px-2 py-1.5 text-xs text-slate-900 sm:gap-2 sm:px-3">
                            @if (isset($crumb['icon']))
                                <img src="{{ $crumb['icon'] }}" alt="{{ $crumb['label'] }}" class="h-3 w-3" />
                            @endif
                            <span>{{ $crumb['label'] }}</span>
                        </span>
                    @endif
                @else
                    @if (isset($crumb['url']) && $crumb['url'])
                        <a href="{{ $crumb['url'] }}"
                            class="-ml-4 inline-flex items-center rounded-lg border border-l-0 border-slate-200 px-2 py-1.5 pl-6 text-xs text-slate-500 transition-colors hover:bg-slate-50 hover:text-slate-900 sm:-ml-5 sm:px-3 sm:pl-7">
                            @if (isset($crumb['icon']))
                                <img src="{{ $crumb['icon'] }}" alt="{{ $crumb['label'] }}" class="mr-1 h-3 w-3" />
                            @endif
                            <span>{{ $crumb['label'] }}</span>
                        </a>
                    @else
                        <span
                            class="-ml-4 inline-flex items-center rounded-lg border border-l-0 border-slate-200 px-2 py-1.5 pl-6 text-xs text-slate-500 sm:-ml-5 sm:px-3 sm:pl-7">
                            @if (isset($crumb['icon']))
                                <img src="{{ $crumb['icon'] }}" alt="{{ $crumb['label'] }}" class="mr-1 h-3 w-3" />
                            @endif
                            <span>{{ $crumb['label'] }}</span>
                        </span>
                    @endif
                @endif
            @endforeach
        @else
            <!-- Default Maintenance Breadcrumb -->
            <span
                class="shadow-soft z-10 inline-flex items-center gap-1 rounded-lg border border-primary-500 bg-accent-200/40 px-2 py-1.5 text-xs text-slate-900 sm:gap-2 sm:px-3">
                <img src="{{ asset('assets/media/icons/sidebar-solid-wrench-scredriver.svg') }}" alt="Maintenance"
                    class="h-3 w-3" />
                <span>Maintenance</span>
            </span>
        @endif
    </div>
    <div class="flex items-center gap-2">
        <!-- Additional Icon Buttons -->
        <button class="icon-hover-btn" title="Share" aria-label="Share">
            <img src="{{ asset('assets/media/icons/plus-circle.svg') }}" class="icon-normal h-6 w-6"
                alt="Share" />
            <img src="{{ asset('assets/media/icons/plus-circle-solid.svg') }}" class="icon-solid hidden h-6 w-6"
                alt="Share" />
        </button>
        <button class="icon-hover-btn" title="Settings" aria-label="Settings">
            <img src="{{ asset('assets/media/icons/chat-bubble.svg') }}" class="icon-normal h-6 w-6"
                alt="Settings" />
            <img src="{{ asset('assets/media/icons/chat-bubble-solid.svg') }}" class="icon-solid hidden h-6 w-6"
                alt="Settings" />
        </button>
        <button class="icon-hover-btn" title="Help" aria-label="Help">
            <img src="{{ asset('assets/media/icons/question-mark-circle.svg') }}" class="icon-normal h-6 w-6"
                alt="Help" />
            <img src="{{ asset('assets/media/icons/question-mark-circle-solid.svg') }}"
                class="icon-solid hidden h-6 w-6" alt="Help" />
        </button>
    </div>
</div>

@push('scripts')
    <script>
        // Announcement dismissal
        document.addEventListener('DOMContentLoaded', function() {
            const dismissBtn = document.getElementById('btnDismissAnnouncement');
            const announcement = document.getElementById('announcement');

            if (dismissBtn && announcement) {
                // Smooth entrance
                requestAnimationFrame(() => {
                    announcement.classList.remove('opacity-0', 'translate-y-2');
                    announcement.classList.add('opacity-100', 'translate-y-0');
                });

                // Smooth dismissal
                dismissBtn.addEventListener('click', function() {
                    announcement.classList.remove('opacity-100', 'translate-y-0');
                    announcement.classList.add('opacity-0', 'translate-y-2');

                    const handleTransitionEnd = () => {
                        announcement.removeEventListener('transitionend', handleTransitionEnd);
                        announcement.remove();
                    };

                    announcement.addEventListener('transitionend', handleTransitionEnd);
                    // Fallback in case transitionend doesn't fire
                    setTimeout(handleTransitionEnd, 350);
                });
            }

            // Update current time
            function updateTime() {
                const timeElement = document.getElementById('currentTime');
                if (timeElement) {
                    const now = new Date();
                    timeElement.textContent = now.toLocaleTimeString('en-US', {
                        hour: '2-digit',
                        minute: '2-digit',
                        hour12: true
                    });
                }
            }

            updateTime();
            setInterval(updateTime, 60000); // Update every minute

            // Icon hover effects
            const iconButtons = document.querySelectorAll('.icon-hover-btn');
            iconButtons.forEach(button => {
                const normalIcon = button.querySelector('.icon-normal');
                const solidIcon = button.querySelector('.icon-solid');

                if (normalIcon && solidIcon) {
                    button.addEventListener('mouseenter', function() {
                        normalIcon.classList.add('hidden');
                        solidIcon.classList.remove('hidden');
                    });

                    button.addEventListener('mouseleave', function() {
                        normalIcon.classList.remove('hidden');
                        solidIcon.classList.add('hidden');
                    });
                }
            });

            // Icon button click handlers
            const shareBtn = document.querySelector('button[aria-label="Share"]');
            const settingsBtn = document.querySelector('button[aria-label="Settings"]');
            const helpBtn = document.querySelector('button[aria-label="Help"]');
            const notificationsBtn = document.querySelector('button[aria-label="Notifications"]');

            if (shareBtn) {
                shareBtn.addEventListener('click', function() {
                    // TODO: Implement share functionality
                    console.log('Share clicked');
                });
            }

            if (settingsBtn) {
                settingsBtn.addEventListener('click', function() {
                    // TODO: Implement settings functionality
                    console.log('Settings clicked');
                });
            }

            if (helpBtn) {
                helpBtn.addEventListener('click', function() {
                    // TODO: Implement help functionality
                    console.log('Help clicked');
                });
            }

            if (notificationsBtn) {
                notificationsBtn.addEventListener('click', function() {
                    // TODO: Implement notifications functionality
                    console.log('Notifications clicked');
                });
            }
        });
    </script>
@endpush
