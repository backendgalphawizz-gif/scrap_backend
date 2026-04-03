<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Banner extends Model
{

    public function getImageAttribute($value)
    {
        return asset('storage/banners/' . $value);
    }
}
