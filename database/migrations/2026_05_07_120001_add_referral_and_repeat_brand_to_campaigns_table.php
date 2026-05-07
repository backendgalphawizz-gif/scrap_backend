<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('campaigns', function (Blueprint $table) {
            $table->decimal('repeat_brand_percentage', 5, 2)->nullable()->after('feedback_coin');
            $table->decimal('user_referral_percentage', 5, 2)->nullable()->after('repeat_brand_percentage');
            $table->decimal('referral_coin', 10, 2)->nullable()->after('user_referral_percentage');
        });
    }

    public function down(): void
    {
        Schema::table('campaigns', function (Blueprint $table) {
            $table->dropColumn(['repeat_brand_percentage', 'user_referral_percentage', 'referral_coin']);
        });
    }
};
