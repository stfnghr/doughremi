<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\View\View; // Import the View class

class MenuController extends Controller
{
    /**
     * Display the Menu 1 page.
     */
    public function menu1(): View // Specify the return type
    {
        return view('menu1', [ // Assumes view is menu1.blade.php
           'pageTitle' => 'Sweet Pick' // Example data
        ]);
    }

    /**
     * Display the Menu 2 page.
     */
    public function menu2(): View // Specify the return type
    {
         return view('menu2', [ // Assumes view is menu2.blade.php
             'pageTitle' => 'Joy Box' // Example data
         ]);
    }
}