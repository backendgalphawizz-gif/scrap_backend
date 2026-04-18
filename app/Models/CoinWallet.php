<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\CPU\Helpers;

class CoinWallet extends Model
{
    protected $fillable = [
        'user_id',
        'balance',
        'withdrawal_frozen',
    ];

    protected $casts = [
        'withdrawal_frozen' => 'boolean',
        'status' => 'boolean',
    ];

    protected $appends = [
        'total_coin_withdrawl',
        'total_coin_earning',
        'todays_coin_earning',
        'total_earning_in_rupees',
        'total_pending_coin'
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function transactions()
    {
        return $this->hasMany(CoinTransaction::class);
    }

    public function getTotalPendingCoinAttribute() {
        return $this->transactions()->where('type', 'credit')->where('status', 'pending')->sum('coin');
    }
    public function getTodaysCoinEarningAttribute() {
        return $this->transactions()
            ->where('type', 'credit')
            ->where(function ($query) {
                $query->where('status', 'completed')->orWhereNull('status');
            })
            ->whereBetween('created_at', [date('Y-m-d 00:00:01'), date('Y-m-d 23:58:00')])
            ->sum('coin');
    }

    public function getTotalCoinWithdrawlAttribute() {
        // ->where('status', 'completed')
        return $this->transactions()->where('type', 'debit')->sum('coin');
    }

    public function getTotalCoinEarningAttribute() {
        return $this->transactions()
            ->where('type', 'credit')
            ->where(function ($query) {
                $query->where('status', 'completed')->orWhereNull('status');
            })
            ->sum('coin');
    }

    public function getTotalearningInRupeesAttribute() {
        return $this->transactions()
            ->where('type', 'credit')
            ->where(function ($query) {
                $query->where('status', 'completed')->orWhereNull('status');
            })
            ->sum('coin') * Helpers::get_business_settings('upi_value');
    }

}
