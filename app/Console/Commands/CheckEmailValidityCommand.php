<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Validator;

class CheckEmailValidityCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:check-email-validity-command';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Check Users Email Validity Command';

    /**
     * Execute the console command.
     */
    public function handle(): void
    {
        $users = User::all();

        foreach ($users as $user) {
            $user->checkEmailValidity();
        }
    }
}
