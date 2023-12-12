<?php

use App\Http\Controllers\DashBoardController;
use Illuminate\Support\Facades\Route;

Route::prefix('dashboard')->as('dashboard.')->group(function () {
    
    Route::prefix('sale')->as('sale.')->group(function () {
        Route::get('index', [DashBoardController::class, 'index'])->name('index');
        
    });
});