<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Str;

class LoginController extends Controller
{
    /**
     * Show the mock login form.
     *
     * @return \Illuminate\View\View
     */
    public function showLoginForm()
    {
        // If already "logged in", redirect to profile
        if (Session::has('mock_user')) {
            return redirect()->route('profile.index');
        }
        return view('login', ['pageTitle' => 'Login']);
    }

    /**
     * Handle the mock login attempt.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function login(Request $request)
    {
        // No validation, just "log in" with whatever is entered
        $email = $request->input('email', 'guest@example.com'); // Default if empty
        $name = Str::before($email, '@'); // Simple way to get a "name"

        $mockUser = [
            'name' => Str::title($name),
            'email' => $email,
        ];

        Session::put('mock_user', $mockUser);

        // Also, update the navigation bar to show "Profile" instead of "Login"
        // We'll use a session variable for this simple case.
        Session::put('is_mock_logged_in', true);


        return redirect()->route('profile.index')->with('success', 'You are now "logged in" as ' . $mockUser['name'] . '!');
    }

    /**
     * Handle mock logout.
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function logout()
    {
        Session::forget('mock_user');
        Session::forget('is_mock_logged_in');
        return redirect()->route('home')->with('success', 'You have been "logged out".');
    }
}
