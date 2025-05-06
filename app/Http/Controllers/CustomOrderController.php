<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class CustomOrderController extends Controller
{
    /**
     * Display the custom cookie base selection page.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        // Define available cookie bases (name, image, identifier)
        // We assume the price for a custom base is fixed (e.g., 12k from your pricelist)
        $cookieBases = [
            ['img' => 'chocochip_cookie.png', 'name' => 'Chocolate Chip Base', 'id' => 'chocolate-chip'],
            ['img' => 'chocolate_cookie.png', 'name' => 'Double Chocolate Base', 'id' => 'double-chocolate'],
            ['img' => 'vanilla_cookie.png', 'name' => 'Vanilla Bean Base', 'id' => 'vanilla-bean'],
            ['img' => 'strawberry_cookie.png', 'name' => 'Strawberry Cream Base', 'id' => 'strawberry-cream'],
            ['img' => 'matcha_cookie.png', 'name' => 'Matcha Green Tea Base', 'id' => 'matcha-green-tea'],
            ['img' => 'saltedcaramel_cookie.png', 'name' => 'Salted Caramel Base', 'id' => 'salted-caramel'],
            ['img' => 'biscoff_cookie.png', 'name' => 'Lotus Biscoff Base', 'id' => 'lotus-biscoff'],
            // Add other bases if needed
            // I love vivian
        ];

        // Price for a single custom cookie base (adjust if needed)
        $customBasePrice = 12000; // 12k

        return view('custom', [
            'pageTitle' => 'Custom',
            'cookieBases' => $cookieBases,
            'customBasePrice' => $customBasePrice, // Pass price if needed in view
        ]);
    }
}