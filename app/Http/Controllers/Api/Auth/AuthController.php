<?php

namespace App\Http\Controllers\Api\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        $data = $request->validate([
            'name'           => 'required|string|max:255',
            'email'          => 'required|string|email|unique:users,email',
            'password'       => 'required|string|min:6|confirmed',
            'phone'          => 'nullable|string|max:20',
            'bio'            => 'nullable|string|max:500',
            'date_of_birth'  => 'nullable|date',
            'avatar'         => 'nullable|string',
            'language_id'    => 'nullable|exists:languages,id',
        ]);

        $user = User::create([
            'name'              => $data['name'],
            'email'             => $data['email'],
            'password'          => Hash::make($data['password']),
            'phone'             => $data['phone'] ?? null,
            'bio'               => $data['bio'] ?? null,
            'date_of_birth'     => $data['date_of_birth'] ?? null,
            'avatar'            => $data['avatar'] ?? null,
            'language_id'       => $data['language_id'] ?? null,
            'verification_code' => Str::random(6),
            'is_email_verified' => false,
            'role'              => 'user',
        ]);

        return response()->json([
            'message' => 'User registered successfully. Please verify your email.',
            'user'    => $user,
        ], 201);
    }

    /**
     * Login user
     */
    public function login(Request $request)
    {
        $data = $request->validate([
            'email'    => 'required|email',
            'password' => 'required',
        ]);

        $user = User::where('email', $data['email'])->first();

        if (! $user || ! Hash::check($data['password'], $user->password)) {
            throw ValidationException::withMessages([
                'email' => ['The provided credentials are incorrect.'],
            ]);
        }

        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'message' => 'Login successful',
            'token'   => $token,
            'user'    => $user,
        ]);
    }

    /**
     * Logout user
     */
    public function logout(Request $request)
    {
        $request->user()->tokens()->delete();

        return response()->json([
            'message' => 'Logged out successfully',
        ]);
    }

    /**
     * Get authenticated user profile
     */
    public function profile(Request $request)
    {
        return response()->json($request->user());
    }

    /**
     * Update profile
     */
    public function updateProfile(Request $request)
    {
        $user = $request->user();

        $data = $request->validate([
            'name'          => 'sometimes|string|max:255',
            'phone'         => 'nullable|string|max:20',
            'bio'           => 'nullable|string|max:500',
            'date_of_birth' => 'nullable|date',
            'avatar'        => 'nullable|string',
            'language_id'   => 'nullable|exists:languages,id',
        ]);

        $user->update($data);

        return response()->json([
            'message' => 'Profile updated successfully',
            'user'    => $user,
        ]);
    }

    /**
     * Verify email
     */
    public function verifyEmail(Request $request)
    {
        $data = $request->validate([
            'email'             => 'required|email|exists:users,email',
            'verification_code' => 'required|string',
        ]);

        $user = User::where('email', $data['email'])->first();

        if ($user->verification_code !== $data['verification_code']) {
            return response()->json(['message' => 'Invalid verification code'], 422);
        }

        $user->is_email_verified = true;
        $user->verification_code = null;
        $user->save();

        return response()->json([
            'message' => 'Email verified successfully',
            'user'    => $user,
        ]);
    }
}
