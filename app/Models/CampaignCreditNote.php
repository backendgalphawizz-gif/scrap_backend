<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CampaignCreditNote extends Model
{
    public const STATUS_ISSUED = 'issued';

    protected $fillable = [
        'campaign_id',
        'campaign_refund_id',
        'brand_id',
        'original_invoice_no',
        'credit_note_no',
        'taxable_reversal_amount',
        'gst_reversal_amount',
        'cgst_reversal',
        'sgst_reversal',
        'reason',
        'credit_note_date',
        'status',
    ];

    protected $casts = [
        'taxable_reversal_amount' => 'float',
        'gst_reversal_amount' => 'float',
        'cgst_reversal' => 'float',
        'sgst_reversal' => 'float',
        'credit_note_date' => 'date',
    ];

    public function campaign()
    {
        return $this->belongsTo(Campaign::class);
    }

    public function refund()
    {
        return $this->belongsTo(CampaignRefund::class, 'campaign_refund_id');
    }

    public function brand()
    {
        return $this->belongsTo(Seller::class, 'brand_id');
    }
}
