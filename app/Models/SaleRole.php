<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
// use Laravel\Passport\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;

class SaleRole extends Model
{
    protected $fillable = [
        'name',
    ];
    protected $hidden = ['created_at', 'updated_at'];
    public function users()
    {
        return $this->hasMany(Sale::class);
    }

    public function permissions()
    {
        return $this->belongsToMany(SalePermission::class);
    }
}
