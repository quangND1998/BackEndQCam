<?php

use Illuminate\Support\Facades\Route;
use Modules\Booking\app\Http\Controllers\BookingController;
use Modules\Booking\app\Http\Controllers\BookingManagerController;

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

auth()->loginUsingId(1);
Route::middleware(['auth'])->group(
    function () {
        Route::prefix('admin')->as('admin.')->group(function () {
            Route::resource('booking', BookingController::class)->names('booking');
            Route::prefix('booking')->as('booking.')->group(function () {
                Route::get('{booking}/detail', [BookingManagerController::class, 'index'])->name('detail');
                Route::post('{booking}/generate', [BookingManagerController::class, 'generate'])->name('generate');
                Route::post('{code}/changeRef', [BookingManagerController::class, 'changeRef'])->name('changeRef');

            });
        });
    }
);
