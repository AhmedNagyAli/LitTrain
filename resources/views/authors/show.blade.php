@extends('layouts.app')

@section('title', $author->name . ' | Author Profile')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="max-w-3xl mx-auto bg-white shadow-lg rounded-xl overflow-hidden">
        <div class="flex flex-col md:flex-row">
            <!-- Author Avatar -->
            <div class="md:w-1/3 p-6 flex items-center justify-center">
                <img src="{{ $author->avatar ?? 'https://via.placeholder.com/150' }}"
                     alt="{{ $author->name }}"
                     class="w-40 h-40 rounded-full object-cover shadow-md">
            </div>

            <!-- Author Info -->
            <div class="md:w-2/3 p-6">
                <h1 class="text-2xl font-bold text-gray-800 mb-2">{{ $author->name }}</h1>
                <p class="text-gray-600 mb-4">{{ $author->bio ?? 'No bio available' }}</p>

                @if($author->born_at)
                    <p><span class="font-semibold">Born:</span> {{ $author->born_at->format('F j, Y') }}</p>
                @endif

                @if($author->died_at)
                    <p><span class="font-semibold">Died:</span> {{ $author->died_at->format('F j, Y') }}</p>
                @endif

                @if($author->age)
                    <p><span class="font-semibold">Age:</span> {{ $author->age }} years</p>
                @endif
            </div>
        </div>

        <!-- About -->
        @if($author->about)
            <div class="p-6 border-t">
                <h2 class="text-xl font-semibold mb-2">About</h2>
                <p class="text-gray-700">{{ $author->about }}</p>
            </div>
        @endif

        <!-- Author Books -->
        @if($author->books->count())
            <div class="p-6 border-t">
                <h2 class="text-xl font-semibold mb-4">Books by {{ $author->name }}</h2>
                <ul class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    @foreach($author->books as $book)
                        <div class="bg-white shadow-md rounded-lg overflow-hidden transition-transform duration-300 hover:shadow-xl hover:-translate-y-1 h-full flex flex-col">
    {{-- Book Cover with Link --}}
    <div class="relative">
        <a href="{{ route('books.show', $book->id) }}">
            <img
                src="{{ asset('storage/'.$book->cover) }}"
                alt="{{ $book->title }}"
                class="w-full aspect-[2/3] object-cover shadow-md" {{-- Full width, 2:3 ratio --}}
                onerror="this.src='{{ asset('images/placeholder-book.jpg') }}'"
            >
        </a>

        {{-- Featured Badge (optional) --}}
        @if(isset($featured) && $featured)
            <span class="absolute top-2 left-2 bg-red-500 text-white text-xs px-2 py-1 rounded">
                Featured
            </span>
        @endif
    </div>

    {{-- Book Details --}}
    <div class="p-4 flex-1 flex flex-col">
        <a href="{{ route('books.show', $book->id) }}" class="hover:text-blue-600">
            <h3 class="text-lg font-semibold line-clamp-1">{{ $book->title }}</h3>
        </a>
        <p class="text-sm text-gray-600 mt-1">{{ $book->author->name ?? 'Unknown Author' }}</p>

        {{-- Categories --}}
        @if($book->categories->count() > 0)
            <div class="mt-2 flex flex-wrap gap-1">
                @foreach($book->categories->take(2) as $category)
                    <span class="bg-gray-100 text-gray-800 text-xs px-2 py-1 rounded">
                        {{ $category->category }}
                    </span>
                @endforeach
                @if($book->categories->count() > 2)
                    <span class="bg-gray-100 text-gray-800 text-xs px-2 py-1 rounded">
                        +{{ $book->categories->count() - 2 }} more
                    </span>
                @endif
            </div>
        @endif
    </div>
</div>

                    @endforeach
                </ul>
            </div>
        @endif
    </div>
</div>
@endsection
