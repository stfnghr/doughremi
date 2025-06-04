<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
// use App\Models\User; // If you need to manage users

class AdminDashboardController extends Controller
{
    public function index()
    {
        return view('admin.home');
    }
}