<?php

namespace App\Http\Controllers;

use App\Models\Author;
use Illuminate\Http\Request;

class AuthorController extends Controller
{
    public function index()
    {
        $authors = Author::paginate();
        return view('authors.index', compact('authors'));
    }

    public function show(Author $author)
    {
        return view('authors.show', compact('author'));
    }
}
