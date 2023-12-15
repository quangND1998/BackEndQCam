<?php

use App\Http\Controllers\DashBoardController;
use Illuminate\Support\Facades\Route;

Route::middleware([
        'auth:sanctum',
        config('jetstream.auth_session'),
        'verified',
    ])->group(function () {


    Route::get('dashboard', [DashBoardController::class, 'index'])->name('dashboard');
    Route::prefix('dashboard')->as('dashboard.')->group(function () {
        Route::prefix('leader-sale')->as('leader-sale.')->group(function () {
            Route::get('', [DashBoardController::class, 'leaderSale'])->name('leade-sale');
        });
    });
});
