<?php

namespace App\Http\Livewire\Admin;

use Rappasoft\LaravelLivewireTables\DataTableComponent;
use Rappasoft\LaravelLivewireTables\Views\Column;
use App\Models\File;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Storage;

class BinFileDatatable extends DataTableComponent
{
    protected $model = File::class;

    protected $listeners = ['recoverFile', 'deleteFile'];

    public function configure(): void
    {
        $this->setPrimaryKey('id');
    }

    public function columns(): array
    {
        return [
            Column::make("Tarikh", "deleted_at")->format(fn ($value) => Carbon::parse($value)->setTimezone("Asia/Kuala_Lumpur")->format('j M Y, g:i a'))
                ->sortable(),
            Column::make("Projek", "folder.project_name")->sortable()->searchable(),
            Column::make("Name", "filename")->sortable()->searchable(),
            Column::make("Size", "size")
                ->sortable()->format(function ($value) {
                    if ($value < 1024) {
                        return $value . ' B';
                    } elseif ($value < 1048576) {
                        return round($value / 1024, 2) . ' KB';
                    } else {
                        return round($value / 1048576, 2) . ' MB';
                    }
                }),
            Column::make("Action")->label(function ($row) {
                $html = "<div class='btn-group'>";
                $html .= "<button class='btn btn-icon btn-primary btn-sm' data-bs-toggle='tooltip' data-bs-placement='top' title='Recover File' wire:click='confirmRecover(" . $row->id . ")'><i class='fa-sharp fa-solid fa-rotate-left'></i></button>";
                $html .= "<button class='btn btn-icon btn-danger btn-sm' data-bs-toggle='tooltip' data-bs-placement='top' title='Delete Project' wire:click='confirmDelete(" . $row->id . ")'><i class='fas fa-trash'></i></button>";
                $html .= "</div>";
                return $html;
            })->html()
        ];
    }

    public function builder(): Builder
    {
        return File::onlyTrashed()->select('files.*')->with(['folder']);
    }

    public function confirmRecover($id)
    {
        $this->dispatchBrowserEvent('swal:confirm', [
            'type' => 'question',
            'title' => 'bin',
            'text' => 'Are you sure you want to recover this file ?',
            'id' => $id
        ]);
    }

    public function confirmDelete($id)
    {
        $this->dispatchBrowserEvent('swal:confirm', [
            'type' => 'question',
            'title' => 'permenant_delete_file',
            'text' => 'Are you sure you want to permanently delete this file ?',
            'id' => $id
        ]);
    }

    public function deleteFile($id)
    {
        $file = File::withTrashed()->find($id);

        $directory = Storage::disk('public')->exists('bin/' . $file->path);

        if ($directory) {

            Storage::disk('public')->deleteDirectory('bin/' . $file->project_name);

            $file->forceDelete();

            $this->dispatchBrowserEvent('swal:modal', [
                'type' => 'success',
                'title' => 'Great!',
                'text' => 'File Permanently Deleted!'
            ]);
        } else {

            $this->dispatchBrowserEvent('swal:modal', [
                'type' => 'error',
                'title' => 'Oops!',
                'text' => 'File Does not exists!'
            ]);
        }
    }

    public function recoverFile($id)
    {
        $file = File::withTrashed()->find($id);

        $directory = Storage::disk('public')->exists('bin/' . $file->path);

        if ($directory) {

            $project = Storage::disk('public')->exists($file->folder->project_name);

            if ($project) {

                Storage::disk('public')->move('bin/' . $file->path, $file->path);

                Storage::disk('public')->deleteDirectory('bin/' . $file->folder->project_name);

                $file->restore();

                $this->dispatchBrowserEvent('swal:modal', [
                    'type' => 'success',
                    'title' => 'Great!',
                    'text' => 'File Restored!'
                ]);
            } else {

                $this->dispatchBrowserEvent('swal:modal', [
                    'type' => 'error',
                    'title' => 'Oops!',
                    'text' => 'Project Does not exists!'
                ]);
            }
        } else {

            $this->dispatchBrowserEvent('swal:modal', [
                'type' => 'error',
                'title' => 'Oops!',
                'text' => 'File Does not exists!'
            ]);
        }
    }
}
