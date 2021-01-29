<?php

namespace App\Http\Livewire\Pages;

use App\Models\News;
use Livewire\Component;

class ShowNews extends Component
{
    public $news;

    public function mount($title, int $newsId)
    {
        $this->news = News::publish()
                            ->select('id', 'user_id', 'banner_image', 'title', 'content', 'created_at')
                            ->with(['author:id,name', 'tags:id,name'])
                            ->where('id', $newsId)
                            ->firstOrFail();
    }
    
    public function render()
    {
        return view('livewire.pages.show-news')
                    ->extends('layouts.home.layout')
                    ->section('content');
    }
}
