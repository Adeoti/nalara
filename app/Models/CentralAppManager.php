<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CentralAppManager extends Model
{
    protected $fillable = [
        'name',
        'link',
        'group',
        'user_id',
        'logo',
        'created_by',
        'is_active',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Casts
    protected $casts = [
        'is_active' => 'boolean',
    ];
}
