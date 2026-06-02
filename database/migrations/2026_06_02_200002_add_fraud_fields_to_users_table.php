<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('device_id', 255)->nullable()->after('fcm_id')->index();
            $table->unsignedTinyInteger('fraud_score')->default(0)->after('device_id');
            $table->enum('fraud_status', ['clean', 'watch', 'flagged', 'blocked'])->default('clean')->after('fraud_score');
            $table->timestamp('last_fraud_check_at')->nullable()->after('fraud_status');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropIndex(['device_id']);
            $table->dropColumn(['device_id', 'fraud_score', 'fraud_status', 'last_fraud_check_at']);
        });
    }
};
