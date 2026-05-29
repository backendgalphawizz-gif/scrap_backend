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

        $sql = file_get_contents($sqlFile);

        DB::unprepared($sql);
    }
}
