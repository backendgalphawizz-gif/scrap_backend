<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CitySeeder extends Seeder
{
    public function run(): void
    {
        if (DB::table('cities')->count() > 0) {
            return;
        }

        $sqlFile = database_path('seeders/sql/cities.sql');

        if (! file_exists($sqlFile)) {
            $this->command->error("Cities SQL file not found: {$sqlFile}");
            return;
        }

        $sql = trim(file_get_contents($sqlFile));

        // Extract the INSERT header (e.g. INSERT INTO `cities` (`col1`, ...) VALUES)
        if (! preg_match('/^(INSERT INTO\s+.+?\s+VALUES)\s*/is', $sql, $headerMatch)) {
            $this->command->error('Could not parse cities SQL file.');
            return;
        }

        $header = $headerMatch[1];

        // Remove header, strip trailing semicolon, then split individual value rows
        $valuesBlock = trim(preg_replace('/^INSERT INTO\s+.+?\s+VALUES\s*/is', '', $sql));
        $valuesBlock = rtrim($valuesBlock, ';');

        // Split on "),(" boundaries to get individual rows, then re-add parentheses
        $rows = preg_split('/\),\s*\(/s', $valuesBlock);
        $rows[0]                    = ltrim($rows[0], '(');
        $rows[count($rows) - 1]     = rtrim($rows[count($rows) - 1], ')');

        DB::statement('SET FOREIGN_KEY_CHECKS=0');

        $batchSize = 500;
        foreach (array_chunk($rows, $batchSize) as $batch) {
            $values = implode('),(', $batch);
            DB::unprepared("{$header} ({$values})");
        }

        DB::statement('SET FOREIGN_KEY_CHECKS=1');
    }
}
