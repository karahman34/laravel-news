<?php

use App\Http\Controllers\PermissionController;
use App\Http\Controllers\RoleController;
use Illuminate\Support\Facades\Route;

Route::prefix('user-managements')->name('user-managements.')->middleware(['auth'])->group(function () {
    // Roles
    Route::prefix('roles')->name('roles.')->group(function () {
        Route::get('/export', [RoleController::class, 'export'])->name('export');
        Route::get('/import', [RoleController::class, 'import'])->name('import');
    
        Route::post('/export', [RoleController::class, 'export']);
        Route::post('/import', [RoleController::class, 'import']);
        Route::post('/{role}/sync-permissions', [RoleController::class, 'syncPermissions'])->name('sync_permissions');
    });
    Route::resource('roles', RoleController::class);

    // Permissions
    Route::prefix('permissions')->name('permissions.')->group(function () {
        Route::get('/', [PermissionController::class, 'index'])->name('index');
        Route::get('/export', [PermissionController::class, 'export'])->name('export');
        Route::get('/import', [PermissionController::class, 'import'])->name('import');
    
        Route::post('/export', [PermissionController::class, 'export']);
        Route::post('/import', [PermissionController::class, 'import']);

        Route::delete('/{permission}', [PermissionController::class, 'destroy'])->name('destroy');
    });
});
