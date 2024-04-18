<?php

namespace App\Http\Livewire\Admin;

use App\Models\File;
use App\Models\Folder;
use Carbon\Carbon;
use Rappasoft\LaravelLivewireTables\DataTableComponent;
use Rappasoft\LaravelLivewireTables\Views\Column;
use Spatie\Activitylog\Models\Activity;
use Illuminate\Database\Eloquent\Builder;

class ActivityLogDatatable extends DataTableComponent
{
    protected $model = Activity::class;

    public $folder_id, $folder;

    public function mount()
    {
        $this->folder = Folder::find($this->folder_id);
    }

    public function configure(): void
    {
        $this->setPrimaryKey('id');
        $this->setDefaultSort('activity_log.created_at', 'desc');
    }

    public function columns(): array
    {
        return [
            Column::make("Id"),
            Column::make("Tarikh", "created_at")->format(fn ($value) => Carbon::parse($value)->setTimezone("Asia/Kuala_Lumpur")->format('j M Y, g:i a'))
                ->sortable(),
            Column::make("Aktiviti", "description")->format(function ($value, $row) {

                $html = "";

                if ($row->subject_type == File::class) {

                    if ($row->event == 'updated') {
                        if (isset($row->properties['old']['status'])) {
                            if ($row->properties['attributes']['status'] == File::APPROVED) {
                                $html = $value . "diterima " . $row->subject->filename;
                            } else if ($row->properties['attributes']['status'] == File::REJECTED) {
                                $html = $value . "ditolak " . $row->subject->filename;
                            } else {
                                $html = $value . "dikemaskini " . $row->subject->filename;
                            }
                        } else {
                            $html = $value . "dikemaskini " . $row->subject->filename;
                        }
                    } else {
                        $html = $value . " " . $row?->subject?->filename;
                    }

                    return $html;
                } else {
                    return $value;
                }
            })->html()->sortable(),
            Column::make("Pengguna")->label(fn ($row) => $row->causer?->name)
        ];
    }

    public function builder(): Builder
    {
        return Activity::query()
            ->select('activity_log.*')
            ->with('causer')
            ->where(function ($query) {
                $query->where('subject_type', Folder::class)->where('subject_id', $this->folder_id);
            })
            ->orWhere(function ($query) {
                $query->where('subject_type', File::class)->whereIn('subject_id', $this->folder->fileWithTrashed->pluck('id')->toArray());
            });
    }
}
