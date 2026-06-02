<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Builder;

class FraudSignal extends Model
{
    public const TYPE_DUPLICATE_DEVICE  = 'duplicate_device';
    public const TYPE_DUPLICATE_UPI     = 'duplicate_upi';
    public const TYPE_REFERRAL_ABUSE    = 'referral_abuse';
    public const TYPE_DUPLICATE_SOCIAL  = 'duplicate_social';
    public const TYPE_POST_DELETED      = 'post_deleted_after_credit';
    public const TYPE_MANUAL_BLOCK      = 'manual_block';

    public const SEVERITY_LOW      = 'low';
    public const SEVERITY_MEDIUM   = 'medium';
    public const SEVERITY_HIGH     = 'high';
    public const SEVERITY_CRITICAL = 'critical';

    protected $fillable = [
        'user_id',
        'signal_type',
        'signal_value',
        'severity',
        'meta',
        'resolved',
        'resolved_at',
        'resolved_by',
    ];

    protected $casts = [
        'meta'        => 'array',
        'resolved'    => 'boolean',
        'resolved_at' => 'datetime',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function resolver(): BelongsTo
    {
        return $this->belongsTo(Admin::class, 'resolved_by');
    }

    public function scopeUnresolved(Builder $query): Builder
    {
        return $query->where('resolved', false);
    }

    public function scopeForUser(Builder $query, int $userId): Builder
    {
        return $query->where('user_id', $userId);
    }
}
