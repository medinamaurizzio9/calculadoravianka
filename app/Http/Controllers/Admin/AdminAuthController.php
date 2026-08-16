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

        $adminUsername = config('admin.username');
        $adminPassword = config('admin.password');

        if (
            ! is_string($adminUsername)
            || $adminUsername === ''
            || ! is_string($adminPassword)
            || $adminPassword === ''
            || ! hash_equals($adminUsername, $credentials['username'])
            || ! hash_equals($adminPassword, $credentials['password'])
        ) {
            return back()
                ->withErrors(['username' => 'Usuario o contraseña incorrectos.'])
                ->withInput(['username' => $credentials['username']]);
        }

        $request->session()->regenerate();
        $request->session()->put('admin_logged_in', true);

        return redirect()->route('admin.dashboard');
    }

    public function logout(Request $request): RedirectResponse
    {
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('admin.login');
    }
}
