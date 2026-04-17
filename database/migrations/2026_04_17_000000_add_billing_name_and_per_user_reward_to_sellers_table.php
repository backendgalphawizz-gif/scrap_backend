<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('sellers', function (Blueprint $table) {
            $table->string('billing_name', 100)->nullable()->after('alternate_contact');
            $table->decimal('per_user_reward_rupees', 10, 2)->nullable()->after('billing_name');
        });
    }

    public function down(): void
    {
        Schema::table('sellers', function (Blueprint $table) {
            $table->dropColumn(['billing_name', 'per_user_reward_rupees']);
        });
    }
};
