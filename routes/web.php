<?php

use Illuminate\Support\Facades\Route;
// --- ADD THESE LINES ---
use App\Http\Controllers\HomeController;
use App\Http\Controllers\MenuController;
// --- END OF ADDED LINES ---
use App\Http\Controllers\CustomOrderController; // This one was already there
use App\Http\Controllers\OrderController;       // This one was already there
use App\Http\Controllers\ProfileController;     // This one was already there

// Home Route (using HomeController)
Route::get('/', [HomeController::class, 'index'])->name('home'); // Now it knows where HomeController is

// Menu Routes (using MenuController)
Route::get('/menu1', [MenuController::class, 'menu1'])->name('menu1'); // Now it knows where MenuController is
Route::get('/menu2', [MenuController::class, 'menu2'])->name('menu2'); // Now it knows where MenuController is

// Custom Order Route (using CustomOrderController)
Route::get('/custom', [CustomOrderController::class, 'index'])->name('custom.index');

// Order Confirmation & Placement Routes (using OrderController)
Route::get('/order/confirm', [OrderController::class, 'confirm'])->name('order.confirm');
Route::post('/order/place', [OrderController::class, 'placeOrder'])->name('order.place');
Route::get('/order', [OrderController::class, 'showOrders'])->name('order.index');

// Profile Route (using ProfileController)
Route::get('/profile', [ProfileController::class, 'index'])->name('profile');

// Basic Welcome Route (comes with Laravel)
// Route::get('/welcome', function () {
//     return view('welcome');
// });