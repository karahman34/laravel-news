<?php

use App\Http\Controllers\NewsController;
use Illuminate\Support\Facades\Route;

Route::resource('news', NewsController::class)
        ->except(['show'])
        ->middleware(['auth']);

Route::prefix('news')->name('news.')->middleware(['auth'])->group(function () {
    Route::post('/upload', [NewsController::class, 'uploadImage'])->name('upload');
});
