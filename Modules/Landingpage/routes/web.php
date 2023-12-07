<?php

use Illuminate\Support\Facades\Route;
use Modules\Landingpage\app\Http\Controllers\ContactController;
use Modules\Landingpage\app\Http\Controllers\LandingpageController;
use Modules\Landingpage\app\Http\Controllers\NewsController;
use Modules\Landingpage\app\Http\Controllers\QuestionAnswerController;
use Modules\Landingpage\app\Http\Controllers\TermsConditionController;

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
    Route::resource('landingpage', LandingpageController::class)->names('landingpage');
});
Route::middleware(['auth'])->group(
    function () {
        Route::resource('news', NewsController::class)->names('news');
        Route::post('news/update/{new}', [NewsController::class, 'update'])->names('news.update');
        Route::delete('news/{id}', [NewsController::class, 'destroy'])->name('news.delete');
        Route::post('updatenew/{id}', [NewsController::class, 'update'])->name('updatenew');


        Route::prefix('admin')->as('admin.')->group(function () {
            Route::prefix('terms')->as('terms.')->group(function () {
                Route::get('', [TermsConditionController::class, 'index'])->name('index');
                Route::post('', [TermsConditionController::class, 'store'])->name('store');
                Route::put('{term}/update', [TermsConditionController::class, 'update'])->name('update');
            });

            Route::prefix('contact')->as('contact.')->group(function () {
                Route::get('', [ContactController::class, 'index'])->name('index');
                Route::post('', [ContactController::class, 'store'])->name('store');
                Route::put('{contact}/update', [ContactController::class, 'update'])->name('update');
            });

            Route::prefix('FAQs')->as('FAQs.')->group(function () {
                Route::get('', [QuestionAnswerController::class, 'index'])->name('index');
                Route::post('', [QuestionAnswerController::class, 'store'])->name('store');
                Route::put('{question}/update', [QuestionAnswerController::class, 'update'])->name('update');
                Route::delete('{question}/destroy', [QuestionAnswerController::class, 'destroy'])->name('destroy');
            });
        });
    }


);
