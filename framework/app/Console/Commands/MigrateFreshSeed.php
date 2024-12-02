<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class MigrateFreshSeed extends Command
{
    protected $signature = 'migrate:fresh-seed';
    protected $description = 'Fresh migrate and seed the database';

    public function handle()
    {
        $this->call('migrate:fresh', [
            '--seed' => true,
        ]);
    }
}
