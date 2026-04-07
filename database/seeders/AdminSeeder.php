<?php

namespace Database\Seeders;

use App\Models\Admin;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        Admin::updateOrCreate(
            ['email' => 'admin@gmail.com'],
            [
                'name' => 'Admin',
                'password' => Hash::make('Admin@123'),
                'admin_role_id' => 1, // make sure you have role column
                'phone' => '1234567890',
                'email' => 'admin@gmail.com',
                'status' => 1,
            ]
        );
    }
}
