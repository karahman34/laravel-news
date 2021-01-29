<?php

namespace App\Http\Livewire\Components;

use App\Models\News;
use Livewire\Component;

class RelatedNews extends Component
{
    public $news;

    public function render()
    {
        $relatedNews = News::select('id', 'banner_image', 'title')
                                    ->where('status', 'publish')
                                    ->where('news.id', '!=', $this->news->id)
                                    ->whereHas('tags', function ($query) {
                                        return $query->whereIn('tags.id', $this->news->tags->pluck('id'));
                                    })
                                    ->orderByDesc('created_at')
                                    ->limit(4)
                                    ->get();

        return view('livewire.components.related-news', [
            'relatedNews' => $relatedNews
        ]);
    }
}
