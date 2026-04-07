<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Role;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        Role::insert([
            ['name' => 'admin'],
            ['name' => 'sub_admin'],
            ['name' => 'surveyor'],
            ['name' => 'supervisor'],
            ['name' => 'technician'],
            ['name' => 'vehicle_manager'],
            ['name' => 'inventory_manager'],
        ]);
    }
}
