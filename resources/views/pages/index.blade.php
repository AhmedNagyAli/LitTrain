@extends('layouts.app')

@section('title', 'LiTrain')
@section('meta_description', 'Read the latest posts on various topics.')
@push('styles')
@endpush

@section('content')

{{-- 🔹 Modern Hero Section --}}
<div class="relative bg-gradient-to-r from-indigo-600 via-purple-600 to-pink-500 text-white rounded-xl shadow-lg overflow-hidden">
    <div class="container mx-auto px-6 py-16 text-center">
        <h1 class="text-4xl md:text-5xl font-extrabold mb-4">Discover & Read Amazing Books</h1>
        <p class="text-lg md:text-xl mb-8 opacity-90">Explore featured books, trending categories, and find your next read on <span class="font-semibold">LiTrain</span>.</p>

        {{-- Live Search Bar --}}
<div class="relative max-w-2xl mx-auto">
    <div class="bg-white rounded-full shadow-lg overflow-hidden flex items-center">
        <input id="search-input" type="text" placeholder="Search for books, authors, or publishers..."
               class="flex-1 px-4 py-3 text-gray-700 focus:outline-none border-0" />
        <div class="px-4 text-gray-400">
            <i class="fas fa-search"></i>
        </div>
    </div>

    {{-- 🔹 Results Dropdown --}}
    <div id="search-results"
         class="relative left-0 mt-2 w-full bg-white border border-gray-200 rounded-2xl shadow-lg overflow-hidden hidden z-50">
        <ul class="divide-y divide-gray-100" id="results-list">
            {{-- Example Item --}}
            <li>
                <a href="#" class="flex items-center gap-4 px-4 py-3 hover:bg-indigo-50 transition">
                    {{-- <img src="https://via.placeholder.com/40" class="w-10 h-10 rounded-full object-cover shadow" alt="avatar"> --}}
                    <div>
                        <h4 class="text-gray-800 font-medium">Post Title</h4>
                        <p class="text-sm text-gray-500">Small description or category</p>
                    </div>
                </a>
            </li>
        </ul>
    </div>

</div>

    </div>
</div>


<div class="container mx-auto px-4 py-6">

    {{-- 🔹 Featured Books Carousel --}}
    @if(!$selectedLanguage && $featuredBooks && $featuredBooks->count())
        <div class="relative mt-10">
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
    {{-- 🔹 Audible Books Section --}}
@if ($audibleBooks && $audibleBooks->count())
    <div class="relative mt-12">
        <h2 class="text-2xl font-bold text-gray-900 mb-4">🎧 Audible Books</h2>

        <!-- Carousel Wrapper -->
        <div id="audible-carousel" class="flex overflow-x-auto space-x-4 pb-4 scroll-smooth scrollbar-hide">
            @foreach ($audibleBooks as $book)
                <div class="min-w-[220px] max-w-[220px] flex-shrink-0 transform transition duration-300 hover:scale-105">
                    @component('components.book-card', ['book' => $book, 'audible' => true])
                    @endcomponent
                </div>
            @endforeach
        </div>

        <!-- Left & Right Arrows -->
        <button onclick="scrollCarousel('left', 'audible-carousel')"
            class="absolute left-0 top-1/2 -translate-y-1/2 bg-white shadow-md border border-gray-200 text-gray-700 hover:bg-gray-100 p-3 rounded-full">
            ‹
        </button>
        <button onclick="scrollCarousel('right', 'audible-carousel')"
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

    {{-- 🔹 Modern Pagination --}}
    @if ($books->hasPages())
    <div class="mt-6 flex justify-center space-x-2">
        {{-- Previous Page Link --}}
        @if ($books->onFirstPage())
            <span class="px-3 py-1 rounded-lg bg-gray-200 text-gray-500 cursor-not-allowed">&laquo;</span>
        @else
            <a href="{{ $books->previousPageUrl() }}" class="px-3 py-1 rounded-lg bg-white border border-gray-300 text-gray-700 hover:bg-indigo-600 hover:text-white transition">
                &laquo;
            </a>
        @endif

        {{-- Pagination Elements --}}
        @foreach ($books->getUrlRange(1, $books->lastPage()) as $page => $url)
            @if ($page == $books->currentPage())
                <span class="px-3 py-1 rounded-lg bg-indigo-600 text-white font-medium">{{ $page }}</span>
            @else
                <a href="{{ $url }}" class="px-3 py-1 rounded-lg bg-white border border-gray-300 text-gray-700 hover:bg-indigo-600 hover:text-white transition">{{ $page }}</a>
            @endif
        @endforeach

        {{-- Next Page Link --}}
        @if ($books->hasMorePages())
            <a href="{{ $books->nextPageUrl() }}" class="px-3 py-1 rounded-lg bg-white border border-gray-300 text-gray-700 hover:bg-indigo-600 hover:text-white transition">&raquo;</a>
        @else
            <span class="px-3 py-1 rounded-lg bg-gray-200 text-gray-500 cursor-not-allowed">&raquo;</span>
        @endif
    </div>
    @endif

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
    function scrollCarousel(direction, carouselId = 'featured-carousel') {
        const carousel = document.getElementById(carouselId);
        const scrollAmount = 260; // px per step
        if (direction === 'left') {
            carousel.scrollBy({ left: -scrollAmount, behavior: 'smooth' });
        } else {
            carousel.scrollBy({ left: scrollAmount, behavior: 'smooth' });
        }
    }
    document.getElementById('search-input').addEventListener('keyup', function() {
    let query = this.value;

    if (query.length < 2) {
        document.getElementById('search-results').classList.add('hidden');
        return;
    }

    fetch(`{{ route('search') }}?q=${encodeURIComponent(query)}`)
        .then(response => response.json())
        .then(data => {
            let results = '';

            if (data.books.length) {
                results += `<div class="p-2 font-semibold text-gray-600">📚 Books</div>`;
                data.books.forEach(book => {
                    results += `
                        <a href="/books/${book.id}" class="flex items-center px-4 py-2 hover:bg-gray-50">
                            <img src="${book.cover ?? '/images/default-book.png'}" class="w-10 h-10 rounded mr-3 object-cover">
                            <span class="text-gray-800">${book.title}</span>
                        </a>`;
                });
            }

            if (data.authors.length) {
                results += `<div class="p-2 font-semibold text-gray-600">✍️ Authors</div>`;
                data.authors.forEach(author => {
                    results += `
                        <a href="/authors/${author.id}" class="flex items-center px-4 py-2 hover:bg-gray-50">
                            <img src="${author.avatar ?? '/images/default-avatar.png'}" class="w-10 h-10 rounded-full mr-3 object-cover">
                            <span class="text-gray-800">${author.name}</span>
                        </a>`;
                });
            }

            if (data.publishers.length) {
                results += `<div class="p-2 font-semibold text-gray-600">🏢 Publishers</div>`;
                data.publishers.forEach(publisher => {
                    results += `
                        <a href="/publishers/${publisher.id}" class="flex items-center px-4 py-2 hover:bg-gray-50">
                            <img src="${publisher.logo ?? '/images/default-publisher.png'}" class="w-10 h-10 rounded mr-3 object-cover">
                            <span class="text-gray-800">${publisher.name}</span>
                        </a>`;
                });
            }

            document.getElementById('search-results').innerHTML = results || `<div class="p-4 text-gray-500">No results found</div>`;
            document.getElementById('search-results').classList.remove('hidden');
        })
        .catch(err => {
            console.error("Search error:", err);
        });
});


</script>
@endpush
