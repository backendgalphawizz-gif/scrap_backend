<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class Admin extends Authenticatable
{
    use Notifiable;

    public function role(){
        return $this->belongsTo(AdminRole::class,'admin_role_id');
    }
    public function assessor(){
        return $this->hasOne(Assessor::class,'assessor_id');
    }

    public function getImageAttribute($value)
    {
        return $value ? asset('storage/profile/' . $value) : null;
    }
    

    public function is_admin(){
        return $this->admin_role_id == 1;
    }

}
