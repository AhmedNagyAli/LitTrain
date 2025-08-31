<?php

namespace App\Http\Controllers\Auth;

use App\Enums\UserRole;
use App\Http\Controllers\Controller;
use App\Models\Language;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
class AuthController extends Controller
{
    // Show login page
    public function showLogin()
    {
        return view('auth.login');
    }

    // Handle login
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email'    => 'required|email',
            'password' => 'required|min:6',
        ]);

        if (Auth::attempt($credentials, $request->filled('remember'))) {
            $request->session()->regenerate();
            return redirect()->intended(route('home.index'))->with('success', 'Welcome back!');
        }

        return back()->withErrors(['email' => 'Invalid email or password.']);
    }

    // Show register page
    public function showRegister()
    {
        $languages = Language::orderBy('name')->get();
        return view('auth.register', compact('languages'));
    }

    // Handle registration
    public function register(Request $request)
{
    // First, check what's actually coming in the request
    \Log::debug('Register request data:', $request->all());

    // Check if this is a POST request
    if (!$request->isMethod('post')) {
        return back()->with('debug', 'Request method is: ' . $request->method());
    }

    // Validate the request
    $validated = $request->validate([
        'name'         => 'required|string|max:255',
        'email'        => 'required|string|email|max:255|unique:users,email',
        'phone'        => 'nullable|string|max:20',
        'password'     => 'required|string|min:6|confirmed',
        'language_id'  => 'required|exists:languages,id',
    ]);

    // Create user
    $user = User::create([
        'name'              => $validated['name'],
        'email'             => $validated['email'],
        'phone'             => $validated['phone'] ?? null,
        'password'          => Hash::make($validated['password']),
        'language_id'       => $validated['language_id'],
        'role'              => UserRole::Reader,
        'is_email_verified' => false,
    ]);

    Auth::login($user);

    return redirect()->route('home.index')->with('success', 'Account created successfully!');
}

    // Handle logout
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('home.index')->with('success', 'You have been logged out.');
    }

    public function requestForm()
    {
        return view('auth.forgot-password');
    }

    /**
     * Handle sending reset link email.
     */
    public function sendResetLink(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
        ]);

        // Send reset link
        $status = Password::sendResetLink(
            $request->only('email')
        );

        return $status === Password::RESET_LINK_SENT
            ? back()->with(['success' => __($status)])
            : back()->withErrors(['email' => __($status)]);
    }
}
