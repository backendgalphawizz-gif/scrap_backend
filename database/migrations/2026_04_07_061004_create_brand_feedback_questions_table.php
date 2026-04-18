<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('brand_feedback_questions')) {
            return;
        }

        Schema::create('brand_feedback_questions', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('brand_id');
            $table->text('question');
            $table->boolean('status')->default(true);
            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at')->useCurrent()->useCurrentOnUpdate();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('brand_feedback_questions');
    }
};
