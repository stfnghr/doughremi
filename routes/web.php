<?php

use App\Models\User;
use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CustomOrderController;
use App\Http\Controllers\OrderController;
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

// Menus
// Route::get('/menu1', function () {
//     return view('menu1', [
//         "pageTitle" => "Sweet Pick"
//     ]);
// });

// Route::get('/menu2', function () {
//     return view('menu2', [
//         "pageTitle" => "Joy Box"
//     ]);
// });

Route::get('/menu1', [MenuController::class, 'sweetPick'])->name('menu.sweetpick'); // Added a name for good practice
Route::get('/menu2', [MenuController::class, 'joyBox'])->name('menu.joybox');

Route::get('/orders/clear', [OrderController::class, 'clearOrderHistory'])->name('order.clearHistory');
Route::get('/start-customization', [CustomOrderController::class, 'startCustomization'])->name('start.customization');
Route::get('/custom', [CustomOrderController::class, 'index'])->name('custom.index');

// Cart and Order Process Pages
Route::get('/cart', [OrderController::class, 'confirm'])->name('cart.show');
Route::match(['get', 'post'], '/order/confirm', [OrderController::class, 'confirm'])->name('order.confirm');
Route::post('/order/place', [OrderController::class, 'placeOrder'])->name('order.place');
Route::get('/order', [OrderController::class, 'showOrders'])->name('order.index');

// Cart Item Management
Route::get('/cart/add', [OrderController::class, 'addItemToCart'])->name('cart.add');
Route::post('/cart/remove', [OrderController::class, 'removeItem'])->name('cart.remove');