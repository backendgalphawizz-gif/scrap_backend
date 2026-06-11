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
                'question'      => 'How would you rate your overall experience?',
                'question_type' => 'multiple_choice',
                'options'       => ['Excellent', 'Good', 'Average', 'Poor', 'Very Poor'],
            ],
            [
                'question'      => 'Would you recommend us to others?',
                'question_type' => 'multiple_choice',
                'options'       => ['Definitely', 'Maybe', 'No'],
            ],
            [
                'question'      => 'Any additional feedback or suggestions?',
                'question_type' => 'input',
                'options'       => [],
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
