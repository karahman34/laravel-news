<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Route::prefix('/administrator')->name('administrator.')->group(function () {
    require __DIR__.'/administrator/auth.php';
    require __DIR__.'/administrator/dashboard.php';
    require __DIR__.'/administrator/menus.php';
    require __DIR__.'/administrator/user-managements.php';
    require __DIR__.'/administrator/tags.php';
    require __DIR__.'/administrator/news.php';
    require __DIR__.'/administrator/users.php';
});
