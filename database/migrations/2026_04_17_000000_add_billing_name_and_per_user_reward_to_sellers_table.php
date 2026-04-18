<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasTable('sellers')) {
            return;
        }

        Schema::table('sellers', function (Blueprint $table) {
            if (! Schema::hasColumn('sellers', 'billing_name')) {
                $table->string('billing_name', 100)->nullable()->after('alternate_contact');
            }
            if (! Schema::hasColumn('sellers', 'per_user_reward_rupees')) {
                $table->decimal('per_user_reward_rupees', 10, 2)->nullable()->after('billing_name');
            }
        });
    }

    public function down(): void
    {
        if (! Schema::hasTable('sellers')) {
            return;
        }

        Schema::table('sellers', function (Blueprint $table) {
            if (Schema::hasColumn('sellers', 'per_user_reward_rupees')) {
                $table->dropColumn('per_user_reward_rupees');
            }
            if (Schema::hasColumn('sellers', 'billing_name')) {
                $table->dropColumn('billing_name');
            }
        });
    }
};
