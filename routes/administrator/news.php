<?php

use App\Http\Controllers\NewsController;
use Illuminate\Support\Facades\Route;

Route::resource('news', NewsController::class)
        ->except(['show'])
        ->middleware(['auth']);

Route::prefix('news')->name('news.')->middleware(['auth'])->group(function () {
    Route::get('/export', [NewsController::class, 'export'])->name('export');
    Route::get('/import', [NewsController::class, 'import'])->name('import');
    
    Route::post('/export', [NewsController::class, 'export']);
    Route::post('/import', [NewsController::class, 'import']);
    Route::post('/upload', [NewsController::class, 'uploadImage'])->name('upload');
});
