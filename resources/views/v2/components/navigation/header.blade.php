@props([
    'tabs' => [
        ['id' => 'overview', 'label' => 'Overview', 'icon' => 'tab-overview.svg', 'active' => true],
    ],
    'breadcrumbs' => [],
    'actions' => [],
    'showSubHeader' => true
])

@php
    $user = auth()->user();
@endphp

<!-- Top bar -->
<header class="relative z-20 flex flex-col">
    <div id="top-header" class="flex items-end justify-between gap-1 sm:gap-2 px-2 sm:px-3 md:px-6 pt-4 sm:pt-4 border-b bg-[#F8F8F6] flex-shrink-0 min-h-[4rem]">
        <!-- Mobile hamburger -->
        <button id="btnOpenSidebar" class="md:hidden p-1.5 sm:p-2 rounded-md hover:bg-slate-100 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-slate-400 flex-shrink-0" aria-label="Open menu">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 sm:h-5 sm:w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 7h16M4 12h16M4 17h16"/></svg>
        </button>
    
        <!-- Announcement -->
        <div class="absolute top-2 left-4 sm:left-6 right-4 sm:right-auto">
            <div id="announcement" class="relative flex items-center gap-1.5 sm:gap-2 rounded-md border-primary-300 bg-accent-200/40 px-2 sm:px-2.5 py-1 text-xs sm:text-sm text-slate-900 shrink min-w-0 overflow-hidden transition-all max-w-[calc(100vw-8rem)] sm:max-w-md">
                <span class="absolute left-0 top-0 bottom-0 w-1 rounded-l bg-primary-500"></span>
                <img src="{{ asset('assets/media/icons/pin-list.svg') }}" alt="pin list" class="h-3 w-3 sm:h-4 sm:w-4 shrink-0" />
                <span class="truncate text-xs">Some announcement or message</span>
                <button id="btnDismissAnnouncement" class="ml-1 text-slate-500 hover:text-slate-700 flex-shrink-0" aria-label="Dismiss">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3 sm:h-3.5 sm:w-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="m6 6 12 12M18 6 6 18"/></svg>
                </button>
            </div>
        </div>
    
        <!-- Main Tab -->
        <div class="flex items-center overflow-x-auto no-scrollbar max-w-full -mb-px gap-0.5 sm:gap-1" role="tablist" aria-label="Primary tabs">
            @foreach($tabs as $index => $tab)
                <button 
                    id="tab-{{ $tab['id'] }}" 
                    role="tab" 
                    aria-selected="{{ $tab['active'] ? 'true' : 'false' }}" 
                    aria-controls="panel-{{ $tab['id'] }}" 
                    tabindex="{{ $tab['active'] ? '0' : '-1' }}"
                    onclick="switchTab('{{ $tab['id'] }}')"
                    @if($tab['active'])
                        data-accent="true" 
                        class="px-2 sm:px-3 py-1.5 rounded-t-md rounded-b-none text-xs sm:text-sm bg-white text-slate-900 border border-[#E4E4E4] border-b-transparent whitespace-nowrap flex items-center gap-1 sm:gap-2 flex-shrink-0"
                    @elseif($tab['id'] === 'workflow')
                        class="px-2 sm:px-3 py-1.5 text-xs sm:text-sm whitespace-nowrap flex items-center gap-1 sm:gap-2 border bg-primary-500 bg-opacity-50 hover:bg-primary-500 hover:bg-opacity-100 text-slate-900 rounded-t-md flex-shrink-0"
                    @else
                        class="px-2 sm:px-3 py-1.5 text-xs sm:text-sm whitespace-nowrap flex items-center gap-1 sm:gap-2 border hover:bg-white rounded-t-md flex-shrink-0"
                    @endif
                >
                    @if(isset($tab['icon']) && !empty($tab['icon']))
                        @php
                            $iconName = $tab['icon'];
                            $solidIconName = str_replace('.svg', '-solid.svg', $iconName);
                        @endphp
                        <img 
                            src="{{ asset('assets/media/icons/' . $iconName) }}" 
                            data-solid-src="{{ asset('assets/media/icons/' . $solidIconName) }}"
                            class="h-4 w-4 tab-icon {{ $tab['active'] || $tab['id'] === 'workflow' ? 'text-slate-900' : 'text-slate-500' }}" 
                            alt="{{ $tab['label'] }}" 
                        />
                    @endif
                    <span class="tab-text">{{ $tab['label'] }}</span>
                </button>
            @endforeach
        </div>
    
        <!-- Right Toolbar -->
        <div class="flex items-center mb-4 gap-2">
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
                        <span class="hidden lg:inline">Vessel Name</span>
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
</header>

