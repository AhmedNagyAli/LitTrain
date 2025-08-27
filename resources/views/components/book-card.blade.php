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
