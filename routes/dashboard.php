<?php

use App\Http\Controllers\DashBoardController;
use Illuminate\Support\Facades\Route;

Route::middleware([
        'auth:sanctum',
        config('jetstream.auth_session'),
        'verified',
    ])->group(function () {


    Route::get('dashboard', [DashBoardController::class, 'index'])->name('dashboard');
    Route::prefix('sale')->as('sale.')->group(function () {
       
        
    });
});