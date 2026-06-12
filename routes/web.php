<?php

use App\Http\Controllers\CreditSimulatorController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect('/simulador-creditos');
});

Route::get('/simulador-creditos', CreditSimulatorController::class)->name('simulador-creditos');
