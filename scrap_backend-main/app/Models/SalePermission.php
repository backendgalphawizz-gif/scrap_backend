<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SalePermission extends Model
{
    protected $fillable = ['name'];

    public function roles()
    {
        return $this->belongsToMany(SaleRole::class);
    }

}
