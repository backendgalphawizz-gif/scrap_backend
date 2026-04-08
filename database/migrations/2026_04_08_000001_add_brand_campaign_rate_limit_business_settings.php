<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        $now = now();
        $rows = [
            [
                'type' => 'brand_max_campaigns_per_timeframe',
                'value' => '0',
            ],
            [
                'type' => 'brand_campaign_creation_timeframe_hours',
                'value' => '24',
            ],
        ];

        foreach ($rows as $row) {
            if (! DB::table('business_settings')->where('type', $row['type'])->exists()) {
                DB::table('business_settings')->insert(array_merge($row, [
                    'created_at' => $now,
                    'updated_at' => $now,
                ]));
            }
        }
    }

    public function down(): void
    {
        DB::table('business_settings')->whereIn('type', [
            'brand_max_campaigns_per_timeframe',
            'brand_campaign_creation_timeframe_hours',
        ])->delete();
    }
};
