<?php

use App\Http\Controllers\TagController;
use Illuminate\Support\Facades\Route;

Route::resource('tags', TagController::class)
        ->except(['show']);

Route::prefix('tags')->name('tags.')->middleware(['auth'])->group(function () {
    Route::get('/search', [TagController::class, 'search'])->name('search');
});
