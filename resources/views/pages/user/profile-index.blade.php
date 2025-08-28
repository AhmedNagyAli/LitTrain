{{-- resources/views/user/profile.blade.php --}}
@extends('layouts.user')

@section('title', 'My Profile')

@section('content')
<div class="flex min-h-screen bg-gray-100">
    {{-- Sidebar --}}
    <aside class="w-64 bg-white shadow-lg">
        <div class="p-6 border-b">
            <div class="flex items-center space-x-3">
                <img src="{{ auth()->user()->avatar ?? 'https://ui-avatars.com/api/?name='.auth()->user()->name }}"
                     alt="Avatar"
                     class="w-12 h-12 rounded-full">
                <div>
                    <h2 class="text-lg font-semibold text-gray-800">{{ auth()->user()->name }}</h2>
                    <p class="text-sm text-gray-500">{{ auth()->user()->email }}</p>
                </div>
            </div>
        </div>

        <nav class="mt-4 space-y-1">
            <a href="#"
               class="block px-6 py-3 text-gray-700 hover:bg-gray-100">
                📊 Dashboard
            </a>
            <a href="#"
               class="block px-6 py-3 text-gray-700 hover:bg-gray-100">
                📚 My Books
            </a>
            <a href="#"
               class="block px-6 py-3 text-gray-700 hover:bg-gray-100">
                ⚙️ Settings
            </a>
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit"
                        class="w-full text-left px-6 py-3 text-red-600 hover:bg-red-50">
                    🚪 Logout
                </button>
            </form>
        </nav>
    </aside>

    {{-- Main Content --}}
    <main class="flex-1 p-8">
        <h1 class="text-2xl font-bold text-gray-800 mb-6">Welcome back, {{ auth()->user()->name }} 👋</h1>

        {{-- Example user info card --}}
        <div class="bg-white rounded-xl shadow p-6">
            <h2 class="text-lg font-semibold mb-4">Profile Information</h2>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <p class="text-sm text-gray-500">Name</p>
                    <p class="font-medium text-gray-800">{{ auth()->user()->name }}</p>
                </div>
                <div>
                    <p class="text-sm text-gray-500">Email</p>
                    <p class="font-medium text-gray-800">{{ auth()->user()->email }}</p>
                </div>
                <div>
                    <p class="text-sm text-gray-500">Phone</p>
                    <p class="font-medium text-gray-800">{{ auth()->user()->phone ?? '—' }}</p>
                </div>
                <div>
                    <p class="text-sm text-gray-500">Member Since</p>
                    <p class="font-medium text-gray-800">{{ auth()->user()->created_at->format('M d, Y') }}</p>
                </div>
            </div>
        </div>
    </main>
</div>
@endsection
