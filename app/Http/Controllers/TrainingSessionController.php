<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\TrainingSession;
use Illuminate\Http\Request;

class TrainingSessionController extends Controller
{
    public function store(Request $request, Book $book)
    {
        try{
            $request->validate([
        'duration' => 'required|integer',
        'accuracy' => 'required|numeric',
        'words_trained' => 'required|integer',
        'started_at' => 'required|date',
        'ended_at' => 'required|date',
        'rank' => 'required|string',
    ]);

    // 🔹 Map rank string to integer
    $rankMap = [
        'Beginner' => 1,
        'Intermediate' => 2,
        'Advanced' => 3,
    ];

        $session = TrainingSession::create([
            'user_id' => auth()->id(),
            'book_id' => $book->id,
            'rank' => $rankMap[$request->rank] ?? 0, // fallback 0 if invalid
            'duration' => $request->duration,
            'accuracy' => $request->accuracy,
            'words_trained' => $request->words_trained,
            'started_at' => $request->started_at,
            'ended_at' => $request->ended_at,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Training session saved successfully!',
            'data' => $session,
        ]);

        } catch (\Throwable $e) {
        return response()->json([
            'success' => false,
            'message' => $e->getMessage(),
        ], 500);
    }

    }

}
