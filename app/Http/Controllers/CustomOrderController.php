<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session; // Import Session facade
use Illuminate\Http\RedirectResponse;   // Import RedirectResponse
use Illuminate\View\View;                // Import View

class CustomOrderController extends Controller
{
    /**
     * Handles the "TRY IT" button click.
     * Redirects to login if not mock-logged-in, otherwise to the customization page.
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function startCustomization(): RedirectResponse
    {
        if (!Session::has('is_mock_logged_in')) {
            // Store the intended URL so we can redirect back after mock login
            // For now, we'll just redirect to the custom page after login.
            // Session::put('url.intended', route('custom.index'));

            return redirect()->route('login.show')->with('info', 'Please log in to start customizing your cookie!');
        }

        // If mock-logged-in, proceed to the customization page.
        // You could also redirect to route('menu.menu1') if that's the desired flow.
        return redirect()->route('custom.index');
    }

    /**
     * Display the custom cookie selection page.
     * This method should provide the data needed by custom.blade.php
     *
     * @return \Illuminate\View\View
     */
    public function index(): View
    {
        // Define available cookie shapes and colors as expected by custom.blade.php
        $cookieShapes = [
            ['img' => 'circle.png', 'name' => 'Circle'],
            ['img' => 'heart.png', 'name' => 'Heart'],
            // Add other shapes if your custom.blade.php uses them
        ];

        $circleCookies = [ // Colors for Circle shape
            ['img' => 'circle_basic.png', 'name' => 'Basic'],
            ['img' => 'circle_red.png', 'name' => 'Red'],
            ['img' => 'circle_yellow.png', 'name' => 'Yellow'],
            ['img' => 'circle_green.png', 'name' => 'Green'],
            ['img' => 'circle_blue.png', 'name' => 'Blue'],
            ['img' => 'circle_purple.png', 'name' => 'Purple'],
            ['img' => 'circle_pink.png', 'name' => 'Pink'],
            ['img' => 'circle_white.png', 'name' => 'White'],
            ['img' => 'circle_orange.png', 'name' => 'Orange'],
            ['img' => 'circle_brown.png', 'name' => 'Brown'],
        ];

        $heartCookies = [ // Colors for Heart shape
            ['img' => 'heart_basic.png', 'name' => 'Basic'],
            ['img' => 'heart_red.png', 'name' => 'Red'],
            ['img' => 'heart_yellow.png', 'name' => 'Yellow'],
            ['img' => 'heart_green.png', 'name' => 'Green'],
            ['img' => 'heart_blue.png', 'name' => 'Blue'],
            ['img' => 'heart_purple.png', 'name' => 'Purple'],
            ['img' => 'heart_pink.png', 'name' => 'Pink'],
            ['img' => 'heart_white.png', 'name' => 'White'],
            ['img' => 'heart_orange.png', 'name' => 'Orange'],
            ['img' => 'heart_brown.png', 'name' => 'Brown'],
        ];


        // Price for a single custom cookie (adjust if needed from your pricelist)
        $customBasePrice = 12000; // 12k

        return view('custom', [
            'pageTitle' => 'Custom Cookie', // Or 'Custom a Cookie'
            'cookieShapes' => $cookieShapes,
            'circleCookies' => $circleCookies,
            'heartCookies' => $heartCookies,
            'customBasePrice' => $customBasePrice,
        ]);
    }
}