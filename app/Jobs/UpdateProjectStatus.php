<?php

namespace App\Jobs;

use App\Models\File;
use App\Models\Folder;
use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class UpdateProjectStatus implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $folder;

    /**
     * Create a new job instance.
     */
    public function __construct($folder)
    {
        $this->folder = $folder;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $folder = Folder::find($this->folder);
        $folder->load('types');

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

        // Update the folder.
        $folder->update([
            'status' => $status,
            'status_date' => $status_date
        ]);
    }
}