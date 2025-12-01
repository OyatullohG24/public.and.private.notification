<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\NotificationController;

// Public Routes
Route::post('/login', [AuthController::class, 'apiLogin']);
Route::post('/register', [AuthController::class, 'register']); // Register ham JSON qaytaradi

// Protected Routes (Token kerak)
Route::middleware('auth:sanctum')->group(function () {

    // User ma'lumotlarini olish
    Route::get('/user', function (Request $request) {
        return $request->user();
    });

    // FCM Tokenni alohida yangilash (agar kerak bo'lsa)
    Route::post('/save-fcm-token', [NotificationController::class, 'saveFcmToken']);
});
