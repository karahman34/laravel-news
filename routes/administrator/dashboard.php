<?php

use App\Http\Controllers\DashboardController;
use Illuminate\Support\Facades\Route;

Route::prefix('dashboard')->name('dashboard')->middleware(['auth'])->group(function () {
    Route::get('/', [DashboardController::class, 'index']);
});
