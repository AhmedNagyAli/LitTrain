<!-- Navbar -->
<nav class="bg-white shadow sticky top-0 z-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16 items-center">

            <!-- Left Section -->
            <div class="flex items-center space-x-6">
                <!-- Logo -->
                <a href="/" class="flex items-center space-x-2">
                    <div class="h-8 w-8 bg-gradient-to-r from-indigo-600 to-purple-500 rounded-md flex items-center justify-center">
                        <i class="fas fa-book-open text-white text-sm"></i>
                    </div>
                    <span class="text-xl font-bold text-gray-900">LitTrain</span>
                </a>

                <!-- Language Dropdown -->
<form action="{{ route('home.index') }}" method="GET" class="inline-block">
    <label for="language_id" class="block text-gray-700 font-medium mb-1">

    </label>
    <select name="language_id" id="language_id"
        onchange="this.form.submit()"
        class="bg-transparent text-sm ">
        <option value="">All Languages</option>
        @foreach(\App\Models\Language::all() as $language)
    <option value="{{ $language->id }}"
        {{ request('language_id') == $language->id ? 'selected' : '' }}>
        {{ $language->name }}
    </option>
@endforeach
    </select>

    {{-- Keep category filter when switching languages --}}
    @if(request('category'))
        <input type="hidden" name="category" value="{{ request('category') }}">
    @endif
</form>



                <!-- Become a Publisher -->
                <a href="{{ route('publishing.request.create') }}"
                   class="text-gray-600 hover:text-indigo-600 text-sm font-medium transition">
                    Become a Publisher
                </a>
            </div>

            <!-- Right Section -->
            <div class="flex items-center space-x-4">
                @auth
                    <!-- Avatar Dropdown -->
                    <div class="relative" x-data="{ open: false }">
                        <button @click="open = !open"
                            class="flex items-center text-sm rounded-full focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                            @if(Auth::user()->avatar)
                                <img class="h-9 w-9 rounded-full border border-gray-200"
                                     src="{{ Auth::user()->avatar }}" alt="{{ Auth::user()->name }}">
                            @else
                                <div class="h-9 w-9 rounded-full bg-gradient-to-r from-indigo-600 to-purple-500 flex items-center justify-center text-white text-xs font-semibold">
                                    {{ strtoupper(substr(Auth::user()->name, 0, 2)) }}
                                </div>
                            @endif
                        </button>

                        <!-- Dropdown Menu -->
<div x-show="open" @click.outside="open = false"
     class="absolute right-0 mt-2 w-48 bg-white rounded-md shadow-lg py-2 z-50">

    <a href="{{ route('user.dashboard') }}"
       class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Dashboard</a>

    <a href="{{ route('user.profile') }}"
       class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Profile</a>

    <a href="{{ route('user.books') }}"
       class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">My Books</a>

    <a href="{{ route('user.sessions') }}"
       class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Training Sessions</a>

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
        </div>
    </div>
</nav>

<!-- Alpine.js (needed for dropdown toggle) -->
<script src="//unpkg.com/alpinejs" defer></script>
