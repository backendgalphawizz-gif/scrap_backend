<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->enum('instagram_status', ['not_submitted', 'pending', 'verified', 'not_verified'])
                ->default('not_submitted')
                ->after('instagram_username');
            $table->enum('facebook_status', ['not_submitted', 'pending', 'verified', 'not_verified'])
                ->default('not_submitted')
                ->after('facebook_username');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['instagram_status', 'facebook_status']);
        });
    }
};
