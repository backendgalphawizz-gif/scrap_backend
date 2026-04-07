<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('email')->nullable()->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->enum('provider', ['google', 'facebook', 'instagram', 'manual'])->nullable()->default('manual');
            $table->string('provider_id', 150)->nullable();
            $table->string('password')->nullable();
            $table->string('actual_password')->nullable();
            $table->rememberToken();
            $table->string('mobile')->nullable();
            $table->unsignedBigInteger('role_id')->nullable();
            $table->string('internal_id')->nullable();
            $table->string('gmr_ci_id')->nullable();
            $table->string('gmr_mi_id')->nullable();
            $table->string('circle_assignment')->nullable();
            $table->string('image')->nullable();
            $table->tinyInteger('status')->default(1)->comment('1=active, 2=inactive');
            $table->string('otp')->nullable();
            $table->timestamp('otp_expires_at')->nullable();
            $table->string('instagram_username', 45)->nullable();
            $table->string('facebook_username', 45)->nullable();
            $table->date('dob')->nullable();
            $table->enum('gender', ['male', 'female', 'others'])->default('male');
            $table->string('referral_code', 45)->nullable();
            $table->string('friends_code', 45)->nullable();
            $table->string('profession', 45)->nullable();
            $table->string('state', 45)->nullable();
            $table->string('city', 45)->nullable();
            $table->string('native_state', 45)->nullable();
            $table->string('native_city', 45)->nullable();
            $table->string('pan_number', 45)->nullable();
            $table->mediumText('pan_image')->nullable();
            $table->enum('pan_status', ['Not Submitted', 'Submitted', 'Under Verification', 'Verified', 'Rejected'])->default('Not Submitted');
            $table->text('pan_rejection_reason')->nullable();
            $table->string('aadhar_number', 45)->nullable();
            $table->mediumText('aadhar_image')->nullable();
            $table->enum('aadhar_status', ['Not Submitted', 'Submitted', 'Under Verification', 'Verified', 'Rejected'])->default('Not Submitted');
            $table->text('aadhar_rejection_reason')->nullable();
            $table->enum('bank_status', ['Not Submitted', 'Submitted', 'Under Verification', 'Verified', 'Rejected'])->default('Not Submitted');
            $table->text('bank_rejection_reason')->nullable();
            $table->enum('upi_status', ['Not Submitted', 'Submitted', 'Under Verification', 'Verified', 'Rejected'])->default('Not Submitted');
            $table->text('upi_rejection_reason')->nullable();
            $table->json('bank_detail')->nullable();
            $table->string('upi_id', 120)->nullable();
            $table->string('device_type', 45)->nullable();
            $table->string('income_range', 45)->nullable();
            $table->longText('fcm_id')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('password_reset_tokens', function (Blueprint $table) {
            $table->string('email')->primary();
            $table->string('token');
            $table->timestamp('created_at')->nullable();
        });

        Schema::create('sessions', function (Blueprint $table) {
            $table->string('id')->primary();
            $table->foreignId('user_id')->nullable()->index();
            $table->string('ip_address', 45)->nullable();
            $table->text('user_agent')->nullable();
            $table->longText('payload');
            $table->integer('last_activity')->index();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
        Schema::dropIfExists('password_reset_tokens');
        Schema::dropIfExists('sessions');
    }
};
