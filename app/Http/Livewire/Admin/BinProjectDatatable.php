<?php

namespace App\Http\Livewire\Admin;

use Rappasoft\LaravelLivewireTables\DataTableComponent;
use Rappasoft\LaravelLivewireTables\Views\Column;
use App\Models\Folder;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Storage;

class BinProjectDatatable extends DataTableComponent
{
    protected $model = Folder::class;

    protected $listeners = ['recoverProject', 'deleteProject'];

    public function configure(): void
    {
        $this->setPrimaryKey('id');
    }

    public function columns(): array
    {
        return [
            Column::make("Tarikh", "deleted_at")->format(fn ($value) => Carbon::parse($value)->setTimezone("Asia/Kuala_Lumpur")->format('j M Y, g:i a'))
                ->sortable(),
            Column::make("Projek", "project_name")->sortable()->searchable(),
            Column::make("Nombor Fail")->label(fn ($row) => $row->fileTrashed->count() . ' Fail')->html(),
            Column::make("Action")->label(function ($row) {
                $html = "<div class='btn-group'>";
                $html .= "<button class='btn btn-icon btn-primary btn-sm' data-bs-toggle='tooltip' data-bs-placement='top' title='Recover Project' wire:click='confirmRecover(" . $row->id . ")'><i class='fa-sharp fa-solid fa-rotate-left'></i></button>";
                $html .= "<button class='btn btn-icon btn-danger btn-sm' data-bs-toggle='tooltip' data-bs-placement='top' title='Delete Project' wire:click='confirmDelete(" . $row->id . ")'><i class='fas fa-trash'></i></button>";
                $html .= "</div>";
                return $html;
            })->html()
        ];
    }

    public function builder(): Builder
    {
        return Folder::onlyTrashed()->select('folders.*')->with('fileTrashed');
    }

    public function confirmRecover($id)
    {
        $this->dispatchBrowserEvent('swal:confirm', [
            'type' => 'question',
            'title' => 'project',
            'text' => 'Are you sure you want to recover this project ?',
            'id' => $id
        ]);
    }

    public function confirmDelete($id)
    {
        $this->dispatchBrowserEvent('swal:confirm', [
            'type' => 'question',
            'title' => 'permenant_delete_project',
            'text' => 'Are you sure you want to permanently delete this project ?',
            'id' => $id
        ]);
    }

    public function deleteProject($id)
    {
        $folder = Folder::withTrashed()->find($id);

        $directory = Storage::disk('public')->exists('bin/' . $folder->project_name);

        if ($directory) {

            Storage::disk('public')->deleteDirectory('bin/' . $folder->project_name);

            $folder->files()->withTrashed()->forceDelete();

            $folder->forceDelete();

            $this->dispatchBrowserEvent('swal:modal', [
                'type' => 'success',
                'title' => 'Great!',
                'text' => 'Project Permanently Deleted!'
            ]);
        } else {

            $this->dispatchBrowserEvent('swal:modal', [
                'type' => 'error',
                'title' => 'Oops!',
                'text' => 'Project Does not exists!'
            ]);
        }
    }

    public function recoverProject($id)
    {
        $folder = Folder::withTrashed()->find($id);

        $directory = Storage::disk('public')->exists('bin/' . $folder->project_name);

        if ($directory) {

            Storage::disk('public')->move('bin/' . $folder->project_name, $folder->project_name);

            Storage::disk('public')->deleteDirectory('bin/' . $folder->project_name);

            $folder->restore();

            $folder->files()->withTrashed()->restore();

            $this->dispatchBrowserEvent('swal:modal', [
                'type' => 'success',
                'title' => 'Great!',
                'text' => 'Project Restored!'
            ]);
        } else {

            $this->dispatchBrowserEvent('swal:modal', [
                'type' => 'error',
                'title' => 'Oops!',
                'text' => 'Project Does not exists!'
            ]);
        }
    }
}
