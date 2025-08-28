<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\Category;
use App\Models\Language;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index(Request $request)
{
    //dd(PHP_VERSION);

    //dd(ini_get('upload_max_filesize'), ini_get('post_max_size'));

    $selectedCategory = $request->get('category', 'latest');
    $selectedLanguage = $request->get('language_id');
    $languages = Language::all();

    // Featured books (only if no language filter is applied)
    $featuredBooks = null;
    if (!$selectedLanguage) {
        $featuredBooks = Book::with('author', 'publisher', 'language')
            ->where('status', 1)
            ->latest()
            ->paginate(7);
    }

    // All categories
    // $categories = Category::withCount('books')
    // ->orderByDesc('books_count') // highest number of books first
    // ->paginate(7);
     $categories = Category::withCount('books')
        ->orderBy('books_count', 'desc')
        ->paginate(7);

    // Books query
    $booksQuery = Book::with('author', 'publisher', 'language')
        ->where('status', 1);

    // Apply language filter if selected
    if ($selectedLanguage) {
        $booksQuery->where('language_id', $selectedLanguage);
    }

    // Apply category filter or latest
    if ($selectedCategory !== 'latest') {
        $booksQuery->whereHas('categories', function ($query) use ($selectedCategory) {
            $query->where('categories.id', $selectedCategory);
        });
    }

    $books = $booksQuery->latest()->paginate(12);

    return view('pages.index', compact(
        'featuredBooks',
        'categories',
        'books',
        'selectedCategory',
        'languages',
        'selectedLanguage'
    ));
}


}
