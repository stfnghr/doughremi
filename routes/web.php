<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CustomOrderController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\MenuController;
use App\Http\Controllers\Admin\AdminDashboardController;
use App\Http\Controllers\Admin\AdminUserController;
use App\Http\Controllers\Admin\AdminMenuController;
use App\Http\Controllers\Admin\AdminOrderController;

Route::get('/', function () {
    return view('home', ["pageTitle" => "Home"]);
})->name('home'); // Added name for home route

// Login Route
Route::get('/login', function () {
    return view('login', ['pageTitle' => 'Login']);
})->name('login.form');

Route::post('/login', [UserController::class, 'login'])->name('login');

// Signup Route
Route::get('/signup', function () {
    return view('signup', ['pageTitle' => 'Sign Up']);
})->name('signup.form');

Route::post('/signup', [UserController::class, 'signup'])->name('signup');

// Logout Route
Route::post('/logout', [UserController::class, 'logout'])->name('logout')->middleware('auth');

// Profile Pages Routes
Route::get('/profile', [ProfileController::class, 'index'])->middleware('auth')->name('profile');

// Menus
Route::get('/menu1', [MenuController::class, 'sweetPick'])->name('menu.sweetpick');
Route::get('/menu2', [MenuController::class, 'joyBox'])->name('menu.joybox');

// Custom Order Flow
Route::get('/start-customization', [CustomOrderController::class, 'startCustomization'])->name('start.customization');
Route::get('/custom', [CustomOrderController::class, 'index'])->name('custom.index'); // Removed auth middleware for now, add if needed based on your app logic

// Cart and Order Process Pages
Route::get('/cart', [OrderController::class, 'confirm'])->name('cart.show'); // Alias for viewing the cart
Route::match(['get', 'post'],'/order/confirm', [OrderController::class, 'confirm'])->name('order.confirm'); // Handles GET for display, and POST for add custom / update quantity
Route::post('/order/place', [OrderController::class, 'placeOrder'])->name('order.place');

// Order History and Details
// In routes/web.php
Route::get('/orders', [OrderController::class, 'showOrders'])->name('orders.index'); // Changed to plural // Changed from /order to /orders for clarity
Route::get('/orders/clear', [OrderController::class, 'clearOrderHistory'])->name('orders.clearHistory');
Route::get('/orders/{orderId}', [OrderController::class, 'showOrderDetail'])->name('orders.show'); // For viewing a specific order detail

// Cart Item Management (other than quantity update which is now in order.confirm)
Route::post('/cart/add', [OrderController::class, 'addItemToCart'])->name('cart.add'); // Should ideally be POST
Route::post('/cart/remove', [OrderController::class, 'removeItem'])->name('cart.remove');

// =========================
// ADMIN ROUTES
// =========================
Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    // Admin Dashboard
    Route::get('/home', [AdminDashboardController::class, 'index'])->name('home');

    // --- USER MANAGEMENT (Example) ---
    // Route::get('/users', [AdminUserController::class, 'index'])->name('users.index');
    // ... other user management routes

    // --- MENU MANAGEMENT ---
    Route::get('/menu', [AdminMenuController::class, 'index'])->name('menu.index');
    Route::get('/menu/add', [AdminMenuController::class, 'create'])->name('menu.add');
    Route::post('/menu/store', [AdminMenuController::class, 'store'])->name('menu.store');
    Route::get('/menu/{id}/edit', [AdminMenuController::class, 'edit'])->name('menu.edit');
    Route::put('/menu/{id}', [AdminMenuController::class, 'update'])->name('menu.update');
    Route::delete('/menu/{id}', [AdminMenuController::class, 'destroy'])->name('menu.destroy');

    // --- ORDER MANAGEMENT ---
    Route::get('/orders', [AdminOrderController::class, 'index'])->name('orders.index');
    Route::get('/orders/{order}', [AdminOrderController::class, 'show'])->name('orders.show'); // This defines admin.orders.show
    Route::put('/orders/{order}/status', [AdminOrderController::class, 'updateStatus'])->name('orders.updateStatus');

});