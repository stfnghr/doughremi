<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\MenuController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\CustomOrderController;
use App\Http\Controllers\ProfileController;

Route::get('/', function () {
    return view('home', [
        "pageTitle" => "Home",
    ]);
});

Route::get('/menu1', function () {
    return view('menu1', [
        "pageTitle" => "Sweet Pick",
    ]);
});

Route::get('/menu2', function () {
    return view('menu2', [
        "pageTitle" => "Joy Box",
    ]);
});

Route::get('/custom', [CustomOrderController::class, 'index'])->name('custom.index');

Route::post('/order/confirm', [OrderController::class, 'confirm'])->name('order.confirm');
Route::post('/order/place', [OrderController::class, 'placeOrder'])->name('order.place');
Route::get('/order', [OrderController::class, 'showOrders'])->name('order.index');

Route::get('/profile', function () {
    return view('profile', [
        "pageTitle" => "Profile",
    ]);
});