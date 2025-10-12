<?php

namespace Database\Seeders;

use App\Models\Role;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
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
            ['name' => 'Company Admin',  'role_key' => 'company_admin', 'description' => 'Company Admin'],
            ['name' => 'Customer',  'role_key' => 'customer', 'description' => 'Customer'],
            ['name' => 'Artist',  'role_key' => 'artist', 'description' => 'Artist'],
            ['name' => 'Affiliate',  'role_key' => 'affiliate', 'description' => 'Affiliate'],

        ];

        foreach ($roles_data as $role) {
            Role::create($role);
        }
    }
}