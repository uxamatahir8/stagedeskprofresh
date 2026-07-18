<?php

namespace Database\Seeders;

use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Seeder;

class UserRolesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $roles_data = [
            ['name' => 'Master Admin', 'role_key' => 'master_admin', 'description' => 'Master Admin'],
            ['name' => 'Company Admin', 'role_key' => 'company_admin', 'description' => 'Company Admin'],
            ['name' => 'Customer', 'role_key' => 'customer', 'description' => 'Customer'],
            ['name' => 'Artist', 'role_key' => 'artist', 'description' => 'Artist'],
            ['name' => 'Affiliate', 'role_key' => 'affiliate', 'description' => 'Affiliate'],
        ];

        foreach ($roles_data as $role) {
            Role::firstOrCreate($role);
        }

        // create master admin user if not exists
        $this->command->info('Creating Master Admin user...');
        $masterAdmin = User::firstOrCreate(
            ['email' => 'info@stagedeskpro.com'],
            [
                'role_id' => $roles_data[0]['id'] ?? 1,
                'company_id' => null,
                'name' => 'StageDesk Pro Admin',
                'password' => 'password123',
                'status' => 'active',
                'email_verified_at' => now(),
            ]
        );

        if ($masterAdmin->wasRecentlyCreated) {
            $this->command->info('Master Admin user created successfully.');
        } else {
            $this->command->info('Master Admin user already exists.');
        }
    }
}
