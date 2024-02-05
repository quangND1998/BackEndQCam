<?php

use Illuminate\Support\Facades\Route;
use Modules\CallCenter\app\Http\Controllers\CallCenterController;

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
    Route::resource('callcenter', CallCenterController::class)->names('callcenter');
});
