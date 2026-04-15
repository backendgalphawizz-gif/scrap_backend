<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use DB; 

class BrandFeedbackQuestion extends Model
{

    protected $fillable = [
        'brand_id',
        'brand_category_id',
        'question',
        'question_type',
        'options',
        'status'
    ];

    protected $casts = [
        'options' => 'array',
        'status' => 'boolean',
    ];

    public function brand()
    {
        return $this->belongsTo(Seller::class, 'brand_id', 'id')->select('id', 'username', DB::raw('CONCAT(f_name, " " ,l_name) as name'), 'image');
    }

    public function category()
    {
        return $this->belongsTo(BrandCategory::class, 'brand_category_id');
    }

}
