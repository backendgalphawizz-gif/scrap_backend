<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class AdminRoleSeeder extends Seeder
{
    public function run(): void
    {
        $now = now();

        $roles = [
            [
                'id' => 1,
                'name' => 'Master Admin',
                'module_access' => null,
                'status' => 1,
            ],
            [
                'id' => 12,
                'name' => 'Admin',
                'module_access' => json_encode(['dashboard']),
                'status' => 1,
            ],
        ];

        foreach ($roles as $role) {
            $id = $role['id'];
            unset($role['id']);

            $exists = DB::table('admin_roles')->where('id', $id)->exists();

            if ($exists) {
                DB::table('admin_roles')->where('id', $id)->update(array_merge($role, [
                    'updated_at' => $now,
                ]));
            } else {
                DB::table('admin_roles')->insert(array_merge($role, [
                    'id' => $id,
                    'created_at' => $now,
                    'updated_at' => $now,
                ]));
            }
        }

        $this->command?->info('Admin roles seeded successfully.');
    }
}
