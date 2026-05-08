<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('campaigns', function (Blueprint $table) {
            $table->enum('refund_status', ['pending', 'processed'])->nullable()->after('compign_budget_with_gst');
            $table->decimal('refunded_amount', 10, 2)->nullable()->default(0)->after('refund_status');
            $table->text('refund_note')->nullable()->after('refunded_amount');
            $table->timestamp('stopped_at')->nullable()->after('refund_note');
        });
    }

    public function down(): void
    {
        Schema::table('campaigns', function (Blueprint $table) {
            $table->dropColumn(['refund_status', 'refunded_amount', 'refund_note', 'stopped_at']);
        });
    }
};
