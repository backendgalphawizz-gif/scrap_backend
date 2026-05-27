<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('campaigns', function (Blueprint $table) {
            $table->enum('settlement_status', ['pending', 'settled'])->default('pending')->after('stopped_at');
            $table->timestamp('settled_at')->nullable()->after('settlement_status');
            $table->decimal('amount_returned_to_wallet', 12, 2)->nullable()->after('settled_at');
        });
    }

    public function down(): void
    {
        Schema::table('campaigns', function (Blueprint $table) {
            $table->dropColumn(['settlement_status', 'settled_at', 'amount_returned_to_wallet']);
        });
    }
};
