<?php

use App\Http\Controllers\CategoryController;
use Illuminate\Support\Facades\Route;

Route::middleware('auth')->group(function () {
    Route::get('/blog-categories', [CategoryController::class, 'index'])->name('blog-categories');
    //edit route
    Route::get('/category/edit/{id}', [CategoryController::class, 'edit'])->name('category.edit');

    Route::post('/catgory/store', [CategoryController::class, 'store'])->name('category.store');

    Route::delete('/category/delete/{id}', [CategoryController::class, 'destroy'])->name('category.destroy');

    Route::put('/category/update/{id}', [CategoryController::class, 'update'])->name('category.update');
});
