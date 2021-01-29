<?php

namespace App\Http\Livewire\Pages;

use App\Models\News;
use Livewire\Component;
use Livewire\WithPagination;

class WelcomePage extends Component
{
    use WithPagination;

    protected $paginationTheme = 'bootstrap';

    public function render()
    {
        $headlineNews = News::publish()
                                ->select('id', 'summary', 'banner_image', 'title', 'created_at')
                                ->where('is_headline', 'Y')
                                ->orderByDesc('created_at')
                                ->limit(4)
                                ->get();

        $news = News::publish()
                        ->select('id', 'summary', 'banner_image', 'title', 'created_at')
                        ->with('tags:id,name')
                        ->orderByDesc('created_at')
                        ->paginate(10);
                        
        return view('livewire.pages.welcome-page', [
            'news' => $news,
            'headlineNews' => $headlineNews
        ])
        ->extends('layouts.home.layout')
        ->section('content');
    }
}
