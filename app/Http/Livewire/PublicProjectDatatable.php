<?php

namespace App\Http\Livewire;

use App\Models\File;
use Rappasoft\LaravelLivewireTables\DataTableComponent;
use Rappasoft\LaravelLivewireTables\Views\Column;
use App\Models\Folder;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Storage;
use Rappasoft\LaravelLivewireTables\Views\Filters\SelectFilter;

class PublicProjectDatatable extends DataTableComponent
{
    protected $model = Folder::class;

    protected $listeners = ['removeProject'];

    public function configure(): void
    {
        $this->setPrimaryKey('id');
    }

    public function columns(): array
    {
        return [
            Column::make("Bil", "bil")
                ->sortable()->searchable(),
            Column::make("Projek", "project_name")
                ->sortable()->searchable(),
            Column::make("Pengguna")->label(fn ($row) => ucwords(strtolower($row->users()->implode("name", ", ")))),
            Column::make("Kluster", "cluster.name")->sortable()->searchable(),
            Column::make("Jenis Fail")->label(fn ($row) => $row->types()->implode("name", ", ")),
            Column::make("Tahun", "year")->sortable()->searchable(),
            Column::make("Tempoh Muatnaik", "status_date")->format(function ($value) {

                if ($value == Folder::ONTIME) {
                    $html = '<span class="badge badge-primary ms-2 fs-8 py-1 px-3">' . $value . '</span>';
                } elseif ($value == Folder::OVERDUE) {
                    $html = '<span class="badge badge-danger ms-2 fs-8 py-1 px-3">' . $value . '</span>';
                } else {
                    $html = '<span class="badge badge-secondary ms-2 fs-8 py-1 px-3">' . $value . '</span>';
                }

                return $html;
            })->html()->sortable()->searchable(),
            Column::make("Status", "status")->format(function ($value) {

                if ($value == Folder::COMPLETE) {
                    $html = '<span class="badge badge-primary ms-2 fs-8 py-1 px-3">' . $value . '</span>';
                } elseif ($value == Folder::INCOMPLETE) {
                    $html = '<span class="badge badge-danger ms-2 fs-8 py-1 px-3">' . $value . '</span>';
                }

                return $html;
            })->html()->sortable()->searchable(),
            Column::make("Action")->label(function ($row) {

                $html = "<div class='btn-group'>";
                if (auth()->user()->hasRole('admin')) {
                    $html .= "<a href='" . route('admin.projects.edit', ['folder' => $row]) . "' class='btn btn-success btn-icon btn-sm'><i class='fas fa-pencil'></i></a>";
                }
                $html .= "<a href='" . route('projects.public.show', ['folder' => $row]) . "' class='btn btn-primary btn-icon btn-sm'><i class='fas fa-eye'></i></a>";
                if (auth()->user()->hasRole('admin')) {
                    $html .= "<button class='btn btn-icon btn-danger btn-sm' data-bs-toggle='tooltip' data-bs-placement='top' title='Delete Project' wire:click='confirmDelete(" . $row->id . ")'><i class='fas fa-trash'></i></button>";
                }
                $html .= "</div>";
            
                return $html;
            })->html()            
        ];
    }

    public function builder(): Builder
    {
        return Folder::query()
            ->select('folders.*')
            ->with(['users', 'types', 'cluster'])
            ->whereHas('files', fn ($query) => $query->where('privacy', File::PUBLIC));
    }

    public function filters(): array
    {
        return [
            SelectFilter::make('Status')
                ->options([
                    '' => 'Any',
                    Folder::COMPLETE => Folder::COMPLETE,
                    Folder::INCOMPLETE => Folder::INCOMPLETE
                ])->filter(function (Builder $builder, string $value) {
                    $builder->where('folders.status', $value);
                }),
            SelectFilter::make('Tempoh Muatnaik')
                ->options([
                    '' => 'Any',
                    Folder::INPROGRESS => Folder::INPROGRESS,
                    Folder::ONTIME => Folder::ONTIME,
                    Folder::OVERDUE => Folder::OVERDUE
                ])->filter(function (Builder $builder, string $value) {
                    $builder->where('folders.status_date', $value);
                }),
        ];
    }

    public function confirmDelete($id)
    {
        $this->dispatchBrowserEvent('swal:confirm', [
            'type' => 'question',
            'title' => 'folder',
            'text' => 'Are you sure you want to delete this project ?',
            'id' => $id
        ]);
    }

    public function removeProject(Folder $folder)
    {
        $directory = Storage::disk('public')->exists($folder->project_name);

        if ($directory) {
            Storage::disk('public')->move($folder->project_name, 'bin/' . $folder->project_name);

            $folder->files()->delete();

            $folder->delete();

            $this->dispatchBrowserEvent('swal:modal', [
                'type' => 'success',
                'title' => 'Great!',
                'text' => 'Project Deleted!'
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
