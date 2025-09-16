<!-- Guest Header -->
<header class="bg-white shadow-sm border-b border-gray-200">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between items-center h-16">
            <!-- Logo -->
            <div class="flex items-center">
                <a href="{{ route('welcome') }}" class="flex items-center space-x-2">
                    <i class="fas fa-clipboard-check text-2xl text-blue-600"></i>
                    <span class="text-xl font-bold text-gray-900">DeckCheck</span>
                </a>
            </div>

            <!-- Navigation Links -->
            <nav class="hidden md:flex space-x-8">
                <a href="{{ route('welcome') }}" 
                   class="text-gray-700 hover:text-blue-600 px-3 py-2 text-sm font-medium">
                    Home
                </a>
                <a href="#features" 
                   class="text-gray-700 hover:text-blue-600 px-3 py-2 text-sm font-medium">
                    Features
                </a>
                <a href="#about" 
                   class="text-gray-700 hover:text-blue-600 px-3 py-2 text-sm font-medium">
                    About
                </a>
            </nav>

            <!-- Auth Links -->
            <div class="flex items-center space-x-4">
                @guest
                    <a href="{{ route('login') }}" 
                       class="text-gray-700 hover:text-blue-600 px-3 py-2 text-sm font-medium">
                        Log In
                    </a>
                    <a href="{{ route('register') }}" 
                       class="bg-blue-600 text-white hover:bg-blue-700 px-4 py-2 rounded-md text-sm font-medium">
                        Get Started
                    </a>
                @else
                    <a href="{{ route('dashboard') }}" 
                       class="bg-blue-600 text-white hover:bg-blue-700 px-4 py-2 rounded-md text-sm font-medium">
                        Dashboard
                    </a>
                @endguest
            </div>
        </div>
    </div>
</header>
