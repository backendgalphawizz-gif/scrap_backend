<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('campaign_transactions', function (Blueprint $table) {
            $table->boolean('manually_verified')->default(false)->after('verified_at');
            $table->unsignedBigInteger('manually_verified_by')->nullable()->after('manually_verified');
            $table->timestamp('manually_verified_at')->nullable()->after('manually_verified_by');
        });
    }

    public function down(): void
    {
        Schema::table('campaign_transactions', function (Blueprint $table) {
            $table->dropColumn(['manually_verified', 'manually_verified_by', 'manually_verified_at']);
        });
    }
};