@if($showSubHeader)
<div id="sub-header" class="sticky top-0 z-10 px-3 sm:px-5 lg:px-8 py-3 border-b bg-white flex flex-col sm:flex-row sm:items-center justify-between gap-3 sm:gap-2 flex-shrink-0">
    <!-- Breadcrumbs -->
    <div class="flex items-center rounded-lg bg-white" id="breadcrumb-container">
        @if(count($breadcrumbs) > 0)
            @foreach($breadcrumbs as $index => $crumb)
                @if($index === 0)
                    <span class="inline-flex items-center gap-1 sm:gap-2 text-xs px-2 sm:px-3 py-1.5 rounded-lg border border-primary-500 bg-accent-200/40 text-slate-900 z-10 shadow-soft">
                        @if(isset($crumb['icon']))
                            <img src="{{ $crumb['icon'] }}" alt="{{ $crumb['label'] }}" class="w-3 h-3" />
                        @endif
                        <span>{{ $crumb['label'] }}</span>
                    </span>
                @else
                    <span class="inline-flex items-center text-xs px-2 sm:px-3 pl-6 sm:pl-7 py-1.5 -ml-4 sm:-ml-5 text-slate-500 rounded-lg border border-l-0 border-slate-200">
                        {{ $crumb['label'] }}
                    </span>
                @endif
            @endforeach
        @else
            <!-- Dynamic breadcrumb based on page and active tab -->
            <span class="inline-flex items-center gap-1 sm:gap-2 text-xs px-2 sm:px-3 py-1.5 rounded-lg border border-primary-500 bg-accent-200/40 text-slate-900 z-10 shadow-soft" id="page-breadcrumb">
                <img src="{{ $pageIcon ?? asset('assets/media/icons/sidebar-solid-wrench-scredriver.svg') }}" alt="{{ $pageName ?? 'Page' }}" class="w-3 h-3" />
                <span>{{ $pageName ?? 'Page' }}</span>
            </span>
            <span class="inline-flex items-center text-xs px-2 sm:px-3 pl-6 sm:pl-7 py-1.5 -ml-4 sm:-ml-5 text-slate-500 rounded-lg border border-l-0 border-slate-200" id="current-tab-breadcrumb">{{ $activeTab ?? 'Tab' }}</span>
        @endif
    </div>

    <!-- Action Buttons -->
    <div class="hidden sm:flex items-center gap-2 lg:gap-4">
        @if(count($actions) > 0)
            @foreach($actions as $action)
                <button class="icon-hover-btn" title="{{ $action['title'] }}" aria-label="{{ $action['aria_label'] ?? $action['title'] }}">
                    <img src="{{ $action['icon'] }}" class="w-6 h-6 icon-normal" alt="{{ $action['title'] }}" />
                    @if(isset($action['icon_solid']))
                        <img src="{{ $action['icon_solid'] }}" class="w-6 h-6 icon-solid hidden" alt="{{ $action['title'] }}" />
                    @endif
                </button>
            @endforeach
        @else
            <!-- Default action buttons -->
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
        @endif
    </div>
</div>
@endif

