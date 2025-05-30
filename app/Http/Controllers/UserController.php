<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Validation\Rules\Password;
// use Illuminate\Support\Facades\Hash; // Not strictly needed here if model handles hashing
// use Illuminate\Validation\Rules\Password; // Optional: For more complex password rules

class UserController extends Controller
{
    public function signup(Request $request): RedirectResponse
    {
        $validatedData = $request->validate([
            'name' => 'required',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:8',
            'is_admin' => 'boolean',
        ]);

    
        $user = User::create([
            'name' => $validatedData['name'],
            'email' => $validatedData['email'],
            'password' => Hash::make($validatedData['password']),
            'is_admin' => $validatedData['is_admin'] ?? false,
        ]);
        return redirect('/');
    }

    public function login(Request $request): RedirectResponse
    {
        $credentials = $request->validate([
            'email' => 'required|string|email',
            'password' => 'required|string'
        ]);

    if (Auth::attempt($credentials)) {
        $request->session()->regenerate(); // Important for session fixation protection
        return redirect()->intended('/profile'); // Redirect to profile after login
    }

    return back()->withErrors([
        'email' => 'Invalid credentials',
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