@extends('layouts.user')

@section('content')
    <h1 class="text-2xl font-bold mb-6">My Training Sessions</h1>

    @if($sessions->count())
        <div class="overflow-x-auto bg-white shadow rounded-lg">
            <table class="min-w-full divide-y divide-gray-200 text-sm">
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
                        <tr>
                            <td class="px-6 py-3">
                                {{ $session->book?->title ?? 'N/A' }}
                            </td>
                            <td class="px-6 py-3">
                                {{ $session->duration }} sec
                            </td>
                            <td class="px-6 py-3">
                                {{ $session->accuracy }}%
                            </td>
                            <td class="px-6 py-3">
                                {{ $session->words_trained }}
                            </td>
                            <td class="px-6 py-3">
                                {{ $session->started_at?->format('d M Y H:i') ?? '-' }}
                            </td>
                            <td class="px-6 py-3">
                                {{ $session->ended_at?->format('d M Y H:i') ?? '-' }}
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div class="mt-4">
            {{ $sessions->links() }}
        </div>
    @else
        <p class="text-gray-500">You don’t have any training sessions yet.</p>
    @endif
@endsection
