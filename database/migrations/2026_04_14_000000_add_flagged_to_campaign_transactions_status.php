<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::statement("ALTER TABLE campaign_transactions MODIFY COLUMN status ENUM(
            'pending','completed','active','deleted','approved','rejected','flagged'
        ) NOT NULL DEFAULT 'pending'");
    }

    public function down(): void
    {
        DB::statement("ALTER TABLE campaign_transactions MODIFY COLUMN status ENUM(
            'pending','completed','active','deleted','approved','rejected'
        ) NOT NULL DEFAULT 'pending'");
    }
};
