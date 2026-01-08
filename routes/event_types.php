<?php

use App\Http\Controllers\EventTypeController;
use Illuminate\Support\Facades\Route;

Route::middleware('auth')->group(function () {
    Route::get('/event-categories', [EventTypeController::class, 'index'])->name('event-categories');
    //edit route
    Route::get('/event/edit/{id}', [EventTypeController::class, 'edit'])->name('event.edit');

    Route::post('/event/store', [EventTypeController::class, 'store'])->name('event.store');
    Route::delete('/event/delete/{id}', [EventTypeController::class, 'destroy'])->name('event.destroy');

    Route::put('/event/update/{id}', [EventTypeController::class, 'update'])->name('event.update');
});
