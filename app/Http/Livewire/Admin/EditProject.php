<?php

namespace App\Http\Livewire\Admin;

use App\Models\File;
use App\Models\Folder;
use App\Models\Category;
use App\Models\Department;
use App\Models\User;
use App\Models\FolderType;
use Illuminate\Validation\Rule;
use Livewire\Component;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Carbon;

class EditProject extends Component
{
    public $folder;
    public $projectName;
    public $description;
    public $year;
    public $kluster;
    public $selectedDepartments = [];
    public $selectedUsers = [];
    public $isTrackable;
    public $selectedFolderTypes = [];
    public $endDate;
    public $previousUrl;
    public $hasUsers = false;

    public function mount(Folder $folder)
    {
        $this->folder = $folder;
        $this->projectName = $folder->project_name;
        $this->description = $folder->description;
        $this->year = $folder->year;
        // $this->kluster = $folder->cluster->id;
        $this->selectedDepartments = $folder->departments->pluck('id')->toArray();
        $this->selectedUsers = $folder->users->pluck('id')->toArray();
        $this->isTrackable = $folder->is_trackable;
        $this->selectedFolderTypes = $folder->types->pluck('id')->toArray();
        $this->endDate = Carbon::createFromFormat('d/m/Y', $folder->tarikh_akhir)->format('Y-m-d');
        $this->previousUrl = session('previous_url', route('admin.projects.index'));

        // Determine if this project involves users
        $this->hasUsers = $folder->users->isNotEmpty();
    }

    public function saveProject()
    {
        $oldProjectName = $this->folder->project_name;

        // Validation Rules
        $validationRules = [
            'projectName' => [
                'required',
                'string',
                'min:5',
                Rule::unique('folders', 'project_name')->ignore($this->folder->id),
            ],
            'description' => 'required|string|min:10',
            'year' => 'required|integer',
            'isTrackable' => 'required|in:' . Folder::YA . ',' . Folder::TIDAK,
            'kluster' => 'required|exists:categories,id',
            'selectedDepartments' => 'sometimes|required|array',
            'selectedFolderTypes' => [
                'required',
                'array',
                Rule::exists('folder_types', 'id'),
            ],
            'endDate' => 'required|date',
        ];

        if ($this->hasUsers) {
            $validationRules['selectedUsers'] = 'required|array';
        }

        // Validate Input
        $validatedData = $this->validate($validationRules);

        // Format the end date
        $formattedEndDate = Carbon::createFromFormat('Y-m-d', $validatedData['endDate'])->format('Y-m-d');

        // Rename the project directory if necessary
        if ($oldProjectName != $validatedData['projectName']) {
            $doesNewDirectoryExist = Storage::disk('public')->exists($validatedData['projectName']);
            if ($doesNewDirectoryExist) {
                $this->addError('projectName', 'A directory with this name already exists.');
                return;
            } else {
                // Rename in 'public'
                Storage::disk('public')->move($oldProjectName, $validatedData['projectName']);

                // Update file paths in the database
                $filesToUpdate = File::where('folder_id', $this->folder->id)->get();
                foreach ($filesToUpdate as $file) {
                    $newPath = str_replace($oldProjectName, $validatedData['projectName'], $file->path);
                    $file->update(['path' => $newPath]);
                }

                // Check if it exists in 'bin' and rename
                if (Storage::disk('public')->exists('bin/' . $oldProjectName)) {
                    Storage::disk('public')->move('bin/' . $oldProjectName, 'bin/' . $validatedData['projectName']);
                }
            }
        }

        // Determine the status date
        $status = $this->folder->status;
        $currentDate = Carbon::now();
        if ($status == Folder::INCOMPLETE) {
            $status_date = ($currentDate->lte(Carbon::createFromFormat('Y-m-d', $formattedEndDate)))
                ? Folder::INPROGRESS : Folder::OVERDUE;
        } elseif ($status == Folder::COMPLETE) {
            $status_date = ($currentDate->lte(Carbon::createFromFormat('Y-m-d', $formattedEndDate)))
                ? Folder::ONTIME : Folder::OVERDUE;
        } else {
            $status_date = Folder::INPROGRESS;
        }

        // Update Folder
        $this->folder->update([
            'project_name' => $validatedData['projectName'],
            'cluster_id' => $validatedData['kluster'],
            'description' => $validatedData['description'],
            'bil' => (string)$this->folder->id . "/" . $validatedData['year'],
            'year' => $validatedData['year'],
            'tarikh_akhir' => $formattedEndDate,
            'is_trackable' => $validatedData['isTrackable'],
            'status_date' => $status_date,
        ]);

        // Sync Folder Types
        $this->folder->types()->sync($validatedData['selectedFolderTypes']);

        // Sync Departments without duplication
        $existingDepartments = $this->folder->folders()
            ->wherePivot('folderable_type', Department::class)
            ->pluck('folderable_id')
            ->toArray();

        $toAttachDepartments = array_diff($validatedData['selectedDepartments'], $existingDepartments);
        $toDetachDepartments = array_diff($existingDepartments, $validatedData['selectedDepartments']);

        // Detach old departments
        $this->folder->folders()
            ->wherePivot('folderable_type', Department::class)
            ->detach($toDetachDepartments);

        // Attach new departments
        $departmentSyncData = [];
        foreach ($toAttachDepartments as $departmentId) {
            $departmentSyncData[$departmentId] = ['folderable_type' => Department::class];
        }
        $this->folder->folders()->syncWithoutDetaching($departmentSyncData);

        // Sync Users if required, without duplication
        if ($this->hasUsers && isset($validatedData['selectedUsers'])) {
            $existingUsers = $this->folder->folders()
                ->wherePivot('folderable_type', User::class)
                ->pluck('folderable_id')
                ->toArray();

            $toAttachUsers = array_diff($validatedData['selectedUsers'], $existingUsers);
            $toDetachUsers = array_diff($existingUsers, $validatedData['selectedUsers']);

            // Detach old users
            $this->folder->folders()
                ->wherePivot('folderable_type', User::class)
                ->detach($toDetachUsers);

            // Attach new users
            $userSyncData = [];
            foreach ($toAttachUsers as $userId) {
                $userSyncData[$userId] = ['folderable_type' => User::class];
            }
            $this->folder->folders()->syncWithoutDetaching($userSyncData);

            // Logging activities for added and removed users
            foreach (User::whereIn('id', $toAttachUsers)->get() as $data) {
                activity()
                    ->causedBy(auth()->id())
                    ->performedOn($this->folder)
                    ->event('assign user')
                    ->log($data->name . ' has been assigned to this project ' . $this->folder->project_name);
            }

            foreach (User::whereIn('id', $toDetachUsers)->get() as $data) {
                activity()
                    ->causedBy(auth()->id())
                    ->performedOn($this->folder)
                    ->event('remove user')
                    ->log($data->name . ' has been removed from this project ' . $this->folder->project_name);
            }
        }

        session()->flash('message', 'Projek berjaya dikemas kini!');
        return redirect($this->previousUrl);
    }


    public function render()
    {
        return view('livewire.admin.edit-project', [
            'categories' => Category::all(),
            'departments' => Department::all(),
            'users' => User::whereNot("status", User::REJECT)->get(),
            'foldertypes' => FolderType::all(),
        ]);
    }
}