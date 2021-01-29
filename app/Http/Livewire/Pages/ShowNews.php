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
                            ->where('id', $newsId)
                            ->firstOrFail();
    }
    
    public function render()
    {
        $this->news->load('tags:id,name');

        return view('livewire.pages.show-news')
                    ->extends('layouts.home.layout')
                    ->section('content');
    }
}
