<?php

namespace App\Http\Controllers;

use App\Models\News;
use App\Models\Tag;
use App\Models\User;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    /**
     * Show index page.
     *
     * @return  \Illuminate\Contracts\View\View|\Illuminate\Contracts\View\Factory
     */
    public function index()
    {
        $totalAdmin = User::role('super admin')->count();
        $totalAuthor = User::role('author')->count();
        $totalNews = News::count();
        $totalTags = Tag::count();

        return view('pages.administrator.dashboard', [
            'title' => 'Dashboard',
            'totalAdmin' => $totalAdmin,
            'totalAuthor' => $totalAuthor,
            'totalNews' => $totalNews,
            'totalTags' => $totalTags,
        ]);
    }
}
