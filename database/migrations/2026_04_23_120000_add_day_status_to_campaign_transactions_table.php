<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('campaign_transactions')) {
            return;
        }

        if (!Schema::hasColumn('campaign_transactions', 'day_status')) {
            Schema::table('campaign_transactions', function (Blueprint $table) {
                $table->unsignedTinyInteger('day_status')->default(0)->after('post_url');
            });
        }

        DB::table('campaign_transactions')
            ->whereNull('day_status')
            ->update(['day_status' => 0]);
    }

    public function down(): void
    {
        if (!Schema::hasTable('campaign_transactions')) {
            return;
        }

        if (Schema::hasColumn('campaign_transactions', 'day_status')) {
            Schema::table('campaign_transactions', function (Blueprint $table) {
                $table->dropColumn('day_status');
            });
        }
    }
};
