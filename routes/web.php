<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\HomeController;
use Illuminate\Support\Facades\Route;

Route::get('/', [HomeController::class, 'index'])->name('home');


// add guests routes with guest middleware
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'login'])->name('login');
    Route::get('/register', [AuthController::class, 'register'])->name('register');
    Route::get('/forgot-password', [AuthController::class, 'forgotPassword'])->name('forgot-password');
    Route::get('/reset-password', [AuthController::class, 'resetPassword'])->name('reset-password');

    Route::post('/login', [AuthController::class, 'userLogin'])->name('user_login');
});



Route::middleware('auth')->group(function () {

    Route::get('/logout', [AuthController::class, 'logout'])->name('logout');


    Route::get('/dashboard', [DashboardController::class, 'dashboard'])->name('dashboard');
});