<?php

namespace App\Models;

use App\Models\News;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;

class Reaction extends Model
{


    protected $fillable = [
        'user_id',
        'type',
    ];


    public function reactable()
    {
        return $this->morphTo();
    }


    public  function user()
    {
        return $this->belongsTo(User::class);
    }
    public  function news()
    {
        return $this->belongsTo(News::class);
    }
}
