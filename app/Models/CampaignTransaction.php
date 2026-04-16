<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\DB;

class CampaignTransaction extends Model
{
    public const STATUS_PENDING = 'pending';
    public const STATUS_COMPLETED = 'completed';
    public const STATUS_ACTIVE = 'active';
    public const STATUS_DELETED = 'deleted';
    public const STATUS_APPROVED = 'approved';
    public const STATUS_REJECTED = 'rejected';
    public const STATUS_FLAGGED = 'flagged';

    public const SLOT_OCCUPIED_STATUSES = [
        self::STATUS_PENDING,
        self::STATUS_ACTIVE,
        self::STATUS_APPROVED,
        self::STATUS_COMPLETED,
        self::STATUS_FLAGGED,
    ];

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

    public function occupiesSlot(): bool
    {
        return in_array($this->status, self::SLOT_OCCUPIED_STATUSES, true);
    }

    // public function getLeftDaysAttribute()
    // {
    //     $endDate = \Carbon\Carbon::parse($this->end_date);
    //     $currentDate = \Carbon\Carbon::now();
    //     return (int)$currentDate->diffInDays($endDate, false);
    // }

}
