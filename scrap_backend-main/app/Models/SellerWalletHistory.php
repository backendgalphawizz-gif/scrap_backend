<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
// use Laravel\Passport\HasApiTokens;

class SellerWalletHistory extends Model
{
    protected $fillable = [
        'seller_id',
        'amount',
        'remarks',
        'type'
    ];
}
