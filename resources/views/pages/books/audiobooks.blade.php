@extends('layouts.app')

@section('title', 'LiTrain')
@section('meta_description', 'Read the latest posts on various topics.')
@push('styles')
@endpush

@section('content')
<div class="container mx-auto px-4 py-6">


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

@endpush
