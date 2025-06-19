<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Validation\Rules\Password;

class UserController extends Controller
{
    public function signup(Request $request): RedirectResponse
    {
        $validatedData = $request->validate([
            'name' => 'required|string|max:255', // Added max length for name
            'email' => 'required|string|email|max:255|unique:users,email', // Added max length for email
            'password' => ['required', 'confirmed', Password::min(8)], // Added password confirmation and rule
            'is_admin' => 'boolean',
        ]);


        $user = User::create([
            'name' => $validatedData['name'],
            'email' => $validatedData['email'],
            'password' => Hash::make($validatedData['password']),
            'is_admin' => $validatedData['is_admin'] ?? false,
        ]);

        // Log the user in after successful signup
        Auth::login($user);

        // Regenerate session after login
        $request->session()->regenerate();

        // Redirect to the profile page with a success message
        return redirect('/profile')->with('success', 'Account created successfully! You are now logged in.');
    }

    public function login(Request $request): RedirectResponse
    {
        $credentials = $request->validate([
            'email' => 'required|string|email',
            'password' => 'required|string'
        ]);

    if (Auth::attempt($credentials)) {
        $request->session()->regenerate(); // Important for session fixation protection
        $user = Auth::user();
            if ($user->is_admin) {
                return redirect()->route('admin.home')->with('success', 'Welcome back, Admin!');
            } else {
                return redirect()->intended('/profile')->with('success', 'Logged in successfully!');
            }
    }

    return back()->withErrors([
        'email' => 'The provided credentials do not match our records.', // More user-friendly message
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