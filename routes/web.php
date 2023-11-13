<?php

use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use App\Http\Controllers\Admin\PermissionController;
use App\Http\Controllers\Admin\RolesController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Home\ScheduleVisitController;
use App\Http\Controllers\TestController;

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

Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
])->group(function () {
    Route::get('/dashboard', function () {
        return Inertia::render('HomeView');
    })->name('dashboard');
});
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
        });
    }

);
