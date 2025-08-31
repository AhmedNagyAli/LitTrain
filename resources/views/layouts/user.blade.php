<!DOCTYPE html>
<html lang="en">
<head>
    @include('layouts.partials.head')
    @stack('styles')
</head>
<body class="flex bg-gray-50 text-gray-800 min-h-screen">

    <!-- Sidebar -->
    <aside class="w-72 bg-white/70 backdrop-blur-xl shadow-xl border-r border-gray-200 flex flex-col">
        <!-- Profile -->
        <div class="p-8 flex flex-col items-center border-b border-gray-200">
                        <img src="{{ Auth::user()->avatar ? asset('storage/'.Auth::user()->avatar) : 'https://ui-avatars.com/api/?name='.urlencode(Auth::user()->name) }}" class="w-24 h-24 rounded-full border shadow mb-3">

            <h2 class="text-xl font-bold text-gray-800">{{ Auth::user()->name }}</h2>
            <p class="text-sm text-gray-500">{{ Auth::user()->email }}</p>
        </div>

        <!-- Navigation -->
        <nav class="flex-1 p-6 space-y-3">
            <a href="{{ route('user.dashboard') }}"
               class="flex items-center gap-3 px-4 py-2 rounded-xl font-medium text-gray-700 hover:bg-indigo-500 hover:text-white transition-all">
                <i class="fas fa-home"></i> Dashboard
            </a>
            <a href="{{ route('user.profile') }}"
               class="flex items-center gap-3 px-4 py-2 rounded-xl font-medium text-gray-700 hover:bg-indigo-500 hover:text-white transition-all">
                <i class="fas fa-user"></i> Profile
            </a>
            <a href="{{ route('user.books') }}"
               class="flex items-center gap-3 px-4 py-2 rounded-xl font-medium text-gray-700 hover:bg-indigo-500 hover:text-white transition-all">
                <i class="fas fa-book"></i> My Books
            </a>
            <a href="{{ route('user.sessions') }}"
               class="flex items-center gap-3 px-4 py-2 rounded-xl font-medium text-gray-700 hover:bg-indigo-500 hover:text-white transition-all">
                <i class="fas fa-chalkboard-teacher"></i> Training Sessions
            </a>
        </nav>

        <!-- Logout -->
        <div class="p-6 border-t border-gray-200">
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button
                    class="w-full flex items-center justify-center gap-2 px-4 py-2 rounded-xl font-semibold text-red-600 bg-red-50 hover:bg-red-100 transition-all">
                    <i class="fas fa-sign-out-alt"></i> Logout
                </button>
            </form>
        </div>
    </aside>

    <!-- Content -->
    <main class="flex-1 p-8 bg-gradient-to-tr from-gray-50 to-gray-100">
        <div class="bg-white rounded-2xl shadow-lg p-8 min-h-[80vh]">
            @yield('content')
        </div>
    </main>

    @stack('scripts')
    <script src="https://kit.fontawesome.com/your-fontawesome-kit.js" crossorigin="anonymous"></script>


</body>
</html>

