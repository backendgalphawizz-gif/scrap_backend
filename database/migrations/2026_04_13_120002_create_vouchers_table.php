<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('vouchers', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('voucher_brands_id');
            $table->string('title', 255);
            $table->text('description')->nullable();
            $table->decimal('coin_price', 12, 2);
            $table->decimal('fiat_value', 12, 2);
            $table->string('code', 100)->unique();
            $table->enum('status', ['available', 'purchased', 'expired'])->default('available');
            $table->integer('validity_days')->default(0);
            $table->boolean('is_active')->default(true)->comment('active = 1, inactive = 0');
            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at')->useCurrent()->useCurrentOnUpdate();

            $table->index('voucher_brands_id');
            $table->index('status');
            $table->index('is_active');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('vouchers');
    }
};
