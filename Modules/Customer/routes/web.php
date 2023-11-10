<?php

use Illuminate\Support\Facades\Route;
use Modules\Customer\app\Http\Controllers\CustomerController;
use Modules\Customer\app\Http\Controllers\CustomerDetailController;
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
    Route::resource('customers', CustomerController::class)->names('customer');
    Route::prefix('customer/{id}')->as('customer.detail.')->group(function () {
        Route::get('info', [CustomerDetailController::class, 'info'])->name('info');
        Route::post('products', [CustomerDetailController::class, 'store'])->name('store');
        Route::put('/activity', [CustomerDetailController::class, 'update'])->name('update');
        Route::delete('/service', [CustomerDetailController::class, 'delete'])->name('delete');
    });
});
