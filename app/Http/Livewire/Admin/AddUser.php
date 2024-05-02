<?php

namespace App\Http\Livewire\Admin;

use Livewire\Component;
use App\Models\User;
use Spatie\Permission\Models\Role;

class AddUser extends Component
{
    public $isVisible = false;
    public $name, $email, $ic_no, $password, $department, $role;

    public $departments = ['IT', 'HR', 'Finance', 'Marketing'];
    public $roles = ['Admin', 'Member'];

    public function openModal()
    {
        $this->isVisible = true;
    }

    public function closeModal()
    {
        $this->isVisible = false;
    }

    public function saveUser()
    {
        // dd($this->name, $this->email);

        $this->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'ic_no' => 'required|string|max:255',
            'department' => 'required|string',
            'role' => 'required|string',
        ]);

        try {
            $user = new User([
                'name' => $this->name,
                'email' => $this->email,
                'ic_no' => $this->ic_no,
                'department' => $this->department,
            ]);
            $user->password = bcrypt($this->ic_no);
            $user->save();

            if (!empty($this->role)) {
                $user->syncRoles([$this->role]);
            }

            $this->closeModal();
        } catch (\Exception $e) {
            session()->flash('error', 'Failed to create user: ' . $e->getMessage());
            return;
        }
    }

    public function render()
    {
        return view('livewire.admin.add-user');
    }
}