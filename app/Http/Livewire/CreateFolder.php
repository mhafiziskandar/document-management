<?php

namespace App\Http\Livewire;

use App\Models\Folder;
use App\Models\FolderType;
use App\Models\User;
use Illuminate\Support\Facades\Storage;
use Livewire\Component;

class CreateFolder extends Component
{
    public $sub = false;
    public $folderId;

    public $project_name, $year, $name, $status = Folder::COMPLETE, $assign = 0, $checkboxes = [], $confirm = false, $bil;
    public array $locationUsers = [];

    protected $listeners = ['getFolderId', 'locationUsersSelected'];

    public function locationUsersSelected($locationUsersValues)
    {
        $this->locationUsers = $locationUsersValues;

        $this->locationUsers = array_values(array_filter($this->locationUsers));

        $this->dispatchBrowserEvent('livewire:load');
    }

    public function render()
    {
        $foldertypes = FolderType::all();
        $users = User::all();

        return view('livewire.create-folder', compact('foldertypes', 'users'));
    }

    public function updateRoot()
    {
        $this->sub = false;
        $this->project_name = null;
        $this->year = null;
        $this->name = null;
        $this->status = Folder::INCOMPLETE;
        $this->assign = 0;
        $this->folderId = null;
        $this->checkboxes = [];
    }

    public function updateSub()
    {
        $this->sub = true;
        $this->confirm = true;
        $this->project_name = null;
        $this->year = null;
        $this->name = null;
        $this->status = Folder::INCOMPLETE;
        $this->assign = 0;
        $this->checkboxes = [];
    }

    public function submit()
    {
        if (empty($this->folderId) || !isset($this->folderId) || is_null($this->folderId)) {
            $folder = Folder::create([
                'project_name' => $this->project_name,
                'year' => $this->year,
                'name' => $this->name,
                'status' => $this->status,
                'assign_toall' => $this->assign
            ]);

            $folder->users()->attach(array_values($this->locationUsers));

            $directories = Storage::disk('public')->exists($this->name);

            if (!$directories) {
                Storage::disk('public')->makeDirectory($this->name);
            }
        } else {

            if ($this->sub && $this->confirm) {
                $folder = Folder::create([
                    'parent_id' => $this->folderId,
                    'project_name' => $this->project_name,
                    'year' => $this->year,
                    'name' => $this->name,
                    'status' => $this->status,
                    'assign_toall' => $this->assign
                ]);

                $folder->users()->attach(array_values($this->locationUsers));

                $current_folder = $folder;
                $parent_folders = [];
                $parent_folder_path = null;

                while ($current_folder->parent()->exists()) {
                    $current_folder = $current_folder->parent()->first();
                    $parent_folders[] = $current_folder->name;
                }

                // Join the parent folder names with slashes
                $parent_folder_path = implode('/', array_reverse($parent_folders));

                $directories = Storage::disk('public')->exists(!is_null($parent_folder_path) ? $parent_folder_path : $this->folder->name);

                if ($directories) {
                    Storage::disk('public')->makeDirectory($parent_folder_path . "/" . $this->name);
                }
            } else {
                $folder = Folder::find($this->folderId);

                $current_folder = $folder;
                $parent_folders = [];
                $parent_folder_path = null;

                while ($current_folder->parent()->exists()) {
                    $current_folder = $current_folder->parent()->first();
                    $parent_folders[] = $current_folder->name;
                }

                // Join the parent folder names with slashes
                $parent_folder_path = implode('/', array_reverse($parent_folders));

                $directories = Storage::disk('public')->exists(!is_null($parent_folder_path) ? $parent_folder_path . "/" . $folder->name : $folder->name);

                if ($directories) {
                    Storage::disk('public')->deleteDirectory(!is_null($parent_folder_path) ? $parent_folder_path . "/" . $folder->name : $folder->name);
                    Storage::disk('public')->makeDirectory(!is_null($parent_folder_path) ? $parent_folder_path . "/" . $this->name : $this->name);
                }

                if ($folder) {
                    $folder->update([
                        'project_name' => $this->project_name,
                        'year' => $this->year,
                        'name' => $this->name,
                        'status' => $this->status,
                        'assign_toall' => $this->assign
                    ]);

                    $filterArray = array_filter($this->locationUsers, function ($value) {
                        return $value !== null;
                    });

                    $folder->users()->sync(array_values($filterArray));
                }
            }
        }

        $array = array_filter($this->checkboxes, function ($value) {
            return $value !== null;
        });

        $folder->types()->sync(array_values($array));

        $this->updateRoot();

        $this->dispatchBrowserEvent('updateJsTree');

        $this->dispatchBrowserEvent('swal:modal', [
            'type' => 'success',
            'title' => 'Great!',
            'text' => 'Folder Created!'
        ]);
    }

    public function delete()
    {
        $folder = Folder::find($this->folderId);

        $directories = Storage::disk('public')->exists($folder->name);

        if ($directories) {
            Storage::deleteDirectory($folder->name);
        }

        $folder->delete();

        $this->updateRoot();

        $this->dispatchBrowserEvent('updateJsTree');

        $this->dispatchBrowserEvent('swal:modal', [
            'type' => 'success',
            'title' => 'Great!',
            'text' => 'Folder Deleted!'
        ]);
    }

    public function updatedCheckboxes($value, $key)
    {
        $this->checkboxes[$key] = $value;
    }

    public function getFolderId($id)
    {
        $this->folderId = $id;
        $this->sub = true;
        $this->confirm = false;

        $folder = Folder::find($id);

        if ($folder->types()->exists()) {

            $foldertypes = FolderType::pluck('id')->toArray();
            $array2 = $folder->types()->pluck('folder_types.id')->toArray();

            $intersectValues = array_intersect($foldertypes, $array2);

            foreach ($foldertypes as $key => $type) {
                if (in_array($type, $intersectValues)) {
                    $this->checkboxes[$key] = $type;
                } else {
                    $this->checkboxes[$key] = null;
                }
            }
        } else {
            $this->checkboxes = [];
        }

        if ($folder->users()->exists()) {
            $this->locationUsers = $folder->users()->pluck('users.id')->toArray();
        } else {
            $this->locationUsers = [];
        }

        $this->dispatchBrowserEvent('livewire:load');

        $this->project_name = $folder->project_name;
        $this->bil = $folder->bil;
        $this->year = $folder->year;
        $this->name = $folder->name;
        $this->status = $folder->status;
        $this->assign = $folder->assign_toall;
    }
}
