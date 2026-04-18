<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CoinTransaction extends Model
{
    protected $fillable = [
        'coin_wallet_id',
        'transaction_id',
        'campaign_id',
        'coin',
        'type',
        'status',
        'amount',
        'tds',
        'convertion_rate',
        'transaction_type',
        'value',
        'description',
    ];

    public function wallet()
    {
        return $this->belongsTo(CoinWallet::class, 'coin_wallet_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function scopeCredit($query)
    {
        return $query->where('type', 'credit');
    }

}
