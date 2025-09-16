<!-- Top Bar -->
<div class="sticky top-0 z-40 flex h-16 shrink-0 items-center gap-x-4 border-b border-gray-200 bg-white px-4 shadow-sm sm:gap-x-6 sm:px-6 lg:px-8">
    <!-- Mobile menu button -->
    <button type="button" class="-m-2.5 p-2.5 text-gray-700 lg:hidden" onclick="toggleMobileMenu()">
        <span class="sr-only">Open sidebar</span>
        <i class="fas fa-bars text-xl"></i>
    </button>

    <!-- Separator -->
    <div class="h-6 w-px bg-gray-200 lg:hidden" aria-hidden="true"></div>

    <div class="flex flex-1 gap-x-4 self-stretch lg:gap-x-6">
        <!-- Search -->
        <form class="relative flex flex-1" action="#" method="GET">
            <label for="search-field" class="sr-only">Search</label>
            <i class="pointer-events-none absolute inset-y-0 left-0 h-full w-5 text-gray-400" aria-hidden="true">
                <i class="fas fa-search"></i>
            </i>
            <input id="search-field" 
                   class="block h-full w-full border-0 py-0 pl-8 pr-0 text-gray-900 placeholder:text-gray-400 focus:ring-0 sm:text-sm" 
                   placeholder="Search..." 
                   type="search" 
                   name="search">
        </form>

        <div class="flex items-center gap-x-4 lg:gap-x-6">
            <!-- Vessel Switcher -->
            @if(auth()->user()->vessels->count() > 1)
            <div class="relative">
                <button type="button" 
                        class="flex items-center gap-x-1 text-sm font-semibold leading-6 text-gray-900"
                        onclick="toggleVesselSwitcher()">
                    <i class="fas fa-ship text-gray-400"></i>
                    {{ currentVessel()?->name ?? 'Select Vessel' }}
                    <i class="fas fa-chevron-down text-xs text-gray-400"></i>
                </button>
                
                <!-- Vessel Switcher Dropdown -->
                <div id="vessel-switcher" class="hidden absolute right-0 z-10 mt-2 w-56 origin-top-right rounded-md bg-white shadow-lg ring-1 ring-black ring-opacity-5 focus:outline-none">
                    <div class="py-1">
                        @foreach(auth()->user()->vessels as $vessel)
                        <form method="POST" action="{{ route('vessel.switch') }}" class="block">
                            @csrf
                            <input type="hidden" name="vessel_id" value="{{ $vessel->id }}">
                            <button type="submit" 
                                    class="flex w-full items-center px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 {{ currentVessel()?->id === $vessel->id ? 'bg-blue-50 text-blue-700' : '' }}">
                                <i class="fas fa-ship mr-3 text-gray-400"></i>
                                {{ $vessel->name }}
                                @if(currentVessel()?->id === $vessel->id)
                                    <i class="fas fa-check ml-auto text-blue-600"></i>
                                @endif
                            </button>
                        </form>
                        @endforeach
                    </div>
                </div>
            </div>
            @endif

            <!-- Notifications -->
            <button type="button" class="-m-2.5 p-2.5 text-gray-400 hover:text-gray-500">
                <span class="sr-only">View notifications</span>
                <i class="fas fa-bell text-xl"></i>
            </button>

            <!-- Separator -->
            <div class="hidden lg:block lg:h-6 lg:w-px lg:bg-gray-200" aria-hidden="true"></div>

            <!-- Profile dropdown -->
            <div class="relative">
                <button type="button" 
                        class="-m-1.5 flex items-center p-1.5"
                        onclick="toggleProfileMenu()">
                    <span class="sr-only">Open user menu</span>
                    <img class="h-8 w-8 rounded-full bg-gray-50" 
                         src="{{ auth()->user()->profile_pic ? Storage::url(auth()->user()->profile_pic) : asset('images/placeholders/user.png') }}" 
                         alt="{{ auth()->user()->first_name }}">
                    <span class="hidden lg:flex lg:items-center">
                        <span class="ml-4 text-sm font-semibold leading-6 text-gray-900" aria-hidden="true">
                            {{ auth()->user()->first_name }} {{ auth()->user()->last_name }}
                        </span>
                        <i class="fas fa-chevron-down ml-2 text-xs text-gray-400"></i>
                    </span>
                </button>

                <!-- Profile Dropdown -->
                <div id="profile-menu" class="hidden absolute right-0 z-10 mt-2.5 w-32 origin-top-right rounded-md bg-white py-2 shadow-lg ring-1 ring-gray-900/5 focus:outline-none">
                    <a href="{{ route('profile.edit') }}" 
                       class="block px-3 py-1 text-sm leading-6 text-gray-900 hover:bg-gray-50">
                        Your profile
                    </a>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" 
                                class="block w-full text-left px-3 py-1 text-sm leading-6 text-gray-900 hover:bg-gray-50">
                            Sign out
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function toggleMobileMenu() {
    // Mobile menu toggle logic
    console.log('Toggle mobile menu');
}

function toggleVesselSwitcher() {
    const dropdown = document.getElementById('vessel-switcher');
    dropdown.classList.toggle('hidden');
}

function toggleProfileMenu() {
    const dropdown = document.getElementById('profile-menu');
    dropdown.classList.toggle('hidden');
}

// Close dropdowns when clicking outside
document.addEventListener('click', function(event) {
    const vesselSwitcher = document.getElementById('vessel-switcher');
    const profileMenu = document.getElementById('profile-menu');
    
    if (!event.target.closest('[onclick="toggleVesselSwitcher()"]')) {
        vesselSwitcher?.classList.add('hidden');
    }
    
    if (!event.target.closest('[onclick="toggleProfileMenu()"]')) {
        profileMenu?.classList.add('hidden');
    }
});
</script>
