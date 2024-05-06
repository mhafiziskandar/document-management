<?php

namespace App\Http\Livewire\Admin;

use App\Models\Cluster;
use App\Models\File;
use Rappasoft\LaravelLivewireTables\DataTableComponent;
use Rappasoft\LaravelLivewireTables\Views\Column;
use App\Models\Folder;
use App\Models\FolderType;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Rappasoft\LaravelLivewireTables\Views\Filters\MultiSelectDropdownFilter;
use Rappasoft\LaravelLivewireTables\Views\Filters\MultiSelectFilter;
use Rappasoft\LaravelLivewireTables\Views\Filters\SelectFilter;

class ProjectDatatable extends DataTableComponent
{
    protected $model = Folder::class;

    protected $index = 0;

    protected $listeners = ['removeProject', 'builder'];

    public function configure(): void
    {
        $this->setTableAttributes([
            'class' => 'table table-hover table-rounded gy-4 gx-4',
        ]);
        $this->setTheadAttributes([
            'class' => 'fw-bold fs-6',
        ]);
        $this->setPrimaryKey('id');
        $this->setDefaultSort('created_at', 'desc');
        $this->setFilterLayout('slide-down');
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
            Column::make("")
                ->label(function ($row) {
                    return Auth::user()->hasRole(["admin"]) && $row->files()->where("status", File::PENDING)->count()
                        ? '<i class="fas fa-bell me-2 text-danger"></i>'
                        : '';
                })
                ->html()
                ->sortable()
                ->searchable(),
            Column::make("Projek", "project_name")
                ->format(function ($value, $row) {
                    return $value;
                })
                ->html()
                ->sortable()
                ->searchable(),
            Column::make("Pengguna")
                ->label(function ($row) {
                    $users = $row->users()->pluck("name")->toArray();
                    $departments = $row->departments()->pluck("name")->toArray();

                    $allUsersAndDepartments = array_merge($users, $departments);

                    return ucwords(strtolower(implode(", ", $allUsersAndDepartments)));
                }),
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
                $html .= "<a href='" . route('admin.projects.show', ['folder' => $row]) . "' class='btn btn-primary btn-icon btn-sm'><i class='fas fa-eye'></i></a>";
                if (auth()->user()->hasRole('admin')) {
                    $html .= "<button class='btn btn-icon btn-danger btn-sm' data-bs-toggle='tooltip' data-bs-placement='top' title='Delete Project' wire:click='confirmDelete(" . $row->id . ")'><i class='fas fa-trash'></i></button>";
                }
                $html .= "</div>";

                return $html;
            })->html()
        ];
    }

    public function builder(array $test = null): Builder
    {
        return Folder::select('folders.*')
            ->with(['users', 'types', 'cluster'])
            ->when(!is_null($test), function ($query) use ($test) {
                $query->whereHas('users', function ($query) use ($test) {
                    $query->whereIn('users.id', $test);
                });
            });
    }

    public function change_country($test)
    {
        $this->emit('select2');
        //$this->emit('refreshDatatable');
        //dd($test);
    }

    public function filters(): array
    {
        $project = Folder::query();

        // Get the collection of created_at values
        $years = (clone $project)->pluck('year', 'year')->toArray();

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
            SelectFilter::make('Kluster')
                ->options([
                    '' => 'Any',
                ] + Cluster::pluck('name', 'id')->toArray())->filter(function (Builder $builder, string $value) {
                    $builder->where('folders.cluster_id', $value);
                }),
            SelectFilter::make('Tahun')
                ->options([
                    '' => 'Any',
                ] + $years)->filter(function (Builder $builder, string $value) {
                    $builder->where('folders.year', $value);
                }),
            SelectFilter::make('Semakan')
                ->options([
                    '' => 'All',
                    'Perlu disemak' => 'Perlu disemak'
                ])->filter(function (Builder $builder, string $value) {
                    if ($value === 'Perlu disemak') {
                        $builder->whereHas('files', function ($query) {
                            $query->where("status", File::PENDING);
                        });
                    }
                }),
            SelectFilter::make('Trackable Status')
                ->options([
                    '' => 'All',
                    'Trackable' => 'Trackable',
                    'Non-trackable' => 'Non-trackable'
                ])
                ->filter(function (Builder $builder, string $value) {
                    if ($value === 'Trackable') {
                        $builder->where('is_trackable', true);
                    } elseif ($value === 'Non-trackable') {
                        $builder->where('is_trackable', false);
                    }
                }),
            MultiSelectDropdownFilter::make('Pengguna')
                ->options(
                    User::query()
                        ->where('status', User::APPROVED)
                        ->orderBy('name')
                        ->get()
                        ->keyBy('id')
                        ->map(fn ($user) => $user->name)
                        ->toArray()
                )->filter(function (Builder $builder, array $value) {
                    $builder->whereHas('users', function ($query) use ($value) {
                        $query->whereIn('users.id', $value);
                    });
                }),
            MultiSelectFilter::make('Jenis Fail')
                ->options(
                    FolderType::query()
                        ->orderBy('name')
                        ->get()
                        ->keyBy('id')
                        ->map(fn ($type) => $type->name)
                        ->toArray()
                )->filter(function (Builder $builder, array $value) {
                    $builder->whereHas('types', function ($query) use ($value) {
                        $query->whereIn('folder_types.id', $value);
                    });
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

    protected function getTrackableBadge($isTrackable) {
        $badgeClass = $isTrackable ? 'badge-success' : 'badge-danger';
        $badgeText = $isTrackable ? 'Trackable' : 'Non-trackable';
        $style = 'font-size: 0.8rem; padding: 0.2em 0.4em;';
        
        return '<span class="badge ' . $badgeClass . '" style="' . $style . '">' . $badgeText . '</span>';
    }    
}
