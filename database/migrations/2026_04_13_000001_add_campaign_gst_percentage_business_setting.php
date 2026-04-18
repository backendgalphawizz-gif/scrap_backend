<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        if (! DB::table('business_settings')->where('type', 'campaign_gst_percentage')->exists()) {
            DB::table('business_settings')->insert([
                'type' => 'campaign_gst_percentage',
                'value' => '18',
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }

    public function down(): void
    {
        DB::table('business_settings')->where('type', 'campaign_gst_percentage')->delete();
    }
};
