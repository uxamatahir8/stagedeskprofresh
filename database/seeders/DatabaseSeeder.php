<?php

namespace Database\Seeders;

use App\Models\EventType;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

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
            CitiesTableSeeder::class,
            addTimeZonesSeeder::class,
            CreateAdminSettingsSeeder::class,
        ]);

        // Fix city-state mappings due to the extra Pakistan state shift in the database
        $this->command->info('Fixing city-state mappings...');
        Schema::disableForeignKeyConstraints();
        DB::table('cities')->where('state_id', '>=', 2728)->update(['state_id' => DB::raw('state_id + 1')]);
        Schema::enableForeignKeyConstraints();
        $this->command->info('City-state mappings corrected!');

        // Seed Event Types
        $this->seedEventTypes();
    }

    protected function seedEventTypes(): void
    {
        $types = ['Wedding', 'Birthday', 'Corporate', 'Party', 'Festival', 'Private', 'Other'];

        foreach ($types as $eventType) {
            $et = EventType::firstOrCreate(['event_type' => $eventType], ['event_type' => $eventType]);
        }
        $this->command->newLine();
    }
}
