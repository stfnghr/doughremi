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
     */
    public function sweetPick(): View
    {
        $sweetPickMenus = Menu::where('categories', 'Sweet Pick')->get();

        return view('menu1', [
            'pageTitle' => 'Sweet Pick',
            'headTitle' => 'Sweet Pick Menus',
            'cookies' => $sweetPickMenus,
        ]);
    }

    /**
     * Display the "Joy Box" menu page.
     *
     * @return \Illuminate\View\View
     */
    public function joyBox(): View
    {
        $joyBoxMenus = Menu::where('categories', 'Joy Box')->get();

        return view('menu2', [
            'pageTitle' => 'Joy Box',
            'headTitle' => 'Joy Box Menus',
            'joyBoxes' => $joyBoxMenus, // Changed variable name for clarity
        ]);
    }
}