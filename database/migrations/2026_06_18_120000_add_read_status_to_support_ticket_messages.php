<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('support_ticket_messages', function (Blueprint $table) {
            $table->boolean('seen_by_admin')->default(false)->after('body');
            $table->boolean('seen_by_requester')->default(false)->after('seen_by_admin');
        });

        // Treat existing messages as already read on both sides.
        DB::table('support_ticket_messages')->update([
            'seen_by_admin' => true,
            'seen_by_requester' => true,
        ]);
    }

    public function down(): void
    {
        Schema::table('support_ticket_messages', function (Blueprint $table) {
            $table->dropColumn(['seen_by_admin', 'seen_by_requester']);
        });
    }
};
