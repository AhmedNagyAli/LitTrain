@extends('layouts.app')

@section('title', 'LiTrain')
@section('meta_description', 'Read the latest posts on various topics.')
@push('styles')
@endpush
@section('content')
<div class="container mx-auto px-4 py-6">

    {{-- 🔹 Featured Books Carousel --}}
    <div class="relative">
        <div id="featured-carousel" class="flex overflow-x-auto space-x-4 pb-4 scrollbar-hide">
            @foreach ($featuredBooks as $book)
                <div class="min-w-[200px] flex-shrink-0">
                    <div class="bg-white shadow-md rounded-lg p-4">
                        <img src="{{ $book->image }}" alt="{{ $book->title }}" class="h-40 w-full object-cover rounded">
                        <h3 class="mt-2 text-lg font-semibold">{{ $book->title }}</h3>
                        <p class="text-sm text-gray-600">{{ $book->author->name }}</p>
                    </div>
                </div>
            @endforeach
        </div>

        {{-- Left & Right Arrows --}}
        <button onclick="scrollCarousel('left')" class="absolute left-0 top-1/2 -translate-y-1/2 bg-gray-800 text-white p-2 rounded-full">‹</button>
        <button onclick="scrollCarousel('right')" class="absolute right-0 top-1/2 -translate-y-1/2 bg-gray-800 text-white p-2 rounded-full">›</button>
    </div>

    {{-- 🔹 Categories Tabs --}}
    <div class="flex space-x-4 mt-6 border-b pb-2 overflow-x-auto scrollbar-hide">
        <a href="{{ route('home.index', ['category' => 'latest']) }}"
           class="px-4 py-2 rounded-lg {{ $selectedCategory === 'latest' ? 'bg-blue-600 text-white' : 'bg-gray-200 text-gray-700' }}">
           Latest
        </a>
        @foreach ($categories as $category)
            <a href="{{ route('home.index', ['category' => $category->id]) }}"
               class="px-4 py-2 rounded-lg {{ $selectedCategory == $category->id ? 'bg-blue-600 text-white' : 'bg-gray-200 text-gray-700' }}">
               {{ $category->category }}
            </a>
        @endforeach
    </div>

    {{-- 🔹 Books Grid --}}
    <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6 mt-6">
        @foreach ($books as $book)
            <div class="bg-white shadow-md rounded-lg p-4">
                <img src="{{ $book->image }}" alt="{{ $book->title }}" class="h-40 w-full object-cover rounded">
                <h3 class="mt-2 text-lg font-semibold">{{ $book->title }}</h3>
                <p class="text-sm text-gray-600">{{ $book->author->name }}</p>
            </div>
        @endforeach
    </div>

    {{-- Pagination --}}
    <div class="mt-6">
        {{ $books->links() }}
    </div>
</div>

@endsection


@push('scripts')
{{-- 🔹 JS for carousel scroll --}}
<script>
    function scrollCarousel(direction) {
        const carousel = document.getElementById('featured-carousel');
        const scrollAmount = 220; // px per step
        if (direction === 'left') {
            carousel.scrollBy({ left: -scrollAmount, behavior: 'smooth' });
        } else {
            carousel.scrollBy({ left: scrollAmount, behavior: 'smooth' });
        }
    }
</script>
    {{-- <script src="https://cdn.jsdelivr.net/npm/swiper@10/swiper-bundle.min.js"></script> --}}
    {{-- <script src="{{ asset('js/home.js') }}"></script> --}}
    <script>
</script>
@endpush




