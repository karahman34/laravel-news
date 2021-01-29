<?php

namespace App\Http\Controllers;

use App\Models\News;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    /**
     * Increase news and the related tags views.
     *
     * @param   Request  $request
     *
     * @return  JsonResponse
     */
    public function increaseView(Request $request)
    {
        $request->validate([
            'news_id' => 'required|gte:1|regex:/^[0-9]+$/'
        ]);

        try {
            $news = News::select('id')
                        ->where('id', $request->news_id)
                        ->firstOrFail();

            // Increase news view.
            $news->increment('views', 1);

            // Increase tags view.
            $news->tags()->increment('views', 1);

            return response()->json([
                'ok' => true,
                'message' => 'Success to increase news & tags views.',
            ]);
        } catch (\Throwable $th) {
            return response()->json([
                'ok' => false,
                'message' => 'News not found or server error.',
             ], 500);
        }
    }
}
