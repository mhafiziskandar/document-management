<?php

namespace App\Http\Livewire\Admin;

use App\Models\FolderType;
use Livewire\Component;

class EditFolderType extends Component
{
    public $folderType, $name, $type;

    public function mount(FolderType $folderType)
    {
        $this->folderType = $folderType;
        $this->type = $folderType->type;
        $this->name = $folderType->name;
    }

    public function render()
    {
        return view('livewire.admin.edit-folder-type');
    }

    public function submit()
    {
        $this->validate([
            'name' => 'required|min:3'
        ]);
        
        $this->folderType->update([
            'name' => $this->name,
            'type' => $this->type
        ]);

        $this->emit('updateDocTypeDatatable');
    }
}
