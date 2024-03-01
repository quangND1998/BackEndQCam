<?php

use Illuminate\Support\Facades\Route;
use Modules\Tree\app\Http\Controllers\ActivityCareController;
use Modules\Tree\app\Http\Controllers\API\ProductRefundController;
use Modules\Tree\app\Http\Controllers\HistoryCareController;
use Modules\Tree\app\Http\Controllers\LandController;
use Modules\Tree\app\Http\Controllers\ProductAddController;
use Modules\Tree\app\Http\Controllers\ProductFailController;
use Modules\Tree\app\Http\Controllers\ProductRetailController;
use Modules\Tree\app\Http\Controllers\ProductServiceController;
use Modules\Tree\app\Http\Controllers\TreeController;
use Modules\Tree\app\Models\ActivityCare;

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
    Route::resource('tree', TreeController::class)->names('tree');
    Route::get('tree/qrcode/{qr}', [TreeController::class, 'treeDetail'])->name('qrcode_tree');
});

Route::middleware(['auth'])->group(
    function () {
        Route::prefix('admin')->as('admin.')->group(function () {
            Route::prefix('land')->as('land.')->group(function () {
                Route::get('all', [LandController::class, 'index'])->name('index');
                Route::post('', [LandController::class, 'store'])->name('store');
                Route::put('/update/{land}', [LandController::class, 'update'])->name('update');
                Route::delete('/delete/{land}', [LandController::class, 'destroy'])->name('delete');
                Route::prefix('{land}/trees')->as('tree.')->group(function () {
                    Route::get('all', [TreeController::class, 'index'])->name('index');
                    Route::post('store', [TreeController::class, 'store'])->name('store');
                });
                Route::prefix('tree')->as('tree.')->group(function () {
                    Route::post('/{tree}/update', [TreeController::class, 'update'])->name('update');
                    Route::delete('/{tree}/destroy', [TreeController::class, 'destroy'])->name('destroy');
                });
            });

            Route::prefix('product-retails')->as('product-retail.')->group(function () {
                Route::get('', [ProductRetailController::class, 'index'])->name('index');
                Route::post('store', [ProductRetailController::class, 'store'])->name('store');
                Route::post('/{product_retail}/update', [ProductRetailController::class, 'update'])->name('update');
                Route::delete('/{product_retail}/destroy', [ProductRetailController::class, 'destroy'])->name('destroy');
                Route::post('/changeStatus', [ProductRetailController::class, 'changeStatus'])->name('changeStatus');
            });

            Route::prefix('product-services')->as('product-service.')->group(function () {
                Route::get('', [ProductServiceController::class, 'index'])->name('index');
                Route::post('store', [ProductServiceController::class, 'store'])->name('store');
                Route::post('/{product_service}/update', [ProductServiceController::class, 'update'])->name('update');
                Route::delete('/{product_service}/destroy', [ProductServiceController::class, 'destroy'])->name('destroy');
                Route::post('/changeStatus', [ProductServiceController::class, 'changeStatus'])->name('changeStatus');
            });
            Route::prefix('activity-care')->as('activity-care.')->group(function () {
                Route::get('', [ActivityCareController::class, 'index'])->name('index');
                Route::post('store', [ActivityCareController::class, 'store'])->name('store');
                Route::post('/{activity}/update', [ActivityCareController::class, 'update'])->name('update');
                Route::delete('/{activity}/destroy', [ActivityCareController::class, 'destroy'])->name('destroy');
            });
            Route::prefix('historyCare')->as('historyCare.')->group(function () {
                Route::post('{tree}/store', [HistoryCareController::class, 'store'])->name('store');
                Route::post('storeLand', [HistoryCareController::class, 'storeLand'])->name('storeLand');
                Route::delete('/{historyCare}/destroy', [HistoryCareController::class, 'destroy'])->name('destroy');
            });
            Route::prefix('importProduct')->as('importProduct.')->group(function () {
                Route::get('index', [ProductAddController::class, 'index'])->name('index');
                Route::post('store', [ProductAddController::class, 'store'])->name('store');
                Route::post('update/{id}', [ProductAddController::class, 'update'])->name('update');
                Route::delete('destroy/{id}', [ProductAddController::class, 'destroy'])->name('destroy');
                Route::post('confirm/{id}', [ProductAddController::class, 'confirm'])->name('confirm');
            });
            Route::prefix('warehouse')->as('warehouse.')->group(function () {
                Route::get('', ProductRefundController::class)->name('index');
                Route::post('product/{id}/confirm', [ProductRefundController::class, 'confirm'])->name('confirm');
            });
            Route::prefix('product_fail')->as('product_fail.')->group(function () {
                Route::get('index', [ProductFailController::class,'index'])->name('index');
                Route::get('getProduct', [ProductFailController::class,'getProduct'])->name('getProduct');
                Route::post('store', [ProductFailController::class, 'store'])->name('store');
                Route::post('destroyProduct/{id}', [ProductFailController::class, 'destroy'])->name('destroyProduct');
            });
        });
    }
);
