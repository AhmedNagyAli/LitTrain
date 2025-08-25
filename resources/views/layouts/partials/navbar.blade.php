<!-- Navbar -->
<nav class="bg-white shadow sticky top-0 z-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
            <!-- Logo and main navigation -->
            <div class="flex items-center">
                <!-- Logo -->
                <a href="/" class="flex-shrink-0 flex items-center">
                    <div class="h-8 w-8 bg-gradient-to-r from-indigo-600 to-purple-500 rounded-md flex items-center justify-center">
                        <i class="fas fa-book-open text-white text-sm"></i>
                    </div>
                    <span class="ml-2 text-xl font-bold text-gray-900">LitTrain</span>
                </a>

                <!-- Navigation Links -->
                <div class="hidden md:ml-10 md:flex md:space-x-8">
                    <a href="#" class="text-gray-800 hover:text-indigo-600 px-3 py-2 text-sm font-medium transition duration-150">Discover</a>
                    <a href="#" class="text-gray-800 hover:text-indigo-600 px-3 py-2 text-sm font-medium transition duration-150">My Library</a>
                    <a href="#" class="text-gray-800 hover:text-indigo-600 px-3 py-2 text-sm font-medium transition duration-150">Writing Exercises</a>
                    <a href="#" class="text-gray-800 hover:text-indigo-600 px-3 py-2 text-sm font-medium transition duration-150">Community</a>
                </div>
            </div>

            <!-- Right section -->
            <div class="flex items-center">
                @auth
                    <!-- If user is logged in -->
                    <div class="hidden md:ml-4 md:flex md:items-center">
                        <div class="ml-3 relative">
                            <div>
                                <button class="max-w-xs flex items-center text-sm rounded-full focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-purple-400" id="user-menu-button">
                                    <span class="sr-only">Open user menu</span>
                                    @if(Auth::user()->avatar)
                                        <!-- Show user uploaded avatar -->
                                        <img class="h-8 w-8 rounded-full" src="{{ Auth::user()->avatar }}" alt="{{ Auth::user()->name }}">
                                    @else
                                        <!-- Fallback: initials -->
                                        <div class="h-8 w-8 rounded-full bg-gradient-to-r from-indigo-600 to-purple-500 flex items-center justify-center text-white text-xs font-semibold">
                                            {{ strtoupper(substr(Auth::user()->name, 0, 2)) }}
                                        </div>
                                    @endif
                                    <span class="ml-2 text-gray-700 text-sm font-medium hidden lg:inline">{{ Auth::user()->name }}</span>
                                    <i class="fas fa-chevron-down ml-1 text-gray-400 text-xs"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                @endauth

                @guest
                    <!-- If not logged in -->
                    <a href="{{ route('login') }}" class="ml-4 px-4 py-2 rounded-lg bg-indigo-600 text-white text-sm font-medium hover:bg-purple-500 transition">
                        Login
                    </a>
                @endguest

                <!-- Mobile menu button -->
                <div class="-mr-2 flex items-center md:hidden">
                    <button type="button" id="mobile-menu-button" class="bg-white inline-flex items-center justify-center p-2 rounded-md text-gray-400 hover:text-gray-500 hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-purple-400">
                        <span class="sr-only">Open main menu</span>
                        <i class="fas fa-bars h-6 w-6"></i>
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Mobile menu, show/hide based on menu state -->
    <div class="md:hidden hidden menu-transition" id="mobile-menu">
        <div class="pt-2 pb-3 space-y-1">
            <a href="#" class="bg-gray-100 text-indigo-600 block pl-3 pr-4 py-2 border-l-4 border-indigo-600 text-base font-medium">Discover</a>
            <a href="#" class="text-gray-800 hover:bg-gray-50 hover:text-indigo-600 block pl-3 pr-4 py-2 border-l-4 border-transparent text-base font-medium">My Library</a>
            <a href="#" class="text-gray-800 hover:bg-gray-50 hover:text-indigo-600 block pl-3 pr-4 py-2 border-l-4 border-transparent text-base font-medium">Writing Exercises</a>
            <a href="#" class="text-gray-800 hover:bg-gray-50 hover:text-indigo-600 block pl-3 pr-4 py-2 border-l-4 border-transparent text-base font-medium">Community</a>
        </div>
        <div class="pt-4 pb-3 border-t border-gray-200">
            <div class="flex items-center px-4">
                <div class="flex-shrink-0">
                    <div class="h-10 w-10 rounded-full bg-gradient-to-r from-indigo-600 to-purple-500 flex items-center justify-center text-white font-semibold">
                        JS
                    </div>
                </div>
                <div class="ml-3">
                    <div class="text-base font-medium text-gray-800">John Smith</div>
                    <div class="text-sm font-medium text-gray-500">john@example.com</div>
                </div>
            </div>
            <div class="mt-3 space-y-1">
                <a href="#" class="block px-4 py-2 text-base font-medium text-gray-800 hover:text-gray-800 hover:bg-gray-100">Profile</a>
                <a href="#" class="block px-4 py-2 text-base font-medium text-gray-800 hover:text-gray-800 hover:bg-gray-100">My Writings</a>
                <a href="#" class="block px-4 py-2 text-base font-medium text-gray-800 hover:text-gray-800 hover:bg-gray-100">Settings</a>
                <a href="#" class="block px-4 py-2 text-base font-medium text-gray-800 hover:text-gray-800 hover:bg-gray-100">Sign out</a>
            </div>
        </div>
    </div>
</nav>
