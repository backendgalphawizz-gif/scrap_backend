<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Notification extends Model
{
    protected $casts = [
        'status'     => 'integer',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    public function scopeActive($query)
    {
        return $query->where('status', '=', 1);
    }

    public function getImageAttribute($image) {
        return (strpos($image, 'https://') === 0) ? $image : asset('storage/profile/' . ( $image ?: 'def.png'));
    }

}
