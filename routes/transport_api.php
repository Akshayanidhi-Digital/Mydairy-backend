<?php

use App\Http\Controllers\Api\CommonController;
use App\Http\Controllers\Api\Transport\AuthController;
use App\Http\Controllers\Api\Transport\DriverController;
use App\Http\Controllers\Api\Transport\ProfileController;
use App\Http\Controllers\Api\Transport\RouteController;
use App\Http\Controllers\Api\Transport\VehicleController;
use Illuminate\Support\Facades\Route;




Route::post('/login', [AuthController::class, 'login']);
Route::post('/verify', [AuthController::class, 'verify']);
Route::post('/resend-otp', [AuthController::class, 'resendOtp']);
Route::post('/forgot-password', [AuthController::class, 'forgotPassword']);
Route::group(['middleware' => 'auth:transport-api'], function () {
    Route::post('/logout', [CommonController::class, 'logout']);
    Route::group(['prefix' => 'driver',], function () {
        Route::post('/', [DriverController::class, 'index']);
        Route::post('/add', [DriverController::class, 'add']);
        Route::post('/update', [DriverController::class, 'update']);
        Route::post('/status-update', [DriverController::class, 'statusChange']);
        Route::post('/delete', [DriverController::class, 'delete']);
    });
    Route::group(['prefix' => 'vehicle',], function () {
        Route::post('/', [VehicleController::class, 'index']);
        Route::match(['get', 'post'], '/drivers', [VehicleController::class, 'drivers']);
        Route::post('/add', [VehicleController::class, 'add']);
        Route::post('/update', [VehicleController::class, 'update']);
        Route::post('/status-update', [VehicleController::class, 'statusChange']);
        Route::post('/delete', [VehicleController::class, 'delete']);
    });
    Route::group(['prefix' => 'routes',], function () {
        Route::post('/', [RouteController::class, 'index']);
        Route::post('edit', [RouteController::class, 'edit']);
    });
    Route::group(['prefix' => 'profile',], function () {
        Route::post('/', [ProfileController::class, 'index']);
        Route::post('update', [ProfileController::class, 'update']);
        Route::post('/update-password', [ProfileController::class, 'updatePassword']);
    });
});
