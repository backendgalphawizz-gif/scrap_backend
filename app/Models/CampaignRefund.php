<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CampaignRefund extends Model
{
    public const STATUS_PENDING   = 'pending';
    public const STATUS_COMPLETED = 'completed';

    protected $fillable = [
        'campaign_id',
        'brand_id',
        'calculated_amount',
        'refunded_amount',
        'bank_account_number',
        'bank_ifsc_code',
        'bank_account_holder_name',
        'bank_account_type',
        'status',
        'admin_note',
        'completed_at',
    ];

    protected $casts = [
        'calculated_amount' => 'float',
        'refunded_amount'   => 'float',
        'completed_at'      => 'datetime',
    ];

    public function campaign()
    {
        return $this->belongsTo(Campaign::class);
    }

    public function brand()
    {
        return $this->belongsTo(Seller::class, 'brand_id');
    }
}
