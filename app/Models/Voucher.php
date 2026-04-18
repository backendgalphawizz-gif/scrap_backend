<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Voucher extends Model
{
    protected $fillable = [
        'voucher_brands_id',
        'title',
        'description',
        'coin_price',
        'fiat_value',
        'code',
        'status',
        'validity_days',
        'is_active',
    ];

    public function voucherBrand()
    {
        return $this->belongsTo(VoucherBrand::class, 'voucher_brands_id');
    }
}
