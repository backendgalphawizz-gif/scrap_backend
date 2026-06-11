<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Widen to 255 to accommodate full Facebook profile URLs
        // e.g. https://www.facebook.com/profile.php?id=61589493652439
        Schema::table('users', function (Blueprint $table) {
            $table->string('facebook_username', 255)->nullable()->change();
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('facebook_username', 45)->nullable()->change();
        });
    }
};
