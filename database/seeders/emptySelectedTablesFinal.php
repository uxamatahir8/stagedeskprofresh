<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Schema;

class emptySelectedTablesFinal extends Seeder
{
    public function run(): void
    {
        Schema::disableForeignKeyConstraints();

        $tables = [
            'affiliates',
            'affiliate_comissions',
            'artists',
            'artist_requests',
            'blogs',
            'blog_categories',
            'booked_services',
            'booking_requests',
            'comments',
            'companies',
            'company_subscriptions',
            'notifications',
            'packages',
            'package_features',
            'payments',
            'social_links',
            'testimonials',
            'users',
            'user_profiles'
        ];

        foreach ($tables as $table) {
            DB::table($table)->truncate();
            $this->command->info("Truncated: {$table}");
        }

        Schema::enableForeignKeyConstraints();

        // Create User
        User::create([
            'name' => 'StageDesk Pro',
            'role_id' => 1,
            'email' => 'info@stagedeskpro.com',
            'password' => Hash::make('password123'),
            'email_verified_at' => now(),
        ]);
    }
}
