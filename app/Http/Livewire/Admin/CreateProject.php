<?php

namespace App\Http\Livewire\Admin;

use Livewire\Component;
use App\Models\Department;
use App\Models\User;
use App\Models\FolderType;
use App\Models\Cluster;
use App\Models\Folder;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Notification;
use App\Notifications\UserAssign;
use RealRashid\SweetAlert\Facades\Alert;

class CreateProject extends Component
{
    public $projectName, $description, $year, $isTrackable, $kluster, $selectedUsers = [], $selectedFolderTypes = [], $endDate, $selectedDepartments = [];

    public $departments, $users, $foldertypes, $categories;

    public function mount()
    {
        $this->departments = Department::all();
        $this->categories = Cluster::all();
        $this->foldertypes = FolderType::all();
        $this->users = collect();
    }

    public function updatedSelectedDepartments()
    {
        $this->users = User::whereIn('department_id', $this->selectedDepartments)
            ->whereNot('status', User::REJECT)
            ->get();
    }

    public function saveProject()
    {
        // Define validation rules within the saveProject method
        $validatedData = $this->validate([
            'projectName' => 'required|string|min:5|unique:folders,project_name',
            'description' => 'required|string|min:10',
            'year' => 'required|numeric',
            'isTrackable' => 'required|in:'.Folder::YA.','.Folder::TIDAK,
            // 'kluster' => 'required',
            'selectedDepartments' => 'required|array',
            'selectedUsers' => 'nullable|array',
            'selectedUsers.*' => [
                'integer',
                Rule::exists('users', 'id'),
            ],
            // 'selectedFolderTypes' => 'required|array',
            'selectedFolderTypes.*' => [
                'integer',
                Rule::exists('folder_types', 'id'),
            ],
            'endDate' => 'required|date',
        ]);

        // Create the project
        $project = Folder::create([
            'cluster_id' => $this->kluster,
            'project_name' => $this->projectName,
            'description' => $this->description,
            'year' => $this->year,
            'status' => Folder::INCOMPLETE,
            'status_date' => Folder::INPROGRESS,
            'tarikh_akhir' => $this->endDate,
            'is_trackable' => $this->isTrackable,
        ]);

        // Ensure the folder directory exists
        if (!Storage::disk('public')->exists($this->projectName)) {
            Storage::disk('public')->makeDirectory($this->projectName);
        }

        // Attach folder types
        $project->types()->attach($this->selectedFolderTypes);

        // Assign to departments or specific users
        if (empty($this->selectedUsers)) {
            // If no specific users are selected, assign to all departments
            $project->departments()->attach($this->selectedDepartments);

            $departmentUsers = User::whereIn('department_id', $this->selectedDepartments)
                ->whereNot('status', User::REJECT)
                ->get();

            foreach ($departmentUsers as $user) {
                activity()
                    ->causedBy(auth()->id())
                    ->performedOn($project)
                    ->event('assign user')
                    ->log($user->name . ' has been assigned to this project ' . $project->project_name);
            }

            Notification::send($departmentUsers, new UserAssign($project));
        } else {
            // Assign to specific users
            $project->users()->attach($this->selectedUsers);

            $users = User::whereIn('id', $this->selectedUsers)->get();

            foreach ($users as $user) {
                activity()
                    ->causedBy(auth()->id())
                    ->performedOn($project)
                    ->event('assign user')
                    ->log($user->name . ' has been assigned to this project ' . $project->project_name);
            }

            Notification::send($users, new UserAssign($project));
        }

        Alert::success('Berjaya', 'Projek berjaya ditambah!');
        return redirect()->route('admin.projects.index');
    }

    public function render()
    {
        return view('livewire.admin.create-project', [
            'departments' => Department::all(),
            'categories' => Cluster::all(),
            'foldertypes' => FolderType::all(),
            'users' => $this->users,
        ]);
    }
}