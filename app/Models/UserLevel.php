<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserLevel extends Model
{
    protected $fillable = [
        'name',
        'range_min',
        'range_max',
        'max_participations_per_day',
    ];
}
