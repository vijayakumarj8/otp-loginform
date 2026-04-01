<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;

// Home
Route::get('/', function () {
    return view('welcome');
});

// Login page (IMPORTANT: name is required)
Route::get('/login', function () {
    return view('login');
})->name('login');

// Send OTP
Route::post('/send-otp', [AuthController::class,'sendOtp']);

// Verify OTP + login
Route::post('/login-otp', [AuthController::class,'loginWithOtp']);

// Protected routes
Route::middleware('auth')->group(function () {

    Route::get('/dashboard', function () {
        return view('dashboard');
    });

    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
    Route::post('/reset-password', [AuthController::class, 'resetPassword'])->name('reset.password');
});
//Route::post('/logout', [AuthController::class, 'logout'])->name('logout');