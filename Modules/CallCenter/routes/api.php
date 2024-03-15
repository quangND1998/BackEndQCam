<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Modules\CallCenter\app\Http\Controllers\API\CallInController;

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
    Route::get('callcenter', fn (Request $request) => $request->user())->name('callcenter');
});
Route::prefix('v1')->name('api.')->group(function () {
    Route::post('dataCallInBack', [CallInController::class, 'dataCallInBack']);
});
