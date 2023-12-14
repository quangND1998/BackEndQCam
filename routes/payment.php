<?php

use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;


use App\Http\Controllers\PaymentController;
use App\Http\Controllers\ShowPaymentController;

Route::middleware(['auth'])->group(
    function () {
       

        Route::prefix('payoo')->as('payoo.')->group(function () {
            Route::get('payment/{order}', [PaymentController::class, 'payment'])->name('payment');
            Route::get('GetOrderInfo/{order}', [PaymentController::class, 'GetOrderInfo'])->name('GetOrderInfo');

            Route::get('paymentOption/{order}', [ShowPaymentController::class, 'paymentOption'])->name('paymentOption');
            Route::get('paymentOption/{order}', [ShowPaymentController::class, 'paymentOption'])->name('paymentOption');
        });

        Route::prefix('payment')->as('payment.')->group(function () {
    
            Route::get('list', [ShowPaymentController::class, 'listPayment'])->name('listPayment');
        });

       
    }

);