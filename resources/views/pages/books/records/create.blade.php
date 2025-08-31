@extends('layouts.app')

@section('title', 'Upload Record | ' . $book->title)

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="max-w-2xl mx-auto bg-white p-6 rounded-lg shadow">
        <h2 class="text-2xl font-bold mb-4">🎤 Upload Record for "{{ $book->title }}"</h2>

        <form action="{{ route('books.records.store', $book->id) }}" method="POST" enctype="multipart/form-data" class="space-y-4">
            @csrf

            <div>
                <label for="record_file" class="block text-sm font-medium text-gray-700">Record File</label>
                <input type="file" name="record_file" id="record_file" accept="audio/*" required
                       class="mt-1 block w-full border border-gray-300 rounded-lg shadow-sm focus:ring focus:ring-orange-500 focus:border-orange-500">
                @error('record_file') <p class="text-red-500 text-sm">{{ $message }}</p> @enderror
            </div>

            <div>
                <label for="duration" class="block text-sm font-medium text-gray-700">Duration (seconds)</label>
                <input type="number" name="duration" id="duration" class="mt-1 block w-full border border-gray-300 rounded-lg shadow-sm">
            </div>

            <div>
                <label for="language_id" class="block text-sm font-medium text-gray-700">Language</label>
                <select name="language_id" id="language_id" class="mt-1 block w-full border border-gray-300 rounded-lg shadow-sm">
                    <option value="">Default (Book Language)</option>
                    @foreach(\App\Models\Language::all() as $language)
                        <option value="{{ $language->id }}">{{ $language->name }}</option>
                    @endforeach
                </select>
            </div>

            <button type="submit" class="bg-orange-600 hover:bg-orange-700 text-white px-6 py-2 rounded-lg font-medium transition">
                Upload Record
            </button>
        </form>
    </div>
</div>
@endsection
