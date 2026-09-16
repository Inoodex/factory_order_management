<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $roles = [
            ['slug' => 'super-admin', 'name' => 'Super Admin', 'is_active' => 1],
            ['slug' => 'admin', 'name' => 'Administrator', 'is_active' => 1],
            ['slug' => 'accountant', 'name' => 'Accountant', 'is_active' => 1],
            ['slug' => 'staff', 'name' => 'Merchandiser Staff', 'is_active' => 1],
        ];

        foreach ($roles as $role) {
            DB::table('roles')->updateOrInsert(
                ['slug' => $role['slug']],
                [
                    'name' => $role['name'],
                    'is_active' => $role['is_active'],
                    'created_at' => now(),
                    'updated_at' => now(),
                ]
            );
        }
    }
}
