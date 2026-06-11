<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->unsignedBigInteger('instagram_followers')->nullable()->after('instagram_status');
            $table->unsignedBigInteger('facebook_followers')->nullable()->after('facebook_status');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['instagram_followers', 'facebook_followers']);
        });
    }
};
