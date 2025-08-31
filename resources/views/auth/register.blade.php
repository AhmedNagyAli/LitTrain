@extends('layouts.guest')

@section('title', 'Register | LiTrain')

@section('content')
<div class="flex items-center justify-center min-h-screen bg-gradient-to-br from-gray-50 to-gray-200">
    <div class="w-full max-w-md bg-white shadow-xl rounded-2xl p-8">

        <!-- Header -->
        <div class="text-center mb-8">
            <div class="w-14 h-14 mx-auto flex items-center justify-center bg-green-100 text-green-600 rounded-full">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-7 w-7" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                </svg>
            </div>
            <h2 class="mt-4 text-2xl font-bold text-gray-800">Create an Account</h2>
            <p class="text-gray-500 text-sm mt-1">Join LiTrain today and start learning smarter 🚀</p>
        </div>

        <!-- Errors -->
        @if($errors->any())
            <div class="bg-red-100 text-red-600 p-3 rounded-lg mb-4 text-sm">
                <ul class="list-disc list-inside">
                    @foreach ($errors->all() as $err)
                        <li>{{ $err }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <!-- Debug Info -->
        @if(session('debug'))
            <div class="bg-yellow-100 text-yellow-800 p-3 rounded-lg mb-4 text-sm">
                {{ session('debug') }}
            </div>
        @endif

        <!-- Form -->
        <form method="POST" action="{{ route('register.store') }}" class="space-y-5" id="registerForm">
            @csrf

            <!-- Full Name -->
            <div>
                <label class="block text-gray-700 font-medium mb-1">Full Name</label>
                <input type="text" name="name" value="{{ old('name') }}" required
                       class="w-full px-4 py-2 border border-gray-300 rounded-lg shadow-sm focus:ring-2 focus:ring-green-400 focus:outline-none transition">
                @error('name')
                    <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Email -->
            <div>
                <label class="block text-gray-700 font-medium mb-1">Email Address</label>
                <input type="email" name="email" value="{{ old('email') }}" required
                       class="w-full px-4 py-2 border border-gray-300 rounded-lg shadow-sm focus:ring-2 focus:ring-green-400 focus:outline-none transition">
                @error('email')
                    <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Phone (optional) -->
            <div>
                <label class="block text-gray-700 font-medium mb-1">Phone (optional)</label>
                <input type="text" name="phone" value="{{ old('phone') }}"
                       class="w-full px-4 py-2 border border-gray-300 rounded-lg shadow-sm focus:ring-2 focus:ring-green-400 focus:outline-none transition">
                @error('phone')
                    <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Password -->
            <div>
                <label class="block text-gray-700 font-medium mb-1">Password</label>
                <input type="password" name="password" required
                       class="w-full px-4 py-2 border border-gray-300 rounded-lg shadow-sm focus:ring-2 focus:ring-green-400 focus:outline-none transition">
                @error('password')
                    <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Confirm Password -->
            <div>
                <label class="block text-gray-700 font-medium mb-1">Confirm Password</label>
                <input type="password" name="password_confirmation" required
                       class="w-full px-4 py-2 border border-gray-300 rounded-lg shadow-sm focus:ring-2 focus:ring-green-400 focus:outline-none transition">
            </div>

            <!-- Preferred Language -->
            <div>
                <label class="block text-gray-700 font-medium mb-1">Preferred Language</label>
                <select name="language_id" required
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg shadow-sm focus:ring-2 focus:ring-green-400 focus:outline-none transition">
                    <option value="" disabled {{ old('language_id') ? '' : 'selected' }}>-- Select Language --</option>
                    @foreach($languages as $language)
                        <option value="{{ $language->id }}" {{ (string)old('language_id') === (string)$language->id ? 'selected' : '' }}>
                            {{ $language->name }}
                        </option>
                    @endforeach
                </select>
                @error('language_id')
                    <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Submit -->
            <button type="submit"
                    class="w-full bg-green-600 hover:bg-green-700 text-white py-2.5 rounded-lg font-semibold shadow-md transition">
                Register
            </button>
        </form>

        <!-- Footer -->
        <p class="text-center text-gray-600 text-sm mt-6">
            Already have an account?
            <a href="{{ route('login') }}" class="text-green-600 font-medium hover:underline">Login</a>
        </p>
    </div>
</div>

@endsection

@push('scripts')
<script>
// Add some debugging to see what's being submitted
document.getElementById('registerForm').addEventListener('submit', function(e) {
    console.log('Form submitted');

    // Log all form data for debugging
    const formData = new FormData(this);
    for (let [key, value] of formData.entries()) {
        console.log(key + ': ' + value);
    }
});

</script>


@endpush

