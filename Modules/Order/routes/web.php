<?php

use Illuminate\Support\Facades\Route;
use Modules\Order\app\Http\Controllers\OrderController;
use Modules\Order\app\Http\Controllers\ProductServiceVoucherController;
use Modules\Order\app\Http\Controllers\ProductVoucherController;
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
                Route::get('{voucher}/product-service', [VoucherController::class, 'getVoucherProjectServices'])->name('product-service.index');

                Route::delete('items/delete', [ProductVoucherController::class, 'deleteItems'])->name('deleteItems');
                Route::post('saveItems/{voucher}', [ProductVoucherController::class, 'saveItems'])->name('saveItems');
                Route::post('updateDiscount', [ProductVoucherController::class, 'updateDiscount'])->name('updateDiscount');
                Route::delete('deleteProductVoucher/{product_voucher}', [ProductVoucherController::class, 'deleteProductVoucher'])->name('deleteProductVoucher');
                Route::prefix('product-service')->as('product-service.')->group(function () {

                    Route::delete('/deleteItems', [ProductServiceVoucherController::class, 'deleteItems'])->name('deleteItems');
                    Route::post('saveItems/{voucher}', [ProductServiceVoucherController::class, 'saveItems'])->name('saveItems');
                    Route::post('updateDiscount', [ProductServiceVoucherController::class, 'updateDiscount'])->name('updateDiscount');
                    Route::delete('deleteProductVoucher/{product_service_voucher}', [ProductServiceVoucherController::class, 'deleteProductServiceVoucher'])->name('deleteProductServiceVoucher');
                });
            });

            Route::prefix('orders')->as('orders.')->group(function () {
                Route::get('all', [OrderController::class, 'index'])->name('index');
                Route::get('/pending', [OrderController::class, 'pending'])->name('pending');
                Route::get('/packing', [OrderController::class, 'packing'])->name('packing');
                Route::get('/shipping', [OrderController::class, 'shipping'])->name('shipping');
                Route::get('/completed', [OrderController::class, 'completed'])->name('completed');
                Route::get('/refund', [OrderController::class, 'refund'])->name('refund');
                Route::get('/decline', [OrderController::class, 'decline'])->name('decline');


                Route::post('orderCancel/{order}', [OrderController::class, 'orderCancel'])->name('orderCancel');
                Route::post('orderRefund/{order}', [OrderController::class, 'orderRefund'])->name('orderRefund');

                Route::post('orderChangeStatus/{order}', [OrderController::class, 'orderChangeStatus'])->name('orderChangeStatus');
                Route::post('orderChangePayment', [OrderController::class, 'orderChangePayment'])->name('orderChangePayment');

                Route::get('/create', [OrderController::class, 'createOrder'])->name('create');
            });
        });
    }
);
