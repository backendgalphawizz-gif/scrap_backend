<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('sale_permission_role', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('role_id');
            $table->unsignedBigInteger('permission_id');
            $table->timestamps();

            $table->unique(['role_id', 'permission_id']);
            $table->index('permission_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('sale_permission_role');
    }
};
