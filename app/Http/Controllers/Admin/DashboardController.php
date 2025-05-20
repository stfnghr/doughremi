<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
// use App\Models\User; // If you need to manage users

class DashboardController extends Controller
{
    public function index()
    {
        // Fetch data needed for the admin dashboard
        // $userCount = User::count();
        // return view('admin.dashboard', compact('userCount'));
        return view('admin.dashboard');
    }
}