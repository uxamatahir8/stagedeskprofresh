<?php

use App\Http\Controllers\PaymentController;
use App\Http\Controllers\PaymentMethodController;
use Illuminate\Support\Facades\Route;

Route::middleware('auth')->group(function () {
    Route::resource('payments', PaymentController::class);
    Route::patch('/payments/{payment}/verify', [PaymentController::class, 'verifyPayment'])
        ->middleware('role:master_admin,company_admin')
        ->name('payments.verify');

    Route::resource('payment-methods', PaymentMethodController::class)->except('show');
});
