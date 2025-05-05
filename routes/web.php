<?php

use Illuminate\Support\Facades\Route;

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

Route::get('/order', function () {
    return view('order', [
        "pageTitle" => "Order",
    ]);
});

Route::get('/custom', function () {
    return view('custom', [
        "pageTitle" => "Custom",
    ]);
});

Route::get('/profile', function () {
    return view('profile', [
        "pageTitle" => "Profile",
    ]);
});