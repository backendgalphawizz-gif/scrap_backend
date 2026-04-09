<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Ensures emoji and other 4-byte UTF-8 (e.g. ₹) store correctly.
     * Error 1366 on descriptions usually means the table/DB used utf8 (3-byte) instead of utf8mb4.
     */
    public function up(): void
    {
        if (! in_array(Schema::getConnection()->getDriverName(), ['mysql', 'mariadb'], true)) {
            return;
        }

        DB::statement('ALTER TABLE campaigns CONVERT TO CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci');
    }

    public function down(): void
    {
        if (! in_array(Schema::getConnection()->getDriverName(), ['mysql', 'mariadb'], true)) {
            return;
        }

        // Reverting can fail if 4-byte characters exist; kept for symmetry only.
        DB::statement('ALTER TABLE campaigns CONVERT TO CHARACTER SET utf8 COLLATE utf8_unicode_ci');
    }
};
