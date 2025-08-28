<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserProfileController extends Controller
{
    public function dashboard()
    {
        return view('pages.user.dashboard');
    }

    public function profile()
    {
        $user = Auth::user();
        return view('pages.user.profile', compact('user'));
    }

    public function books()
    {
        $books = Auth::user()->savedBooks;
        return view('pages.user.books', compact('books'));
    }

    public function sessions()
    {

    $sessions = Auth::user()
        ->trainingSessions()
        ->with('book') // eager load book details
        ->latest()
        ->paginate(10);
        return view('pages.user.training-sessions', compact('sessions'));
    }
    public function updateProfile(Request $request)
{
    $user = Auth::user();

    $field = $request->input('field');
    $value = $request->input('value');

    // Validation rules per field
    $rules = [
        'name' => 'required|string|max:255',
        'email' => 'required|email|unique:users,email,'.$user->id,
        'phone' => 'nullable|string|max:20',
        'bio' => 'nullable|string|max:1000',
        'password' => 'nullable|min:6',
        'avatar' => 'nullable|url', // or file upload if you want
        'date_of_birth' => 'nullable|date',
        'language_id' => 'nullable|exists:languages,id',
    ];

    $request->validate([
        'field' => 'required|string|in:'.implode(',', array_keys($rules)),
        'value' => $rules[$field] ?? 'nullable',
    ]);

    // Special handling for password
    if ($field === 'password') {
        $user->password = bcrypt($value);
    } else {
        $user->$field = $value;
    }

    $user->save();

    return response()->json(['success' => true, 'message' => ucfirst($field).' updated successfully']);
}
}
