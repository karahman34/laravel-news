<?php

namespace App\Http\Controllers;

use App\Http\Requests\NewsRequest;
use App\Models\News;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\DataTables;

class NewsController extends Controller
{
    protected $news_content_image_path = 'news/contents';
    protected $news_banner_image_path = 'news/banners';

    /**
     * Delete banner image.
     *
     * @param   string  $banner_path
     *
     * @return  bool
     */
    private function deleteBannerImage(string $banner_path)
    {
        if (Storage::exists($banner_path)) {
            return Storage::delete($banner_path);
        }

        return false;
    }

    /**
     * Display a listing of the resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $this->authorize('viewAny', News::class);

        if (!$request->wantsJson()) {
            return view('pages.administrator.news', [
                'title' => 'News'
            ]);
        }

        $news = News::query();
        $news->select('id', 'user_id', 'title', 'banner_image', 'views', 'is_headline', 'status', 'created_at', 'updated_at')
        ->with('author:id,name,email');
        
        $auth = $request->user();

        return DataTables::of($news)
                            ->addColumn('actions', function (News $news) use ($auth) {
                                $editButton = '';
                                $deleteButton = '';

                                if ($auth->can('update', $news)) {
                                    $editButton = '<a href="'.route('administrator.news.edit', ['news' => $news->id]).'" class="btn btn-warning btn-modal-trigger" data-modal="#news-form-modal"><i class="fas fa-edit"></i></a>';
                                }
                                
                                if ($auth->can('delete', $news)) {
                                    $deleteButton = '<a href="'.route('administrator.news.destroy', ['news' => $news->id]).'" class="btn btn-danger delete-prompt-trigger has-datatable" data-datatable="#news-datatable" data-item-name="'.$news->title.'"><i class="fas fa-trash"></i></a>';
                                }

                                return $editButton . $deleteButton;
                            })
                            ->rawColumns(['actions'])
                            ->make(true);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $this->authorize('create', News::class);

        return view('components.news.form-modal');
    }

    /**
     * Upload news content images from CKEditor.
     *
     * @param   Request  $request
     *
     * @return  mixed
     */
    public function uploadImage(Request $request)
    {
        $this->authorize('create', News::class);

        $validator = Validator::make($request->all(), [
            'upload' => 'required|mimes:png,jpg,jpeg,gif',
        ]);

        $ckeditor = $request->input('CKEditorFuncNum');

        if (!$validator->fails()) {
            $file = $request->file('upload');
            $path = $file->store($this->news_content_image_path);

            // Set response
            $url = Storage::url($path);

            $response = "<script>window.parent.CKEDITOR.tools.callFunction($ckeditor, '$url');</script>";
        } else {
            $response = "<script>window.parent.CKEDITOR.tools.callFunction($ckeditor, '', 'Only png,jpg,jpeg and gif are allowed.');</script>";
        }
    
        @header('Content-type: text/html; charset=utf-8');

        return $response;
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  NewsRequest  $newsRequest
     * @return \Illuminate\Http\Response
     */
    public function store(NewsRequest $newsRequest)
    {
        $payload = $newsRequest->only(['title', 'summary', 'content', 'is_headline', 'status']);
        $payload['user_id'] = Auth::id();
        $payload['banner_image'] = $newsRequest->file('banner_image')->store($this->news_banner_image_path);

        $news = News::create($payload);

        $news->tags()->sync($newsRequest->tags);

        return response()->json([
            'ok' => true,
            'message' => 'News created.',
            'data' => $news,
        ], 201);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\News  $news
     * @return \Illuminate\Http\Response
     */
    public function edit(News $news)
    {
        $this->authorize('update', $news);

        $news->load('tags');

        return view('components.news.form-modal', [
            'news' => $news
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  NewsRequest  $newsRequest
     * @param  \App\Models\News  $news
     * @return \Illuminate\Http\Response
     */
    public function update(NewsRequest $newsRequest, News $news)
    {
        $payload = $newsRequest->only(['title', 'summary', 'content', 'is_headline', 'status']);

        if ($newsRequest->hasFile('banner_image')) {
            // Delete old banner
            $this->deleteBannerImage($news->banner_image);

            // Upload new banner
            $payload['banner_image'] = $newsRequest->file('banner_image')->store($this->news_banner_image_path);
        }

        $news->update($payload);

        $news->tags()->sync($newsRequest->tags);

        return response()->json([
            'ok' => true,
            'message' => 'News updated.',
            'data' => $news,
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\News  $news
     * @return \Illuminate\Http\Response
     */
    public function destroy(News $news)
    {
        $this->authorize('delete', $news);

        // Delete banner
        $this->deleteBannerImage($news->banner_image);

        $news->delete();

        return response()->json([
            'ok' => true,
            'message' => 'News deleted.',
            'data' => $news,
        ]);
    }
}
