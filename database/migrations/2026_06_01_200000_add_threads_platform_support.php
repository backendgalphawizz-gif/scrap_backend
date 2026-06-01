<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // Extend platform enum on social_verification_transactions
        DB::statement("ALTER TABLE social_verification_transactions MODIFY platform ENUM('instagram', 'facebook', 'threads') NOT NULL");

        // Extend shared_on enum on campaign_transactions
        DB::statement("ALTER TABLE campaign_transactions MODIFY shared_on ENUM('instagram', 'facebook', 'threads') DEFAULT 'instagram'");

        // Add threads columns to users table
        Schema::table('users', function (Blueprint $table) {
            $table->string('threads_username', 45)->nullable()->after('facebook_status');
            $table->enum('threads_status', ['not_submitted', 'pending', 'verified', 'not_verified'])->default('not_submitted')->after('threads_username');
        });

        // Add threads columns to sellers table (matching seller enum value order)
        Schema::table('sellers', function (Blueprint $table) {
            $table->string('threads_username', 45)->nullable()->after('facebook_status');
            $table->enum('threads_status', ['not_verified', 'pending', 'verified', 'not_submitted'])->default('not_submitted')->after('threads_username');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['threads_username', 'threads_status']);
        });

        Schema::table('sellers', function (Blueprint $table) {
            $table->dropColumn(['threads_username', 'threads_status']);
        });

        DB::statement("ALTER TABLE social_verification_transactions MODIFY platform ENUM('instagram', 'facebook') NOT NULL");

        DB::statement("ALTER TABLE campaign_transactions MODIFY shared_on ENUM('instagram', 'facebook') DEFAULT 'instagram'");
    }
};
