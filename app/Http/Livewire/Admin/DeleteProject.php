<?php

namespace App\Http\Livewire\Admin;

use App\Models\Folder;
use Illuminate\Support\Facades\Storage;
use Livewire\Component;

class DeleteProject extends Component
{
    public Folder $folder;

    protected $listeners = ['folderDelete'];

    public function render()
    {
        return view('livewire.admin.delete-project');
    }

    public function confirmDelete($id)
    {
        $this->dispatchBrowserEvent('swal:confirm', [
            'type' => 'question',
            'title' => 'folderDelete',
            'text' => 'Are you sure you want to delete this project ?',
            'id' => $id
        ]);
    }

    public function folderDelete(Folder $folder)
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

            return redirect()->route('admin.projects.index');
        } else {

            $this->dispatchBrowserEvent('swal:modal', [
                'type' => 'error',
                'title' => 'Oops!',
                'text' => 'Project Does not exists!'
            ]);
        }
    }
}
