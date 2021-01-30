<?php

use App\Http\Controllers\DashboardController;
use Illuminate\Support\Facades\Route;

Route::prefix('dashboard')->name('dashboard.')->middleware(['auth', 'can:dashboard-view'])->group(function () {
    Route::get('/', [DashboardController::class, 'index'])->name('index');
    Route::get('/popular-tags', [DashboardController::class, 'getPopularTags'])->name('popular_tags');
    Route::get('/trending-news', [DashboardController::class, 'getTrendingNews'])->name('trending_news');
});
