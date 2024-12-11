<?php

namespace App\Http\Livewire\Member;

use App\Models\Folder;
use Livewire\Component;

class EditProjectDescription extends Component
{
    public $folder, $description;

    public function mount(Folder $folder)
    {
        $this->folder = $folder;
        $this->description = $folder->description;
    }

    public function render()
    {
        return view('livewire.member.edit-project-description');
    }

    public function submit(Folder $folder)
    {
        $folder->update([
            'description' => $this->description
        ]);

        $this->emit('updateProjectDatatable');
    }
}
