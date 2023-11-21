<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Modules\Customer\app\Http\Controllers\API\ComplaintController;
use Modules\Customer\app\Http\Controllers\API\CustomerProductOwerController;
use Modules\Customer\app\Http\Controllers\API\ReviewManagerController;
use Modules\Customer\app\Http\Controllers\API\ScheduleVisitController;
use Modules\Customer\app\Http\Controllers\API\OrderHistoryController;
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

// Route::middleware(['auth:sanctum'])->prefix('v1')->name('api.')->group(function () {
//     Route::prefix('customer')->as('customer.')->group(function () {
//         Route::get('product_service', [CustomerProductOwerController::class, 'getProductService']);
//     });
// });
Route::middleware(['auth:sanctum'])->group(function () {
    Route::prefix('v1')->name('api.')->group(function () {
        Route::prefix('customer')->as('customer.')->group(function () {
            Route::get('product_service', [CustomerProductOwerController::class, 'getProductService']);
            Route::get('productWithID/{id}', [CustomerProductOwerController::class, 'getOneProductActivity']);
            Route::get('getDetailExtendHistory', [CustomerProductOwerController::class, 'getDetailExtendHistory']);

            Route::post('visit/save', [ScheduleVisitController::class, 'saveScheduleVisit']);
            Route::get('visit', [ScheduleVisitController::class, 'getsheduleCustomer']);
            Route::get('visit/{id}', [ScheduleVisitController::class, 'getVisitWithProduct']);

            Route::get('orderRetail', [OrderHistoryController::class, 'getListOrderRetail']);
            Route::get('orderGift', [OrderHistoryController::class, 'getListGift']);
            Route::get('orderGift/{id}', [OrderHistoryController::class, 'getHistoryGift']);
            // getHistoryGift
            Route::get('orderDetail/{id}', [OrderHistoryController::class, 'orderDetail']);


            Route::prefix('complaint')->as('customer.')->group(function () {
                Route::post('save', [ComplaintController::class, 'save']);
            });
            Route::prefix('review')->as('review.')->group(function () {
                Route::post('saveApp', [ReviewManagerController::class, 'saveApp']);
                Route::post('saveOrder/{id}', [ReviewManagerController::class, 'saveOrder']);
            });
        });
    });
});
