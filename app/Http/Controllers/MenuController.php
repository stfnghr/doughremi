<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Menu; // Import your Menu model
use Illuminate\View\View;

class MenuController extends Controller
{
    /**
     * Display the "Sweet Pick" menu page.
     *
     * @return \Illuminate\View\View
     */e
    public function sweetPick(): View
    {
        $sweetPickMenus = Menu::where('categories', 'Sweet Pick')->get();

        $sweetPickPrice = 10000; 

        return view('menu1', [
            'pageTitle' => 'Sweet Pick',
            'headTitle' => 'Sweet Pick Menus',
            'cookies' => $sweetPickMenus, 
            'sweetPickPrice' => $sweetPickPrice 
        ]);
    }

    /**
     * Display the "Joy Box" menu page (Example for menu2).
     *
     * @return \Illuminate\View\View
     */
    public function joyBox(): View
    {
        $joyBoxMenus = Menu::where('categories', 'Joy Box')->get();
        
        $joyBoxPrice = 250000; 

        return view('menu2', [
            'pageTitle' => 'Joy Box',
            'headTitle' => 'Joy Box Menus',
            'cookies' => $joyBoxMenus, 
            'joyBoxPrice' => $joyBoxPrice 
        ]);
    }
}