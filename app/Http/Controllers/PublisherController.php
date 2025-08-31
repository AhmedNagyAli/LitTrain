<?php

namespace App\Http\Controllers;

use App\Models\Publisher;
use Illuminate\Http\Request;

class PublisherController extends Controller
{
    public function index()
    {
        $publishers = Publisher::paginate();
        return view('publishers.index', compact('publishers'));
    }

    public function show(Publisher $publisher)
    {
        return view('publishers.show', compact('publisher'));
    }
}
