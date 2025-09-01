<!-- resources/views/components/hero.blade.php -->

<section class="relative bg-gradient-to-r from-indigo-600 via-purple-600 to-pink-500 text-white rounded-xl shadow-lg overflow-hidden">
    <!-- 🔹 Background image (optional) -->
    <div class="absolute inset-0">
        <img src="{{ asset('images/books-hero.jpg') }}"
             
             class="w-full h-full object-cover opacity-30">
    </div>
    <div class="absolute inset-0 bg-black/40"></div>

    <div class="relative container mx-auto px-6 py-20 text-center">
        <!-- 🔹 Title & Subtitle -->
        <h1 class="text-4xl md:text-6xl font-extrabold mb-4 leading-tight">
            Discover & Read Amazing Books
        </h1>
        <p class="text-lg md:text-xl mb-8 opacity-90 max-w-2xl mx-auto">
            Explore featured books, trending categories, and find your next read on
            <span class="font-semibold">LiTrain</span>.
            Train your writing skills and listen to a world of audiobooks 🎧
        </p>

        <!-- 🔹 CTA Buttons -->
        <div class="flex flex-wrap justify-center gap-4 mb-10">
            <a href="{{ route('home.index') }}"
               class="px-6 py-3 bg-yellow-400 text-gray-900 rounded-full font-semibold hover:bg-yellow-300 transition shadow-lg">
                📖 Start Reading
            </a>
            <a href="{{ url('/audiobooks') }}"
               class="px-6 py-3 bg-white/20 border border-white rounded-full font-semibold hover:bg-white/30 transition shadow-lg">
                🎧 Listen Now
            </a>
            <a href="{{ route('authors.index') }}"
               class="px-6 py-3 bg-indigo-700 rounded-full font-semibold hover:bg-indigo-800 transition shadow-lg">
                ✍️ Authors
            </a>
        </div>

        <!-- 🔹 Live Search Bar -->
        <div class="relative max-w-2xl mx-auto">
            <div class="bg-white rounded-full shadow-lg overflow-hidden flex items-center">
                <input id="search-input" type="text"
                       placeholder="Search for books, authors, or publishers..."
                       class="flex-1 px-4 py-3 text-gray-700 focus:outline-none border-0" />
                <div class="px-4 text-gray-400">
                    <i class="fas fa-search"></i>
                </div>
            </div>

            <!-- 🔹 Results Dropdown -->
            <div id="search-results"
                 class="relative left-0 right-0 mt-2 w-full bg-white border border-gray-200 rounded-2xl shadow-lg overflow-hidden hidden z-50">
                <ul class="divide-y divide-gray-100" id="results-list">
                    {{-- Example Item --}}
                    <li>
                        <a href="#" class="flex items-center gap-4 px-4 py-3 hover:bg-indigo-50 transition">
                            <div>
                                <h4 class="text-gray-800 font-medium">Book Title</h4>
                                <p class="text-sm text-gray-500">Small description or author</p>
                            </div>
                        </a>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</section>
