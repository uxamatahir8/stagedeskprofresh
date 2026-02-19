<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class FreshDataWithDefaultUserSeeder extends Seeder
{
    /**
     * Tables to preserve (never truncate).
     */
    protected array $preserveTables = [
        'cities',
        'countries',
        'migrations',
        'states',
        'timezones',
        'roles',
        'settings',
    ];

    /**
     * Run the database seeds.
     * Empties all tables except preserved ones, then creates default admin user.
     */
    public function run(): void
    {
        $connection = DB::connection();
        $driver = $connection->getDriverName();

        if ($driver !== 'mysql') {
            $this->command->warn('This seeder is written for MySQL. Skipping truncate.');
            $this->createDefaultUser();
            return;
        }

        $tables = $this->getAllTableNames();
        $tablesToTruncate = array_diff($tables, $this->preserveTables);

        if (empty($tablesToTruncate)) {
            $this->command->info('No tables to truncate.');
            $this->createDefaultUser();
            return;
        }

        $this->command->info('Disabling foreign key checks and truncating tables...');
        DB::statement('SET FOREIGN_KEY_CHECKS=0');

        foreach ($tablesToTruncate as $table) {
            if (!Schema::hasTable($table)) {
                continue;
            }
            try {
                DB::table($table)->truncate();
                $this->command->getOutput()->write('.');
            } catch (\Throwable $e) {
                $this->command->warn(" Could not truncate [{$table}]: " . $e->getMessage());
            }
        }

        DB::statement('SET FOREIGN_KEY_CHECKS=1');
        $this->command->newLine();
        $this->command->info('Tables truncated.');

        $this->createDefaultUser();
    }

    /**
     * Get all table names from the database.
     *
     * @return array<int, string>
     */
    protected function getAllTableNames(): array
    {
        $databaseName = DB::getDatabaseName();
        $key = 'Tables_in_' . $databaseName;
        $results = DB::select('SHOW TABLES');

        return array_map(function ($row) use ($key) {
            return $row->{$key};
        }, $results);
    }

    /**
     * Create the default user (role_id=1, activated).
     */
    protected function createDefaultUser(): void
    {
        $email = 'info@stagedeskpro.com';

        User::withoutEvents(function () use ($email) {
            User::withTrashed()->where('email', $email)->forceDelete();
        });

        User::create([
            'role_id'   => 1,
            'company_id' => null,
            'name'      => 'StageDesk Pro Admin',
            'email'     => $email,
            'password'  => 'password123',
            'status'    => 'active',
            'email_verified_at' => now(),
        ]);

        $this->command->info('Default user created: ' . $email . ' (role_id=1, password=password123)');
    }
}
