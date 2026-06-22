<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\DB;

class BrandFeedbackQuestion extends Model
{

    protected $fillable = [
        'brand_id',
        'question',
        'question_type',
        'options',
        'status'
    ];

    protected $casts = [
        'options' => 'array',
        'status' => 'boolean',
    ];

    public function brand()
    {
        return $this->belongsTo(Seller::class, 'brand_id', 'id')->select('id', 'username', DB::raw('CONCAT(f_name, " " ,l_name) as name'), 'image');
    }

    public static function createDefaultsForBrand(int $brandId): void
    {
        $globals = self::where('brand_id', 0)->where('status', 1)->orderBy('id')->limit(3)->get();

        if ($globals->count() === 3) {
            foreach ($globals as $template) {
                self::create([
                    'brand_id'      => $brandId,
                    'question'      => $template->question,
                    'question_type' => $template->question_type,
                    'options'       => $template->options ?? [],
                    'status'        => true,
                ]);
            }
            return;
        }

        $defaults = [
            [
                'question'      => 'How relevant was this campaign to you?',
                'question_type' => 'multiple_choice',
                'options'       => ['Very Relevant', 'Relevant', 'Neutral', 'Slightly Relevant', 'Not Relevant'],
            ],
            [
                'question'      => 'Did this campaign increase your interest in the brand?',
                'question_type' => 'multiple_choice',
                'options'       => ['Significantly Increased', 'Increased', 'No Change', 'Reduced Interest', 'Not Sure'],
            ],
            [
                'question'      => 'What influenced you most about this campaign?',
                'question_type' => 'multiple_choice',
                'options'       => ['Product/Service Offering', 'Price / Offer', 'Brand Reputation', 'Visual Content', 'Campaign Message', 'Nothing in Particular'],
            ],
        ];

        foreach ($defaults as $default) {
            self::create([
                'brand_id'      => $brandId,
                'question'      => $default['question'],
                'question_type' => $default['question_type'],
                'options'       => $default['options'],
                'status'        => true,
            ]);
        }
    }

}
