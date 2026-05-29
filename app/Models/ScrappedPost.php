<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ScrappedPost extends Model
{
    protected $table = 'scrapped_posts';

    public $timestamps = false;

    protected $fillable = [
        'platform',
        'unique_code',
        'instagram_post_id',
        'post_url',
        'username',
        'caption',
        'hashtags',
        'mentions',
        'scraped_at',
    ];

    protected $casts = [
        'scraped_at' => 'datetime',
    ];
}
