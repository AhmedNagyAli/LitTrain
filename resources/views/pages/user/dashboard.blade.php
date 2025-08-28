@extends('layouts.user')

@section('content')
    <h1 class="text-2xl font-bold mb-4">Welcome, {{ Auth::user()->name }}</h1>
    <div class="grid grid-cols-3 gap-4">
        <div class="bg-white p-6 rounded shadow">
            <h2 class="text-lg font-semibold">Saved Books</h2>
        </div>
        <div class="bg-white p-6 rounded shadow">
            <h2 class="text-lg font-semibold">Training Sessions</h2>
            <p>{{ Auth::user()->trainingSessions()->count() }}</p>
        </div>
        <div class="bg-white p-6 rounded shadow">
            <h2 class="text-lg font-semibold">Writing Rank</h2>
            <p>{{ optional(Auth::user()->writingRank)->rank ?? 'N/A' }}</p>
        </div>
    </div>
@endsection
