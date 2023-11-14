<?php

use Illuminate\Support\Facades\Route;
use Modules\Customer\app\Http\Controllers\ContractController;
use Modules\Customer\app\Http\Controllers\CustomerController;
use Modules\Customer\app\Http\Controllers\CustomerDetailController;
use Modules\Customer\app\Http\Controllers\CustomerActivityController;
use Modules\Customer\app\Http\Controllers\CustomerServiceController;
use Modules\Customer\app\Http\Controllers\CustomerProductController;
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

Route::middleware(['auth'])->group(
    function () {
    Route::resource('customers', CustomerController::class)->names('customer');
    Route::prefix('customer/{id}')->as('customer.detail.')->group(function () {
        Route::get('info', [CustomerDetailController::class, 'info'])->name('info');

        Route::resource('products', CustomerProductController::class)->names('products');
        Route::resource('activity', CustomerActivityController::class)->names('activity');
        Route::resource('service', CustomerServiceController::class)->names('service');


    });
    Route::prefix('product_owner/{id}')->as('product_owner.')->group(function () {
        Route::resource('contract', ContractController::class)->names('contract');
        Route::post('extend', [ContractController::class,'extend'])->name('extend');
    });
});
