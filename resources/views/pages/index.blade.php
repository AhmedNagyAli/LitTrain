@extends('layouts.app')

@section('title', 'LiTrain')
@section('meta_description', 'Read the latest posts on various topics.')
@push('styles')
@endpush

@section('content')
@include('layouts.partials.header')
<div class="container mx-auto px-4 py-6">

    {{-- 🔹 Featured Books Carousel --}}
    @if(!$selectedLanguage && $featuredBooks && $featuredBooks->count())
        <div class="relative">
            <h2 class="text-2xl font-bold text-gray-900 mb-4">📚 Featured Books</h2>

            <!-- Carousel Wrapper -->
            <div id="featured-carousel" class="flex overflow-x-auto space-x-4 pb-4 scroll-smooth scrollbar-hide">
                @foreach ($featuredBooks as $book)
                    <div class="min-w-[220px] max-w-[220px] flex-shrink-0 transform transition duration-300 hover:scale-105">
                        @component('components.book-card', ['book' => $book, 'featured' => true])
                        @endcomponent
                    </div>
                @endforeach
            </div>

            <!-- Left & Right Arrows -->
            <button onclick="scrollCarousel('left')"
                class="absolute left-0 top-1/2 -translate-y-1/2 bg-white shadow-md border border-gray-200 text-gray-700 hover:bg-gray-100 p-3 rounded-full">
                ‹
            </button>
            <button onclick="scrollCarousel('right')"
                class="absolute right-0 top-1/2 -translate-y-1/2 bg-white shadow-md border border-gray-200 text-gray-700 hover:bg-gray-100 p-3 rounded-full">
                ›
            </button>
        </div>
    @endif

    {{-- 🔹 Categories Tabs --}}
<div class="flex space-x-6 mt-8 border-b border-gray-300 pb-2 overflow-x-auto scrollbar-hide">
    <a href="{{ route('home.index', ['category' => 'latest']) }}"
       class="whitespace-nowrap pb-2 {{ $selectedCategory === 'latest' ? 'text-indigo-600 border-b-2 border-indigo-600 font-medium' : 'text-gray-600 hover:text-indigo-500 hover:border-b-2 hover:border-indigo-400' }}">
       Latest
    </a>

    @foreach ($categories as $category)
        <a href="{{ route('home.index', ['category' => $category->id]) }}"
           class="whitespace-nowrap pb-2 {{ $selectedCategory == $category->id ? 'text-indigo-600 border-b-2 border-indigo-600 font-medium' : 'text-gray-600 hover:text-indigo-500 hover:border-b-2 hover:border-indigo-400' }}">
           {{ $category->category }}
           <span class="text-sm text-gray-400">({{ $category->books_count }})</span>
        </a>
    @endforeach
</div>


    {{-- 🔹 Books Grid --}}
    <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6 mt-6">
        @foreach ($books as $book)
            @component('components.book-card', ['book' => $book])
            @endcomponent
        @endforeach
    </div>

    {{-- Pagination --}}
    <div class="mt-6">
        {{ $books->links() }}
    </div>
</div>
@endsection

@push('scripts')
<script>
    function scrollCarousel(direction) {
        const carousel = document.getElementById('featured-carousel');
        const scrollAmount = 260; // px per step
        if (direction === 'left') {
            carousel.scrollBy({ left: -scrollAmount, behavior: 'smooth' });
        } else {
            carousel.scrollBy({ left: scrollAmount, behavior: 'smooth' });
        }
    }
</script>
@endpush
