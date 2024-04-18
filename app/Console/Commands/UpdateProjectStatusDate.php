<?php

namespace App\Console\Commands;

use App\Models\Folder;
use Carbon\Carbon;
use Illuminate\Console\Command;

class UpdateProjectStatusDate extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:update-project-status-date';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'To check daily if current date is more than project tarikh_akhir';

    /**
     * Execute the console command.
     */
    public function handle(): void
    {
        $projects = Folder::where('is_trackable', 'true')->get();

        $currentDate = Carbon::now()->setTimezone("Asia/Kuala_Lumpur");

        foreach ($projects as $project) {
            if ($currentDate->format('Y-m-d') > Carbon::parse($project->tarikh_akhir)->setTimezone("Asia/Kuala_Lumpur")) {
                if($project->status == Folder::INCOMPLETE)
                {
                    $project->update(['status_date' => Folder::OVERDUE]);
                }
            }
        }
    }
}
