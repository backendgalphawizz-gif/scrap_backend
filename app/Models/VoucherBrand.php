<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class VoucherBrand extends Model
{
    protected $fillable = [
        'name',
        'logo',
        'is_active',
    ];

    public function vouchers()
    {
        return $this->hasMany(Voucher::class, 'voucher_brands_id');
    }
}
