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
                    ['label' => 'Maintenance', 'icon' => asset('assets/media/icons/sidebar-solid-wrench-scredriver.svg'), 'url' => route('maintenance.index')],
                    ['label' => $equipment->category->name, 'url' => route('maintenance.show', $equipment->category)],
                    ['label' => $equipment->name, 'active' => true]
                ];
            } elseif (Str::startsWith($refererPath, $categoryShowPathPattern)) {
                $breadcrumbs = [
                    ['label' => $equipment->category->name, 'url' => route('maintenance.showCategory', $equipment->category)],
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
                    ['label' => 'Maintenance', 'icon' => asset('assets/media/icons/sidebar-solid-wrench-scredriver.svg'), 'url' => route('maintenance.index')],
                    ['label' => $equipment->category->name, 'url' => route('maintenance.show', $equipment->category)],
                    ['label' => $equipment->name, 'url' => route('equipment.show', $equipment)],
                    ['label' => ucfirst($interval->frequency) . ' ' . $interval->description, 'active' => true]
                ];
            } elseif (Str::startsWith($refererPath, $categoryShowPathPattern)) {
                $breadcrumbs = [
                    ['label' => $equipment->category->name, 'url' => route('maintenance.showCategory', $equipment->category)],
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
    <div class="flex items-end justify-between gap-1 sm:gap-2 px-2 sm:px-3 md:px-6 pt-4 sm:pt-4 border-b bg-[#F8F8F6] flex-shrink-0 min-h-[4rem]">
        <!-- Mobile hamburger -->
        <button id="btnOpenSidebar" class="md:hidden p-1.5 sm:p-2 rounded-md hover:bg-slate-100 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-slate-400 flex-shrink-0" aria-label="Open menu">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 sm:h-5 sm:w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 7h16M4 12h16M4 17h16"/>
            </svg>
        </button>
    
        <!-- Announcement Bar -->
        @if($showAnnouncement)
        <div class="absolute top-2 left-4 sm:left-6 right-4 sm:right-auto">
            <div id="announcement" class="relative flex items-center gap-1.5 sm:gap-2 rounded-md border-primary-300 bg-accent-200/40 px-2 sm:px-2.5 py-1 text-xs sm:text-sm text-slate-900 shrink min-w-0 overflow-hidden transition-all max-w-[calc(100vw-8rem)] sm:max-w-md">
                <span class="absolute left-0 top-0 bottom-0 w-1 rounded-l bg-primary-500"></span>
                <img src="{{ asset('assets/media/icons/pin-list.svg') }}" alt="pin list" class="h-3 w-3 sm:h-4 sm:w-4 shrink-0" />
                <span class="truncate text-xs">{{ $announcementText }}</span>
                <button id="btnDismissAnnouncement" class="ml-1 text-slate-500 hover:text-slate-700 flex-shrink-0" aria-label="Dismiss">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3 sm:h-3.5 sm:w-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="m6 6 12 12M18 6 6 18"/>
                    </svg>
                </button>
            </div>
        </div>
        @endif
    
        <!-- Navigation Tabs -->
        <div class="flex items-center overflow-x-auto no-scrollbar max-w-full -mb-px gap-0.5 sm:gap-1" role="tablist" aria-label="{{ $isInventory ? 'Inventory' : 'Maintenance' }} tabs">
            @foreach($tabs as $tab)
                <a href="{{ route($tab['route']) }}" 
                   @if($tab['active'])
                       @if($tab['id'] === 'workflow')
                           class="px-2 sm:px-3 py-1.5 rounded-t-md rounded-b-none text-xs sm:text-sm bg-primary-500 bg-opacity-50 hover:bg-primary-500 hover:bg-opacity-100 text-slate-900 border border-[#E4E4E4] border-b-transparent whitespace-nowrap flex items-center gap-1 sm:gap-2 flex-shrink-0"
                       @else
                           class="px-2 sm:px-3 py-1.5 rounded-t-md rounded-b-none text-xs sm:text-sm bg-white text-slate-900 border border-[#E4E4E4] border-b-transparent whitespace-nowrap flex items-center gap-1 sm:gap-2 flex-shrink-0"
                       @endif
                   @elseif($tab['id'] === 'workflow')
                       class="px-2 sm:px-3 py-1.5 text-xs sm:text-sm whitespace-nowrap flex items-center gap-1 sm:gap-2 border bg-primary-500 bg-opacity-50 hover:bg-primary-500 hover:bg-opacity-100 text-slate-900 rounded-t-md flex-shrink-0"
                   @else
                       class="px-2 sm:px-3 py-1.5 text-xs sm:text-sm whitespace-nowrap flex items-center gap-1 sm:gap-2 border hover:bg-white text-slate-700 rounded-t-md flex-shrink-0"
                   @endif
                >
                    @if(isset($tab['icon']) && !empty($tab['icon']))
                        @php
                            $iconName = $tab['icon'];
                            $solidIconName = str_replace('.svg', '-solid.svg', $iconName);
                        @endphp
                        @if($tab['active'])
                            <img 
                                src="{{ asset('assets/media/icons/' . $solidIconName) }}" 
                                class="h-4 w-4 tab-icon text-slate-900" 
                                alt="{{ $tab['label'] }}" 
                            />
                        @else
                            <img 
                                src="{{ asset('assets/media/icons/' . $iconName) }}" 
                                class="h-4 w-4 tab-icon text-slate-500" 
                                alt="{{ $tab['label'] }}" 
                            />
                        @endif
                    @endif
                    <span class="tab-text">{{ $tab['label'] }}</span>
                </a>
            @endforeach
        </div>
    
        <!-- Right Toolbar with Notifications and User -->
        <div class="flex items-center mb-4 gap-2">
            <!-- Notifications -->
            <button class="h-5 w-5 icon-hover-btn" aria-label="Notifications">
                <img src="{{ asset('assets/media/icons/bell.svg') }}" class="h-4 w-4 sm:h-5 sm:w-5 text-slate-900 icon-normal" alt="Notifications" />
                <img src="{{ asset('assets/media/icons/bell-solid.svg') }}" class="h-4 w-4 sm:h-5 sm:w-5 text-slate-900 icon-solid hidden" alt="Notifications" />
            </button>
    
            <div class="w-px h-4 sm:h-5 bg-[#E4E4E4] mx-1 sm:mx-2 md:mx-6"></div>
            
            <!-- Mobile: Just avatar button -->
            <button id="btnOpenProfileMobile" class="sm:hidden rounded-md border-2 border-primary-300 h-7 w-7 sm:h-8 sm:w-8 overflow-hidden flex-shrink-0">
                <img src="{{ $user->profile_pic ? Storage::url($user->profile_pic) : asset('assets/media/avatars/avatar2.png') }}" alt="avatar" class="w-full h-full object-cover" />
            </button>
            
            <!-- Desktop: Full profile section -->
            <div class="hidden sm:flex items-center gap-2 lg:gap-3">
                <img src="{{ $user->profile_pic ? Storage::url($user->profile_pic) : asset('assets/media/avatars/avatar2.png') }}" alt="avatar" class="w-8 h-8 lg:w-10 lg:h-10 rounded-lg border-2 border-primary-300 flex-shrink-0" />
                <button class="leading-tight text-left hover:bg-slate-100 rounded-md px-2 py-1 -mx-2 -my-1 transition-colors min-w-0">
                    <div class="font-medium text-xs lg:text-sm flex items-center gap-1 lg:gap-2">
                        <span class="hidden lg:inline">{{ $vessel ? $vessel->name : 'Vessel Name' }}</span>
                        <span class="lg:hidden">Vessel</span>
                        <svg class="w-2.5 h-2.5 lg:w-3 lg:h-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                        </svg>
                    </div>
                    <div class="text-xs text-slate-800 truncate" id="currentTime">Loading...</div>
                </button>
            </div>
        </div>
    </div>

    <!-- Sub Header with Breadcrumbs and Actions -->
    <div class="sticky top-0 z-10 px-3 sm:px-5 lg:px-8 py-3 border-b bg-white flex flex-col sm:flex-row sm:items-center justify-between gap-3 sm:gap-2 flex-shrink-0">
        <!-- Breadcrumbs -->
        <div class="flex items-center rounded-lg bg-white" id="breadcrumb-container">
            @if(count($breadcrumbs) > 0)
                @foreach($breadcrumbs as $index => $crumb)
                    @if($index === 0)
                        @if(isset($crumb['url']) && $crumb['url'])
                            <a href="{{ $crumb['url'] }}" class="inline-flex items-center gap-1 sm:gap-2 text-xs px-2 sm:px-3 py-1.5 rounded-lg border border-primary-500 bg-accent-200/40 text-slate-900 z-10 shadow-soft hover:bg-accent-200/60 transition-colors">
                                @if(isset($crumb['icon']))
                                    <img src="{{ $crumb['icon'] }}" alt="{{ $crumb['label'] }}" class="w-3 h-3" />
                                @endif
                                <span>{{ $crumb['label'] }}</span>
                            </a>
                        @else
                            <span class="inline-flex items-center gap-1 sm:gap-2 text-xs px-2 sm:px-3 py-1.5 rounded-lg border border-primary-500 bg-accent-200/40 text-slate-900 z-10 shadow-soft">
                                @if(isset($crumb['icon']))
                                    <img src="{{ $crumb['icon'] }}" alt="{{ $crumb['label'] }}" class="w-3 h-3" />
                                @endif
                                <span>{{ $crumb['label'] }}</span>
                            </span>
                        @endif
                    @else
                        @if(isset($crumb['url']) && $crumb['url'])
                            <a href="{{ $crumb['url'] }}" class="inline-flex items-center text-xs px-2 sm:px-3 pl-6 sm:pl-7 py-1.5 -ml-4 sm:-ml-5 text-slate-500 rounded-lg border border-l-0 border-slate-200 hover:text-slate-900 hover:bg-slate-50 transition-colors">
                                @if(isset($crumb['icon']))
                                    <img src="{{ $crumb['icon'] }}" alt="{{ $crumb['label'] }}" class="w-3 h-3 mr-1" />
                                @endif
                                <span>{{ $crumb['label'] }}</span>
                            </a>
                        @else
                            <span class="inline-flex items-center text-xs px-2 sm:px-3 pl-6 sm:pl-7 py-1.5 -ml-4 sm:-ml-5 text-slate-500 rounded-lg border border-l-0 border-slate-200">
                                @if(isset($crumb['icon']))
                                    <img src="{{ $crumb['icon'] }}" alt="{{ $crumb['label'] }}" class="w-3 h-3 mr-1" />
                                @endif
                                <span>{{ $crumb['label'] }}</span>
                            </span>
                        @endif
                    @endif
                @endforeach
            @else
                <!-- Default Maintenance Breadcrumb -->
                <span class="inline-flex items-center gap-1 sm:gap-2 text-xs px-2 sm:px-3 py-1.5 rounded-lg border border-primary-500 bg-accent-200/40 text-slate-900 z-10 shadow-soft">
                    <img src="{{ asset('assets/media/icons/sidebar-solid-wrench-scredriver.svg') }}" alt="Maintenance" class="w-3 h-3" />
                    <span>Maintenance</span>
                </span>
            @endif
        </div>
        <div class="flex items-center gap-2">
              <!-- Additional Icon Buttons -->
            <button class="icon-hover-btn" title="Share" aria-label="Share">
                <img src="{{ asset('assets/media/icons/plus-circle.svg') }}" class="w-6 h-6 icon-normal" alt="Share" />
                <img src="{{ asset('assets/media/icons/plus-circle-solid.svg') }}" class="w-6 h-6 icon-solid hidden" alt="Share" />
            </button>
            <button class="icon-hover-btn" title="Settings" aria-label="Settings">
                <img src="{{ asset('assets/media/icons/chat-bubble.svg') }}" class="w-6 h-6 icon-normal" alt="Settings" />
                <img src="{{ asset('assets/media/icons/chat-bubble-solid.svg') }}" class="w-6 h-6 icon-solid hidden" alt="Settings" />
            </button>
            <button class="icon-hover-btn" title="Help" aria-label="Help">
                <img src="{{ asset('assets/media/icons/question-mark-circle.svg') }}" class="w-6 h-6 icon-normal" alt="Help" />
                <img src="{{ asset('assets/media/icons/question-mark-circle-solid.svg') }}" class="w-6 h-6 icon-solid hidden" alt="Help" />
            </button>
        </div>
    </div>
</header>

@push('scripts')
<script>
    // Announcement dismissal
    document.addEventListener('DOMContentLoaded', function() {
        const dismissBtn = document.getElementById('btnDismissAnnouncement');
        const announcement = document.getElementById('announcement');
        
        if (dismissBtn && announcement) {
            dismissBtn.addEventListener('click', function() {
                announcement.style.transition = 'opacity 0.3s ease-out';
                announcement.style.opacity = '0';
                setTimeout(() => {
                    announcement.remove();
                }, 300);
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
