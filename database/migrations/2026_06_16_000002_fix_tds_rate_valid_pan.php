<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::table('business_settings')
            ->whereIn('key', ['tds_rate_valid_pan', 'tds_percent'])
            ->update(['value' => '1']);
    }

    public function down(): void
    {
        DB::table('business_settings')
            ->whereIn('key', ['tds_rate_valid_pan', 'tds_percent'])
            ->update(['value' => '30']);
    }
};
