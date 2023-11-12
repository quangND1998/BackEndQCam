<?php

use App\Http\Controllers\API\LoginController;
use App\Http\Controllers\API\ProductRetailController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
Route::prefix('v1')->as('v1.')->group(function () {

    Route::post('login', [LoginController::class, 'login'])->name('login');
    Route::post('loginOtp', [LoginController::class, 'loginOtp'])->name('loginOtp');
    Route::post('verify', [LoginController::class, 'verify'])->name('verify');
});
Route::middleware(['auth:sanctum'])->group(function () {

    Route::prefix('v1')->as('v1.')->group(function () {
        Route::get('product-retail', [ProductRetailController::class, 'getProducts']);
    });
});
