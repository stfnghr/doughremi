<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Validation\Rules\Password;

class UserController extends Controller
{
    public function signup(Request $request): RedirectResponse
    {
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email',
            'password' => ['required', 'string', Password::defaults()], // Use Password::defaults() for Laravel's recommended rules
            // If you want password confirmation:
            // 'password' => ['required', 'string', Password::defaults(), 'confirmed'],
            'is_admin' => 'sometimes|boolean', // 'sometimes' ensures it's only validated if present
        ]);

        // The 'hashed' cast in the User model will handle password hashing.
        $user = User::create([
            'name' => $validatedData['name'],
            'email' => $validatedData['email'],
            'password' => $validatedData['password'], // Pass the PLAIN password
            'is_admin' => $request->boolean('is_admin'), // Use $request->boolean() for cleaner boolean input handling
            // 'email_verified_at' => now(), // Uncomment if you want to auto-verify on signup
        ]);

        // Optional: Log the user in immediately after signup
        // Auth::login($user);
        // if ($user->isAdmin()) {
        //     return redirect()->route('admin.dashboard')->with('success', 'Registration successful and logged in!');
        // }
        // return redirect()->intended('/profile')->with('success', 'Registration successful and logged in!');

        return redirect('/login')->with('success', 'Registration successful! Please log in.');
    }

    public function login(Request $request): RedirectResponse
    {
        $credentials = $request->validate([
            'email' => 'required|string|email',
            'password' => 'required|string'
        ]);

        if (Auth::attempt($credentials, $request->filled('remember'))) {
            $request->session()->regenerate();
            $user = Auth::user();
            if ($user->is_admin) {
                return redirect()->route('admin.home')->with('success', 'Welcome back, Admin!');
            } else {
                return redirect()->intended('/profile')->with('success', 'Logged in successfully!');
            }
        }

        return back()->withErrors([
            'email' => 'The provided credentials do not match our records.',
        ])->onlyInput('email');
    }

    public function logout(Request $request): RedirectResponse
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/')->with('info', 'You have been logged out.');
    }
}