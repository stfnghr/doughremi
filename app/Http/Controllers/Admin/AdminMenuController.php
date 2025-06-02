<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class AdminMenuController extends Controller
{
    /**
     * Display the admin menu management page.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        return view('admin.menu.index', ['pageTitle' => 'Admin Menu Management']);
    }

    /**
     * Show the form for creating a new menu item.
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        return view('admin.menu.create', ['pageTitle' => 'Add Menu Item']);
    }

    /**
     * Store a newly created menu item in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        // Validate and store the new menu item
        // Logic to save the menu item goes here

        return redirect()->route('admin.menu.index')->with('success', 'Menu item created successfully.');
    }

    /**
     * Show the form for editing the specified menu item.
     *
     * @param  int  $id
     * @return \Illuminate\View\View
     */
    public function edit($id)
    {
        // Logic to fetch the menu item by $id
        return view('admin.menu.edit', [
            'pageTitle' => 'Edit Menu Item',
            'menu' => $id // You should pass the actual menu item here
        ]);
    }

    /**
     * Update the specified menu item in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, $id)
    {
        // Validate and update the menu item
        // Logic to update the menu item by $id goes here

        return redirect()->route('admin.menu.index')->with('success', 'Menu item updated successfully.');
    }

    /**
     * Remove the specified menu item from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy($id)
    {
        // Logic to delete the menu item by $id
        // For example, Menu::find($id)->delete();

        return redirect()->route('admin.menu.index')->with('success', 'Menu item deleted successfully.');
    }
}