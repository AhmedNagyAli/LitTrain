@extends('layouts.app')

@section('title', $publisher->name . ' | Publisher')

@section('content')
<div class="container mx-auto px-4 py-8">
    {{-- Publisher Profile --}}
    <div class="bg-white rounded-xl shadow p-6 mb-8 flex flex-col md:flex-row items-center md:items-start gap-6">
        <img src="{{ $publisher->avatar ? asset('storage/' . $publisher->avatar) : 'https://via.placeholder.com/150' }}"
             alt="{{ $publisher->name }}"
             class="w-32 h-32 object-cover rounded-full border">
        <div>
            <h1 class="text-3xl font-bold text-gray-800">{{ $publisher->name }}</h1>

            <p class="mt-2 text-gray-600"><strong>Type:</strong> {{ ucfirst($publisher->type) }}</p>

            @if($publisher->born_at)
                <p class="text-gray-600"><strong>Born:</strong> {{ $publisher->born_at }}</p>
            @endif

            @if($publisher->age)
                <p class="text-gray-600"><strong>Age:</strong> {{ $publisher->age }}</p>
            @endif

            @if($publisher->is_verified)
                <span class="inline-block mt-2 px-3 py-1 bg-green-100 text-green-700 text-sm font-semibold rounded-full">
                    Verified Publisher
                </span>
            @endif
        </div>
    </div>

    {{-- About / Bio --}}
    <div class="bg-white rounded-xl shadow p-6 mb-8">
        <h2 class="text-xl font-semibold text-gray-800 mb-4">About</h2>
        <p class="text-gray-600">{{ $publisher->about ?? 'No about information provided.' }}</p>

        @if($publisher->bio)
            <p class="mt-4 text-gray-600 italic">“{{ $publisher->bio }}”</p>
        @endif
    </div>

    {{-- Publisher Books --}}
    <div>
        <h2 class="text-2xl font-bold text-gray-800 mb-6">Books by {{ $publisher->name }}</h2>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            @forelse($publisher->books as $book)
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

            @empty
                <p class="text-gray-600">No books published yet.</p>
            @endforelse
        </div>
    </div>
</div>
@endsection
