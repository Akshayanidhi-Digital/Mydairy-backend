<?php

use App\Http\Controllers\Api\CommonController;
use App\Http\Controllers\Api\Driver\AuthController;
use App\Http\Controllers\Api\Driver\UserController;
use Illuminate\Support\Facades\Route;




Route::post('/login', [AuthController::class, 'login']);
Route::post('/verify', [AuthController::class, 'verify']);
Route::post('/resend-otp', [AuthController::class, 'resendOtp']);
Route::post('/forgot-password', [AuthController::class, 'forgotPassword']);
Route::group(['middleware' => 'auth:driver-api'], function () {
    Route::post('/logout', [CommonController::class, 'logout']);
    Route::group(['prefix' => 'profile',], function () {
        Route::post('/', [UserController::class, 'index']);
        Route::post('update', [UserController::class, 'update']);
        Route::post('/update-password', [UserController::class, 'updatePassword']);
    });
    Route::post('routes/list', [UserController::class, 'routeList']);
    Route::post('routes/view', [UserController::class, 'routeview']);
});
