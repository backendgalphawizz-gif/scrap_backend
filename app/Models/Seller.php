<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
// use Laravel\Passport\HasApiTokens;
use Illuminate\Database\Eloquent\SoftDeletes; // Import the trait

class Seller extends Authenticatable
{
    use Notifiable, SoftDeletes;

    protected static function booted(): void
    {
        static::created(function (self $seller): void {
            if (empty($seller->unique_code)) {
                $seller->forceFill([
                    'unique_code' => 'RXB-' . $seller->id,
                ])->saveQuietly();
            }
        });
    }

    protected $fillable = [
        'f_name',
        'l_name',
        'username',
        'phone',
        'email',
        'referral_code',
        'friends_code',
        'unique_code',
        'city',
        'state',
        'auth_token',
        'instagram_username',
        'instagram_status',
        'facebook_username',
        'facebook_status',
        'website_url',
        'sale_id',
        'visibility_status',
        'category_id',
        'sub_category_id',
        'gst_number',
        'gst_status',
        'gst_rejection_reason',
        'business_registeration_type',
        'pan_number',
        'pan_image',
        'pan_status',
        'pan_rejection_reason',
        'primary_contact',
        'alternate_contact',
        'full_address',
        'google_map_link',
        'website_link',
        'bank_account_number',
        'bank_ifsc_code',
        'bank_account_holder_name',
        'bank_account_type',
        'bank_status',
        'bank_rejection_reason',
    ];

    protected $appends = [
        'total_campaign',
        'total_campaign_participant',
        'total_campaign_budget',
        'total_campaign_budget_spent',
        'campaign_engagement',
        'total_campaign_ratings'
    ];

    public function questions() {
        return $this->hasMany(BrandFeedbackQuestion::class, 'brand_id');
    }
    
    public function campaigns() {
        return $this->hasMany(Campaign::class, 'brand_id');
    }

    public function wallet() {
        return $this->hasOne(SellerWallet::class);
    }

    public function getImageAttribute($value) {
        return (strpos($value, 'https://') === 0) ? $value : asset('storage/profile/' . ( $value ?: 'def.png'));
    }
    
    public function getPanImageAttribute($value) {
        return $value ? ((strpos($value, 'https://') === 0) ? $value : asset('storage/profile/' . $value)) : null;
    }

    public function getTotalCampaignAttribute() {
        return $this->campaigns()->count();
    }
    public function getTotalCampaignParticipantAttribute() {
        return \App\Models\CampaignTransaction::whereIn(
            'campaign_id',
            $this->campaigns()->pluck('id')
        )->count();
    }
    public function getTotalCampaignBudgetAttribute() {
        return $this->campaigns()->sum('total_campaign_budget');
    }
    public function getTotalCampaignBudgetSpentAttribute() {
        return $this->campaigns()->where('status', 'completed')->sum('total_campaign_budget');0;
    }
    public function getCampaignEngagementAttribute() {
        return "0";
    }
    public function getTotalCampaignRatingsAttribute() {
        return Feedback::whereIn(
            'campaign_id',
            $this->campaigns()->pluck('id')
        )->avg('ratings');
    }

}
