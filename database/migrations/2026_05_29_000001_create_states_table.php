<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('states')) {
            return;
        }

        Schema::create('states', function (Blueprint $table) {
            $table->integer('state_id')->unsigned()->primary();
            $table->string('name', 30);
            $table->integer('country_id')->unsigned()->default(101);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('states');
    }
};
