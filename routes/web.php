<?php

use App\Http\Controllers\HomeController;
use App\Http\Livewire\Pages\Search;
use App\Http\Livewire\Pages\ShowNews;
use App\Http\Livewire\Pages\WelcomePage;
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

Route::prefix('/administrator')->name('administrator.')->group(function () {
    require __DIR__.'/administrator/auth.php';
    require __DIR__.'/administrator/dashboard.php';
    require __DIR__.'/administrator/menus.php';
    require __DIR__.'/administrator/user-managements.php';
    require __DIR__.'/administrator/tags.php';
    require __DIR__.'/administrator/news.php';
    require __DIR__.'/administrator/users.php';
    require __DIR__.'/administrator/profile.php';
});

Route::get('/', WelcomePage::class)->name('welcome');
Route::get('/{title}/{newsId}', ShowNews::class)->name('show_news');
Route::get('/search', Search::class)->name('search');
Route::post('/increase-view', [HomeController::class, 'increaseView'])->name('increase_view');
