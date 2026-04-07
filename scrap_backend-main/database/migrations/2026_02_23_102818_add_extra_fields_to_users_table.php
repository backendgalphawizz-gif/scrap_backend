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
        Schema::table('users', function (Blueprint $table) {
            //
            $table->string('actual_password')->nullable()->after('password');
            $table->string('mobile')->nullable()->after('email');
            $table->string('internal_id')->nullable();
            $table->string('gmr_ci_id')->nullable();
            $table->string('gmr_mi_id')->nullable();
            $table->string('circle_assignment')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            //
             $table->dropColumn([
                'role_id',
                'mobile',
                'internal_id',
                'gmr_ci_id',
                'gmr_mi_id',
                'circle_assignment'
            ]);
        });
    }
};
