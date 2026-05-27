<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        $now = now();

        $language = json_encode([
            [
                'id' => '1',
                'name' => 'English',
                'code' => 'en',
                'status' => 1,
                'default' => true,
                'direction' => 'ltr',
            ],
        ]);

        $rows = [
            ['type' => 'language', 'value' => $language],
            ['type' => 'company_name', 'value' => 'Rexarix'],
            ['type' => 'timezone', 'value' => 'Asia/Kolkata'],
            ['type' => 'currency_model', 'value' => 'single_currency'],
            ['type' => 'currency_symbol_position', 'value' => 'left'],
            ['type' => 'decimal_point_settings', 'value' => '2'],
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
            'language',
            'company_name',
            'timezone',
            'currency_model',
            'currency_symbol_position',
            'decimal_point_settings',
        ])->delete();
    }
};
