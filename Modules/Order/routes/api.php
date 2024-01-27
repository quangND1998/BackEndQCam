<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Modules\Order\app\Http\Controllers\API\ApiShipperController;

/*
    |--------------------------------------------------------------------------
    | API Routes
    |--------------------------------------------------------------------------
    |
    | Here is where you can register API routes for your application. These
    | routes are loaded by the RouteServiceProvider within a group which
    | is assigned the "api" middleware group. Enjoy building your API!
    |
*/

Route::middleware(['auth:sanctum'])->prefix('v1')->name('api.')->group(function () {
    Route::get('order', fn (Request $request) => $request->user())->name('order');
});

Route::middleware(['auth:sanctum'])->prefix('v1')->name('api.')->group(function () {
    Route::get('orderStatus', [ApiShipperController::class, 'getOrderStatus'])->name('orderStatus');
    Route::get('fetchOrders', [ApiShipperController::class, 'fetchOrders'])->name('fetchOrders');


    Route::prefix('shipper')->as('shipper.')->group(function () {

        Route::put('/{id}/confirm-shipping', [ApiShipperController::class, 'confirmShipping'])->name('confirm-shipping');
        Route::put('/{id}/confirm-not-shipping', [ApiShipperController::class, 'confirmNotShipping'])->name('confirm-not-shipping');
        Route::post('/{id}/confirm-recive', [ApiShipperController::class, 'confirmCustomerRecive'])->name('confirm-recive');

        Route::post('/{id}/upload-order', [ApiShipperController::class, 'uploadOrder'])->name('uploadOrder');

        Route::delete('{id}/order/{media_id}/deleteImage', [ApiShipperController::class, 'deleteImage'])->name('deleteImage');
    });
});
