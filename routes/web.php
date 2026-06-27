<?php

use App\Http\Controllers\Admin\AdminAuthController;
use App\Http\Controllers\Admin\AdminCreditLevelController;
use App\Http\Controllers\Admin\AdminDashboardController;
use App\Http\Controllers\Admin\AdminSettingController;
use App\Http\Controllers\CreditSimulatorController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect('/simulador-creditos');
});

Route::get('/simulador-creditos', CreditSimulatorController::class)->name('simulador-creditos');

Route::get('/admin/login', [AdminAuthController::class, 'showLogin'])->name('admin.login');
Route::post('/admin/login', [AdminAuthController::class, 'login'])->name('admin.login.post');
Route::post('/admin/logout', [AdminAuthController::class, 'logout'])->name('admin.logout');

Route::middleware('admin.auth')->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');
    Route::get('/credit-levels', [AdminCreditLevelController::class, 'index'])->name('credit-levels.index');
    Route::get('/credit-levels/{creditLevel}/edit', [AdminCreditLevelController::class, 'edit'])->name('credit-levels.edit');
    Route::put('/credit-levels/{creditLevel}', [AdminCreditLevelController::class, 'update'])->name('credit-levels.update');
    Route::get('/settings', [AdminSettingController::class, 'edit'])->name('settings.edit');
    Route::put('/settings', [AdminSettingController::class, 'update'])->name('settings.update');
});
