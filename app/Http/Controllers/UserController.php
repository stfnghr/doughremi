<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User; // Ensure User model is imported
use Illuminate\Http\RedirectResponse;
// use Illuminate\Support\Facades\Hash; // Not strictly needed here if model handles hashing
use Illuminate\Validation\Rules\Password; // Optional: For more complex password rules

class UserController extends Controller
{
    public function signup(Request $request): RedirectResponse
    {
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email',
            'password' => ['required', 'string', Password::min(8)], // Example using Password rule object
            // If you want password confirmation, add 'confirmed' rule and a 'password_confirmation' field in your form
            // 'password' => ['required', 'string', Password::min(8), 'confirmed'],
            'is_admin' => 'sometimes|boolean',
        ]);

        // The User model's setPasswordAttribute mutator (or 'hashed' cast) will handle hashing.
        // We pass the plain password from the validated data.
        $user = User::create([
            'name' => $validatedData['name'],
            'email' => $validatedData['email'],
            'password' => $validatedData['password'], // Pass the PLAIN password here
            'is_admin' => $validatedData['is_admin'] ?? false,
        ]);

        // Optional: Log the user in immediately after signup
        // Auth::login($user);
        // return redirect()->intended('/profile')->with('success', 'Registration successful and logged in!');

        // Or redirect to login page with a success message
        return redirect('/login')->with('success', 'Registration successful! Please log in.');
    }

    public function login(Request $request): RedirectResponse
    {
        $credentials = $request->validate([
            'email' => 'required|string|email',
            'password' => 'required|string'
        ]);

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate(); // Security best practice
            return redirect()->intended('/profile');
        }

        // If authentication fails
        return back()->withErrors([
            'email' => 'The provided credentials do not match our records.',
        ])->onlyInput('email'); // Return only email input to the form for better UX
    }

    public function logout(Request $request): RedirectResponse
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/')->with('info', 'You have been logged out.');
    }
}