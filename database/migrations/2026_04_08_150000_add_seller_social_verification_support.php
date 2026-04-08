<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Add seller_id and make user_id nullable on social_verification_transactions
        Schema::table('social_verification_transactions', function (Blueprint $table) {
            // Drop the existing FK so we can alter the column
            $table->dropForeign(['user_id']);

            // Make user_id nullable (brand rows won't have a user)
            $table->unsignedBigInteger('user_id')->nullable()->change();

            // Brand-side owner column
            $table->unsignedBigInteger('seller_id')->nullable()->after('user_id');

            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('seller_id')->references('id')->on('sellers')->onDelete('cascade');
        });

        // Add social verification status columns to sellers
        Schema::table('sellers', function (Blueprint $table) {
            $table->enum('instagram_status', ['not_verified', 'pending', 'verified'])
                ->default('not_verified')
                ->after('instagram_username');

            $table->enum('facebook_status', ['not_verified', 'pending', 'verified'])
                ->default('not_verified')
                ->after('facebook_username');
        });
    }

    public function down(): void
    {
        Schema::table('social_verification_transactions', function (Blueprint $table) {
            $table->dropForeign(['seller_id']);
            $table->dropColumn('seller_id');

            $table->dropForeign(['user_id']);
            $table->unsignedBigInteger('user_id')->nullable(false)->change();
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });

        Schema::table('sellers', function (Blueprint $table) {
            $table->dropColumn(['instagram_status', 'facebook_status']);
        });
    }
};
