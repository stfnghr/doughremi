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
        ];

        $circleCookies = [ // Colors/Variations for Circle shape
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

        $heartCookies = [ // Colors/Variations for Heart shape
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

        $toppings = [
            ['img' => 'shaped_sprinkles.png', 'name' => 'Shaped Sprinkles'],
            ['img' => 'rainbow_sprinkles.png', 'name' => 'Rainbow Sprinkles'],
            ['img' => 'choco_sprinkles.png', 'name' => 'Choco Sprinkles'],
            ['img' => 'mix_sprinkles.png', 'name' => 'Mix Sprinkles'],
            ['img' => 'marshmallow.png', 'name' => 'Marshmallow'],
            ['img' => 'chocolate.png', 'name' => 'Chocolate'],
            ['img' => 'none.png', 'name' => 'None'], 
        ];

        $customBasePrice = 12000; // Example base price

        return view('custom', [
            'pageTitle' => 'Customize Your Cookie',
            'cookieShapes' => $cookieShapes,
            'circleCookies' => $circleCookies,
            'heartCookies' => $heartCookies,
            'toppings' => $toppings,
            'customBasePrice' => $customBasePrice,
        ]);
    }
}