<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Seller;

class SocialVerificationTransaction extends Model
{
    const STATUS_PENDING      = 'pending';
    const STATUS_VERIFIED     = 'verified';
    const STATUS_NOT_VERIFIED = 'not_verified';

    const PLATFORM_INSTAGRAM = 'instagram';
    const PLATFORM_FACEBOOK  = 'facebook';
    const PLATFORM_THREADS   = 'threads';

    protected $fillable = [
        'user_id',
        'seller_id',
        'platform',
        'username',
        'unique_code',
        'status',
        'submitted_at',
        'verified_at',
        'end_date',
        'failure_reason',
        'manually_verified',
        'manually_verified_by',
        'manually_verified_at',
    ];

    protected $casts = [
        'submitted_at'         => 'datetime',
        'verified_at'          => 'datetime',
        'end_date'             => 'date',
        'manually_verified'    => 'boolean',
        'manually_verified_at' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function seller()
    {
        return $this->belongsTo(Seller::class, 'seller_id');
    }
}
