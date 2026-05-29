<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('cities')) {
            return;
        }

        Schema::create('cities', function (Blueprint $table) {
            $table->integer('city_id')->unsigned()->primary();
            $table->string('name', 30);
            $table->integer('state_id')->unsigned();

            $table->foreign('state_id')->references('state_id')->on('states');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('cities');
    }
};
