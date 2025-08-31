@extends('layouts.user')

@section('content')
<div class="max-w-3xl mx-auto bg-white shadow-lg rounded-2xl p-8 mt-6">
    <h1 class="text-3xl font-bold text-gray-800 mb-6">📄 Submit a Publishing Request</h1>

    @if(session('success'))
        <div class="mb-4 px-4 py-3 rounded-lg bg-green-100 text-green-800">
            {{ session('success') }}
        </div>
    @endif

    @if ($errors->any())
        <div class="mb-4 px-4 py-3 rounded-lg bg-red-100 text-red-800">
            <ul class="list-disc list-inside">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    @if(auth()->user()->publishingRequests()->exists())
        @php
            $request = auth()->user()->publishingRequests()->latest()->first();
        @endphp

        <div class="p-6 rounded-lg bg-green-50 border border-gray-200">
    <h2 class="text-xl font-semibold text-gray-800 mb-2">✅ You have already submitted a publishing request</h2>
    <p class="text-gray-700 mb-4">{{ $request->description }}</p>

    @if($request->image)
        <img src="{{ asset('storage/' . $request->image) }}" alt="Request Image" class="rounded-lg w-48 h-48 object-cover mb-4">
    @endif

    <p class="text-sm text-gray-500">
        Submitted on {{ $request->created_at->format('M d, Y') }}
        <span class="ml-2">
            @if($request->is_accepted)
                <span class="px-2 py-1 rounded-full bg-green-100 text-green-800 text-xs font-medium">✅ Accepted</span>
            @else
                <span class="px-2 py-1 rounded-full bg-orange-500 text-white text-xs font-medium">Proccessing</span>
            @endif
        </span>
    </p>
</div>

    @else
        <form action="{{ route('publishing-request.store') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
            @csrf

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Description</label>
                <textarea name="description" rows="4"
                          class="mt-1 block w-full border-gray-300 rounded-lg shadow-sm focus:ring-indigo-500 focus:border-indigo-500"
                          placeholder="Describe your publishing request...">{{ old('description') }}</textarea>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Upload Image</label>
                <input type="file" name="image"
                       class="mt-1 block w-full border-gray-300 rounded-lg shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
            </div>

            <div class="flex justify-end">
                <button type="submit" class="px-6 py-2 rounded-lg bg-indigo-600 text-white font-medium hover:bg-indigo-700 transition">
                    Submit Request
                </button>
            </div>
        </form>
    @endif
</div>
@endsection
