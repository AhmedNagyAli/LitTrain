<?php

namespace App\Http\Controllers;

use App\Models\PublishingRequest;
use Illuminate\Http\Request;

class PublishingRequestController extends Controller
{
    public function create()
    {
        return view('publishers.publishing-request.create');
    }

    // Store new publishing request
    public function store(Request $request)
    {
        $request->validate([
            'description' => 'required|string|max:1000',
            'image' => 'required|image|max:2048',
        ]);

        $imagePath = $request->file('image')->store('publishing-requests', 'public');

        PublishingRequest::create([
            'user_id' => auth()->user()->id,
            'description' => $request->description,
            'image' => $imagePath,
            'is_accepted' => 0, // default pending
        ]);

        return redirect()->route('publishing.request.create')->with('success', 'Publishing request submitted successfully!');
    }
}
