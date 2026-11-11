<?php

use App\Http\Controllers\AnalitikController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\PemantauanController;
use App\Http\Controllers\PerangkatController;
use App\Http\Controllers\WisatawanController;
use App\Http\Controllers\AuthController;
use Illuminate\Support\Facades\Route;

Route::get('/login', [AuthController::class, 'signin'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

Route::middleware(['auth'])->group(function () {
    Route::redirect('/', '/dashboard');

    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/dashboard/data', [DashboardController::class, 'data'])->name('dashboard.data');

    Route::resource('wisatawan', WisatawanController::class);
    Route::resource('perangkat', PerangkatController::class);

    Route::controller(PemantauanController::class)
        ->prefix('pemantauan')
        ->name('pemantauan.')
        ->group(function () {
            Route::get('/', 'index')->name('index');
            Route::get('/{wellnessSession}', 'show')->name('show');            // Route Model Binding
            Route::patch('/{wellnessSession}/selesai', 'selesai')->name('selesai');
        });

    Route::get('/analitik', [AnalitikController::class, 'index'])->name('analitik.index');
});
