<?php

use Illuminate\Support\Facades\Route;
use Modules\Customer\app\Http\Controllers\ContractController;
use Modules\Customer\app\Http\Controllers\CustomerController;
use Modules\Customer\app\Http\Controllers\CustomerDetailController;
use Modules\Customer\app\Http\Controllers\CustomerActivityController;
use Modules\Customer\app\Http\Controllers\CustomerServiceController;
use Modules\Customer\app\Http\Controllers\CustomerProductController;
use Modules\Customer\app\Http\Controllers\ShipperController;

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
        Route::get('orderPackage/create', [CustomerController::class, 'orderPackage'])->name('orderPackage.create');
        Route::post('orderPackage/save', [CustomerController::class, 'saveOrderPackage'])->name('orderPackage.save');
        Route::resource('customers', CustomerController::class)->names('customer');
        Route::prefix('customer/{id}')->as('customer.detail.')->group(function () {
            Route::get('info', [CustomerDetailController::class, 'info'])->name('info');

            Route::get('document', [CustomerDetailController::class, "listDocument"])->name('document');
            Route::resource('products', CustomerProductController::class)->names('products');
            Route::get('gift', [CustomerActivityController::class, "gift"])->name('gift');
            Route::resource('activity', CustomerActivityController::class)->names('activity');
            Route::resource('service', CustomerServiceController::class)->names('service');
        });
        Route::prefix('product_owner/{id}')->as('product_owner.')->group(function () {
            Route::resource('contract', ContractController::class)->names('contract');
            Route::post('extend', [CustomerProductController::class, 'extend'])->name('extend');
            Route::post('contract/{idContract}/PostUpdate', [ContractController::class, 'update'])->name('contract.PostUpdate');

            Route::get('extend', [CustomerProductController::class, 'getExtendHistory'])->name('getExtendHistory');

            Route::post('history_extend', [CustomerProductController::class, 'editHistoryExtend'])->name('history_extend');
        });
        Route::prefix('admin/shippers')->as('shippers.')->group(function () {
            Route::get('', [ShipperController::class, 'index'])->name('index');
            Route::get('{shipper}/detail', [ShipperController::class,  'shipperDetail'])->name('detail');
        });
    }
);
