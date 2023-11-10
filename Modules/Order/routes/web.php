<?php

use Illuminate\Support\Facades\Route;
use Modules\Order\app\Http\Controllers\OrderController;
use Modules\Order\app\Http\Controllers\VoucherController;

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
    Route::resource('order', OrderController::class)->names('order');
});

Route::middleware(['auth'])->group(
    function () {
        Route::prefix('admin')->as('admin.')->group(function () {
            Route::prefix('vouchers')->as('voucher.')->group(function () {
                Route::get('all', [VoucherController::class, 'index'])->name('index');
                Route::post('', [VoucherController::class, 'store'])->name('store');
                Route::put('/update/{voucher}', [VoucherController::class, 'update'])->name('update');
                Route::delete('/delete/{voucher}', [VoucherController::class, 'destroy'])->name('destroy');

                Route::get('{voucher}/products', [VoucherController::class, 'voucher_project'])->name('products');
            });
        });
    }
);
