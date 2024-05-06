<?php

namespace App\Http\Livewire\Member;

use Rappasoft\LaravelLivewireTables\DataTableComponent;
use Rappasoft\LaravelLivewireTables\Views\Column;
use App\Models\Folder;
use App\Models\User;
use Illuminate\Database\Eloquent\Builder;
use Rappasoft\LaravelLivewireTables\Views\Filters\SelectFilter;

class ProjectDatatable extends DataTableComponent
{
    protected $model = Folder::class;

    protected $index = 0;

    protected $listeners = ['updateProjectDatatable'];

    public function configure(): void
    {
        $this->setPrimaryKey('id');
        $this->setDefaultSort('created_at', 'desc');
        $this->setTableAttributes([
            'class' => 'table table-hover table-rounded gy-4 gx-4',
        ]);
        $this->setTheadAttributes([
            'class' => 'fw-bold fs-6',
        ]);
    }

    public function columns(): array
    {
        return [
            Column::make("Bil.", "id")
                ->format(fn () => ++$this->index +  ($this->page - 1) * $this->perPage),
            Column::make("ID", "bil")
                ->format(function ($value, $row) {
                    return $value . $this->getTrackableBadge($row->is_trackable);
                })
                ->html()
                ->sortable()
                ->searchable(),
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
                $html .= "<button wire:click='edit(" . $row . ")' class='btn btn-success btn-icon btn-sm'><i class='fas fa-pencil'></i></button>";
                $html .= "<a href='" . route('member.projects.show', $row) . "' class='btn btn-primary btn-icon btn-sm'><i class='fas fa-eye'></i></a>";
                $html .= "</div>";

                return $html;
            })->html()
        ];
    }

    public function builder(): Builder
    {
        $user = auth()->user();
        $departmentId = $user->department_id;

        $userFolderIds = $user->folders->pluck('id');

        $departmentFolderIds = Folder::whereHas('departments', function (Builder $query) use ($departmentId) {
            $query->where('departments.id', $departmentId);
        })->pluck('id');

        $folderIds = $userFolderIds->merge($departmentFolderIds)->unique();

        return Folder::select('folders.*')
            ->with(['users', 'types', 'cluster'])
            ->whereIn('folders.id', $folderIds);
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

    public function edit($folder)
    {
        $this->dispatchBrowserEvent('open-x-modal', [
            'title' => 'Edit Project Description',
            'modal' => 'member.edit-project-description',
            'size' => 'lg',
            'args' => ['folder' => $folder]
        ]);
    }

    public function updateProjectDatatable()
    {
        $this->dispatchBrowserEvent('swal:modal', [
            'type' => 'success',
            'title' => 'Great!',
            'text' => 'Project Description Updated!'
        ]);

        $this->dispatchBrowserEvent('closeLivewireModal');
    }

    protected function getTrackableBadge($isTrackable)
    {
        $badgeClass = $isTrackable ? 'badge-success' : 'badge-danger';
        $badgeText = $isTrackable ? 'Trackable' : 'Non-trackable';
        $style = 'font-size: 0.8rem; padding: 0.2em 0.4em;';

        return '<span class="badge ' . $badgeClass . '" style="' . $style . '">' . $badgeText . '</span>';
    }
}