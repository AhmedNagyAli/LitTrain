<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\Category;
use Illuminate\Http\Request;

class HomeController extends Controller
{
     public function index(Request $request)
    {
        $selectedCategory = $request->get('category', 'latest');

        // Top featured books (first 10, or however you want)
        $featuredBooks = Book::with('author', 'publisher', 'language')
        //->where('status','accepted')
        ->latest()->paginate(10);

        // All categories
        $categories = Category::paginate(5);

        // Books by category or latest
        if ($selectedCategory === 'latest') {
            $books = Book::latest()->paginate(12);
        } else {
            $books = Book::whereHas('categories', function ($query) use ($selectedCategory) {
    $query->where('categories.id', $selectedCategory);
})
->latest()
->paginate(12);
        }

        return view('pages.index', compact('featuredBooks', 'categories', 'books', 'selectedCategory'));
    }

}
