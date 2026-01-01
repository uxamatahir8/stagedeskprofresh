<?php
use App\Http\Controllers\SupportTicketController;

Route::middleware(['auth'])->group(function () {
    Route::get('/support-tickets', [SupportTicketController::class, 'index'])->name('support.tickets');
    Route::get('/support-tickets/create', [SupportTicketController::class, 'create'])->name('support.tickets.create');
    Route::post('/support-tickets/store', [SupportTicketController::class, 'store'])->name('support.tickets.store');
    Route::get('/support-tickets/{ticket}/edit', [SupportTicketController::class, 'edit'])->name('support.tickets.edit');
    Route::post('/support-tickets/{ticket}/update', [SupportTicketController::class, 'update'])->name('support.tickets.update');
    Route::delete('/support-tickets/{ticket}', [SupportTicketController::class, 'destroy'])->name('support.tickets.delete');
});
