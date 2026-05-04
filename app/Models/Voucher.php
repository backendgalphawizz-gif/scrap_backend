<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Voucher extends Model
{
    protected $fillable = [
        'voucher_brands_id',
        'sale_id',
        'title',
        'description',
        'coin_price',
        'fiat_value',
        'code',
        'status',
        'validity_days',
        'valid_from',
        'valid_to',
        'max_uses',
        'max_uses_per_user',
        'used_count',
        'is_active',
    ];

    protected $casts = [
        'valid_from' => 'date',
        'valid_to' => 'date',
    ];

    public function voucherBrand()
    {
        return $this->belongsTo(VoucherBrand::class, 'voucher_brands_id');
    }

    public function sale()
    {
        return $this->belongsTo(Sale::class, 'sale_id');
    }
}
