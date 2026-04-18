<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use DB; 

class CampaignTransaction extends Model
{

    protected $fillable = [
        'user_id',
        'campaign_id',
        'shared_on',
        'status',
        'earning',
        'start_date',
        'end_date',
        'unique_code',
        'violation_reason',
        'post_url',
        'day_status',
    ];

    // protected $appends = ['left_days'];

    // public function getThumbnailAttribute($value)
    // {
    //     return (strpos($value, 'https://') === 0) ? $value : asset('storage/profile/' . ( $value ?: 'def.png'));
    //     // return asset('storage/profile/' . ( $value ?: 'def.png'));
    // }

    // public function getImagesAttribute($images)
    // {
    //     $images = explode(',', $images);
    //     $imageUrls = [];
    //     foreach ($images as $image) {
    //         $imageUrls[] = (strpos($image, 'https://') === 0) ? $image : asset('storage/profile/' . ( $image ?: 'def.png'));
    //     }
    //     return $imageUrls;
    // }

    public function brand()
    {
        return $this->belongsTo(Seller::class, 'brand_id', 'id')->select('id', DB::raw('CONCAT(f_name, " " ,l_name) as name'), 'image');
    }

    public function campaign()
    {
        return $this->belongsTo(Campaign::class, 'campaign_id', 'id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id')->select('id', 'name', 'image', 'email');
    }

    // public function getLeftDaysAttribute()
    // {
    //     $endDate = \Carbon\Carbon::parse($this->end_date);
    //     $currentDate = \Carbon\Carbon::now();
    //     return (int)$currentDate->diffInDays($endDate, false);
    // }

}
