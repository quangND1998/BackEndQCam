<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Modules\Landingpage\app\Http\Controllers\API\NewsController;

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
Route::prefix('landingpage')->as('landingpage.')->group(function () {
    Route::prefix('news')->as('news.')->group(function () {
        Route::get('index', [NewsController::class, 'index'])->name('index');
        Route::get('news', [NewsController::class, 'listNews'])->name('listNews');
        Route::get('activity', [NewsController::class, 'listActivity'])->name('listActivity');
    });
});
Route::middleware(['auth:sanctum'])->prefix('v1')->name('api.')->group(function () {

});
