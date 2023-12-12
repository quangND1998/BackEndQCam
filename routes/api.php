<?php

use App\Http\Controllers\API\FAQsController;
use App\Http\Controllers\API\LoginController;
use App\Http\Controllers\API\NotificationController;
use App\Http\Controllers\API\OrderController;
use App\Http\Controllers\API\ProductRetailController;
use App\Http\Controllers\API\SettingAndInforController;
use App\Http\Controllers\API\ShipperController;
use App\Http\Controllers\API\VoucherController;
use Illuminate\Http\Request;
use Modules\Customer\app\Http\Controllers\API\ScheduleVisitController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PaymentController;
/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
Route::prefix('v1')->as('v1.')->group(function () {

    Route::post('login', [LoginController::class, 'login'])->name('login');
    Route::post('loginOtp', [LoginController::class, 'loginOtp'])->name('loginOtp');
    Route::post('verify', [LoginController::class, 'verify'])->name('verify');
});

Route::prefix('payoo')->as('payoo.')->group(function () {
    Route::post('ipn', [PaymentController::class, 'payooIPN'])->name('payooIPN');
            
});
Route::middleware(['auth:sanctum'])->group(function () {

    Route::prefix('v1')->as('v1.')->group(function () {


        // logout
        Route::post('logout', [LoginController::class, 'logout']);
        Route::post('updatePassword', [LoginController::class, 'updatePassword']);
        Route::post('verifyChangePassword', [LoginController::class, 'verifyChangePassword'])->name('verifyChangePassword');
        Route::post('getFireBaseToken', [LoginController::class, 'getFireBaseToken']);
        Route::post('sendOtp', [LoginController::class, 'sendOtp'])->name('sendOtp');
        // Product-retail
        Route::get('product-retail', [ProductRetailController::class, 'getProducts']);
        Route::get('product-retail/{id}/detail', [ProductRetailController::class, 'productDetail']);
        // FAQs
        Route::get('faqs', [SettingAndInforController::class, 'FAQs']);

        // term & condition
        Route::get('term_condition', [SettingAndInforController::class, 'get']);
        // term & condition
        Route::get('contact', [SettingAndInforController::class, 'contact']);


        // Voucher

        Route::prefix('voucher')->as('voucher.')->group(function () {
            Route::get('{code}/find', [VoucherController::class, 'findVoucher'])->name('find');
            Route::get('listVoucher', [VoucherController::class, 'getVouchers'])->name('list');
        });


        // Voucher

        Route::prefix('order')->as('order.')->group(function () {

            Route::post('saveOrder', [OrderController::class, 'saveOrder'])->name('saveOrder');

            Route::get('lisOrder', [OrderController::class, 'getUserOrders'])->name('lisOrder');

            Route::put('{id}/orderCompeleted', [OrderController::class, 'orderCompeleted'])->name('orderCompeleted');
            Route::put('{id}/orderCancel', [OrderController::class, 'orderCancel'])->name('orderCancel');
            
        });


        Route::prefix('notification')->as('notification.')->group(function () {

            Route::get('all', [NotificationController::class, 'notifications'])->name('all');

            Route::get('unreadNotifications', [NotificationController::class, 'getUnreadNotifications'])->name('unreadNotifications');
            Route::post('readNotifications', [NotificationController::class, 'readNotifications'])->name('readNotifications');
            Route::delete('deleteNotifications', [NotificationController::class, 'deleteNotifications'])->name('deleteNotifications');
        });

        Route::prefix('shipper')->as('shipper.')->group(function () {

            Route::get('ordersRetailShipper', [ShipperController::class, 'ordersRetailShipper'])->name('ordersRetailShipper');
            Route::get('ordersGiftShipper', [ShipperController::class, 'ordersGiftShipper'])->name('ordersGiftShipper');

            Route::get('{id}/orderDetail', [ShipperController::class, 'orderDetail'])->name('orderDetail');
            Route::post('{id}/saveImageCompleted', [ShipperController::class, 'saveImageCompleted'])->name('saveImageCompleted');
        });


        Route::prefix('user')->as('user.')->group(function () {

            Route::post('updateUserInfor', [LoginController::class, 'updateUserInfor'])->name('updateUserInfor');
            Route::get('getUser', [LoginController::class, 'getUser'])->name('getUser');
        });
    });
});
