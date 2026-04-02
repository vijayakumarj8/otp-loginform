<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ApiController;



Route::middleware('api.key')->group(function () {

    Route::post('/send-otp', [ApiController::class, 'sendOtp']);

    Route::post('/login-otp', [ApiController::class, 'loginWithOtp']);


Route::middleware(['auth:sanctum'])->group(function () {

    Route::post('/reset-password', [ApiController::class, 'resetPassword']);
    Route::post('/logout', [ApiController::class, 'logout']);
});
});