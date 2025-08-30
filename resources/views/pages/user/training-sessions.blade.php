@extends('layouts.user')

@section('content')
    <h1 class="text-3xl font-bold mb-6 text-gray-800">📖 My Training Sessions</h1>

    @if($sessions->count())
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200 text-sm bg-white rounded-lg shadow-lg">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left font-semibold text-gray-600">Book</th>
                        <th class="px-6 py-3 text-left font-semibold text-gray-600">Duration</th>
                        <th class="px-6 py-3 text-left font-semibold text-gray-600">Accuracy</th>
                        <th class="px-6 py-3 text-left font-semibold text-gray-600">Words Trained</th>
                        <th class="px-6 py-3 text-left font-semibold text-gray-600">Started</th>
                        <th class="px-6 py-3 text-left font-semibold text-gray-600">Ended</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @foreach($sessions as $session)
                        <tr class="hover:bg-indigo-50 transition-colors">
                            <td class="px-6 py-4 font-medium text-gray-800">
                                {{ $session->book?->title ?? 'N/A' }}
                            </td>
                            <td class="px-6 py-4 text-gray-700">
                                {{ $session->duration }} sec
                            </td>
                            <td class="px-6 py-4 text-gray-700">
                                <span class="inline-block px-2 py-1 rounded-full text-xs font-semibold
                                    {{ $session->accuracy >= 90 ? 'bg-green-100 text-green-800' : ($session->accuracy >= 70 ? 'bg-yellow-100 text-yellow-800' : 'bg-red-100 text-red-800') }}">
                                    {{ $session->accuracy }}%
                                </span>
                            </td>
                            <td class="px-6 py-4 text-gray-700">
                                {{ $session->words_trained }}
                            </td>
                            <td class="px-6 py-4 text-gray-600">
                                {{ $session->started_at?->format('d M Y H:i') ?? '-' }}
                            </td>
                            <td class="px-6 py-4 text-gray-600">
                                {{ $session->ended_at?->format('d M Y H:i') ?? '-' }}
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        {{-- Modern Pagination --}}
        @if ($sessions->hasPages())
        <div class="mt-6 flex justify-center space-x-2">
            {{-- Previous Page --}}
            @if ($sessions->onFirstPage())
                <span class="px-3 py-1 rounded-lg bg-gray-200 text-gray-500 cursor-not-allowed">&laquo;</span>
            @else
                <a href="{{ $sessions->previousPageUrl() }}" class="px-3 py-1 rounded-lg bg-white border border-gray-300 text-gray-700 hover:bg-indigo-600 hover:text-white transition">&laquo;</a>
            @endif

            {{-- Page Numbers --}}
            @foreach ($sessions->getUrlRange(1, $sessions->lastPage()) as $page => $url)
                @if ($page == $sessions->currentPage())
                    <span class="px-3 py-1 rounded-lg bg-indigo-600 text-white font-medium">{{ $page }}</span>
                @else
                    <a href="{{ $url }}" class="px-3 py-1 rounded-lg bg-white border border-gray-300 text-gray-700 hover:bg-indigo-600 hover:text-white transition">{{ $page }}</a>
                @endif
            @endforeach

            {{-- Next Page --}}
            @if ($sessions->hasMorePages())
                <a href="{{ $sessions->nextPageUrl() }}" class="px-3 py-1 rounded-lg bg-white border border-gray-300 text-gray-700 hover:bg-indigo-600 hover:text-white transition">&raquo;</a>
            @else
                <span class="px-3 py-1 rounded-lg bg-gray-200 text-gray-500 cursor-not-allowed">&raquo;</span>
            @endif
        </div>
        @endif

    @else
        <p class="text-gray-500 mt-4">You don’t have any training sessions yet.</p>
    @endif
@endsection
