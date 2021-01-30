<?php

namespace App\Http\Controllers;

use App\Models\News;
use App\Models\Tag;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;

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

    /**
     * Get popular tags data.
     *
     * @return  JsonResponse
     */
    public function getPopularTags()
    {
        $tags = Tag::select('name', 'views')
                        ->orderByDesc('views')
                        ->limit(20)
                        ->get();

        return response()->json([
            'ok' => true,
            'message' => 'Success to get popular tags data.',
            'data' => $tags
        ]);
    }

    /**
     * Get trending news data.
     *
     * @return  JsonResponse
     */
    public function getTrendingNews()
    {
        $news = News::select('title', 'views')
                        ->whereBetween('created_at', [Carbon::now()->subDays(7), Carbon::now()])
                        ->orderByDesc('views')
                        ->limit(15)
                        ->get();

        return response()->json([
            'ok' => true,
            'message' => 'Success to get trending news data.',
            'data' => $news
        ]);
    }
}
