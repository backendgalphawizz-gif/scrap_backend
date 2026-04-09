<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Per-column utf8mb4: fixes 1366 when CONVERT TABLE did not apply or legacy column collations remained.
     */
    public function up(): void
    {
        if (! in_array(Schema::getConnection()->getDriverName(), ['mysql', 'mariadb'], true)) {
            return;
        }

        DB::statement('ALTER TABLE campaigns
            MODIFY title VARCHAR(45) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
            MODIFY descriptions LONGTEXT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL,
            MODIFY guidelines LONGTEXT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL,
            MODIFY tags TEXT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL,
            MODIFY images LONGTEXT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL,
            MODIFY thumbnail TEXT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL,
            MODIFY city VARCHAR(45) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL,
            MODIFY state VARCHAR(45) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL,
            MODIFY share_on VARCHAR(45) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT \'instagram,facebook\',
            MODIFY age_range VARCHAR(45) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL');
    }

    public function down(): void
    {
        if (! in_array(Schema::getConnection()->getDriverName(), ['mysql', 'mariadb'], true)) {
            return;
        }

        DB::statement('ALTER TABLE campaigns
            MODIFY title VARCHAR(45) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
            MODIFY descriptions LONGTEXT CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL,
            MODIFY guidelines LONGTEXT CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL,
            MODIFY tags TEXT CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL,
            MODIFY images LONGTEXT CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL,
            MODIFY thumbnail TEXT CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL,
            MODIFY city VARCHAR(45) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL,
            MODIFY state VARCHAR(45) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL,
            MODIFY share_on VARCHAR(45) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL DEFAULT \'instagram,facebook\',
            MODIFY age_range VARCHAR(45) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL');
    }
};
