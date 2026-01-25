<?php

use App\Http\Controllers\BlogController;
use App\Http\Controllers\CommentController;
use Illuminate\Support\Facades\Route;

Route::middleware('auth')->group(function () {

    Route::get('/blogs-list', [BlogController::class, 'index'])->name('blogs.list');
    Route::get('/blog/create', [BlogController::class, 'create'])->name('blog.create');
    Route::post('/blog', [BlogController::class, 'store'])->name('blog.store');
    Route::get('/blog/{blog}/show', [BlogController::class, 'showDashboard'])->name('blog.details');
    Route::get('/blog/{blog}/edit', [BlogController::class, 'edit'])->name('blog.edit');
    Route::put('/blog/{blog}', [BlogController::class, 'update'])->name('blog.update');
    Route::delete('/blog/{blog}', [BlogController::class, 'destroy'])->name('blog.destroy');

    // Blog approval routes (master admin only)
    Route::post('/blog/{blog}/approve', [BlogController::class, 'approve'])->name('blog.approve');
    Route::post('/blog/{blog}/reject', [BlogController::class, 'reject'])->name('blog.reject');

    Route::post('/blogs/upload-image', [BlogController::class, 'uploadImage'])->name('blogs.uploadImage');

    // Comment management routes
    Route::get('/comments', [CommentController::class, 'index'])->name('comments.index');
    Route::get('/blog/{blog}/comments', [CommentController::class, 'blogComments'])->name('blog.comments');
    Route::post('/comment/approve/{comment}', [CommentController::class, 'approve'])->name('comment.approve');
    Route::post('/comment/unapprove/{comment}', [CommentController::class, 'unapprove'])->name('comment.unapprove');
    Route::delete('/comment/{comment}', [CommentController::class, 'destroy'])->name('comment.destroy');
    Route::post('/comment/like/{comment}', [CommentController::class, 'like'])->name('comment.like');
});

// Public comment posting
Route::post('/comment', [CommentController::class, 'store'])->name('comment.store')->middleware('auth');
