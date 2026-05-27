<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ProfessionSeeder extends Seeder
{
    public function run(): void
    {
        $professions = [
            'Developer',
            'Sales',
            'Private Employee',
            'Govt Employee',
        ];

        foreach ($professions as $name) {
            $existing = DB::table('professions')->where('name', $name)->first();

            if ($existing) {
                DB::table('professions')
                    ->where('id', $existing->id)
                    ->update([
                        'status' => true,
                        'updated_at' => now(),
                    ]);

                continue;
            }

            DB::table('professions')->insert([
                'name' => $name,
                'status' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}
