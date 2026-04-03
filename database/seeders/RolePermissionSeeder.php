<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Role;
use App\Models\Permission;

class RolePermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        $adminRole = Role::where('name', 'admin')->first();

        if (!$adminRole) {
            return;
        }

        // Get permissions by name
        $permissions = Permission::whereIn('name', [
            'create_user',
            'delete_user',
            'view_users',
            'create_role',
            'assign_role',
        ])->pluck('id')->toArray();

        // Attach permissions to admin role
        $adminRole->permissions()->sync($permissions);
        

    }
}
