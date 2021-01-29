<?php

namespace App\Http\Livewire\Pages;

use App\Models\News;
use Livewire\Component;
use Livewire\WithPagination;

class Search extends Component
{
    use WithPagination;

    protected $paginationTheme = 'bootstrap';

    public $order = '';
    public $q = '';
    public $tags = '';

    protected $queryString = [
        'q' => ['except' => ''],
        'order' => ['except' => ''],
        'tags' => ['except' => ''],
    ];

    public function render()
    {
        $news = News::select('id', 'summary', 'banner_image', 'title', 'created_at')
                            ->with('tags:id,name')
                            ->where('status', 'publish')
                            ->where('title', 'like', '%'.$this->q.'%')
                            ->when(strlen($this->order) > 0, function ($query) {
                                return $query->orderBy('created_at', $this->order);
                            })
                            ->when(strlen($this->order) === 0, function ($query) {
                                return $query->orderByDesc('created_at');
                            })
                            ->when(strlen($this->tags) > 0, function ($query) {
                                return $query->whereHas('tags', function ($scopeQuery) {
                                    return $scopeQuery->whereIn('tags.name', explode(',', $this->tags));
                                });
                            })
                            ->orderByDesc('created_at')
                            ->paginate(10);

        return view('livewire.pages.search', ['news' => $news])
                ->extends('layouts.home.layout')
                ->section('content');
    }
}
