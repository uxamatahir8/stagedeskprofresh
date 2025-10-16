<?php

use App\Http\Controllers\CompanyController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth', 'role:master_admin'])->group(function () {

    Route::get('/companies', [CompanyController::class, 'index'])->name('companies');
    Route::get('/company/{company}', [CompanyController::class, 'show'])->name('company.show');
    Route::get('/company/create', [CompanyController::class, 'create'])->name('company.create');
    Route::post('/company', [CompanyController::class, 'store'])->name('company.store');
    Route::get('/company/{company}/edit', [CompanyController::class, 'edit'])->name('company.edit');
    Route::put('/company/{company}', [CompanyController::class, 'update'])->name('company.update');
    Route::delete('/company/{company}', [CompanyController::class, 'destroy'])->name('company.destroy');
});