<?php

namespace App\Http\Livewire\Admin;

use Rappasoft\LaravelLivewireTables\DataTableComponent;
use Rappasoft\LaravelLivewireTables\Views\Column;
use App\Models\User;
use App\Notifications\UserApproved;
use Illuminate\Database\Eloquent\Builder;
use Rappasoft\LaravelLivewireTables\Views\Filters\SelectFilter;
use Spatie\Permission\Models\Role;

class UserDatatable extends DataTableComponent
{
    protected $model = User::class;

    protected $listeners = ['storeUser', 'updateTable', 'updateUserDatatable', 'userAdded' => '$refresh'];

    public array $bulkActions = [
        'approved' => 'Terima',
        'reject' => 'Tolak',
        'unactive' => 'Tidak Aktif'
    ];

    public function configure(): void
    {
        $this->setPrimaryKey('id');
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
            // Column::make("Id", "id")
            //     ->sortable(),
            Column::make("Name", "name")->format(function ($value) {
                return ucwords(strtolower($value));
            })
                ->sortable()->searchable(),
            Column::make("Kad Pengenalan", "ic_no")
                ->sortable()->searchable(),
            Column::make("Emel", "email")
                ->sortable()->searchable(),
            Column::make("Peranan")->label(function ($row) {
                return $row->roles->implode('name', ', ');
            }),
            // Column::make("Tarikh Sync", "sync")
            //     ->sortable()->searchable(),
            Column::make("Status", "status")->format(function ($value) {

                if ($value == User::APPROVED) {
                    $html = '<span class="badge badge-primary ms-2 fs-8 py-1 px-3">' . $value . '</span>';
                } elseif ($value == User::REJECT) {
                    $html = '<span class="badge badge-warning ms-2 fs-8 py-1 px-3">' . $value . '</span>';
                } elseif ($value == User::PENDING) {
                    $html = '<span class="badge badge-secondary ms-2 fs-8 py-1 px-3">' . $value . '</span>';
                } elseif ($value == User::DELETE) {
                    $html = '<span class="badge badge-danger ms-2 fs-8 py-1 px-3">' . $value . '</span>';
                } else {
                    $html = null;
                }

                return $html;
            })->html()->sortable()->searchable(),
            Column::make('Action')->label(function ($row) {

                $html = "<button class='btn btn-secondary btn-sm dropdown-toggle' type='button' data-bs-toggle='dropdown' aria-expanded='false'>More</button>";
                $html .= "<ul class='dropdown-menu'>";
                $html .= "<li><button class='dropdown-item' wire:click='approve(" . $row . ")'>Terima</button></li>";
                $html .= "<li><button class='dropdown-item' wire:click='rejects(" . $row . ")'>Tolak</button></li>";
                $html .= "<li><button class='dropdown-item' wire:click='unactive(" . $row . ")'>Tidak Aktif</button></li>";
                $html .= "<li><button class='dropdown-item'wire:click='edit(" . $row . ")'>Edit</a>";
                $html .= "</ul>";
                $html .= "</div>";

                return $html;
            })->html(),
        ];
    }

    public function builder(): Builder
    {
        return User::select('users.*')->with(['roles']);
    }

    public function filters(): array
    {
        return [
            SelectFilter::make('Status')
                ->options([
                    '' => 'Any',
                    User::APPROVED => User::APPROVED,
                    User::REJECT => User::REJECT,
                    User::DELETE => User::DELETE,
                    User::PENDING => User::PENDING
                ])->filter(function (Builder $builder, string $value) {
                    $builder->where('users.status', $value);
                }),
            SelectFilter::make('Role')
                ->options(['' => 'Any'] + Role::pluck('name', 'name')->toArray())
                ->filter(function (Builder $builder, string $value) {
                    $builder->role($value);
                }),
        ];
    }

    public function edit($user)
    {
        $this->dispatchBrowserEvent('open-x-modal', [
            'title' => 'Edit User',
            'modal' => 'admin.edit-user',
            'args' => ['user' => $user]
        ]);
    }

    public function approve(User $user)
    {
        $user->update(['status' => User::APPROVED]);

        $user->notify(new UserApproved());

        $this->dispatchBrowserEvent('swal:modal', [
            'type' => 'success',
            'title' => 'Great!',
            'text' => 'User Approved!'
        ]);
    }

    public function rejects(User $user)
    {
        $user->update(['status' => User::REJECT]);
        $user->folders()->detach();

        $this->dispatchBrowserEvent('swal:modal', [
            'type' => 'success',
            'title' => 'Great!',
            'text' => 'User Rejected!'
        ]);
    }

    public function unactive(User $user)
    {
        $user->update(['status' => User::DELETE]);
        $user->folders()->detach();

        $this->dispatchBrowserEvent('swal:modal', [
            'type' => 'success',
            'title' => 'Great!',
            'text' => 'Tidak Aktif!'
        ]);
    }

    public function approved()
    {
        foreach ($this->getSelected() as $item) {
            $user = User::find($item);

            $user->update(['status' => User::APPROVED]);

            $user->notify(new UserApproved());
        }

        $this->dispatchBrowserEvent('swal:modal', [
            'type' => 'success',
            'title' => 'Great!',
            'text' => 'User Approved!'
        ]);

        $this->clearSelected();
    }

    public function reject()
    {
        foreach ($this->getSelected() as $item) {
            User::find($item)->update(['status' => User::REJECT]);
            User::find($item)->folders()->detach();
        }

        $this->dispatchBrowserEvent('swal:modal', [
            'type' => 'success',
            'title' => 'Great!',
            'text' => 'User Rejected!'
        ]);

        $this->clearSelected();
    }

    public function storeUser($data)
    {
        $user = User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => $data['password']
        ]);

        $user->assignRole($data['role']);

        $this->dispatchBrowserEvent('swal:modal', [
            'type' => 'success',
            'title' => 'Great!',
            'text' => 'User Created!'
        ]);
    }

    public function updateTable()
    {
        $this->dispatchBrowserEvent('swal:modal', [
            'type' => 'success',
            'title' => 'Great!',
            'text' => 'User Sync!'
        ]);
    }

    public function updateUserDatatable()
    {
        $this->dispatchBrowserEvent('swal:modal', [
            'type' => 'success',
            'title' => 'Great!',
            'text' => 'User Updated!'
        ]);

        $this->dispatchBrowserEvent('closeLivewireModal');
    }
}
