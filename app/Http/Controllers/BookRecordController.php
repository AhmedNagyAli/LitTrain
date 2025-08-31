<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\BookRecord;
use Illuminate\Http\Request;

class BookRecordController extends Controller
{
    public function create(Book $book)
    {
        return view('pages.books.records.create', compact('book'));
    }

    public function store(Request $request, Book $book)
    {
        $request->validate([
            'record_file' => 'required|mimes:mp3,wav,m4a|max:51200', // max 50MB
            'duration'    => 'nullable|numeric',
            'language_id' => 'nullable|exists:languages,id',
        ]);

        $path = $request->file('record_file')->store("book/records", 'public');

        BookRecord::create([
            'book_id'     => $book->id,
            'user_id'     => auth()->id(),
            'record_file' => $path,
            'duration'    => $request->duration,
            'language_id' => $request->language_id ?? $book->language_id,
        ]);

        return redirect()->route('books.show', $book->id)
                         ->with('success', '✅ Your record has been uploaded successfully.');
    }
}
