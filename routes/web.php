<?php

use App\Models\User;
use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CustomOrderController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\UserController; // This is your regular user controller (for login/signup)
use App\Http\Controllers\MenuController;
use App\Http\Controllers\Admin\DashboardController as AdminDashboardController; // Alias if needed
use App\Http\Controllers\Admin\UserController as AdminUserController; // <-- IMPORT YOUR ADMIN USER CONTROLLER

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
Route::get('/cart', [OrderController::class, 'confirm'])->name('cart.show');
Route::match(['get', 'post'], '/order/confirm', [OrderController::class, 'confirm'])->name('order.confirm');
Route::post('/order/place', [OrderController::class, 'placeOrder'])->name('order.place');
Route::get('/order', [OrderController::class, 'showOrders'])->name('order.index');

// Cart Item Management
Route::get('/cart/add', [OrderController::class, 'addItemToCart'])->name('cart.add');
Route::post('/cart/remove', [OrderController::class, 'removeItem'])->name('cart.remove');
Route::post('/cart/update-quantity', [OrderController::class, 'updateQuantity'])->name('cart.update.quantity');

// =========================
// ADMIN ROUTES
// =========================
Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    // Admin Dashboard
    Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');

    // --- USER MANAGEMENT (Using Admin/UserController) ---
    // Listing users
    Route::get('/users', [AdminUserController::class, 'index'])->name('users.index');

    // Show form to create a new user
    Route::get('/users/create', [AdminUserController::class, 'create'])->name('users.create');

    // Store the new user
    Route::post('/users', [AdminUserController::class, 'store'])->name('users.store');

    // Show a specific user (optional, but good for detail views)
    Route::get('/users/{user}', [AdminUserController::class, 'show'])->name('users.show');

    // Show form to edit a user
    Route::get('/users/{user}/edit', [AdminUserController::class, 'edit'])->name('users.edit');

    // Update the user
    Route::put('/users/{user}', [AdminUserController::class, 'update'])->name('users.update');
    // You might also use PATCH: Route::patch('/users/{user}', [AdminUserController::class, 'update']);

    // Delete a user
    Route::delete('/users/{user}', [AdminUserController::class, 'destroy'])->name('users.destroy');


    // You can also use a resource route for cleaner syntax if your AdminUserController
    // follows RESTful conventions (index, create, store, show, edit, update, destroy):
    // Route::resource('users', AdminUserController::class);
    // This single line would create all the routes above (except you'd need to name them explicitly if you need custom names).
    // If you use Route::resource, the names will be like 'admin.users.index', 'admin.users.store', etc.

    // Add more admin routes here... for example, managing orders:
    // Route::get('/orders', [AdminOrderController::class, 'index'])->name('orders.index');
    // Route::get('/orders/{order}', [AdminOrderController::class, 'show'])->name('orders.show');
    // ... etc.
});