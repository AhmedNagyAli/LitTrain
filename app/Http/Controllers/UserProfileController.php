<?php

namespace App\Http\Controllers;

use App\Models\Language;
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
        $languages = Language::all();
        return view('pages.user.profile', compact('user','languages'));
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
    public function updateName(Request $request)
    {
        $request->validate(['name' => 'required|string|max:255']);
        $request->user()->update(['name' => $request->name]);
        return back()->with('success', 'Name updated successfully.');
    }

    public function updateEmail(Request $request)
    {
        $request->validate(['email' => 'required|email|unique:users,email,' . $request->user()->id]);
        $request->user()->update(['email' => $request->email]);
        return back()->with('success', 'Email updated successfully.');
    }

    public function updatePhone(Request $request)
    {
        $request->validate(['phone' => 'nullable|string|max:20']);
        $request->user()->update(['phone' => $request->phone]);
        return back()->with('success', 'Phone updated successfully.');
    }

    public function updateBio(Request $request)
    {
        $request->validate(['bio' => 'nullable|string|max:500']);
        $request->user()->update(['bio' => $request->bio]);
        return back()->with('success', 'Bio updated successfully.');
    }

    public function updateDob(Request $request)
    {
        $request->validate(['date_of_birth' => 'nullable|date']);
        $request->user()->update(['date_of_birth' => $request->date_of_birth]);
        return back()->with('success', 'Date of birth updated successfully.');
    }

    public function updateLanguage(Request $request)
    {
        $request->validate(['language_id' => 'required|exists:languages,id']);
        $request->user()->update(['language_id' => $request->language_id]);
        return back()->with('success', 'Language updated successfully.');
    }

    public function updateAvatar(Request $request)
    {
        $request->validate(['avatar' => 'required|image|max:2048']);
        $path = $request->file('avatar')->store('storage/avatars', 'public');
        $request->user()->update(['avatar' => $path]);
        return back()->with('success', 'Avatar updated successfully.');
    }
}
