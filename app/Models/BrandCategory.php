<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BrandCategory extends Model
{
    protected $fillable = [
        'name',
        'parent_id',
        'status',
    ];

    public function childes() {
        return $this->hasMany(BrandCategory::class, 'parent_id');
    }

    public function parent()
    {
        return $this->belongsTo(BrandCategory::class, 'parent_id');
    }

}
