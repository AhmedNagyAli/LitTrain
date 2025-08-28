<!DOCTYPE html>
<html lang="en">
{{-- <html lang="{{ str_replace('_', '-', app()->getLocale()) }}" dir="rtl"> --}}
<head>
    @include('layouts.partials.head')
    @stack('styles')
</head>
<body class="flex bg-gray-100">
    <!-- Sidebar -->
    <aside class="w-64 bg-white shadow-md min-h-screen">
        <div class="p-6 flex flex-col items-center border-b">
            <img src="{{ Auth::user()->avatar ?? 'https://ui-avatars.com/api/?name='.Auth::user()->name }}"
                 class="w-20 h-20 rounded-full mb-2">
            <h2 class="text-lg font-semibold">{{ Auth::user()->name }}</h2>
            <p class="text-sm text-gray-500">{{ Auth::user()->email }}</p>
        </div>

        <nav class="p-4 space-y-2">
            <a href="{{ route('user.dashboard') }}" class="block px-4 py-2 rounded hover:bg-gray-200">Dashboard</a>
            <a href="{{ route('user.profile') }}" class="block px-4 py-2 rounded hover:bg-gray-200">Profile</a>
            <a href="{{ route('user.books') }}" class="block px-4 py-2 rounded hover:bg-gray-200">My Books</a>
            <a href="{{ route('user.sessions') }}" class="block px-4 py-2 rounded hover:bg-gray-200">Training Sessions</a>
            <form method="POST" action="{{ route('logout') }}" class="mt-4">
                @csrf
                <button class="w-full text-left px-4 py-2 rounded hover:bg-red-200 text-red-600">Logout</button>
            </form>
        </nav>
    </aside>

    <!-- Content -->
    <main class="flex-1 p-6">
        @yield('content')
    </main>
    @stack('scripts')
</body>
</html>
