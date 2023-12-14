<?php

use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use App\Http\Controllers\Admin\PermissionController;
use App\Http\Controllers\Admin\RolesController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\ComissionController;
use App\Http\Controllers\Home\ScheduleVisitController;
use App\Http\Controllers\MediaController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\TestController;
use Modules\Landingpage\app\Http\Controllers\TermsConditionController;
use App\Http\Controllers\PreviewController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\OtpTestController;
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

Route::get('/', function () {
    return redirect('/login');
});
Route::get('preview/data/{url?}',[PreviewController::class,'previewData']);
Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
])->group(function () {
    Route::get('/dashboard', function () {
        return Inertia::render('HomeView');
    })->name('dashboard');
});

Route::get('terms&condition', [TermsConditionController::class, 'previewTerm'])->name('terms&condition');
Route::middleware(['auth'])->group(
    function () {
        Route::prefix('permissions')->as('permissions.')->group(function () {
            Route::get('', [PermissionController::class, 'index'])->name('index');

            Route::post('', [PermissionController::class, 'store'])->name('store');
            Route::put('/update/{id}', [PermissionController::class, 'update'])->name('update');
            Route::delete('/delete/{id}', [PermissionController::class, 'delete'])->name('delete');
        });
        Route::prefix('roles')->as('roles.')->group(function () {
            Route::get('', [RolesController::class, 'index'])->name('index');
            Route::post('', [RolesController::class, 'store'])->name('store');
            Route::put('/update/{id}', [RolesController::class, 'update'])->name('update');
            Route::delete('/delete/{id}', [RolesController::class, 'delete'])->name('delete');
        });

        Route::prefix('users')->as('users.')->group(function () {
            Route::get('', [UserController::class, 'index'])->name('index');
            Route::post('', [UserController::class, 'store'])->name('store');
            Route::get('create-user', [UserController::class, 'create'])->name('create');
            Route::get('edit-user/{user}', [UserController::class, 'edit'])->name('edit');
            Route::put('/update/{id}', [UserController::class, 'update'])->name('update');
            Route::delete('/delete/{id}', [UserController::class, 'destroy'])->name('destroy');
            Route::post('setActive', [UserController::class, 'setActive'])->name('setActive');


            // Route::post('import',  [UserController::class, 'importUser'])->name('import');
            // Route::post('update-users',  [UserController::class, 'updateUsers'])->name('update-users');
            // Route::get('updateDemo', [UserController::class, 'updateDemo'])->name('update-demo');
        });

        Route::get('test', [TestController::class, 'index']);

        Route::prefix('visit')->as('visit.')->group(function () {
            Route::get('all', [ScheduleVisitController::class, 'getAll'])->name('all');
            Route::get('pending', [ScheduleVisitController::class, 'getPending'])->name('pending');
            Route::post('changeStateToConfirm/{id}', [ScheduleVisitController::class, 'changeState'])->name('changeStateToConfirm');
            Route::post('changeState/{id}', [ScheduleVisitController::class, 'changeState'])->name('changeState');

            Route::get('confirm', [ScheduleVisitController::class, 'getConfirm'])->name('confirm');
            Route::get('cancel', [ScheduleVisitController::class, 'getCancel'])->name('cancel');
            Route::get('completed', [ScheduleVisitController::class, 'getComplete'])->name('completed');

            Route::get('createShedule', [ScheduleVisitController::class, 'createShedule'])->name('createShedule');
            Route::get('edit/{schedule}', [ScheduleVisitController::class, 'edit'])->name('edit');
            Route::post('saveShedule', [ScheduleVisitController::class, 'saveShedule'])->name('saveShedule');
            Route::post('updateShedule/{schedule}', [ScheduleVisitController::class, 'updateShedule'])->name('updateShedule');
            
        });


        Route::prefix('notification')->as('notification.')->group(function () {
            Route::get('all', [NotificationController::class, 'index'])->name('all');
        });

        Route::prefix('media')->as('media.')->group(function () {
            Route::delete('delete/{id}', [MediaController::class, 'delete'])->name('delete');
        });



        Route::get('otp/token', [OtpTestController::class,'test'])->name('otp.test');
        Route::get('checkOtp/{otp}', [OtpTestController::class,'checkOtp'])->name('otp.checkOtp');


        Route::prefix('commission')->as('commission.')->group(function () {
            Route::get('index', [ComissionController::class, 'index'])->name('index');
            Route::post('', [ComissionController::class, 'store'])->name('store');
            Route::post('update/{commission}', [ComissionController::class, 'update'])->name('update');
            Route::post('changeStatus/{commission}', [ComissionController::class, 'changeStatus'])->name('changeStatus');

        });
    }

);
require __DIR__ . '/dashboard.php';
require __DIR__ . '/payment.php';
