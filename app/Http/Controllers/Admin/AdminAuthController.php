<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class AdminAuthController extends Controller
{
    public function showLogin(): View|RedirectResponse
    {
        if (session('admin_logged_in')) {
            return redirect()->route('admin.dashboard');
        }

        return view('admin.login');
    }

    public function login(Request $request): RedirectResponse
    {
        $credentials = $request->validate([
            'username' => ['required', 'string'],
            'password' => ['required', 'string'],
        ]);

        if ($credentials['username'] !== 'admin' || $credentials['password'] !== 'admin123') {
            return back()
                ->withErrors(['username' => 'Usuario o contraseña incorrectos.'])
                ->withInput(['username' => $credentials['username']]);
        }

        session(['admin_logged_in' => true]);

        return redirect()->route('admin.dashboard');
    }

    public function logout(): RedirectResponse
    {
        session()->forget('admin_logged_in');

        return redirect()->route('admin.login');
    }
}
