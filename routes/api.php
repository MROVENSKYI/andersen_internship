<?php

use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;

Route::middleware('auth:api')->group(function () {
    Route::put('/users/{user}', [UserController::class, 'update'])->name('update.user');
});

Route::post('/forgot-password', [AuthController::class, 'sendResetLink'])->name('password.link');
Route::post('/reset-password', [AuthController::class, 'resetPassword'])->middleware('guest')->name('password.reset');

Route::post('/auth/register', [AuthController::class, 'register'])->name('auth.register');
Route::post('/auth/login', [AuthController::class, 'login'])->name('auth.login');