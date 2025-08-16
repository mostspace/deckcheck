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
                            500: "#333C52",
                            600: "#2A3142",
                            700: "#1E2433",
                            800: "#161A25",
                            900: "#0F1117"
                        },
                        accent: {
                            primary: "#3B82F6",
                            secondary: "#10B981",
                            danger: "#EF4444",
                            warning: "#F59E0B"
                        }
                    },
                    fontFamily: {
                        sans: ["Inter", "sans-serif"]
                    }
                }
            }
        };
    </script>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Inter:wght@300;500;600;700;800;900&display=swap">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/js/all.min.js" crossorigin="anonymous"></script>

    {{-- Optional Highcharts (used in dashboard only) --}}
    <script src="https://code.highcharts.com/highcharts.js" defer></script>
    <script src="https://code.highcharts.com/modules/exporting.js" defer></script>
    <script src="https://code.highcharts.com/modules/export-data.js" defer></script>

    <style>
        body {
            font-family: 'Inter', sans-serif !important;
        }

        ::-webkit-scrollbar {
            display: none;
        }

        .fa,
        .fas,
        .far,
        .fal,
        .fab {
            font-family: "Font Awesome 6 Free", "Font Awesome 6 Brands" !important;
        }
    </style>
</head>

<body class="bg-dark-900 text-gray-100 flex h-screen overflow-hidden font-sans">
    {{-- Sidebar --}}
    <div class="w-64 bg-dark-800 h-screen flex flex-col border-r border-dark-600">
        <div class="p-4 border-b border-dark-600">
            <div class="flex items-center space-x-2">
                <i class="fa-solid fa-ship text-accent-primary text-xl"></i>
                <span class="font-bold text-xl">DeckCheck</span>
            </div>
        </div>

        <div class="flex-1 overflow-y-auto py-4 px-4 space-y-6">
            <div>
                <div class="text-xs uppercase text-gray-500 font-semibold mb-2 px-1">Management</div>
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
            <div>
                <div class="text-xs uppercase text-gray-500 font-semibold mb-2 px-1">System</div>
                <x-admin.nav-link icon="fa-shield-halved" label="Security" />
                <x-admin.nav-link icon="fa-toolbox" label="Utilities" />
                <x-admin.nav-link icon="fa-code" label="APIs" />
            </div>
            <div>
                <div class="text-xs uppercase text-gray-500 font-semibold mb-2 px-1">Business</div>
                <x-admin.nav-link icon="fa-money-bill-wave" label="Revenue" />
                <x-admin.nav-link icon="fa-chart-line" label="Analytics" />
                <x-admin.nav-link icon="fa-file-lines" label="Reports" />
            </div>
        </div>

        <div class="p-4 border-t border-dark-600">
            <div class="flex items-center">
                <img src="{{ auth()->user()->profile_pic_url ?? 'https://storage.googleapis.com/uxpilot-auth.appspot.com/avatars/avatar-3.jpg' }}"
                    class="w-10 h-10 rounded-full border-2 border-accent-primary" />
                <div class="ml-3">
                    <div class="font-medium">{{ auth()->user()->name }}</div>
                    <div class="text-xs text-gray-400">{{ auth()->user()->system_role }}</div>
                </div>
            </div>
        </div>
    </div>

    {{-- Main Panel --}}
    <div class="flex-1 overflow-y-auto">
        {{-- Top Bar --}}
        <div class="bg-dark-800 border-b border-dark-600 py-3 px-6 flex items-center justify-between">
            <h1 class="text-xl font-semibold">@yield('title', 'Dashboard')</h1>
            <div class="flex items-center space-x-4">
                <div class="relative">
                    <input type="text" placeholder="Search..."
                        class="bg-dark-700 text-sm rounded-md px-4 py-2 pl-9 w-64 focus:outline-none focus:ring focus:ring-accent-primary">
                    <i class="fa-solid fa-search absolute left-3 top-2.5 text-gray-400"></i>
                </div>
                <i class="fa-solid fa-bell text-gray-400 hover:text-white"></i>
                <i class="fa-solid fa-cog text-gray-400 hover:text-white"></i>
            </div>
        </div>

        {{-- Page Content --}}
        <div class="p-6">
            @yield('content')
        </div>
    </div>
@yield('scripts')
</body>

</html>
