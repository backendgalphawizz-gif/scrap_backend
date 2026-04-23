<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
// use Laravel\Passport\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;

class Sale extends Authenticatable
{
    use Notifiable;

    protected $fillable = [
        'name',
        'phone',
        'email',
        'balance',
        'image',
        'auth_token',
        'referral_code',
    ];

    protected $appends = [
        'brand_count',
        'campaign_count',
        'total_earnings'
    ];

    public function getImageAttribute($value)
    {
        return (strpos($value, 'https://') === 0) ? $value : asset('storage/profile/' . ( $value ?: 'def.png'));
    }

    public function brands() {
        return $this->hasMany(Seller::class, 'sale_id', 'id')->orderBy('id', 'DESC');
    }

    public function campaigns() {
        return $this->hasMany(Campaign::class, 'sale_id', 'id')->orderBy('id', 'DESC');
    }

    public function getBrandCountAttribute() {
        return $this->brands()?->count() ?? 0;
    }

    public function getCampaignCountAttribute() {
        return $this->campaigns()?->count() ?? 0;
    }
    
    public function getTotalEarningsAttribute() {
        return strval(0);
    }

    public function getBankDetailAttribute($value) {
        return json_decode($value) ?? "{}";
    }
    public function getPanImageAttribute($value) {
        return (strpos($value, 'https://') === 0) ? $value : asset('storage/profile/' . ( $value ?: 'def.png'));
    }

}
