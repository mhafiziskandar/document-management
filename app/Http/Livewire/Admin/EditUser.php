<?php

namespace App\Http\Livewire\Admin;

use App\Models\User;
use Livewire\Component;
use Spatie\Permission\Models\Role;

class EditUser extends Component
{
    public $user, $role;

    public function mount(User $user)
    {
        $this->user = $user;
        $this->role = $user->roles->first()->name;
    }

    public function render()
    {
        $roles = Role::all();

        return view('livewire.admin.edit-user', compact('roles'));
    }

    public function submit()
    {
        $this->user->syncRoles($this->role);

        $this->emit('updateUserDatatable');
    }
}
