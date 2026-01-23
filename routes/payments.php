<?php

use App\Http\Controllers\PaymentController;
use Illuminate\Support\Facades\Route;

Route::middleware('auth')->group(function () {
    Route::resource('payments', PaymentController::class);
    Route::patch('/payments/{payment}/verify', [PaymentController::class, 'verifyPayment'])
        ->middleware('role:master_admin')
        ->name('payments.verify');
});
