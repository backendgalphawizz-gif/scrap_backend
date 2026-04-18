<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('brand_feedback_questions', function (Blueprint $table) {
            $table->unsignedInteger('brand_category_id')->nullable()->after('brand_id');
            $table->json('options')->nullable()->after('question');
        });
    }

    public function down(): void
    {
        Schema::table('brand_feedback_questions', function (Blueprint $table) {
            $table->dropColumn(['brand_category_id', 'options']);
        });
    }
};
