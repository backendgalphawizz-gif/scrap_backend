<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
// use Laravel\Passport\HasApiTokens;

class SaleWalletTransaction extends Model
{
    protected $fillable = [
        'sale_id',
        'amount',
        'tds',
        'net_amount',
        'tds_rate',
        'tds_section',
        'pan_status_at_withdrawal',
        'remarks',
        'type',
        'status',
    ];

    public function sale() {
        return $this->belongsTo(Sale::class, 'sale_id');
    }

}
