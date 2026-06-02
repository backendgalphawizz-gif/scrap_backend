<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class CampaignDiscountVoucher extends Model
{
    protected $fillable = [
        'sale_id',
        'code',
        'discount_amount',
        'max_uses',
        'used_count',
        'valid_from',
        'valid_to',
        'is_active',
    ];

    protected $casts = [
        'discount_amount' => 'float',
        'max_uses'        => 'integer',
        'used_count'      => 'integer',
        'valid_from'      => 'date',
        'valid_to'        => 'date',
        'is_active'       => 'boolean',
    ];

    // ── Relations ────────────────────────────────────────────────────────────

    public function sale()
    {
        return $this->belongsTo(Sale::class, 'sale_id');
    }

    // ── Validity ─────────────────────────────────────────────────────────────

    /**
     * Determines whether this voucher can still be applied.
     */
    public function isValid(): bool
    {
        if (! $this->is_active) {
            return false;
        }

        if ($this->max_uses !== null && $this->used_count >= $this->max_uses) {
            return false;
        }

        $today = now()->toDateString();

        if ($this->valid_from !== null && $today < $this->valid_from->toDateString()) {
            return false;
        }

        if ($this->valid_to !== null && $today > $this->valid_to->toDateString()) {
            return false;
        }

        return true;
    }

    // ── Code generation ───────────────────────────────────────────────────────

    /**
     * Generate a unique discount voucher code (DISC-XXXXXXXX).
     * Retries up to 10 times if a collision occurs.
     */
    public static function generateCode(): string
    {
        $attempts = 0;

        do {
            $code = 'DISC-' . strtoupper(Str::random(8));
            $exists = static::where('code', $code)->exists();
            $attempts++;
        } while ($exists && $attempts < 10);

        return $code;
    }
}
