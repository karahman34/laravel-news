<?php

use App\Http\Controllers\MenuController;
use App\Http\Controllers\MenuPermissionController;
use Illuminate\Support\Facades\Route;

Route::resource('menus', MenuController::class)
        ->except(['show'])
        ->middleware(['auth']);

Route::prefix('menus')->name('menus.')->middleware(['auth'])->group(function () {
    Route::get('export', [MenuController::class, 'export'])->name('export');
    Route::get('import', [MenuController::class, 'import'])->name('import');
        
    Route::post('export', [MenuController::class, 'export']);
    Route::post('import', [MenuController::class, 'import']);

    Route::prefix('{menu}')->name('permissions.')->group(function () {
        Route::get('permissions', [MenuPermissionController::class, 'index'])->name('index');
        
        Route::post('permissions', [MenuPermissionController::class, 'store'])->name('store');

        Route::patch('permissions/{permission}', [MenuPermissionController::class, 'update'])->name('update');

        Route::delete('permissions/{permission}', [MenuPermissionController::class, 'destroy'])->name('destroy');
    });
});
