<?php

use App\Http\Controllers\TagController;
use Illuminate\Support\Facades\Route;

Route::resource('tags', TagController::class)
        ->except(['show']);
