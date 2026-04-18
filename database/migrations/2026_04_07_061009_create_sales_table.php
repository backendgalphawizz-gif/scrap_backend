<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('sales')) {
            return;
        }

        Schema::create('sales', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name', 45);
            $table->string('email', 45);
            $table->string('mobile', 45);
            $table->string('password', 250)->nullable();
            $table->enum('status', ['pending', 'active', 'inactive', 'blocked'])->default('pending');
            $table->decimal('balance', 10, 2)->default(0);
            $table->text('image')->nullable();
            $table->string('auth_token', 150)->nullable();
            $table->mediumText('reset_token')->nullable();
            $table->string('reset_otp', 10)->nullable();
            $table->dateTime('reset_otp_expires_at')->nullable();
            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at')->useCurrent();
            $table->string('pan_number', 45)->nullable();
            $table->mediumText('pan_image')->nullable();
            $table->enum('pan_status', ['Not Submitted', 'Submitted', 'Under Verification', 'Verified', 'Rejected'])->default('Not Submitted');
            $table->text('pan_rejection_reason')->nullable();
            $table->mediumText('bank_detail')->nullable();
            $table->enum('bank_status', ['Not Submitted', 'Submitted', 'Under Verification', 'Verified', 'Rejected'])->default('Not Submitted');
            $table->text('bank_rejection_reason')->nullable();
            $table->enum('kyc_status', ['pending', 'verified', 'rejected'])->nullable()->default('pending');
            $table->text('kyc_rejection_reason')->nullable();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('sales');
    }
};
