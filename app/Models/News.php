<?php

namespace App\Models;

use App\Models\Comment;
use App\Models\NewsView;
use App\Models\NewsSource;
use Illuminate\Database\Eloquent\Model;

class News extends Model
{


    protected $fillable = [
        'news_source_id',
        'title',
        'slug',
        'summary',
        'news_category_id',
        'content',
        'image',
        'author',
        'url',
        'published_at',
        'is_featured',
        'is_trending',
    ];


    // Cast
    protected $casts = [
        'is_featured' => 'boolean',
        'is_trending' => 'boolean',
        'image' => 'array',
    ];


    public function newsSource()
    {
        return $this->belongsTo(NewsSource::class);
    }
    public function source()
    {
        return $this->belongsTo(NewsSource::class, 'news_source_id');
    }

    public function newsCategory()
    {
        return $this->belongsTo(NewsCategory::class, 'news_category_id');
    }

    public function reactions()
    {
        return $this->morphMany(Reaction::class, 'reactable');
    }

    public function views()
    {
        return $this->hasMany(NewsView::class);
    }

    public function comments()
    {
        return $this->hasMany(Comment::class);
    }


    public function category()
    {
        return $this->belongsTo(NewsCategory::class, 'news_category_id');
    }
}
