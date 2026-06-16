<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('campaign_credit_notes', function (Blueprint $table) {
            $table->unsignedInteger('purchased_posts')->nullable()->after('status');
            $table->unsignedInteger('completed_posts')->nullable()->after('purchased_posts');
            $table->unsignedInteger('unutilized_posts')->nullable()->after('completed_posts');
            $table->decimal('per_post_amount', 12, 2)->default(0)->after('unutilized_posts');
            $table->decimal('gross_reversal_amount', 12, 2)->default(0)->after('per_post_amount');
            $table->decimal('discount_reversal', 12, 2)->default(0)->after('gross_reversal_amount');
            $table->decimal('igst_reversal', 12, 2)->default(0)->after('discount_reversal');
            $table->boolean('is_intra_state')->default(true)->after('igst_reversal');
        });
    }

    public function down(): void
    {
        Schema::table('campaign_credit_notes', function (Blueprint $table) {
            $table->dropColumn([
                'purchased_posts',
                'completed_posts',
                'unutilized_posts',
                'per_post_amount',
                'gross_reversal_amount',
                'discount_reversal',
                'igst_reversal',
                'is_intra_state',
            ]);
        });
    }
};
