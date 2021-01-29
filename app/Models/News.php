<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Support\Facades\Storage;

class News extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'banner_image',
        'title',
        'summary',
        'content',
        'views',
        'is_headline',
        'status',
    ];

    /**
     * Get author model.
     *
     * @return  BelongsTo
     */
    public function author()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * Get News's Tags.
     *
     * @return  BelongsToMany
     */
    public function tags()
    {
        return $this->belongsToMany(Tag::class);
    }

    /**
     * Get banner image url.
     *
     * @return  string
     */
    public function getBannerImageUrl()
    {
        return Storage::url($this->banner_image);
    }

    /**
     * Scope a query to only include published news.
     *
     * @param   \Illuminate\Database\Eloquent\Builder   $query
     *
     * @return  \Illuminate\Database\Eloquent\Builder
     */
    public function scopePublish($query)
    {
        return $query->where('status', 'publish');
    }
}
