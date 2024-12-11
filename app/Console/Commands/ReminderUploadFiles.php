<?php

namespace App\Console\Commands;

use App\Models\Folder;
use App\Notifications\RemindUserUploadFiles;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Notification;

class ReminderUploadFiles extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:reminder-upload-files';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $tomorrowDate = Carbon::now()->addDay()->format('y-m-d');
        $oneWeekBefore = Carbon::now()->subWeek()->format('Y-m-d');

        $projects = Folder::query()
            ->where('status', Folder::INCOMPLETE)
            ->whereBetween('tarikh_akhir', [$oneWeekBefore, $tomorrowDate])
            ->get();

        foreach($projects as $project)
        {
            Notification::send($project->users, new RemindUserUploadFiles($project));
        }
    }
}
