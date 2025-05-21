<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request; // Keep if any method might need it in the future
use Illuminate\Support\Facades\Auth;   // Import Auth facade
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
// use Illuminate\Support\Facades\Session; // No longer needed for mock login

class CustomOrderController extends Controller
{
    /**
     * Handles the "TRY IT" button click.
     * Redirects to login if not authenticated, otherwise to the customization page.
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function startCustomization(): RedirectResponse
    {
        // Use Laravel's built-in authentication check
        if (!Auth::check()) {
            return redirect()->route('login')->with('info', 'Please log in to start customizing your cookie!');
        }
        // If authenticated, proceed to the customization page
        return redirect()->route('custom.index');
    }

    /**
     * Display the custom cookie selection page.
     * This method should provide the data needed by custom.blade.php
     *
     * Note: This route itself should also be protected by 'auth' middleware
     * in your web.php file if only logged-in users can access it.
     *
     * @return \Illuminate\View\View
     */
    public function index(): View
    {
        // Data preparation remains the same
        $cookieShapes = [
            ['img' => 'circle.png', 'name' => 'Circle'],
            ['img' => 'heart.png', 'name' => 'Heart'],
            // Add more shapes as needed
        ];

        $circleCookies = [ // Colors/Variations for Circle shape
            ['img' => 'circle_basic.png', 'name' => 'Basic'],
            ['img' => 'circle_red.png', 'name' => 'Red'],
            ['img' => 'circle_yellow.png', 'name' => 'Yellow'],
            // ... more circle variations
        ];

        $heartCookies = [ // Colors/Variations for Heart shape
            ['img' => 'heart_basic.png', 'name' => 'Basic'],
            ['img' => 'heart_red.png', 'name' => 'Red'],
            ['img' => 'heart_yellow.png', 'name' => 'Yellow'],
            // ... more heart variations
        ];

        $customBasePrice = 12000; // Example base price

        return view('custom', [
            'pageTitle' => 'Customize Your Cookie',
            'cookieShapes' => $cookieShapes,
            'circleCookies' => $circleCookies,
            'heartCookies' => $heartCookies,
            'customBasePrice' => $customBasePrice,
        ]);
    }
}