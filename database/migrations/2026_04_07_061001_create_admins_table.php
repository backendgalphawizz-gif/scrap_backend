<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('admins', function (Blueprint $table) {
            $table->id();
            $table->string('name', 80)->nullable();
            $table->string('phone', 25);
            $table->bigInteger('admin_role_id')->default(2);
            $table->string('image', 30)->default('def.png');
            $table->string('email', 80);
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password', 80);
            $table->rememberToken();
            $table->text('auth_token')->nullable();
            $table->integer('witness_status')->default(0)->comment('0=not_yet, 1=pending, 2=approved, 3=reject	');
            $table->timestamps();
            $table->boolean('status')->default(true);

            $table->unique('email');
            $table->unique('phone');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('admins');
    }
};
