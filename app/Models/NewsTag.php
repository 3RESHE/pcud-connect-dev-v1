<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class NewsTag extends Model
{
    protected $fillable = ['name', 'slug'];

    /**
     * Get articles with this tag
     */
    public function articles(): BelongsToMany
    {
        return $this->belongsToMany(NewsArticle::class, 'news_article_tag', 'news_tag_id', 'news_article_id')
                    ->withTimestamps();
    }
}
