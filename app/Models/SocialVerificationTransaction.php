<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SocialVerificationTransaction extends Model
{
    const STATUS_PENDING      = 'pending';
    const STATUS_VERIFIED     = 'verified';
    const STATUS_NOT_VERIFIED = 'not_verified';

    const PLATFORM_INSTAGRAM = 'instagram';
    const PLATFORM_FACEBOOK  = 'facebook';

    protected $fillable = [
        'user_id',
        'platform',
        'username',
        'unique_code',
        'status',
        'submitted_at',
        'verified_at',
        'end_date',
    ];

    protected $casts = [
        'submitted_at' => 'datetime',
        'verified_at'  => 'datetime',
        'end_date'     => 'date',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
