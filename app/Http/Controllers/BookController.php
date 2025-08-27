<?php

namespace App\Http\Controllers;

use App\Models\Book;
use Illuminate\Http\Request;
use Smalot\PdfParser\Parser;
class BookController extends Controller
{
      public function show($id)
    {
        $book = Book::findOrFail($id);
        if(!$book){
            return redirect()->back()->withErrors('failed to oad the book');
        }

        $categoryIds = $book->categories->pluck('id');

        $relatedBooks = Book::whereHas('categories', function($query) use ($categoryIds) {
            $query->whereIn('categories.id', $categoryIds);
        })
        ->where('id', '!=', $book->id)
        ->with(['author', 'categories'])
        ->paginate(4);


        return view('pages.book.show', compact('book', 'relatedBooks'));
    }

    public function read($id)
{
    $book = Book::findOrFail($id);

    if (!$book->file) {
        abort(404, 'No PDF available for this book.');
    }

    return view('pages.book.show', compact('book'));
}

public function view($id)
{
    $book = Book::findOrFail($id);

    // Path to stored file (make sure $book->file stores relative path like 'books/myfile.pdf')
    $path = public_path("storage/{$book->file}");

    if (!file_exists($path)) {
        abort(404, 'File not found.');
    }

    // Return as inline PDF (browser opens it inside iframe)
    return response()->file($path, [
        'Content-Type' => 'application/pdf',
        'Content-Disposition' => 'inline; filename="'.basename($path).'"'
    ]);

}


public function train(Book $book, Request $request)
{
    // file path — adjust if you store files elsewhere
    $filePath = public_path('storage/' . $book->file);

    if (!file_exists($filePath)) {
        return response()->json(['error' => 'file_not_found'], 404);
    }

    try {
        $parser = new Parser();
        $pdf = $parser->parseFile($filePath);
        $pages = $pdf->getPages();

        if (count($pages) > 0) {
            $text = trim($pages[0]->getText());
        } else {
            // fallback: whole text
            $text = trim($pdf->getText());
        }

        // normalize whitespace
        $text = preg_replace('/\s+/', ' ', $text);

        return response()->json(['text' => $text]);
    } catch (\Exception $e) {
        // return helpful error for debugging (remove message in production)
        return response()->json(['error' => 'parse_error', 'message' => $e->getMessage()], 500);
    }
}



}
