<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('sale_commission_ledgers', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('sale_id');
            $table->integer('brand_id');
            $table->integer('campaign_id');
            $table->decimal('amount', 10, 2)->default(0);
            $table->decimal('commission_rate', 10, 2)->default(0);
            $table->decimal('commission_amount', 10, 2)->default(0);
            $table->string('reference_type', 45)->nullable();
            $table->enum('status', ['pending', 'approved', 'rejected'])->default('pending');
            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at')->useCurrent()->useCurrentOnUpdate();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('sale_commission_ledgers');
    }
};
