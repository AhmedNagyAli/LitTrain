@extends('layouts.user')

@section('content')
<div x-data="{ nameModal:false, emailModal:false, phoneModal:false, bioModal:false, dobModal:false, languageModal:false, avatarModal:false }" class="space-y-8">

    <h1 class="text-3xl font-bold text-gray-800 mb-6">My Profile</h1>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
        <!-- Name -->
        <div class="bg-white rounded-2xl shadow-md p-6 relative hover:shadow-xl transition">
            <h2 class="text-gray-500 text-sm uppercase">Name</h2>
            <p class="text-lg font-semibold">{{ $user->name }}</p>
            <button @click="nameModal = true" class="absolute top-3 right-3 text-indigo-600 hover:text-indigo-800">
                <i class="fas fa-edit"></i>
            </button>
        </div>

        <!-- Email -->
        <div class="bg-white rounded-2xl shadow-md p-6 relative hover:shadow-xl transition">
            <h2 class="text-gray-500 text-sm uppercase">Email</h2>
            <p class="text-lg font-semibold">{{ $user->email }}</p>
            <button @click="emailModal = true" class="absolute top-3 right-3 text-indigo-600 hover:text-indigo-800">
                <i class="fas fa-edit"></i>
            </button>
        </div>

        <!-- Phone -->
        <div class="bg-white rounded-2xl shadow-md p-6 relative hover:shadow-xl transition">
            <h2 class="text-gray-500 text-sm uppercase">Phone</h2>
            <p class="text-lg font-semibold">{{ $user->phone ?? 'Not set' }}</p>
            <button @click="phoneModal = true" class="absolute top-3 right-3 text-indigo-600 hover:text-indigo-800">
                <i class="fas fa-edit"></i>
            </button>
        </div>

        <!-- Bio -->
        <div class="bg-white rounded-2xl shadow-md p-6 relative hover:shadow-xl transition">
            <h2 class="text-gray-500 text-sm uppercase">Bio</h2>
            <p class="text-lg font-semibold">{{ $user->bio ?? 'Not set' }}</p>
            <button @click="bioModal = true" class="absolute top-3 right-3 text-indigo-600 hover:text-indigo-800">
                <i class="fas fa-edit"></i>
            </button>
        </div>

        <!-- Date of Birth -->
        <div class="bg-white rounded-2xl shadow-md p-6 relative hover:shadow-xl transition">
            <h2 class="text-gray-500 text-sm uppercase">Date of Birth</h2>
            <p class="text-lg font-semibold">{{ $user->date_of_birth?->format('Y-m-d') ?? 'Not set' }}</p>
            <button @click="dobModal = true" class="absolute top-3 right-3 text-indigo-600 hover:text-indigo-800">
                <i class="fas fa-edit"></i>
            </button>
        </div>

        <!-- Language -->
        <div class="bg-white rounded-2xl shadow-md p-6 relative hover:shadow-xl transition">
            <h2 class="text-gray-500 text-sm uppercase">Language</h2>
            <p class="text-lg font-semibold">{{ $user->language?->name ?? 'Not set' }}</p>
            <button @click="languageModal = true" class="absolute top-3 right-3 text-indigo-600 hover:text-indigo-800">
                <i class="fas fa-edit"></i>
            </button>
        </div>

        <!-- Avatar -->
        <div class="bg-white rounded-2xl shadow-md p-6 flex flex-col items-center relative hover:shadow-xl transition">
            <h2 class="text-gray-500 text-sm uppercase mb-2">Avatar</h2>
            <img src="{{ $user->avatar ? asset('storage/'.$user->avatar) : 'https://ui-avatars.com/api/?name='.urlencode($user->name) }}" class="w-24 h-24 rounded-full border shadow mb-3">
            <button @click="avatarModal = true" class="text-indigo-600 hover:text-indigo-800">
                <i class="fas fa-edit"></i> Change
            </button>
        </div>
    </div>

    <!-- Modals -->
    @include('pages.user.modals.name')
    @include('pages.user.modals.email')
    @include('pages.user.modals.phone')
    @include('pages.user.modals.bio')
    @include('pages.user.modals.language')
    @include('pages.user.modals.avatar')

</div>
@endsection

@push('scripts')
<script src="//unpkg.com/alpinejs" defer></script>
@endpush
