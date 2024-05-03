<?php

namespace App\Http\Livewire\Admin;

use App\Models\Department;
use Livewire\Component;
use App\Models\User;
use Spatie\Permission\Models\Role;

class AddUser extends Component
{
    public $isVisible = false;
    public $name, $email, $ic_no, $password, $department_id, $role;

    public $departments;
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
            'department_id' => 'required|exists:departments,id',
            'role' => 'required|string',
        ]);

        try {
            $user = new User([
                'name' => $this->name,
                'email' => $this->email,
                'ic_no' => $this->ic_no,
                'department_id' => $this->department_id,
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

    public function mount()
    {
        $this->departments = Department::pluck('name', 'id');
    }

    public function render()
    {
        return view('livewire.admin.add-user');
    }
}