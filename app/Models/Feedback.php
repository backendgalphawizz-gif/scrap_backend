<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\DB;

class Feedback extends Model
{

    protected $fillable = [
        'brand_id',
        'campaign_id',
        'user_id',
        'questions',
        'ratings',
        'user_feedback'
    ];

    protected $table = 'feedbacks';

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id')->select('id', 'name', 'image');
    }
    public function campaign()
    {
        return $this->belongsTo(Campaign::class, 'campaign_id', 'id')->select('id', 'title');
    }
    public function brand()
    {
        return $this->belongsTo(Seller::class, 'brand_id', 'id')->select('id', 'username', DB::raw('CONCAT(f_name, " " ,l_name) as name'), 'image');
    }

    public function getQuestionsAttribute($value) {
        return json_decode($value, true);
    }

}
