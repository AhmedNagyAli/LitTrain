@extends('layouts.guest')

@section('title', 'Forgot Password | LiTrain')

@section('content')
<div class="flex items-center justify-center min-h-screen bg-gradient-to-br from-gray-50 to-gray-200">
    <div class="w-full max-w-md bg-white shadow-xl rounded-2xl p-8">

        <!-- Header -->
        <div class="text-center mb-8">
            <div class="w-14 h-14 mx-auto flex items-center justify-center bg-yellow-100 text-yellow-600 rounded-full">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-7 w-7" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M12 11c0-1.105-.895-2-2-2s-2 .895-2 2 .895 2 2 2 2-.895 2-2zm0 0c0 2-2 4-2 4h8s-2-2-2-4c0-2.209-1.791-4-4-4z" />
                </svg>
            </div>
            <h2 class="mt-4 text-2xl font-bold text-gray-800">Forgot Your Password?</h2>
            <p class="text-gray-500 text-sm mt-1">Enter your email and we’ll send you a reset link.</p>
        </div>

        <!-- Success -->
        @if(session('success'))
            <div class="bg-green-100 text-green-700 p-3 rounded-lg mb-4 text-sm">
                {{ session('success') }}
            </div>
        @endif

        <!-- Errors -->
        @if($errors->any())
            <div class="bg-red-100 text-red-600 p-3 rounded-lg mb-4 text-sm">
                {{ $errors->first() }}
            </div>
        @endif

        <!-- Form -->
        <form method="POST" action="{{ route('password.email') }}" class="space-y-5">
            @csrf

            <!-- Email -->
            <div>
                <label class="block text-gray-700 font-medium mb-1">Email Address</label>
                <input type="email" name="email" value="{{ old('email') }}" required autofocus
                       class="w-full px-4 py-2 border border-gray-300 rounded-lg shadow-sm focus:ring-2 focus:ring-yellow-400 focus:outline-none transition">
                @error('email')
                    <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Submit -->
            <button type="submit"
                    class="w-full bg-yellow-600 hover:bg-yellow-700 text-white py-2.5 rounded-lg font-semibold shadow-md transition">
                Send Reset Link
            </button>
        </form>

        <!-- Footer -->
        <p class="text-center text-gray-600 text-sm mt-6">
            <a href="{{ route('login') }}" class="text-yellow-600 font-medium hover:underline">Back to Login</a>
        </p>
    </div>
</div>
@endsection
