<?php

use App\Http\Controllers\AnalitikController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\PemantauanController;
use App\Http\Controllers\PerangkatController;
use App\Http\Controllers\WisatawanController;
use Illuminate\Support\Facades\Route;

Route::redirect('/', '/dashboard');

Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
Route::get('/dashboard/data', [DashboardController::class, 'data'])->name('dashboard.data');

Route::get('/wisatawan', [WisatawanController::class, 'index'])->name('wisatawan.index');

Route::controller(PemantauanController::class)
    ->prefix('pemantauan')
    ->name('pemantauan.')
    ->group(function () {
        Route::get('/', 'index')->name('index');
        Route::post('/', 'store')->name('store');
        Route::get('/{wellnessSession}', 'show')->name('show');            // Route Model Binding
        Route::patch('/{wellnessSession}/selesai', 'selesai')->name('selesai');
    });

Route::get('/perangkat', [PerangkatController::class, 'index'])->name('perangkat.index');
Route::get('/analitik', [AnalitikController::class, 'index'])->name('analitik.index');