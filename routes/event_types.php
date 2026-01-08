<?php

use App\Http\Controllers\EventTypeController;
use Illuminate\Support\Facades\Route;

Route::middleware('auth')->group(function () {
    Route::get('/event-types', [EventTypeController::class, 'index'])->name('event-types');
    //edit route
    Route::get('/event-type/edit/{id}', [EventTypeController::class, 'edit'])->name('event.edit');

    Route::post('/event-type/store', [EventTypeController::class, 'store'])->name('event.store');
    Route::delete('/event-type/delete/{id}', [EventTypeController::class, 'destroy'])->name('event.destroy');

    Route::put('/event-type/update/{id}', [EventTypeController::class, 'update'])->name('event.update');
});
