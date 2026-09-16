<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $admin = User::firstOrCreate(
            ['email' => 'hello@inoodex.com'],
            [
                'name' => 'Admin',
                'username' => 'admin',
                'designation' => 'System Administrator',
                'password' => Hash::make('hello@inoodex.com'),
                'plain_password' => 'hello@inoodex.com',
                'email_verified_at' => now(),
            ]
        );

        // Assign all core roles to default admin user
        $roles = DB::table('roles')->whereIn('slug', ['super-admin', 'admin', 'accountant', 'staff'])->get();

        foreach ($roles as $role) {
            DB::table('user_roles')->updateOrInsert([
                'user_id' => $admin->id,
                'role_id' => $role->id,
            ]);
        }
    }
}
