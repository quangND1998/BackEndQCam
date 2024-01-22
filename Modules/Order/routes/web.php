<?php

use Illuminate\Support\Facades\Route;
use Modules\Order\app\Http\Controllers\CSKH\CSKHOrderController;
use Modules\Order\app\Http\Controllers\CskhController;
use Modules\Order\app\Http\Controllers\HistoryPaymentController;
use Modules\Order\app\Http\Controllers\OrderController;
use Modules\Order\app\Http\Controllers\PaymentController;
use Modules\Order\app\Http\Controllers\ProductServiceVoucherController;
use Modules\Order\app\Http\Controllers\ProductVoucherController;
use Modules\Order\app\Http\Controllers\VoucherController;
use Modules\Order\app\Http\Controllers\OrderPackageController;
use Modules\Order\app\Http\Controllers\ReviewController;

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
    Route::get('order/qrcode/{qr}', [OrderController::class, 'scanOrderDetail'])->name('qrcode_order');
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
                Route::get('/{order}/update', [OrderController::class, 'edit'])->name('update');
                Route::get('/searchUser', [OrderController::class, 'searchUser'])->name('searchUser');
                Route::post('/addToCart', [OrderController::class, 'addToCart'])->name('addToCart');
                Route::post('saveOrder', [OrderController::class, 'saveOrder'])->name('saveOrder');
                Route::post('saveOrderGift', [OrderController::class, 'saveOrderGift'])->name('saveOrderGift');
                Route::post('/{order}/orderChangeShipping', [OrderController::class, 'orderChangeShipping'])->name('orderChangeShipping');
                Route::post('{order}/updateOrderRetail/{user}', [OrderController::class, 'updateOrderRetail'])->name('updateOrderRetail');
                Route::post('{order}/updateOrderGift/{user}', [OrderController::class, 'updateOrderGift'])->name('updateOrderGift');
                Route::prefix('package')->as('package.')->group(function () {
                    Route::get('all', [OrderPackageController::class, 'index'])->name('all');
                    // cskh
                    Route::get('cskh', [CskhController::class, 'cskh'])->name('cskh');
                    Route::get('pending', [OrderPackageController::class, 'listPending'])->name('pending');
                    Route::get('decline', [OrderPackageController::class, 'listOrderCancel'])->name('decline');
                    Route::get('complete', [OrderPackageController::class, 'listOrderComplete'])->name('complete');
                    Route::get('partiallyPaid', [OrderPackageController::class, 'partiallyPaid'])->name('partiallyPaid');
                    Route::get('draf', [OrderPackageController::class, 'drafPaid'])->name('draf');

                    Route::post('orderChangeStatus/{order}', [OrderPackageController::class, 'orderChangeStatus'])->name('orderChangeStatus');

                    Route::get('create', [OrderPackageController::class, 'orderPackage'])->name('create');
                    Route::get('edit/{id}', [OrderPackageController::class, 'editOrderPackage'])->name('edit');
                    Route::get('detail/{id}', [OrderPackageController::class, 'OrderPending'])->name('detail');
                    Route::post('/addToCartPackage', [OrderPackageController::class, 'addToCart'])->name('addToCartPackage');
                    Route::post('/editOrderPackage/{id}', [OrderPackageController::class, 'saveEditOrder'])->name('editToCartPackage');
                    // editOrderPackage
                    Route::post('orderCancel/{order}', [OrderPackageController::class, 'orderCancel'])->name('orderCancel');
                    Route::post('orderComplete/{order}', [OrderPackageController::class, 'orderComplete'])->name('orderComplete');
                    Route::post('orderDelete/{order}', [OrderPackageController::class, 'orderDelete'])->name('orderDelete');
                    Route::post('historyPayment/{order}', [OrderPackageController::class, 'saveHistoryPaymentOrder'])->name('historyPayment');
                    Route::post('{order}/deleteHistoryPayment/{id}', [HistoryPaymentController::class, 'deleteHistoryPayment'])->name('deleteHistoryPayment');
                    Route::post('{order}/payment/{id}/complete', [HistoryPaymentController::class, 'setPaymentComplete'])->name('setPaymentComplete');
                    Route::post('{order}/payment/{id}/completeDocument', [HistoryPaymentController::class, 'setPaymentCompleteDocument'])->name('setPaymentCompleteDocument');

                    Route::post('/payment/{id}/update', [HistoryPaymentController::class, 'updateImageHistoryPayment'])->name('saveImagePayment');

                    // saveHistoryPaymentOrder
                });
            });

            Route::prefix('cart')->as('cart.')->group(function () {


                Route::post('/updateCart', [OrderController::class, 'updateCart'])->name('updateCart');
                Route::post('/removeItem', [OrderController::class, 'removeItem'])->name('removeItem');
                Route::post('/removeCart', [OrderController::class, 'removeCart'])->name('removeCart');
                Route::post('/deleteCarts', [OrderController::class, 'deleteMultipleItem'])->name('deleteCarts');
                Route::get('/fetchCart', [OrderController::class, 'fetchCart'])->name('fetchCart');

                // Route::put('/update/{shipping}', [PaymentMethodsController::class, 'update'])->name('update');
                // Route::delete('/delete/{shipping}', [PaymentMethodsController::class, 'destroy'])->name('destroy');
            });


            Route::prefix('payment')->as('payment.')->group(function () {
                Route::get('/{order}/Cash&Banking', [PaymentController::class, 'orderCashBankingPayment'])->name('orderCashBankingPayment');
            });
            Route::prefix('review')->as('review.')->group(function () {
                Route::get('index', [ReviewController::class, 'index'])->name('index');
            });
            Route::prefix('cskh')->as('cskh.')->group(function () {
                Route::get('all', [CSKHOrderController::class, 'all'])->name('all');
            });


            Route::prefix('cskh')->as('cskh.')->group(function () {

                Route::post('pushOrder', [CSKHOrderController::class, 'pushOrder'])->name('pushOrder');
                Route::post('shipperOwner', [CSKHOrderController::class, 'shipperOwner'])->name('shipperOwner');

                Route::post('packedOrder', [CSKHOrderController::class, 'packedOrder'])->name('packedOrder');
                Route::post('shipperOwner', [CSKHOrderController::class, 'shipperOwner'])->name('shipperOwner');

                Route::get('pending', [CSKHOrderController::class, 'pending'])->name('pending');
                Route::get('packing', [CSKHOrderController::class, 'packing'])->name('packing');
                Route::get('packed', [CSKHOrderController::class, 'packed'])->name('packed');
                // Route::get('pending', [CSKHOrderController::class, 'pending'])->name('pending');
                // Route::get('pending', [CSKHOrderController::class, 'pending'])->name('pending');
                // Route::get('pending', [CSKHOrderController::class, 'pending'])->name('pending');
                // Route::get('pending', [CSKHOrderController::class, 'pending'])->name('pending');
                // Route::get('pending', [CSKHOrderController::class, 'pending'])->name('pending');
                // Route::get('pending', [CSKHOrderController::class, 'pending'])->name('pending');
            });
        });
    }
);
