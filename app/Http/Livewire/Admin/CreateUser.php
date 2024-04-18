<?php

namespace App\Http\Livewire\Admin;

use Illuminate\Support\Facades\Hash;
use Livewire\Component;
use Spatie\Permission\Models\Role;

class CreateUser extends Component
{
    public $name;
    public $email;
    public $role = "member";
    public $password;
    public $password_confirmation;

    public function render()
    {
        $roles = Role::all();

        return view('livewire.admin.create-user', compact('roles'));
    }

    public function empty()
    {
        $this->name = null;
        $this->email = null;
        $this->role = null;
        $this->password = null;
        $this->password_confirmation = null;
    }

    public function submit()
    {
        $this->validate([
            'name' => 'required',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:8|confirmed',
        ]);

        $this->emit('storeUser', [
            'name' => $this->name,
            'email' => $this->email,
            'password' => Hash::make($this->password),
            'role' => $this->role
        ]);

        $this->empty();

        $this->dispatchBrowserEvent('closeModal');
    }
}
