<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('campaign_transactions', function (Blueprint $table) {
            $table->longText('unique_code')->nullable()->unique()->after('campaign_id');
        });

        Schema::table('tagged_posts_test', function (Blueprint $table) {
            $table->longText('unique_code')->nullable()->index()->after('scraped_at');
        });

        Schema::table('facebook_posts_test', function (Blueprint $table) {
            $table->longText('unique_code')->nullable()->index()->after('scraped_at');
        });
    }

    public function down(): void
    {
        Schema::table('campaign_transactions', function (Blueprint $table) {
            $table->dropColumn('unique_code');
        });

        Schema::table('tagged_posts_test', function (Blueprint $table) {
            $table->dropColumn('unique_code');
        });

        Schema::table('facebook_posts_test', function (Blueprint $table) {
            $table->dropColumn('unique_code');
        });
    }
};
