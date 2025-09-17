@props([
    'tabs' => [
        ['id' => 'overview', 'label' => 'Overview', 'icon' => 'tab-overview.svg', 'active' => true],
    ]
])

@php
    $user = auth()->user();
@endphp

<!-- Top bar -->
<header class="sticky top-0 z-50 flex items-end justify-between gap-1 sm:gap-2 px-2 sm:px-3 md:px-6 pt-4 sm:pt-4 border-b bg-[#F8F8F6] flex-shrink-0 min-h-[4rem]">
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
                    class="px-2 sm:px-3 py-1.5 rounded-t-md rounded-b-none text-xs sm:text-sm bg-primary-500 text-slate-900 border border-primary-500 whitespace-nowrap flex items-center gap-1 sm:gap-2 flex-shrink-0"
                @else
                    class="px-2 sm:px-3 py-1.5 text-xs sm:text-sm whitespace-nowrap flex items-center gap-1 sm:gap-2 border hover:bg-white rounded-t-md flex-shrink-0"
                @endif
            >
                @if(isset($tab['icon']) && !empty($tab['icon']))
                    <img 
                        src="{{ asset('assets/media/icons/' . $tab['icon']) }}" 
                        class="h-4 w-4 {{ $tab['active'] ? 'text-slate-900' : 'text-slate-500' }}" 
                        alt="{{ $tab['label'] }}" 
                    />
                @endif
                <span>{{ $tab['label'] }}</span>
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
</header>

<script>
    function switchTab(tabId) {
        // Get all tab buttons and panels
        const tabButtons = document.querySelectorAll('[role="tab"]');
        const tabPanels = document.querySelectorAll('.tab-panel');

        // Remove active state from all tabs
        tabButtons.forEach(tab => {
            tab.setAttribute('aria-selected', 'false');
            tab.setAttribute('tabindex', '-1');
            tab.classList.remove('px-2', 'sm:px-3', 'py-1.5', 'rounded-t-md', 'rounded-b-none', 'text-xs', 'sm:text-sm', 'bg-primary-500', 'text-slate-900', 'border', 'border-primary-500', 'whitespace-nowrap', 'flex', 'items-center', 'gap-1', 'sm:gap-2', 'flex-shrink-0');
            tab.classList.add('px-2', 'sm:px-3', 'py-1.5', 'text-xs', 'sm:text-sm', 'whitespace-nowrap', 'flex', 'items-center', 'gap-1', 'sm:gap-2', 'border', 'hover:bg-white', 'rounded-t-md', 'flex-shrink-0');
            
            // Update icon color
            const icon = tab.querySelector('img');
            if (icon) {
                icon.classList.remove('text-slate-900');
                icon.classList.add('text-slate-500');
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
            activeTab.classList.remove('px-2', 'sm:px-3', 'py-1.5', 'text-xs', 'sm:text-sm', 'whitespace-nowrap', 'flex', 'items-center', 'gap-1', 'sm:gap-2', 'border', 'hover:bg-white', 'rounded-t-md', 'flex-shrink-0');
            activeTab.classList.add('px-2', 'sm:px-3', 'py-1.5', 'rounded-t-md', 'rounded-b-none', 'text-xs', 'sm:text-sm', 'bg-primary-500', 'text-slate-900', 'border', 'border-primary-500', 'whitespace-nowrap', 'flex', 'items-center', 'gap-1', 'sm:gap-2', 'flex-shrink-0');
            
            // Update icon color for active tab
            const activeIcon = activeTab.querySelector('img');
            if (activeIcon) {
                activeIcon.classList.remove('text-slate-500');
                activeIcon.classList.add('text-slate-900');
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
