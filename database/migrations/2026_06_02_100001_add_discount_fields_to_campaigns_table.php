<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('campaigns', function (Blueprint $table) {
            $table->decimal('discount_amount', 10, 2)->default(0)->after('compign_budget_with_gst')
                ->comment('Voucher discount absorbed from sales commission');
            $table->string('discount_code', 50)->nullable()->after('discount_amount')
                ->comment('The discount voucher code applied at campaign creation');
        });
    }

    public function down(): void
    {
        Schema::table('campaigns', function (Blueprint $table) {
            $table->dropColumn(['discount_amount', 'discount_code']);
        });
    }
};
