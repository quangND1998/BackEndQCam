<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Modules\Customer\app\Http\Controllers\API\CustomerProductOwerController;
use Modules\Customer\app\Http\Controllers\API\ScheduleVisitController;
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

Route::prefix('v1')->name('api.')->group(function () {
    Route::prefix('customer')->as('customer.')->group(function () {
        Route::get('product_service', [CustomerProductOwerController::class, 'getProductService']);
        Route::get('productWithID/{id}', [CustomerProductOwerController::class, 'getOneProductActivity']);

        Route::post('visit/save', [ScheduleVisitController::class, 'saveScheduleVisit']);
        Route::get('visit', [ScheduleVisitController::class, 'getsheduleCustomer']);
    });
});
