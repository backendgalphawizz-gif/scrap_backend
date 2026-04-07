<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;

use DB; 

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
        'share_on'
    ];

    protected $appends = [
        'left_days',
        'sale_person_name',
        'sale_person_image',
        'engagement',
        'avg_feedback',
        'cost_per_click',
        'budget'
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
        return $this->belongsTo(Seller::class, 'brand_id', 'id')->select('id', 'username', DB::raw('CONCAT(f_name, " " ,l_name) as name'), 'image');
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

}
