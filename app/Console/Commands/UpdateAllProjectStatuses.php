<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\File;
use App\Models\Folder;
use Carbon\Carbon;

class UpdateAllProjectStatuses extends Command
{
    protected $signature = 'update:project-statuses';
    protected $description = 'Update the statuses of projects that are incomplete';

    public function handle()
    {
        // Fetch folders with the status of Folder::INCOMPLETE
        // $folders = Folder::where('status', Folder::INCOMPLETE)->get();

        $folders = Folder::all();

        foreach ($folders as $folder) {
            
            $currentDate = Carbon::now()->setTimezone("Asia/Kuala_Lumpur");

            $countType = $folder->types->count();
            $count = 0;

            foreach ($folder->types as $type) {
                $check = File::where('status', File::APPROVED)
                            ->where('folder_id', $folder->id)
                            ->where('folder_type_id', $type->id)
                            ->first();

                if ($check) {
                    $count++;
                }
            }

            $progress = ($countType > 0) ? ($count / $countType * 100) : 0;
            $result = round($progress, 2);

            $status = ($result >= 100) ? Folder::COMPLETE : Folder::INCOMPLETE;

            // Conditions for setting the status_date
            if ($status == Folder::INCOMPLETE) {
                $status_date = ($currentDate->lte(Carbon::createFromFormat('d/m/Y', $folder->tarikh_akhir)->setTimezone("Asia/Kuala_Lumpur"))) 
                                ? Folder::INPROGRESS : Folder::OVERDUE;
            } elseif ($status == Folder::COMPLETE) {
                $status_date = ($currentDate->lte(Carbon::createFromFormat('d/m/Y', $folder->tarikh_akhir)->setTimezone("Asia/Kuala_Lumpur"))) 
                                ? Folder::ONTIME : Folder::OVERDUE;
            } else {
                // Handle any unexpected cases here
                $status_date = Folder::INPROGRESS; 
            }

            $folder->update([
                'status' => $status,
                'status_date' => $status_date
            ]);
        }

        $this->info('Project statuses updated successfully!');
    }
}
