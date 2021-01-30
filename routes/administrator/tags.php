<?php

use App\Http\Controllers\TagController;
use Illuminate\Support\Facades\Route;

Route::resource('tags', TagController::class)
        ->except(['show'])
        ->middleware(['auth']);

Route::prefix('tags')->name('tags.')->middleware(['auth'])->group(function () {
    Route::get('/search', [TagController::class, 'search'])->name('search');
    Route::get('/export', [TagController::class, 'export'])->name('export');
    Route::get('/import', [TagController::class, 'import'])->name('import');
    
    Route::post('/export', [TagController::class, 'export']);
    Route::post('/import', [TagController::class, 'import']);
});
