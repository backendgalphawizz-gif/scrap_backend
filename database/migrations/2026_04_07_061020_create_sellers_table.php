<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('sellers', function (Blueprint $table) {
            $table->id();
            $table->string('username', 45)->nullable();
            $table->string('f_name', 30)->nullable();
            $table->string('l_name', 30)->nullable();
            $table->string('phone', 25)->nullable();
            $table->string('image', 30)->default('def.png');
            $table->string('email', 80);
            $table->string('password', 80)->nullable();
            $table->string('status', 15)->default('pending');
            $table->rememberToken();
            $table->timestamp('created_at')->nullable()->useCurrent();
            $table->timestamp('updated_at')->nullable()->useCurrent()->useCurrentOnUpdate();
            $table->text('auth_token')->nullable();
            $table->string('cm_firebase_token', 191)->nullable();
            $table->string('city', 45)->nullable();
            $table->string('state', 45)->nullable();
            $table->string('instagram_username', 45)->nullable();
            $table->string('facebook_username', 45)->nullable();
            $table->string('friends_code', 45)->nullable();
            $table->string('referral_code', 45)->nullable();
            $table->string('website_url', 45)->nullable();
            $table->string('visibility_status', 45)->nullable();
            $table->integer('sale_id')->nullable();
            $table->integer('category_id');
            $table->integer('sub_category_id')->nullable();
            $table->string('gst_number', 45)->nullable();
            $table->enum('gst_status', ['Not Submitted', 'Submitted', 'Under Verification', 'Verified', 'Rejected'])->default('Not Submitted');
            $table->text('gst_rejection_reason')->nullable();
            $table->enum('business_registeration_type', ['Proprietor', 'Pvt Ltd', 'LLP'])->default('Proprietor');
            $table->string('pan_number', 45)->nullable();
            $table->mediumText('pan_image')->nullable();
            $table->enum('pan_status', ['Not Submitted', 'Submitted', 'Under Verification', 'Verified', 'Rejected'])->default('Not Submitted');
            $table->text('pan_rejection_reason')->nullable();
            $table->string('primary_contact', 45)->nullable();
            $table->string('alternate_contact', 45)->nullable();
            $table->mediumText('full_address')->nullable();
            $table->mediumText('google_map_link')->nullable();
            $table->mediumText('website_link')->nullable();
            $table->softDeletes();

            $table->unique('email');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('sellers');
    }
};
