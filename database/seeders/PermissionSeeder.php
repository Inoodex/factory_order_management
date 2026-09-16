<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 1. Define Privileges / Permissions
        $privileges = [
            ['slug' => '*', 'name' => 'Wildcard Full Access'],
            ['slug' => '*accountant', 'name' => 'Accountant Access'],
        ];

        foreach ($privileges as $privilege) {
            DB::table('privileges')->updateOrInsert(
                ['slug' => $privilege['slug']],
                [
                    'name' => $privilege['name'],
                    'created_at' => now(),
                    'updated_at' => now(),
                ]
            );
        }

        // 2. Fetch IDs
        $adminRoleId = DB::table('roles')->where('slug', 'admin')->value('id');
        $superAdminRoleId = DB::table('roles')->where('slug', 'super-admin')->value('id');
        $accountantRoleId = DB::table('roles')->where('slug', 'accountant')->value('id');

        $wildcardId = DB::table('privileges')->where('slug', '*')->value('id');
        $accId = DB::table('privileges')->where('slug', '*accountant')->value('id');

        // 3. Attach Privileges to Roles
        if ($wildcardId) {
            if ($adminRoleId) {
                DB::table('privilege_role')->updateOrInsert(
                    ['privilege_id' => $wildcardId, 'role_id' => $adminRoleId],
                    ['created_at' => now(), 'updated_at' => now()]
                );
            }
            if ($superAdminRoleId) {
                DB::table('privilege_role')->updateOrInsert(
                    ['privilege_id' => $wildcardId, 'role_id' => $superAdminRoleId],
                    ['created_at' => now(), 'updated_at' => now()]
                );
            }
        }

        if ($accId && $accountantRoleId) {
            DB::table('privilege_role')->updateOrInsert(
                ['privilege_id' => $accId, 'role_id' => $accountantRoleId],
                ['created_at' => now(), 'updated_at' => now()]
            );
        }
    }
}