<script>
    // Handle tab icon switching for active states only
    document.addEventListener('DOMContentLoaded', function() {
        // Initialize active tab icons
        const activeTab = document.querySelector('[role="tab"][aria-selected="true"]');
        if (activeTab) {
            const activeIcon = activeTab.querySelector('.tab-icon');
            if (activeIcon && activeIcon.dataset.solidSrc) {
                activeIcon.src = activeIcon.dataset.solidSrc;
            }
        }
    });

    function switchTab(tabId) {
        // Get all tab buttons and panels
        const tabButtons = document.querySelectorAll('[role="tab"]');
        const tabPanels = document.querySelectorAll('.tab-panel');

        // Remove active state from all tabs
        tabButtons.forEach(tab => {
            tab.setAttribute('aria-selected', 'false');
            tab.setAttribute('tabindex', '-1');
            
            // Remove all possible background and styling classes
            tab.classList.remove('px-2', 'sm:px-3', 'py-1.5', 'rounded-t-md', 'rounded-b-none', 'text-xs', 'sm:text-sm', 'bg-white', 'bg-primary-500', 'bg-opacity-50', 'bg-opacity-100', 'text-slate-900', 'border', 'border-[#E4E4E4]', 'border-primary-500', 'border-b-0', 'border-b-transparent', 'whitespace-nowrap', 'flex', 'items-center', 'gap-1', 'sm:gap-2', 'flex-shrink-0', 'hover:bg-white', 'hover:bg-primary-500', 'hover:bg-opacity-100');
            
            // Check if this is the workflow tab
            const isWorkflowTab = tab.id === 'tab-workflow';
            if (isWorkflowTab) {
                tab.classList.add('px-2', 'sm:px-3', 'py-1.5', 'text-xs', 'sm:text-sm', 'whitespace-nowrap', 'flex', 'items-center', 'gap-1', 'sm:gap-2', 'border', 'bg-primary-500', 'bg-opacity-50', 'hover:bg-primary-500', 'hover:bg-opacity-100', 'text-slate-900', 'rounded-t-md', 'flex-shrink-0');
            } else {
                tab.classList.add('px-2', 'sm:px-3', 'py-1.5', 'text-xs', 'sm:text-sm', 'whitespace-nowrap', 'flex', 'items-center', 'gap-1', 'sm:gap-2', 'border', 'hover:bg-white', 'rounded-t-md', 'flex-shrink-0');
            }
            
            // Update icon color and switch to normal icon for inactive tabs
            const icon = tab.querySelector('.tab-icon');
            if (icon) {
                if (isWorkflowTab) {
                    icon.classList.remove('text-slate-500');
                    icon.classList.add('text-slate-900');
                } else {
                    icon.classList.remove('text-slate-900');
                    icon.classList.add('text-slate-500');
                }
                // Switch back to normal icon for inactive tabs
                if (icon.dataset.solidSrc) {
                    icon.src = icon.src.replace('-solid.svg', '.svg');
                }
            }
        });

        // Hide all panels
        tabPanels.forEach(panel => {
            panel.classList.add('hidden');
        });

        // Find and activate the clicked tab
        const activeTab = document.getElementById(`tab-${tabId}`);
        const targetPanel = document.getElementById(`panel-${tabId}`);

        if (activeTab) {
            activeTab.setAttribute('aria-selected', 'true');
            activeTab.setAttribute('tabindex', '0');
            
            // Check if this is the workflow tab
            const isWorkflowTab = activeTab.id === 'tab-workflow';
            
            // Clear all classes first
            activeTab.className = '';
            
            if (isWorkflowTab) {
                // Workflow tab should always have primary/50 background, even when active
                activeTab.className = 'px-2 sm:px-3 py-1.5 rounded-t-md rounded-b-none text-xs sm:text-sm bg-primary-500 bg-opacity-50 hover:bg-primary-500 hover:bg-opacity-100 text-slate-900 border border-[#E4E4E4] border-b-transparent whitespace-nowrap flex items-center gap-1 sm:gap-2 flex-shrink-0';
            } else {
                // Regular tab active state - white background
                activeTab.className = 'px-2 sm:px-3 py-1.5 rounded-t-md rounded-b-none text-xs sm:text-sm bg-white text-slate-900 border border-[#E4E4E4] border-b-transparent whitespace-nowrap flex items-center gap-1 sm:gap-2 flex-shrink-0';
            }
            
            // Update icon color and switch to solid icon for active tab
            const activeIcon = activeTab.querySelector('.tab-icon');
            if (activeIcon) {
                activeIcon.classList.remove('text-slate-500');
                activeIcon.classList.add('text-slate-900');
                // Switch to solid icon for active tab
                if (activeIcon.dataset.solidSrc) {
                    activeIcon.src = activeIcon.dataset.solidSrc;
                }
            }
        }

        // Show target panel
        if (targetPanel) {
            targetPanel.classList.remove('hidden');
        }

        // Update breadcrumb
        updateBreadcrumb(tabId);
    }

    function updateBreadcrumb(tabId) {
        const breadcrumbElement = document.getElementById('current-tab-breadcrumb');
        if (breadcrumbElement) {
            const tabLabels = {
                'information': 'Information',
                'crew': 'Crew',
                'deck_plan': 'Deck Plan',
                'index': 'Index',
                'schedule': 'Schedule', 
                'deficiencies': 'Deficiencies',
                'equipment': 'Equipment',
                'consumables': 'Consumables',
                'all_reports': 'All Reports',
                'my_reports': 'My Reports'
            };
            
            const label = tabLabels[tabId] || tabId.charAt(0).toUpperCase() + tabId.slice(1);
            breadcrumbElement.textContent = label;
        }
    }
</script>