<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\View\View; // Import the View class

class HomeController extends Controller
{
    /**
     * Display the home page.
     */
    public function index(): View // Specify the return type
    {
        // You can pass data to the view if needed later
        return view('home', [ // Make sure your view is named home.blade.php
            'pageTitle' => 'Home' // Example data
        ]);
    }
}