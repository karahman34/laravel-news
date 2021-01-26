<?php

use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

Route::resource('users', UserController::class)
        ->except(['show'])
        ->middleware(['auth']);

Route::prefix('users')->name('users.')->middleware(['auth'])->group(function () {
    Route::get('/{user}/sync-roles', [UserController::class, 'syncRoles'])->name('sync_roles');
    Route::post('/{user}/sync-roles', [UserController::class, 'syncRoles']);
});
