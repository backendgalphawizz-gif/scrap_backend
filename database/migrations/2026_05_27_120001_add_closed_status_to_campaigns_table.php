<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::statement("ALTER TABLE campaigns MODIFY COLUMN status ENUM(
            'active', 'inactive', 'pending', 'violated', 'live', 'completed', 'closed',
            'paused', 'stopped', 'rejected', 'accepted'
        ) NOT NULL DEFAULT 'pending'");
    }

    public function down(): void
    {
        DB::table('campaigns')->where('status', 'closed')->update(['status' => 'completed']);

        DB::statement("ALTER TABLE campaigns MODIFY COLUMN status ENUM(
            'active', 'inactive', 'pending', 'violated', 'live', 'completed',
            'paused', 'stopped', 'rejected', 'accepted'
        ) NOT NULL DEFAULT 'pending'");
    }
};
