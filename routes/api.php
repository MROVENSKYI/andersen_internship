<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

Route::middleware('auth:api')->group(function () {
    Route::put('/users/{user}', [UserController::class, 'update'])->name('user.update');
    Route::get('/users/{user}', [UserController::class, 'show'])->name('user.show');
    Route::delete('/users/{user}', [UserController::class, 'destroy'])->name('user.destroy');
});

Route::get('/users', [UserController::class, 'showList'])->name('users.list');

Route::post('/forgot-password', [AuthController::class, 'sendResetLink'])->name('password.link');
Route::post('/reset-password', [AuthController::class, 'resetPassword'])->middleware('guest')->name('password.reset');

Route::post('/auth/register', [AuthController::class, 'register'])->name('auth.register');
Route::post('/auth/login', [AuthController::class, 'login'])->name('auth.login');
