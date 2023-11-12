<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Modules\Order\app\Http\Controllers\CartController;

/*
    |--------------------------------------------------------------------------
    | API Routes
    |--------------------------------------------------------------------------
    |
    | Here is where you can register API routes for your application. These
    | routes are loaded by the RouteServiceProvider within a group which
    | is assigned the "api" middleware group. Enjoy building your API!
    |
*/

Route::middleware(['auth:sanctum'])->prefix('v1')->name('api.')->group(function () {
    Route::get('tree', fn (Request $request) => $request->user())->name('tree');
});
Route::prefix('v1')->group(function () {
    Route::get('cart', [CartController::class,'getCart']);
    Route::post('addToCart', [CartController::class,'addToCart']);
    Route::post('updateCart', [CartController::class,'updateCart']);
    Route::post('removeItem', [CartController::class,'removeItem']);
    Route::post('clearCart', [CartController::class,'clearCart']);
    
});
