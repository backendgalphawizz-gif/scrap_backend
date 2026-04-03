<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BrandCategory extends Model
{

    public function childes() {
        return $this->hasMany(BrandCategory::class, 'parent_id');
    }

}
