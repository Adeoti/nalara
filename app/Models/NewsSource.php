<?php

namespace App\Models;

use App\Models\News;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;

class NewsSource extends Model
{


    protected $fillable = [
        'name',
        'type',
        'base_url',
        'feed_url',
        'logo',
        'about',
        'is_active',
    ];


    protected $casts = [
        'logo' => 'array',
        'is_active' => 'boolean',
    ];


    function news()
    {
        return $this->hasMany(News::class);
    }

    public function followers()
{
    return $this->belongsToMany(User::class);
}




}
