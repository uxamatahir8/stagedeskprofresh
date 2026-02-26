<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     * Run base seeders first (roles, countries, states, timezones, settings), then FullSystemSeeder.
     */
    public function run(): void
    {
        $this->call([
            UserRolesSeeder::class,
            CountriesTableSeeder::class,
            StatesTableSeeder::class,
            addTimeZonesSeeder::class,
            CreateAdminSettingsSeeder::class,
            FullSystemSeeder::class,
        ]);
    }
}