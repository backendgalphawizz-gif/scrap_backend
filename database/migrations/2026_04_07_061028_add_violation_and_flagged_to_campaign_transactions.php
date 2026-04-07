<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // Add violation_reason column
        Schema::table('campaign_transactions', function (Blueprint $table) {
            $table->text('violation_reason')->nullable()->after('post_url');
        });

        // Extend the status enum to include 'flagged'
        DB::statement("ALTER TABLE campaign_transactions MODIFY COLUMN status ENUM('pending','completed','active','deleted','approved','rejected','flagged') DEFAULT 'pending'");
    }

    public function down(): void
    {
        DB::statement("ALTER TABLE campaign_transactions MODIFY COLUMN status ENUM('pending','completed','active','deleted','approved','rejected') DEFAULT 'pending'");

        Schema::table('campaign_transactions', function (Blueprint $table) {
            $table->dropColumn('violation_reason');
        });
    }
};
