<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session; // To manage a mock login state
use Illuminate\View\View; // Import View
use Illuminate\Http\RedirectResponse; // Import RedirectResponse

class ProfileController extends Controller
{
    /**
     * Display the user's profile page.
     *
     * @return \Illuminate\View\View|\Illuminate\Http\RedirectResponse
     */
    public function index(): View|RedirectResponse // Updated return type
    {
        // For this mock login, we'll just get the "user" info from the session
        $user = Session::get('mock_user');

        if (!$user) {
            // If not "logged in", redirect to the mock login page
            return redirect()->route('login.show')->with('info', 'Please "log in" to view your profile.');
        }

        return view('profile', [
            'pageTitle' => 'Your Profile',
            'user' => $user
        ]);
    }
}