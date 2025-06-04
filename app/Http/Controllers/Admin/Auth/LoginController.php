<?php

namespace App\Http\Controllers\Admin\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class LoginController extends Controller
{
    public function __construct()
    {
        $this->middleware(function ($request, $next) {
            if (Auth::check() && Auth::user()->is_admin) {
                if ($request->route()->getName() === 'admin.login.form' || $request->route()->getName() === 'admin.login.submit') {
                    return redirect()->route('admin.dashboard');
                }
            }
            return $next($request);
        })->except('logout');
        $this->middleware('guest')->except('logout');
    }

    public function showLoginForm(): View
    {
        return view('admin.auth.login');
    }

    public function login(Request $request): RedirectResponse
    {
        $credentials = $request->validate([
            'email' => 'required|string|email',
            'password' => 'required|string',
        ]);

        if (Auth::attempt($credentials, $request->filled('remember'))) {
            $user = Auth::user();
            if ($user->is_admin) {
                $request->session()->regenerate();
                return redirect()->intended(route('admin.dashboard'));
            } else {
                Auth::logout();
                return back()
                    ->withErrors(['email' => 'These credentials do not belong to an administrator.'])
                    ->onlyInput('email');
            }
        }
        return back()->withErrors(['email' => 'The provided credentials do not match our records.'])->onlyInput('email');
    }

    public function logout(Request $request): RedirectResponse
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('admin.login.form')->with('info', 'You have been logged out from the admin panel.');
    }
}