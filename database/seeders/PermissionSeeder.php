<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;

class PermissionSeeder extends Seeder
{
    public function run()
    {
        // Get all permissions from config
        $roles = Config::get('permissions.roles');
        $allPermissions = [];

        // Collect all unique permissions
        foreach ($roles as $rolePermissions) {
            $allPermissions = array_merge($allPermissions, $rolePermissions);
        }
        $allPermissions = array_unique($allPermissions);

        // Insert permissions into database
        foreach ($allPermissions as $permission) {
            DB::table('permissions')->insertOrIgnore([
                'name' => $permission,
                'guard_name' => 'web',
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        $this->command->info('Permissions seeded successfully!');
    }
}