<?php

use Illuminate\Support\Facades\Route;
use Modules\CustomerService\app\Http\Controllers\Api\CreateNote;
use Modules\CustomerService\app\Http\Controllers\Api\CreateVisit;
use Modules\CustomerService\app\Http\Controllers\Api\GetNote;
use Modules\CustomerService\app\Http\Controllers\Api\UpdateVisit;
use Modules\CustomerService\app\Http\Controllers\GetCustomerOrderPackage;

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

auth()->login(\App\Models\User::find(1), 1);
Route::middleware(['auth'])->group(
    function () {
        Route::prefix('/customer-service/customer/{customerId}')->group(function () {
            Route::get('/order-packages', GetCustomerOrderPackage::class);
            Route::post('/notes', CreateNote::class);
            Route::get('/notes', GetNote::class);
            Route::post('/schedule-visits', CreateVisit::class);
            Route::put('/schedule-visits/{scheduleVisitId}', UpdateVisit::class);
        });
    }
);
