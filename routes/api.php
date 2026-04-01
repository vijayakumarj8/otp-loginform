<?php
// without token based auth (sanctum) - for Postman testing only, not recommended for production

// use Illuminate\Support\Facades\Route;
// use App\Http\Controllers\ApiController;

// Route::post('/send-otp',[ApiController::class,'sendOtp']);
// Route::post('/login-otp',[ApiController::class,'loginWithOtp']);
// Route::post('/reset-password',[ApiController::class,'resetPassword']);


/// for token based auth (sanctum) - optional for Postman, important for SPA

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ApiController;

// Public
Route::post('/send-otp',[ApiController::class,'sendOtp']);
Route::post('/login-otp',[ApiController::class,'loginWithOtp']);

// Protected
Route::middleware('auth:sanctum')->group(function () {
    Route::post('/reset-password',[ApiController::class,'resetPassword']);
    Route::post('/logout',[ApiController::class,'logout']);
});