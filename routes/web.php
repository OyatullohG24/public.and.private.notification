<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;

Route::get('/', function () {
    return view('welcome');
});

// Authentication Routes
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
    Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [AuthController::class, 'register']);
});

Route::middleware('auth')->group(function () {
    Route::get('/dashboard', [AuthController::class, 'dashboard'])->name('dashboard');
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

    // Post CRUD Routes
    Route::resource('posts', \App\Http\Controllers\PostController::class);

    // Firebase Notification Routes
    Route::post('/save-fcm-token', [\App\Http\Controllers\NotificationController::class, 'saveFcmToken'])->name('fcm.save');
    Route::post('/send-test-notification', [\App\Http\Controllers\NotificationController::class, 'sendTestNotification'])->name('notification.test');

    // Firebase Test Page
    Route::get('/firebase-test', function () {
        return view('firebase-test');
    })->name('firebase.test');
});
