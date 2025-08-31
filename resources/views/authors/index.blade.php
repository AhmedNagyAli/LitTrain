@extends('layouts.app')

@section('title', 'Authors')

@section('content')
<div class="container mx-auto px-4 py-10">
    <h1 class="text-3xl font-bold text-gray-800 hover:text-purple-800 mb-8 text-center">Meet Our Authors</h1>

    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-8">
        @foreach($authors as $author)
            <div class="bg-white rounded-2xl shadow-md hover:shadow-xl transition overflow-hidden">
                <!-- Avatar -->
                <div class="w-full h-64 bg-gray-50 flex items-center justify-center">
                    @if($author->avatar)
                        <img src="{{ asset('storage/' . $author->avatar) }}"
                             alt="{{ $author->name }}"
                             class="h-full w-full object-cover">
                    @else
                        <div class="flex items-center justify-center h-full w-full text-gray-400 text-sm">
                            No Image
                        </div>
                    @endif
                </div>

                <!-- Info -->
                <div class="p-5">
                    <h2 class="text-xl font-semibold text-gray-900 mb-1">{{ $author->name }}</h2>

                    <p class="text-gray-500 text-sm mb-3">
                        {{ $author->age ? $author->age . ' years old' : 'Age unknown' }}
                    </p>

                    <p class="text-gray-600 text-sm mb-4">
                        {{ $author->bio ? Str::limit($author->bio, 100) : 'No bio available' }}
                    </p>

                    <div class="flex justify-between items-center text-sm">
                        <a href="{{ route('authors.show', $author->id) }}"
                           class="text-indigo-800 hover:text-purple-800 font-medium">
                            View Books →
                        </a>
                        <span class="text-gray-400">
                            {{ $author->born_at?->format('Y') }} - {{ $author->died_at?->format('Y') ?? 'Present' }}
                        </span>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
</div>
@endsection
