<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Tag extends Model
{
    use HasFactory;

    protected $fillable = [
        'name', 'views'
    ];

    /**
     * Get related news.
     *
     * @return  BelongsToMany
     */
    public function news()
    {
        return $this->belongsToMany(News::class);
    }
}
