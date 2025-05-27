<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use App\Models\User;

class DashboardController extends Controller
{
    public function index()
    {
        $totalUsers = User::count();
        $sessionOrdersCount = count(Session::get('placed_orders', []));
        return view('admin.dashboard', compact('totalUsers', 'sessionOrdersCount'));
    }
}