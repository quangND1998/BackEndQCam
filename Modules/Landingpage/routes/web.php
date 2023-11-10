<?php

use Illuminate\Support\Facades\Route;
use Modules\Landingpage\app\Http\Controllers\LandingpageController;
use Modules\Landingpage\app\Http\Controllers\NewsController;
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::group([], function () {
    Route::resource('landingpage', LandingpageController::class)->names('landingpage');

});
Route::middleware(['auth'])->group(
    function () {
    Route::resource('news', NewsController::class)->names('news');
    Route::delete('news/{id}', [NewsController::class,'destroy'])->name('news.delete');
    }
)
;
