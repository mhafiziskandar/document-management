<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class TestCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:test';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Test command for supervisor';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        \Log::info('Test command has run at '.now());
    }
}
