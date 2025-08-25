<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
class AuthController extends Controller
{
    /**
     * Show login form.
     */
    public function showLogin()
    {
        return view('auth.login');
    }

    /**
     * Handle login.
     */
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email'    => 'required|email',
            'password' => 'required|min:6',
        ]);

        if (Auth::attempt($credentials, $request->filled('remember'))) {
            $user = Auth::user();

            // if (!$user->is_email_verified) {
            //     Auth::logout();
            //     return back()->withErrors(['email' => 'Please verify your email before logging in.']);
            // }

            return redirect()->route('dashboard')->with('success', 'Welcome back!');
        }

        return back()->withErrors(['email' => 'Invalid credentials']);
    }

    /**
     * Show registration form.
     */
    public function showRegister()
    {
        return view('auth.register');
    }

    /**
     * Handle registration.
     */
    public function register(Request $request)
    {
        $data = $request->validate([
            'name'     => 'required|string|max:255',
            'email'    => 'required|email|unique:users',
            'phone'    => 'nullable|string|max:20',
            'password' => 'required|string|min:6|confirmed',
        ]);

        $verification_code = Str::random(6);

        $user = User::create([
            'name'               => $data['name'],
            'email'              => $data['email'],
            'phone'              => $data['phone'] ?? null,
            'password'           => Hash::make($data['password']),
            'verification_code'  => $verification_code,
            'is_email_verified'  => false,
            'role'               => 'reader',
        ]);

        // Send verification email
        Mail::raw("Your verification code is: $verification_code", function ($message) use ($user) {
            $message->to($user->email)->subject('Verify Your Email');
        });

        return redirect()->route('auth.verify')->with('success', 'Account created! Please check your email for verification code.');
    }

    /**
     * Show verification form.
     */
    public function showVerify()
    {
        return view('auth.verify');
    }

    /**
     * Handle email verification.
     */
    public function verify(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'code'  => 'required|string',
        ]);

        $user = User::where('email', $request->email)->where('verification_code', $request->code)->first();

        if (!$user) {
            return back()->withErrors(['code' => 'Invalid verification code.']);
        }

        $user->is_email_verified = true;
        $user->verification_code = null;
        $user->save();

        return redirect()->route('login')->with('success', 'Email verified! You can now log in.');
    }

    /**
     * Handle logout.
     */
    public function logout()
    {
        Auth::logout();
        return redirect()->route('login')->with('success', 'You have been logged out.');
    }
}
