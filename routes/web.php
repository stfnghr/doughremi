<?php

use App\Models\User;
use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CustomOrderController;
use App\Http\Controllers\OrderController;      // Make sure this is your correct OrderController
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\MenuController;

Route::get('/', function () {
    return view('home', [
        "pageTitle" => "Home"
    ]);
});

//Login Route
Route::get('/login', function () {
    return view('login', [
        "pageTitle" => "Log In"
    ]);
});
Route::post('/login', [UserController::class, 'login'])->name('login');

// Signup Route
Route::get('/signup', function () {
    return view('signup', [
        "pageTitle" => "Sign Up"
    ]);
});
Route::post('/signup', [UserController::class, 'signup'])->name('signup');

// Logout Route
Route::post('/logout', [UserController::class, 'logout'])->name('logout');

// Profile Pages Routes
Route::get('/profile', [ProfileController::class, 'index'])->middleware('auth')->name('profile');

Route::get('/menu1', [MenuController::class, 'sweetPick'])->name('menu.sweetpick');
Route::get('/menu2', [MenuController::class, 'joyBox'])->name('menu.joybox');

Route::get('/orders/clear', [OrderController::class, 'clearOrderHistory'])->name('order.clearHistory');
Route::get('/start-customization', [CustomOrderController::class, 'startCustomization'])->name('start.customization');
Route::get('/custom', [CustomOrderController::class, 'index'])->name('custom.index');

// Cart and Order Process Pages
// 'confirm' method handles displaying the cart/confirm page.
Route::get('/cart', [OrderController::class, 'confirm'])->name('cart.show'); // Alias for confirm page
Route::match(['get', 'post'], '/order/confirm', [OrderController::class, 'confirm'])->name('order.confirm'); // Main confirm page route
Route::post('/order/place', [OrderController::class, 'placeOrder'])->name('order.place');
Route::get('/order', [OrderController::class, 'showOrders'])->name('order.index'); // User's order history

// Cart Item Management
Route::get('/cart/add', [OrderController::class, 'addItemToCart'])->name('cart.add'); // Assuming this takes query params
Route::post('/cart/remove', [OrderController::class, 'removeItem'])->name('cart.remove');
Route::post('/cart/update-quantity', [OrderController::class, 'updateQuantity'])->name('cart.update.quantity'); // <-- NEW ROUTE

Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    // Admin Dashboard
    Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');

    // Example: Manage Users
    // Route::get('/users', [AdminUserController::class, 'index'])->name('users.index');
    // Route::get('/users/{user}/edit', [AdminUserController::class, 'edit'])->name('users.edit');
    // Route::put('/users/{user}', [AdminUserController::class, 'update'])->name('users.update');

    // Add more admin routes here...
});