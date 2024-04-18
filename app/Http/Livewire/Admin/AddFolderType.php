<?php

namespace App\Http\Livewire\Admin;

use Livewire\Component;

class AddFolderType extends Component
{
    public $name, $type = "fail";

    public function render()
    {
        return view('livewire.admin.add-folder-type');
    }

    public function submit()
    {
        $this->validate([
            'name' => 'required|string|min:3',
            'type' => 'required'
        ]);

        $this->emit('storeFolderType', [
            'name' => $this->name,
            'type' => $this->type
        ]);
    }
}
