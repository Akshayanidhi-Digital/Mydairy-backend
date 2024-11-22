<?php

use App\Http\Controllers\v1\Transport\AuthController;
use App\Http\Controllers\v1\Transport\DriverController;
use App\Http\Controllers\v1\Transport\HomeController;
use App\Http\Controllers\v1\Transport\RouteController;
use App\Http\Controllers\v1\Transport\UserController;
use App\Http\Controllers\v1\Transport\VehicleController;
use Illuminate\Support\Facades\Route;



Route::match(['get', 'post'], '/login', [AuthController::class, 'index'])->name('login');
Route::post('/forgot-password', [AuthController::class, 'forgotPassword'])->name('forgotPassword');
Route::get('logout', [AuthController::class, 'logout'])->name('logout');
Route::group(['middleware' => ['auth:transport']], function () {
    Route::get('/', [HomeController::class, 'index'])->name('dashboard');
    Route::group(['prefix' => 'profile', 'as' => 'profile.'], function () {
        Route::get('/', [UserController::class, 'index'])->name('index');
        Route::get('/edit', [UserController::class, 'edit'])->name('edit');
        Route::post('/update', [UserController::class, 'update'])->name('update');
        Route::match(['get', 'post'], '/password-change', [UserController::class, 'updatePassword'])->name('password.update');
    });
    Route::group(['prefix' => 'driver', 'as' => 'driver.'], function () {
        Route::get('/', [DriverController::class, 'index'])->name('index');
        Route::get('/add', [DriverController::class, 'add'])->name('add');
        Route::get('{id}/edit', [DriverController::class, 'edit'])->name('edit');
        Route::post('/store', [DriverController::class, 'store'])->name('store');
        Route::post('{id}/update', [DriverController::class, 'update'])->name('update');
        Route::post('/status-update', [DriverController::class, 'updateStatus'])->name('updateStatus');
        Route::post('/delete', [DriverController::class, 'delete'])->name('delete');
    });
    Route::group(['prefix' => 'vehicle', 'as' => 'vehicle.'], function () {
        Route::get('/', [VehicleController::class, 'index'])->name('index');
        Route::get('/add', [VehicleController::class, 'add'])->name('add');
        Route::get('{id}/edit', [VehicleController::class, 'edit'])->name('edit');
        Route::post('/store', [VehicleController::class, 'store'])->name('store');
        Route::post('{id}/update', [VehicleController::class, 'update'])->name('update');
        Route::post('/status-update', [VehicleController::class, 'updateStatus'])->name('updateStatus');
        Route::post('/delete', [VehicleController::class, 'delete'])->name('delete');
    });
    Route::group(['prefix' => 'route', 'as' => 'route.'], function () {
        Route::get('/', [RouteController::class, 'index'])->name('index');
        //     Route::get('/add', [VehicleController::class, 'add'])->name('add');
        //     Route::get('{id}/edit', [VehicleController::class, 'edit'])->name('edit');
        //     Route::post('/store', [VehicleController::class, 'store'])->name('store');
        //     Route::match([],'{id}/update', [VehicleController::class, 'update'])->name('update');
        //     Route::post('/status-update', [VehicleController::class, 'updateStatus'])->name('updateStatus');
        //     Route::post('/delete', [VehicleController::class, 'delete'])->name('delete');
    });
    Route::match(['get', 'post'], '/settings', [UserController::class, 'settings'])->name('settings');
});
