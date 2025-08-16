<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>@yield('title', 'Admin Panel') | DeckCheck</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    {{-- Tailwind & Fonts --}}
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://code.highcharts.com/highcharts.js"></script>
    <script src="https://code.highcharts.com/modules/exporting.js"></script>
    <script src="https://code.highcharts.com/modules/export-data.js"></script>

    <script>
        window.FontAwesomeConfig = {
            autoReplaceSvg: 'nest'
        };
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        dark: {
                            50: "#F8FAFC",
                            100: "#F1F5F9",
                            200: "#E2E8F0",
                            300: "#CBD5E1",
                            400: "#94A3B8",
                            500: "#64748B",
                            600: "#475569",
                            700: "#334155",
                            800: "#1E293B",
                            900: "#0F172A",
                            950: "#020617"
                        },
                        accent: {
                            primary: "#3B82F6",
                            secondary: "#10B981",
                            danger: "#EF4444",
                            warning: "#F59E0B",
                            muted: "#6B7280"
                        },
                        surface: {
                            50: "#FAFAFA",
                            100: "#F5F5F5",
                            200: "#E5E5E5",
                            300: "#D4D4D4",
                            400: "#A3A3A3",
                            500: "#737373",
                            600: "#525252",
                            700: "#404040",
                            800: "#262626",
                            900: "#171717"
                        }
                    },
                    fontFamily: {
                        sans: ["Inter", "system-ui", "sans-serif"],
                        mono: ["JetBrains Mono", "monospace"]
                    },
                    spacing: {
                        '18': '4.5rem',
                        '88': '22rem'
                    },
                    borderWidth: {
                        '3': '3px'
                    }
                }
            }
        };
    </script>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&family=JetBrains+Mono:wght@400;500;600&display=swap">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/js/all.min.js" crossorigin="anonymous"></script>

    <style>
        body {
            font-family: 'Inter', system-ui, sans-serif !important;
        }

        ::-webkit-scrollbar {
            width: 6px;
            height: 6px;
        }

        ::-webkit-scrollbar-track {
            background: #1E293B;
        }

        ::-webkit-scrollbar-thumb {
            background: #475569;
            border-radius: 3px;
        }

        ::-webkit-scrollbar-thumb:hover {
            background: #64748B;
        }

        .fa,
        .fas,
        .far,
        .fal,
        .fab {
            font-family: "Font Awesome 6 Free", "Font Awesome 6 Brands" !important;
        }

        /* Custom geometric accents */
        .geometric-accent {
            background: linear-gradient(135deg, #3B82F6 0%, #1D4ED8 100%);
        }
        
        .geometric-accent::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: linear-gradient(135deg, transparent 0%, rgba(59, 130, 246, 0.1) 100%);
            pointer-events: none;
        }

        /* Subtle borders and shadows */
        .border-subtle {
            border: 1px solid rgba(148, 163, 184, 0.1);
        }

        .shadow-subtle {
            box-shadow: 0 1px 3px 0 rgba(0, 0, 0, 0.1), 0 1px 2px 0 rgba(0, 0, 0, 0.06);
        }

        /* Hover effects */
        .hover-lift {
            transition: all 0.2s cubic-bezier(0.4, 0, 0.2, 1);
        }

        .hover-lift:hover {
            transform: translateY(-1px);
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
        }

        /* Sidebar collapse transitions */
        .sidebar-transition {
            transition: width 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }

        .sidebar-collapsed {
            width: 4rem;
        }

        .sidebar-expanded {
            width: 18rem;
        }

        .content-transition {
            transition: margin-left 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }

        .content-collapsed {
            margin-left: 1rem;
        }

        .content-expanded {
            margin-left: 0;
        }

        /* Collapsed state styles */
        .sidebar-collapsed .nav-text,
        .sidebar-collapsed .section-label,
        .sidebar-collapsed .user-info,
        .sidebar-collapsed .logo-text {
            display: none !important;
        }

        .sidebar-collapsed .subnav-items {
            display: none !important;
        }

        /* Center icons in collapsed state */
        .sidebar-collapsed .nav-item {
            justify-content: center !important;
            padding-left: 0 !important;
            padding-right: 0 !important;
        }

        .sidebar-collapsed .icon-container {
            margin-right: 0 !important;
            margin-left: 0 !important;
        }

        /* Hide chevron in collapsed state */
        .sidebar-collapsed .chevron-container {
            display: none !important;
        }

        /* Center user avatar in collapsed state */
        .sidebar-collapsed .user-profile {
            justify-content: center !important;
        }

        .sidebar-collapsed .user-avatar {
            margin: 0 !important;
        }

        .sidebar-collapsed .user-menu {
            display: none !important;
        }

        /* Toggle button styling */
        .sidebar-toggle {
            position: fixed;
            bottom: 1rem;
            left: 3.5rem;
            width: 2rem;
            height: 2rem;
            background: rgba(59, 130, 246, 0.1);
            border: 1px solid rgba(59, 130, 246, 0.2);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #3B82F6;
            transition: all 0.3s ease;
            z-index: 1000;
            cursor: pointer;
        }

        .sidebar-toggle:hover {
            background: rgba(59, 130, 246, 0.2);
            border-color: rgba(59, 130, 246, 0.4);
            transform: scale(1.05);
        }

        .sidebar-expanded .sidebar-toggle {
            left: 17.5rem;
        }

        .sidebar-collapsed .sidebar-toggle {
            left: 3.5rem;
        }

        /* Tooltip styles */
        .tooltip {
            position: relative;
        }

        .tooltip .tooltip-text {
            visibility: hidden;
            width: auto;
            background-color: #1E293B;
            color: #fff;
            text-align: center;
            border-radius: 6px;
            padding: 8px 12px;
            position: absolute;
            z-index: 1;
            left: 100%;
            top: 50%;
            transform: translateY(-50%);
            margin-left: 8px;
            white-space: nowrap;
            font-size: 12px;
            font-weight: 500;
            opacity: 0;
            transition: opacity 0.3s;
            border: 1px solid rgba(148, 163, 184, 0.2);
        }

        .tooltip:hover .tooltip-text {
            visibility: visible;
            opacity: 1;
        }

        .tooltip .tooltip-text::after {
            content: "";
            position: absolute;
            top: 50%;
            right: 100%;
            margin-top: -5px;
            border-width: 5px;
            border-style: solid;
            border-color: transparent #1E293B transparent transparent;
        }

        /* Mobile-first responsive design */
        @media (max-width: 768px) {
            .sidebar-desktop {
                display: none !important;
            }
            
            .mobile-menu-toggle {
                display: flex !important;
            }
            
            .main-panel-mobile {
                margin-left: 0 !important;
                width: 100% !important;
            }
            
            .top-bar-mobile {
                padding: 1rem !important;
            }
            
            .content-mobile {
                padding: 1rem !important;
            }
        }

        @media (min-width: 769px) {
            .sidebar-desktop {
                display: flex !important;
            }
            
            .mobile-menu-toggle {
                display: none !important;
            }
        }

        /* Mobile menu overlay */
        .mobile-menu-overlay {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: rgba(0, 0, 0, 0.8);
            z-index: 2000;
            opacity: 0;
            visibility: hidden;
            transition: all 0.3s ease;
        }

        .mobile-menu-overlay.active {
            opacity: 1;
            visibility: visible;
        }

        .mobile-menu {
            position: fixed;
            top: 0;
            left: -100%;
            width: 280px;
            height: 100vh;
            background: #0F172A;
            z-index: 2001;
            transition: left 0.3s ease;
            overflow-y: auto;
        }

        .mobile-menu.active {
            left: 0;
        }

        /* Mobile menu toggle button */
        .mobile-menu-toggle {
            display: none;
            align-items: center;
            justify-content: center;
            width: 2.5rem;
            height: 2.5rem;
            background: rgba(59, 130, 246, 0.1);
            border: 1px solid rgba(59, 130, 246, 0.2);
            border-radius: 0.5rem;
            color: #3B82F6;
            transition: all 0.2s ease;
        }

        .mobile-menu-toggle:hover {
            background: rgba(59, 130, 246, 0.2);
            border-color: rgba(59, 130, 246, 0.4);
        }

        /* Mobile search */
        .mobile-search {
            display: none;
        }

        @media (max-width: 768px) {
            .mobile-search {
                display: block !important;
            }
            
            .desktop-search {
                display: none !important;
            }
        }
    </style>
