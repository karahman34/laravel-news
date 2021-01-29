<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::prefix('profile')->name('profile.')->middleware(['auth'])->group(function () {
    Route::get('/', [ProfileController::class, 'index'])->name('index');
    Route::patch('/', [ProfileController::class, 'update'])->name('update');
});
