<!-- Sidebar -->
<div class="hidden lg:fixed lg:inset-y-0 lg:z-50 lg:flex lg:w-64 lg:flex-col">
    <div class="flex grow flex-col gap-y-5 overflow-y-auto bg-white px-6 pb-4 shadow-sm border-r border-gray-200">
        <!-- Logo -->
        <div class="flex h-16 shrink-0 items-center">
            <a href="{{ route('dashboard') }}" class="flex items-center space-x-2">
                <i class="fas fa-clipboard-check text-2xl text-blue-600"></i>
                <span class="text-xl font-bold text-gray-900">DeckCheck</span>
            </a>
        </div>

        <!-- Navigation -->
        <nav class="flex flex-1 flex-col">
            <ul role="list" class="flex flex-1 flex-col gap-y-7">
                <!-- Main Navigation -->
                <li>
                    <ul role="list" class="-mx-2 space-y-1">
                        <!-- Dashboard -->
                        <li>
                            <a href="{{ route('dashboard') }}" 
                               class="group flex gap-x-3 rounded-md p-2 text-sm leading-6 font-semibold {{ request()->routeIs('dashboard') ? 'bg-blue-50 text-blue-700' : 'text-gray-700 hover:text-blue-700 hover:bg-gray-50' }}">
                                <i class="fas fa-tachometer-alt {{ request()->routeIs('dashboard') ? 'text-blue-700' : 'text-gray-400 group-hover:text-blue-700' }}"></i>
                                Dashboard
                            </a>
                        </li>

                        <!-- Maintenance Module -->
                        <li>
                            <a href="{{ route('maintenance.index') }}" 
                               class="group flex gap-x-3 rounded-md p-2 text-sm leading-6 font-semibold {{ request()->routeIs('maintenance.*') ? 'bg-blue-50 text-blue-700' : 'text-gray-700 hover:text-blue-700 hover:bg-gray-50' }}">
                                <i class="fas fa-tools {{ request()->routeIs('maintenance.*') ? 'text-blue-700' : 'text-gray-400 group-hover:text-blue-700' }}"></i>
                                Maintenance
                            </a>
                        </li>

                        <!-- Inventory Module -->
                        <li>
                            <a href="{{ route('equipment.index') }}" 
                               class="group flex gap-x-3 rounded-md p-2 text-sm leading-6 font-semibold {{ request()->routeIs('equipment.*') ? 'bg-blue-50 text-blue-700' : 'text-gray-700 hover:text-blue-700 hover:bg-gray-50' }}">
                                <i class="fas fa-boxes {{ request()->routeIs('equipment.*') ? 'text-blue-700' : 'text-gray-400 group-hover:text-blue-700' }}"></i>
                                Inventory
                            </a>
                        </li>

                        <!-- Vessel Module -->
                        <li>
                            <a href="{{ route('vessel.index') }}" 
                               class="group flex gap-x-3 rounded-md p-2 text-sm leading-6 font-semibold {{ request()->routeIs('vessel.*') ? 'bg-blue-50 text-blue-700' : 'text-gray-700 hover:text-blue-700 hover:bg-gray-50' }}">
                                <i class="fas fa-ship {{ request()->routeIs('vessel.*') ? 'text-blue-700' : 'text-gray-400 group-hover:text-blue-700' }}"></i>
                                Vessel
                            </a>
                        </li>

                        <!-- Schedule -->
                        <li>
                            <a href="{{ route('schedule.index') }}" 
                               class="group flex gap-x-3 rounded-md p-2 text-sm leading-6 font-semibold {{ request()->routeIs('schedule.*') ? 'bg-blue-50 text-blue-700' : 'text-gray-700 hover:text-blue-700 hover:bg-gray-50' }}">
                                <i class="fas fa-calendar-alt {{ request()->routeIs('schedule.*') ? 'text-blue-700' : 'text-gray-400 group-hover:text-blue-700' }}"></i>
                                Schedule
                            </a>
                        </li>

                        <!-- Deficiencies -->
                        <li>
                            <a href="{{ route('deficiencies.index') }}" 
                               class="group flex gap-x-3 rounded-md p-2 text-sm leading-6 font-semibold {{ request()->routeIs('deficiencies.*') ? 'bg-blue-50 text-blue-700' : 'text-gray-700 hover:text-blue-700 hover:bg-gray-50' }}">
                                <i class="fas fa-exclamation-triangle {{ request()->routeIs('deficiencies.*') ? 'text-blue-700' : 'text-gray-400 group-hover:text-blue-700' }}"></i>
                                Deficiencies
                            </a>
                        </li>
                    </ul>
                </li>

                <!-- Admin Section -->
                @if(auth()->user()->hasSystemAccess('superadmin') || auth()->user()->hasSystemAccess('staff'))
                <li>
                    <div class="text-xs font-semibold leading-6 text-gray-400 uppercase tracking-wider">Administration</div>
                    <ul role="list" class="-mx-2 mt-2 space-y-1">
                        <li>
                            <a href="{{ route('admin.dashboard') }}" 
                               class="group flex gap-x-3 rounded-md p-2 text-sm leading-6 font-semibold {{ request()->routeIs('admin.*') ? 'bg-blue-50 text-blue-700' : 'text-gray-700 hover:text-blue-700 hover:bg-gray-50' }}">
                                <i class="fas fa-cog {{ request()->routeIs('admin.*') ? 'text-blue-700' : 'text-gray-400 group-hover:text-blue-700' }}"></i>
                                Admin Panel
                            </a>
                        </li>
                    </ul>
                </li>
                @endif

                <!-- User Section -->
                <li class="mt-auto">
                    <div class="flex items-center gap-x-4 px-6 py-3 text-sm font-semibold leading-6 text-gray-900">
                        <img class="h-8 w-8 rounded-full bg-gray-50" 
                             src="{{ auth()->user()->profile_pic ? Storage::url(auth()->user()->profile_pic) : asset('images/placeholders/user.png') }}" 
                             alt="{{ auth()->user()->first_name }}">
                        <span class="sr-only">Your profile</span>
                        <span aria-hidden="true">{{ auth()->user()->first_name }} {{ auth()->user()->last_name }}</span>
                    </div>
                </li>
            </ul>
        </nav>
    </div>
</div>
