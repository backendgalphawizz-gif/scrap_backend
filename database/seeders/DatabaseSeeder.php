<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $hasUsersPersonalClient = DB::table('oauth_clients')
            ->where('provider', 'users')
            ->where('grant_types', 'like', '%personal_access%')
            ->exists();

        if (!$hasUsersPersonalClient) {
            Artisan::call('passport:client', [
                '--personal' => true,
                '--name' => 'Default Personal Access Client',
                '--provider' => 'users',
            ]);
        }

        $this->call(RoleSeeder::class);
        $this->call(PermissionSeeder::class);
        $this->call(RolePermissionSeeder::class);
        $this->call(AdminSeeder::class);
        $this->call(BrandCategorySeeder::class);
    }
}
