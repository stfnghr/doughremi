<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\Menu;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;

class AdminMenuController extends Controller
{
    /**
     * Show the form for creating a new menu item.
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        return view('admin.addmenu', ['pageTitle' => 'Add New Menu Item']);
    }

    /**
     * Store a newly created menu item in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        // Validate the request data
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:100',
            'price' => 'required|numeric|min:0',
            'categories' => 'required|string|in:Sweet Pick,Joy Box',
            'image' => 'required|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        if ($validator->fails()) {
            return redirect()->route('admin.menu.add')
                        ->withErrors($validator)
                        ->withInput();
        }

        // Handle image upload
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('menu_images', 'public');
        }

        // Create the menu item
        Menu::create([
            'name' => $request->input('name'),
            'price' => $request->input('price'),
            'categories' => $request->input('categories'),
            'image' => $imagePath ?? null,
        ]);

        return redirect()->route('admin.menu.index')
            ->with('success', 'Menu item "' . $request->input('name') . '" added successfully.');
    }

    /**
     * Display the admin menu management page.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        $menus = Menu::all();
        return view('admin.menu', ['pageTitle' => 'Manage Menu', 'menus' => $menus]);
    }

    /**
     * Show the form for editing the specified menu item.
     *
     * @param  Menu  $menu
     * @return \Illuminate\View\View
     */
    public function edit(Menu $menu)
    {
        return view('admin.menu.edit', [
            'pageTitle' => 'Edit Menu Item',
            'menu' => $menu
        ]);
    }

    /**
     * Update the specified menu item in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  Menu  $menu
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, Menu $menu)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:100',
            'price' => 'required|numeric|min:0',
            'categories' => 'required|string|in:sweet_pick,joy_box',
            'image' => 'sometimes|image|mimes:jpeg,png|max:2048',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                        ->withErrors($validator)
                        ->withInput();
        }

        $data = [
            'name' => $request->input('name'),
            'price' => $request->input('price'),
            'categories' => $request->input('categories'),
        ];

        // Handle image update if new image was uploaded
        if ($request->hasFile('image')) {
            // Delete old image if it exists
            if ($menu->image) {
                Storage::disk('public')->delete($menu->image);
            }
            $data['image'] = $request->file('image')->store('menu_images', 'public');
        }

        $menu->update($data);

        return redirect()->route('admin.menu.index')
            ->with('success', 'Menu item updated successfully.');
    }

    /**
     * Remove the specified menu item from storage.
     *
     * @param  Menu  $menu
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(Menu $menu)
    {
        // Delete associated image
        if ($menu->image) {
            Storage::disk('public')->delete($menu->image);
        }

        $menu->delete();

        return redirect()->route('admin.menu.index')
            ->with('success', 'Menu item deleted successfully.');
    }
}