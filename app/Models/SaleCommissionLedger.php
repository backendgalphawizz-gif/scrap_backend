<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use DB; 

class SaleCommissionLedger extends Model
{

    protected $fillable = [
        'sale_id',
        'brand_id',
        'campaign_id',
        'amount',
        'commission_rate',
        'commission_amount',
        'reference_type',
        'status'
    ];

    public function sale() {
        return $this->belongsTo(Sale::class, 'sale_id')->select('id', 'name', 'image');
    }
    
    public function brand() {
        return $this->belongsTo(Seller::class, 'brand_id', 'id')->select('id', 'username', DB::raw('CONCAT(f_name, " " ,l_name) as name'), 'image');
    }

    public function campaign() {
        return $this->belongsTo(Campaign::class, 'campaign_id')->select('id', 'title');
    }
}
