<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('payment_splits', function (Blueprint $table) {
            $table->decimal('repeat_brand_percentage', 5, 2)->default(0)->after('feedback_percentage');
            $table->decimal('user_referral_percentage', 5, 2)->default(0)->after('repeat_brand_percentage');
        });
    }

    public function down(): void
    {
        Schema::table('payment_splits', function (Blueprint $table) {
            $table->dropColumn(['repeat_brand_percentage', 'user_referral_percentage']);
        });
    }
};
