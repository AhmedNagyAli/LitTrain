<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\TrainingSession;
use Illuminate\Http\Request;

class TrainingSessionController extends Controller
{
    public function store(Request $request, Book $book)
{
    $this->validate($request, [
        'duration' => 'required|integer',
        'accuracy' => 'required|numeric',
        'rank' => 'required',
        'words_trained' => 'required|integer',
        'started_at' => 'required|date',
        'ended_at' => 'required|date',
    ]);

    $session = TrainingSession::create([
        'user_id' => auth()->id(),
        'book_id' => $book->id,
        'rank' => $request->rank,
        'duration' => $request->duration,
        'accuracy' => $request->accuracy,
        'words_trained' => $request->words_trained,
        'started_at' => $request->started_at,
        'ended_at' => $request->ended_at,
    ]);

    return response()->json($session);
}
}
