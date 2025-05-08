<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\MenuController;
use App\Http\Controllers\CustomOrderController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\LoginController;
use Illuminate\Support\Facades\Session; // Ensure Session is imported if used directly in route closures

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/welcome', function () {
    return view('welcome');
});

// Home, Menu, Custom Routes
Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/menu1', [MenuController::class, 'menu1'])->name('menu.menu1');
Route::get('/menu2', [MenuController::class, 'menu2'])->name('menu.menu2');
Route::get('/orders/clear', [OrderController::class, 'clearOrderHistory'])->name('order.clearHistory');
// Route for the "TRY IT" button logic
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


// Mock Login, Logout, and Profile Routes
Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login.show');
Route::post('/login', [LoginController::class, 'login'])->name('login.submit');
Route::post('/logout', [LoginController::class, 'logout'])->name('logout.submit');
Route::get('/profile', [ProfileController::class, 'index'])->name('profile.index');

// Dashboard
Route::get('/dashboard', function () {
    if (!Session::has('mock_user')) {
        return redirect()->route('login.show')->with('info', 'Please log in to view the dashboard.');
    }
    // This is a very basic mock dashboard page content
    $userName = Session::get('mock_user')['name'] ?? 'Guest';
    $logoutForm = "<form method='POST' action='".route('logout.submit')."'>".csrf_field()."<button type='submit' style='padding:5px 10px; background-color: #c0392b; color:white; border:none; border-radius:4px; cursor:pointer;'>Logout</button></form>";
    return "<h1>Mock Dashboard</h1><p>Welcome, " . htmlspecialchars($userName) . "!</p>" . $logoutForm;
})->name('dashboard');