<?php

use Illuminate\Support\Facades\Route;
use Modules\Tree\app\Http\Controllers\LandController;
use Modules\Tree\app\Http\Controllers\TreeController;

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
});


Route::middleware(['auth'])->group(
    function () {
        Route::prefix('admin')->as('admin.')->group(function () {
            Route::prefix('land')->as('land.')->group(function () {
                Route::get('all', [LandController::class, 'index'])->name('index');
                Route::post('', [LandController::class, 'store'])->name('store');
                Route::put('/update/{land}', [LandController::class, 'update'])->name('update');
                Route::delete('/delete/{land}', [LandController::class, 'delete'])->name('delete');
                Route::prefix('{land}/trees')->as('tree.')->group(function () {
                    Route::get('all', [TreeController::class, 'index'])->name('index');
                    Route::post('store', [TreeController::class, 'store'])->name('store');
                });
                Route::prefix('tree')->as('tree.')->group(function () {
                    Route::post('/{tree}/update', [TreeController::class, 'update'])->name('update');
                    Route::delete('/{tree}/delete', [TreeController::class, 'delete'])->name('delete');
                });
            });
        });
    }
);
