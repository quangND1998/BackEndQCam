<?php

use Illuminate\Support\Facades\Route;
use Modules\CustomerService\app\Http\Controllers\Api\Complaints\CreateComplaint;
use Modules\CustomerService\app\Http\Controllers\Api\DistributeCalls\GetDistributeCall;
use Modules\CustomerService\app\Http\Controllers\Api\ExtraServices\CreateExtraService;
use Modules\CustomerService\app\Http\Controllers\Api\ExtraServices\GetExtraService;
use Modules\CustomerService\app\Http\Controllers\Api\ExtraServices\UpdateExtraService;
use Modules\CustomerService\app\Http\Controllers\Api\Notes\CreateNote;
use Modules\CustomerService\app\Http\Controllers\Api\Reminds\CreateRemind;
use Modules\CustomerService\app\Http\Controllers\Api\ScheduleVisits\CreateVisit;
use Modules\CustomerService\app\Http\Controllers\Api\Notes\GetNote;
use Modules\CustomerService\app\Http\Controllers\Api\Orders\CreateOrder;
use Modules\CustomerService\app\Http\Controllers\Api\Orders\CreateRetailOrder;
use Modules\CustomerService\app\Http\Controllers\Api\Orders\UpdateOrder;
use Modules\CustomerService\app\Http\Controllers\Api\ProductRetils\GetProductRetails;
use Modules\CustomerService\app\Http\Controllers\Api\ScheduleVisits\UpdateVisit;
use Modules\CustomerService\app\Http\Controllers\GetCustomerOrderPackage;
use Modules\CustomerService\app\Http\Controllers\Api\Reminds\GetRemind;
use Modules\CustomerService\app\Http\Controllers\Api\Reminds\UpdateRemind;
use Modules\CustomerService\app\Http\Controllers\GetRecentActivity;
use Modules\CustomerService\app\Http\Controllers\GetUserByPhoneNumber;
use Modules\CustomerService\app\Http\Controllers\GetWeeklyPlan;

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
Route::middleware(['auth'])->group(
    function () {
        Route::prefix('/customer-service/customer/{customerId}')->group(function () {
            Route::get('/order-packages', GetCustomerOrderPackage::class);
            Route::post('/notes', CreateNote::class);
            Route::get('/notes', GetNote::class);
            Route::post('/schedule-visits', CreateVisit::class);
            Route::put('/schedule-visits/{scheduleVisit}', UpdateVisit::class);
            Route::post('/reminds', CreateRemind::class);
            Route::post('/orders', CreateOrder::class);
            Route::put('/orders/{order}', UpdateOrder::class);
            Route::post('/complaints', CreateComplaint::class);
            Route::get('/recent-activities', GetRecentActivity::class);
        });


        Route::prefix('/customer-service')->group(function () {
            Route::get('/weekly-plan', GetWeeklyPlan::class);
            Route::post('/orders', CreateRetailOrder::class);
            Route::get('/reminds', GetRemind::class);
            Route::put('/reminds/{remind}', UpdateRemind::class);
            Route::get('/product-retails', GetProductRetails::class);
            Route::get('/find-user-by-phone-number', GetUserByPhoneNumber::class);
            Route::get('/plans', GetDistributeCall::class);
        });

        Route::prefix('/extra-services')->group(function () {
            Route::get('/', GetExtraService::class);
            Route::post('/', CreateExtraService::class);
            Route::put('/{visitExtraService}', UpdateExtraService::class);
        });
    }
);
