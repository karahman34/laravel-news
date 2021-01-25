<?php

use App\Http\Controllers\PermissionController;
use App\Http\Controllers\RoleController;
use Illuminate\Support\Facades\Route;

Route::prefix('user-managements')->name('user-managements.')->middleware(['auth'])->group(function () {
    // Roles
    Route::resource('roles', RoleController::class);
    Route::prefix('roles')->name('roles.')->group(function () {
        Route::post('/{role}/sync-permissions', [RoleController::class, 'syncPermissions'])->name('sync_permissions');
    });

    // Permissions
    Route::prefix('permissions')->name('permissions.')->group(function () {
        Route::get('/', [PermissionController::class, 'index'])->name('index');
    });
});
