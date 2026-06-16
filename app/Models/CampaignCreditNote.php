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
        'purchased_posts',
        'completed_posts',
        'unutilized_posts',
        'per_post_amount',
        'gross_reversal_amount',
        'discount_reversal',
        'igst_reversal',
        'is_intra_state',
    ];

    protected $casts = [
        'taxable_reversal_amount' => 'float',
        'gst_reversal_amount' => 'float',
        'cgst_reversal' => 'float',
        'sgst_reversal' => 'float',
        'credit_note_date' => 'date',
        'purchased_posts' => 'integer',
        'completed_posts' => 'integer',
        'unutilized_posts' => 'integer',
        'per_post_amount' => 'float',
        'gross_reversal_amount' => 'float',
        'discount_reversal' => 'float',
        'igst_reversal' => 'float',
        'is_intra_state' => 'boolean',
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
