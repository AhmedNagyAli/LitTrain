<!-- Navbar -->
<nav class="bg-white shadow sticky top-0 z-50" x-data="{ mobileMenu: false, avatarMenu: false }">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16 items-center">

            <!-- Left Section -->
            <div class="flex items-center">
                <!-- Logo -->
                <a href="/" class="flex items-center space-x-2">
                    <div class="h-8 w-8 bg-gradient-to-r from-indigo-600 to-purple-500 rounded-md flex items-center justify-center">
                        <i class="fas fa-book-open text-white text-sm"></i>
                    </div>
                    <span class="text-xl font-bold text-gray-900">LitTrain</span>
                </a>
            </div>

            <!-- Desktop Menu -->
            <div class="hidden md:flex items-center space-x-6">
                <!-- Language Dropdown -->
                <form action="{{ route('home.index') }}" method="GET" class="inline-block">
                    <select name="language_id" id="language_id"
                        onchange="this.form.submit()"
                        class="bg-transparent text-sm border-gray-300 rounded-md focus:ring-indigo-500 focus:border-indigo-500">
                        <option value="">All Languages</option>
                        @foreach(\App\Models\Language::all() as $language)
                            <option value="{{ $language->id }}"
                                {{ request('language_id') == $language->id ? 'selected' : '' }}>
                                {{ $language->name }}
                            </option>
                        @endforeach
                    </select>
                    @if(request('category'))
                        <input type="hidden" name="category" value="{{ request('category') }}">
                    @endif
                </form>

                <!-- Audiobooks -->
                <a href="{{ route('books.audiobooks') }}"
                   class="text-gray-900 hover:text-indigo-600 text-sm font-medium transition">
                    Audiobooks
                </a>

                <!-- Authors -->
                <a href="{{ route('authors.index') }}"
                   class="text-gray-900 hover:text-indigo-600 text-sm font-medium transition">
                    Authors
                </a>

                <!-- Publishers -->
                <a href="{{ route('publishers.index') }}"
                   class="text-gray-900 hover:text-indigo-600 text-sm font-medium transition">
                    Publishers
                </a>

                <!-- Become a Publisher (only if logged in) -->
                @auth
                    <a href="{{ route('publishing.request.create') }}"
                       class="text-gray-900 hover:text-indigo-600 text-sm font-medium transition">
                        Become a Publisher
                    </a>
                @endauth
            </div>

            <!-- Right Section (Profile/Login) -->
            <div class="hidden md:flex items-center space-x-4">
                @auth
                    <!-- Avatar Dropdown -->
                    <div class="relative">
                        <button @click="avatarMenu = !avatarMenu"
                            class="flex items-center text-sm rounded-full focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                            @if(Auth::user()->avatar)
                                <img class="h-9 w-9 rounded-full border border-gray-200"
                                     src="{{ Auth::user()->avatar ? asset('storage/'.Auth::user()->avatar) : 'https://ui-avatars.com/api/?name='.urlencode(Auth::user()->name) }}" alt="{{ Auth::user()->name }}">
                            @else
                                <div class="h-9 w-9 rounded-full bg-gradient-to-r from-indigo-600 to-purple-500 flex items-center justify-center text-white text-xs font-semibold">
                                    {{ strtoupper(substr(Auth::user()->name, 0, 2)) }}
                                </div>
                            @endif
                        </button>

                        <!-- Dropdown Menu -->
                        <div x-show="avatarMenu" @click.outside="avatarMenu = false"
                             class="absolute right-0 mt-2 w-48 bg-white rounded-md shadow-lg py-2 z-50"
                             x-transition>
                            <a href="{{ route('user.dashboard') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Dashboard</a>
                            <a href="{{ route('user.profile') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Profile</a>
                            <a href="{{ route('user.books') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">My Books</a>
                            <a href="{{ route('user.sessions') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Training Sessions</a>
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit"
                                    class="w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                    Logout
                                </button>
                            </form>
                        </div>
                    </div>
                @endauth

                @guest
                    <a href="{{ route('login') }}"
                       class="px-4 py-2 rounded-lg bg-indigo-600 text-white text-sm font-medium hover:bg-purple-500 transition">
                        Login
                    </a>
                @endguest
            </div>

            <!-- Mobile Hamburger -->
            <div class="md:hidden flex items-center">
                <button @click="mobileMenu = !mobileMenu" class="text-gray-700 focus:outline-none">
                    <svg class="h-6 w-6" fill="none" stroke="currentColor" stroke-width="2"
                         viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round"
                              d="M4 6h16M4 12h16M4 18h16"/>
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <!-- Mobile Menu -->
<div
    x-show="mobileMenu"
    x-cloak
    class="md:hidden bg-white shadow-md z-50"
    x-transition
>
    <div class="px-4 pt-4 pb-6 space-y-4">
        <!-- Language -->
        <form action="{{ route('home.index') }}" method="GET" class="w-full">
            <select name="language_id" id="language_id"
                onchange="this.form.submit()"
                class="w-full border-gray-300 rounded-md focus:ring-indigo-500 focus:border-indigo-500">
                <option value="">All Languages</option>
                @foreach(\App\Models\Language::all() as $language)
                    <option value="{{ $language->id }}"
                        {{ request('language_id') == $language->id ? 'selected' : '' }}>
                        {{ $language->name }}
                    </option>
                @endforeach
            </select>
            @if(request('category'))
                <input type="hidden" name="category" value="{{ request('category') }}">
            @endif
        </form>

        <!-- Audiobooks -->
        <a href="{{ route('books.audiobooks') }}"
           class="block text-gray-600 hover:text-indigo-600 text-sm font-medium">
            Audiobooks
        </a>

        <!-- Authors -->
        <a href="{{ route('authors.index') }}"
           class="block text-gray-600 hover:text-indigo-600 text-sm font-medium">
            Authors
        </a>

        <!-- Publishers -->
        <a href="{{ route('publishers.index') }}"
           class="block text-gray-600 hover:text-indigo-600 text-sm font-medium">
            Publishers
        </a>

        <!-- Become a Publisher (only logged in) -->
        @auth
            <a href="{{ route('publishing.request.create') }}"
               class="block text-gray-600 hover:text-indigo-600 text-sm font-medium">
                Become a Publisher
            </a>
        @endauth

        @auth
            <a href="{{ route('user.dashboard') }}" class="block text-gray-700 hover:bg-gray-100 px-3 py-2 rounded">Dashboard</a>
            <a href="{{ route('user.profile') }}" class="block text-gray-700 hover:bg-gray-100 px-3 py-2 rounded">Profile</a>
            <a href="{{ route('user.books') }}" class="block text-gray-700 hover:bg-gray-100 px-3 py-2 rounded">My Books</a>
            <a href="{{ route('user.sessions') }}" class="block text-gray-700 hover:bg-gray-100 px-3 py-2 rounded">Training Sessions</a>
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit"
                    class="block w-full text-left text-gray-700 hover:bg-gray-100 px-3 py-2 rounded">
                    Logout
                </button>
            </form>
        @endauth

        @guest
            <a href="{{ route('login') }}"
               class="block px-4 py-2 rounded-lg bg-indigo-600 text-white text-center text-sm font-medium hover:bg-purple-500 transition">
                Login
            </a>
        @endguest
    </div>
</div>

</nav>
