<?php

use Illuminate\Support\Facades\Route;

Route::middleware(['auth', 'role:master_admin'])->group(function () {

    Route::get('testimonials', 'TestimonialController@index')->name('testimonials.index');
    Route::get('testimonials/create', 'TestimonialController@create')->name('testimonials.create');
    Route::post('testimonials', 'TestimonialController@store')->name('testimonials.store');
    Route::get('testimonials/{id}/edit', 'TestimonialController@edit')->name('testimonials.edit');
    Route::put('testimonials/{id}', 'TestimonialController@update')->name('testimonials.update');
    Route::delete('testimonials/{id}', 'TestimonialController@destroy')->name('testimonials.destroy');
});
