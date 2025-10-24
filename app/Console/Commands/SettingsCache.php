<?php

namespace App\Console\Commands;

use App\Models\Settings;
use Illuminate\Console\Command;

class SettingsCache extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'settings:cache';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Cache Settings';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        //
        Settings::clearCache();
        Settings::loadAll();
        $this->info('Settings Cached');

        return Command::SUCCESS;
    }
}