</head>

<body class="bg-dark-950 text-gray-100 flex h-screen overflow-hidden font-sans antialiased">
    {{-- Mobile Menu Overlay --}}
    <div id="mobile-menu-overlay" class="mobile-menu-overlay"></div>
    
    {{-- Mobile Menu --}}
    <div id="mobile-menu" class="mobile-menu">
        {{-- Mobile Menu Header --}}
        <div class="p-4 border-b border-subtle">
            <div class="flex items-center justify-between">
                <div class="flex items-center space-x-3">
                    <div class="relative">
                        <div class="w-8 h-8 bg-gradient-to-br from-accent-primary to-accent-secondary rounded-lg flex items-center justify-center shadow-lg">
                            <i class="fa-solid fa-ship text-white text-lg"></i>
                        </div>
                        <div class="absolute -top-1 -right-1 w-2.5 h-2.5 bg-accent-secondary rounded-full border-2 border-dark-900"></div>
                    </div>
                    <div>
                        <span class="font-bold text-lg text-white">DeckCheck</span>
                        <div class="text-xs text-gray-400 font-mono">ADMIN</div>
                    </div>
                </div>
                <button id="mobile-menu-close" class="p-2 text-gray-400 hover:text-white hover:bg-dark-800 rounded-lg transition-colors duration-200">
                    <i class="fa-solid fa-times text-lg"></i>
                </button>
            </div>
        </div>

        {{-- Mobile Navigation --}}
        <div class="py-4 px-3 space-y-2">
            <x-admin.nav-link icon="fa-tachometer-alt" label="Dashboard" route="admin.dashboard" />
            
            <div class="pt-4">
                <div class="text-xs uppercase text-gray-500 font-semibold mb-3 px-2 tracking-wider">Management</div>
                <div class="space-y-1">
                    <x-admin.nav-link icon="fa-users" label="Staff" route="admin.staff.index" />
                    <x-admin.nav-link icon="fa-user-gear" label="User Management" route="admin.users.index" />
                    <x-admin.nav-link 
                        icon="fa-database" 
                        label="Data Management"
                        :subItems="[
                            ['label' => 'Vessel', 'route' => 'admin.vessels.index']
                        ]"
                    />
                    <x-admin.nav-link icon="fa-file-pen" label="Content Management" />
                    <x-admin.nav-link icon="fa-headset" label="Support" />
                </div>
            </div>

            <div class="pt-4">
                <div class="text-xs uppercase text-gray-500 font-semibold mb-3 px-2 tracking-wider">System</div>
                <div class="space-y-1">
                    <x-admin.nav-link icon="fa-shield-halved" label="Security" />
                    <x-admin.nav-link icon="fa-toolbox" label="Utilities" />
                    <x-admin.nav-link icon="fa-code" label="APIs" />
                </div>
            </div>

            <div class="pt-4">
                <div class="text-xs uppercase text-gray-500 font-semibold mb-3 px-2 tracking-wider">Business</div>
                <div class="space-y-1">
                    <x-admin.nav-link icon="fa-money-bill-wave" label="Revenue" />
                    <x-admin.nav-link icon="fa-chart-line" label="Analytics" />
                    <x-admin.nav-link icon="fa-file-lines" label="Reports" />
                </div>
            </div>
        </div>

        {{-- Mobile User Profile --}}
        <div class="p-4 border-t border-subtle mt-auto">
            <div class="flex items-center space-x-3">
                <div class="relative">
                    <img src="{{ auth()->user()->profile_pic_url ?? 'https://storage.googleapis.com/uxpilot-auth.appspot.com/avatars/avatar-3.jpg' }}"
                        class="w-8 h-8 rounded-lg border-2 border-accent-primary object-cover" />
                    <div class="absolute -bottom-1 -right-1 w-2.5 h-2.5 bg-green-400 rounded-full border-2 border-dark-900"></div>
                </div>
                <div class="flex-1 min-w-0">
                    <div class="font-medium text-white truncate text-sm">{{ auth()->user()->name }}</div>
                    <div class="text-xs text-gray-400 font-mono">{{ auth()->user()->system_role }}</div>
                </div>
            </div>
        </div>
    </div>

    {{-- Desktop Sidebar --}}
    <div id="sidebar" class="sidebar-desktop sidebar-transition sidebar-expanded bg-dark-900 h-screen flex flex-col border-r border-subtle shadow-subtle">
        {{-- Logo Section --}}
        <div class="p-4">
            <div class="flex items-center space-x-3" id="logo-content">
                <div class="relative">
                    <div class="w-8 h-8 bg-gradient-to-br from-accent-primary to-accent-secondary rounded-lg flex items-center justify-center shadow-lg">
                        <i class="fa-solid fa-ship text-white text-lg"></i>
                    </div>
                    <div class="absolute -top-1 -right-1 w-2.5 h-2.5 bg-accent-secondary rounded-full border-2 border-dark-900"></div>
                </div>
                <div id="logo-text" class="logo-text">
                    <span class="font-bold text-lg text-white">DeckCheck</span>
                    <div class="text-xs text-gray-400 font-mono">ADMIN</div>
                </div>
            </div>
        </div>

        {{-- Navigation --}}
        <div class="flex-1 overflow-y-auto py-4 px-3 space-y-5">
            {{-- Dashboard --}}
            <div>
                <x-admin.nav-link icon="fa-tachometer-alt" label="Dashboard" route="admin.dashboard" />
            </div>
            
            {{-- Management Section --}}
            <div>
                <div class="text-xs uppercase text-gray-500 font-semibold mb-3 px-2 tracking-wider section-label" id="management-label">Management</div>
                <div class="space-y-0.5">
                    <x-admin.nav-link icon="fa-users" label="Staff" route="admin.staff.index" />
                    <x-admin.nav-link icon="fa-user-gear" label="User Management" route="admin.users.index" />
                    <x-admin.nav-link 
                        icon="fa-database" 
                        label="Data Management"
                        :subItems="[
                            ['label' => 'Vessel', 'route' => 'admin.vessels.index']
                        ]"
                    />
                    <x-admin.nav-link icon="fa-file-pen" label="Content Management" />
                    <x-admin.nav-link icon="fa-headset" label="Support" />
                </div>
            </div>

            {{-- System Section --}}
            <div>
                <div class="text-xs uppercase text-gray-500 font-semibold mb-3 px-2 tracking-wider section-label" id="system-label">System</div>
                <div class="space-y-0.5">
                    <x-admin.nav-link icon="fa-shield-halved" label="Security" />
                    <x-admin.nav-link icon="fa-toolbox" label="Utilities" />
                    <x-admin.nav-link icon="fa-code" label="APIs" />
                </div>
            </div>

            {{-- Business Section --}}
            <div>
                <div class="text-xs uppercase text-gray-500 font-semibold mb-3 px-2 tracking-wider section-label" id="business-label">Business</div>
                <div class="space-y-0.5">
                    <x-admin.nav-link icon="fa-money-bill-wave" label="Revenue" />
                    <x-admin.nav-link icon="fa-chart-line" label="Analytics" />
                    <x-admin.nav-link icon="fa-file-lines" label="Reports" />
                </div>
            </div>
        </div>

        {{-- User Profile --}}
        <div class="p-4 border-t border-subtle">
            <div class="flex items-center space-x-3 user-profile" id="user-profile">
                <div class="relative user-avatar">
                    <img src="{{ auth()->user()->profile_pic_url ?? 'https://storage.googleapis.com/uxpilot-auth.appspot.com/avatars/avatar-3.jpg' }}"
                        class="w-8 h-8 rounded-lg border-2 border-accent-primary object-cover" />
                    <div class="absolute -bottom-1 -right-1 w-2.5 h-2.5 bg-green-400 rounded-full border-2 border-dark-900"></div>
                </div>
                <div class="flex-1 min-w-0 user-info" id="user-info">
                    <div class="font-medium text-white truncate text-sm">{{ auth()->user()->name }}</div>
                    <div class="text-xs text-gray-400 font-mono">{{ auth()->user()->system_role }}</div>
                </div>
            </div>
        </div>
    </div>

    {{-- Sidebar Toggle Button (Outside sidebar) --}}
    <button id="sidebar-toggle" class="sidebar-toggle" type="button">
        <i class="fa-solid fa-chevron-left text-sm"></i>
    </button>

    {{-- Main Panel --}}
    <div class="flex-1 overflow-hidden flex flex-col content-transition content-expanded main-panel-mobile">
        {{-- Top Bar --}}
        <div class="py-6 px-8 top-bar-mobile flex items-center justify-between">
            <div class="flex items-center space-x-4">
                {{-- Mobile Menu Toggle --}}
                <button id="mobile-menu-toggle" class="mobile-menu-toggle">
                    <i class="fa-solid fa-bars text-sm"></i>
                </button>
                
                <a href="{{ route('admin.dashboard') }}" class="text-accent-primary hover:text-accent-secondary transition-colors duration-200 p-2 rounded-lg hover:bg-dark-800">
                    <i class="fa-solid fa-home"></i>
                </a>
                <h1 class="text-xl font-semibold text-white">@yield('title', 'Dashboard')</h1>
            </div>
            
            <div class="flex items-center space-x-4">
                {{-- Desktop Search --}}
                <div class="relative desktop-search">
                    <input type="text" placeholder="Search..."
                        class="bg-dark-800 text-sm rounded-lg px-4 py-2 pl-10 w-64 focus:outline-none focus:ring-2 focus:ring-accent-primary focus:border-transparent border border-subtle">
                    <i class="fa-solid fa-search absolute left-3 top-2.5 text-gray-400 text-sm"></i>
                </div>
                
                {{-- Mobile Search --}}
                <div class="relative mobile-search">
                    <input type="text" placeholder="Search..."
                        class="bg-dark-800 text-sm rounded-lg px-4 py-2 pl-10 w-48 focus:outline-none focus:ring-2 focus:ring-accent-primary focus:border-transparent border border-subtle">
                    <i class="fa-solid fa-search absolute left-3 top-2.5 text-gray-400 text-sm"></i>
                </div>
                
                {{-- Notifications --}}
                <button class="relative p-2 text-gray-400 hover:text-white hover:bg-dark-800 rounded-lg transition-colors duration-200">
                    <i class="fa-solid fa-bell text-sm"></i>
                    <span class="absolute -top-1 -right-1 w-2 h-2 bg-accent-danger rounded-full"></span>
                </button>
                
                {{-- Settings --}}
                <button class="p-2 text-gray-400 hover:text-white hover:bg-dark-800 rounded-lg transition-colors duration-200">
                    <i class="fa-solid fa-cog text-sm"></i>
                </button>
            </div>
        </div>

        {{-- Page Content --}}
        <div class="flex-1 overflow-y-auto bg-dark-950 content-mobile">
            <div class="p-8">
                @yield('content')
            </div>
        </div>
    </div>

    <script>
        // Sidebar collapse functionality
        document.addEventListener('DOMContentLoaded', function() {
            const sidebar = document.getElementById('sidebar');
            const sidebarToggle = document.getElementById('sidebar-toggle');
            const logoText = document.getElementById('logo-text');
            const userInfo = document.getElementById('user-info');
            const managementLabel = document.getElementById('management-label');
            const systemLabel = document.getElementById('system-label');
            const businessLabel = document.getElementById('business-label');
            const mainPanel = document.querySelector('.flex-1');
            
            // Mobile menu elements
            const mobileMenuToggle = document.getElementById('mobile-menu-toggle');
            const mobileMenu = document.getElementById('mobile-menu');
            const mobileMenuOverlay = document.getElementById('mobile-menu-overlay');
            const mobileMenuClose = document.getElementById('mobile-menu-close');
            
            // Check for saved state
            const isCollapsed = localStorage.getItem('sidebarCollapsed') === 'true';
            if (isCollapsed) {
                collapseSidebar();
            }
            
            // Desktop sidebar toggle
            sidebarToggle.addEventListener('click', function() {
                if (sidebar.classList.contains('sidebar-expanded')) {
                    collapseSidebar();
                } else {
                    expandSidebar();
                }
            });
            
            // Mobile menu toggle
            mobileMenuToggle.addEventListener('click', function() {
                openMobileMenu();
            });
            
            // Mobile menu close
            mobileMenuClose.addEventListener('click', function() {
                closeMobileMenu();
            });
            
            // Close mobile menu when clicking overlay
            mobileMenuOverlay.addEventListener('click', function() {
                closeMobileMenu();
            });
            
            // Close mobile menu when pressing Escape key
            document.addEventListener('keydown', function(e) {
                if (e.key === 'Escape' && mobileMenu.classList.contains('active')) {
                    closeMobileMenu();
                }
            });
            
            // Close mobile menu when clicking on navigation links
            document.querySelectorAll('#mobile-menu a').forEach(link => {
                link.addEventListener('click', function() {
                    closeMobileMenu();
                });
            });
            
            function openMobileMenu() {
                mobileMenu.classList.add('active');
                mobileMenuOverlay.classList.add('active');
                document.body.style.overflow = 'hidden';
            }
            
            function closeMobileMenu() {
                mobileMenu.classList.remove('active');
                mobileMenuOverlay.classList.remove('active');
                document.body.style.overflow = '';
            }
            
            function collapseSidebar() {
                sidebar.classList.remove('sidebar-expanded');
                sidebar.classList.add('sidebar-collapsed');
                mainPanel.classList.remove('content-expanded');
                mainPanel.classList.add('content-collapsed');
                
                // Hide text elements
                logoText.style.display = 'none';
                userInfo.style.display = 'none';
                managementLabel.style.display = 'none';
                systemLabel.style.display = 'none';
                businessLabel.style.display = 'none';
                
                // Hide navigation text and subnav items
                document.querySelectorAll('.nav-text').forEach(el => el.style.display = 'none');
                document.querySelectorAll('.subnav-items').forEach(el => el.style.display = 'none');
                
                // Update toggle button
                sidebarToggle.innerHTML = '<i class="fa-solid fa-chevron-right text-sm"></i>';
                
                // Save state
                localStorage.setItem('sidebarCollapsed', 'true');
            }
            
            function expandSidebar() {
                sidebar.classList.remove('sidebar-collapsed');
                sidebar.classList.add('sidebar-expanded');
                mainPanel.classList.remove('content-collapsed');
                mainPanel.classList.add('content-expanded');
                
                // Show text elements
                logoText.style.display = 'block';
                userInfo.style.display = 'block';
                managementLabel.style.display = 'block';
                systemLabel.style.display = 'block';
                businessLabel.style.display = 'block';
                
                // Show navigation text and subnav items
                document.querySelectorAll('.nav-text').forEach(el => el.style.display = 'block');
                document.querySelectorAll('.subnav-items').forEach(el => el.style.display = 'block');
                
                // Update toggle button
                sidebarToggle.innerHTML = '<i class="fa-solid fa-chevron-left text-sm"></i>';
                
                // Save state
                localStorage.setItem('sidebarCollapsed', 'false');
            }
        });
    </script>

@yield('scripts')
</body>

</html>
