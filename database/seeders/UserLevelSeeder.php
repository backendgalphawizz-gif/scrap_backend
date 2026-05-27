<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class UserLevelSeeder extends Seeder
{
    public function run(): void
    {
        $levels = [
            [
                'name' => 'Bronze',
                'range_min' => 0,
                'range_max' => 500,
                'max_participations_per_day' => 2,
            ],
            [
                'name' => 'Silver',
                'range_min' => 501,
                'range_max' => 2000,
                'max_participations_per_day' => 5,
            ],
            [
                'name' => 'Gold',
                'range_min' => 2001,
                'range_max' => 5000,
                'max_participations_per_day' => 10,
            ],
            [
                'name' => 'Platinum',
                'range_min' => 5001,
                'range_max' => 10000,
                'max_participations_per_day' => 15,
            ],
        ];

        foreach ($levels as $level) {
            $existing = DB::table('user_levels')->where('name', $level['name'])->first();

            if ($existing) {
                DB::table('user_levels')
                    ->where('id', $existing->id)
                    ->update([
                        'range_min' => $level['range_min'],
                        'range_max' => $level['range_max'],
                        'max_participations_per_day' => $level['max_participations_per_day'],
                        'updated_at' => now(),
                    ]);

                continue;
            }

            DB::table('user_levels')->insert([
                'name' => $level['name'],
                'range_min' => $level['range_min'],
                'range_max' => $level['range_max'],
                'max_participations_per_day' => $level['max_participations_per_day'],
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}
