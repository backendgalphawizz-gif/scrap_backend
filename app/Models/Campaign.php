<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;
use Illuminate\Support\Facades\DB;

class Campaign extends Model
{
    // use LogsActivity;

    protected $fillable = [
        'title',
        'descriptions',
        'guidelines',
        'coins',
        'tags',
        'start_date',
        'end_date',
        'brand_id',
        'status',
        'thumbnail',
        'images',
        'share_on',
        'category_id',
        'sub_category_id',
        'unique_code','used_post','total_user_required','sale_id','sales_referal_code','admin_percentage','user_percentage','sales_percentage','compign_budget_with_gst',
        'repeat_brand_percentage','user_referral_percentage','referral_coin',
    ];

    protected $appends = [
        'left_days',
        'sale_person_name',
        'sale_person_image',
        'engagement',
        'avg_feedback',
        'cost_per_click',
        'budget',
        'occupied_slots',
        'available_slots',
        'is_slot_full'
    ];

    // public function getActivitylogOptions(): LogOptions
    // {
    //     return LogOptions::defaults()
    //         ->logAll()
    //         ->useLogName('campaign')
    //         ->logOnlyDirty()
    //         ->setDescriptionForEvent(fn(string $eventName) => "Campaign {$eventName}");
    // }

    public function getGuidelinesAttribute($value)
    {
        return $value ? explode('|', $value) : [];
    }

    public function getThumbnailAttribute($value)
    {
        return (strpos($value, 'https://') === 0) ? $value : ($value ? asset('storage/profile/'.$value) : asset('assets/logo/logo-1.png'));

        // return asset('storage/profile/' . ( $value ?: 'def.png'));
    }

    public function getImagesAttribute($images)
    {
        $images = explode(',', $images);
        $imageUrls = [];
        foreach ($images as $image) {
            $imageUrls[] = (strpos($image, 'https://') === 0) ? $image : asset('storage/profile/' . ( $image ?: 'def.png'));
        }
        return $imageUrls;
    }

    public function brand()
    {
        return $this->belongsTo(Seller::class, 'brand_id', 'id')->select('id', 'username', DB::raw('CONCAT(f_name, " " ,l_name) as name'), 'image', 'instagram_username', 'facebook_username');
    }

    public function sale()
    {
        return $this->belongsTo(Sale::class, 'sale_id', 'id')->select('id', 'name', 'image');
    }

    public function feedbacks()
    {
        return $this->hasMany(Feedback::class, 'campaign_id', 'id');
    }

    public function campaign_transactions()
    {
        return $this->hasMany(CampaignTransaction::class, 'campaign_id');
    }

    public function occupiedTransactions()
    {
        return $this->hasMany(CampaignTransaction::class, 'campaign_id')
            ->whereIn('status', CampaignTransaction::SLOT_OCCUPIED_STATUSES);
    }

    public function getLeftDaysAttribute()
    {
        $endDate = \Carbon\Carbon::parse($this->end_date);
        $currentDate = \Carbon\Carbon::now();
        return max(0, (int)$currentDate->diffInDays($endDate, false));
    }

    public function getSalePersonNameAttribute()
    {
        return $this->sale()->name ?? '';
    }
    public function getSalePersonImageAttribute()
    {
        return $this->sale()->image ?? '';
    }

    public function getEngagementAttribute() {
        
        return "0";
    }
    public function getAvgFeedbackAttribute() {
        $avgFeedback = $this->feedbacks()->avg('ratings');
        return $avgFeedback ? round($avgFeedback, 2) : "0";
    }
    public function getCostPerClickAttribute() {
        return "0";
    }
    public function getBudgetAttribute() {
        return "0";
    }

    public function getOccupiedSlotsAttribute()
    {
        if (array_key_exists('occupied_slots', $this->attributes)) {
            return (int) $this->attributes['occupied_slots'];
        }

        if ($this->relationLoaded('occupiedTransactions')) {
            return $this->occupiedTransactions->count();
        }

        return $this->occupiedTransactions()->count();
    }

    public function getAvailableSlotsAttribute()
    {
        $requiredSlots = (int) $this->total_user_required;

        if ($requiredSlots <= 0) {
            return 0;
        }

        return max(0, $requiredSlots - $this->occupied_slots);
    }

    public function getIsSlotFullAttribute()
    {
        $requiredSlots = (int) $this->total_user_required;

        if ($requiredSlots <= 0) {
            return false;
        }

        return $this->occupied_slots >= $requiredSlots;
    }

}
