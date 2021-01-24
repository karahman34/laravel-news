<?php

use App\Http\Controllers\MenuController;
use Illuminate\Support\Facades\Route;

Route::resource('menus', MenuController::class)
        ->middleware(['auth']);

Route::prefix('menus')->name('menus.')->middleware(['auth'])->group(function () {
});
