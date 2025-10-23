<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\BlogController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

Route::get('/', [HomeController::class, 'index'])->name('home');

Route::get('/blog-details/{slug}', [BlogController::class, 'show'])->name('blog.details');

Route::get('/states/{country_id}', [UserController::class, 'getStates']);
Route::get('/cities/{state_id}', [UserController::class, 'getCities']);


// add guests routes with guest middleware
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'login'])->name('login');
    Route::get('/register', [AuthController::class, 'register'])->name('register');
    Route::get('/forgot-password', [AuthController::class, 'forgotPassword'])->name('forgot-password');
    Route::get('/reset-password', [AuthController::class, 'resetPassword'])->name('reset-password');

    Route::post('/login', [AuthController::class, 'userLogin'])->name('user_login');
    Route::post('/register', [AuthController::class, 'userRegister'])->name('user_register');
    Route::post('/forgot-password', [AuthController::class, 'userForgotPassword'])->name('user.forgot-password');
    Route::post('/reset-password', [AuthController::class, 'userResetPassword'])->name('user.reset-password');
});



Route::middleware('auth')->group(function () {

    Route::get('/logout', [AuthController::class, 'logout'])->name('logout');


    Route::get('/dashboard', [DashboardController::class, 'dashboard'])->name('dashboard');
});