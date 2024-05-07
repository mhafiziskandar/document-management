<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Cluster;
use App\Models\Department;
use App\Models\File;
use App\Models\Folder;
use App\Models\Folderables;
use App\Models\FolderType;
use App\Models\User;
use App\Notifications\UserAssign;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
use RealRashid\SweetAlert\Facades\Alert;
use Carbon\Carbon;

class FolderController extends Controller
{
    public function index()
    {
        return view('project.index');
    }

    public function create()
    {
        // $foldertypes = FolderType::all();
        // $users = User::whereNot("status", User::REJECT)->get();
        // $departments = Department::all();
        // $categories = Cluster::all();

        return view('project.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'project_name' => 'required|string|min:5|unique:folders',
            'description' => 'required|string|min:10',
            'year' => 'required|numeric',
            'is_trackable' => 'required',
            'kluster' => 'required',
            'users' => 'required|array',
            'users.*' => [
                'integer',
                Rule::exists('users', 'id'),
            ],
            'folder_types' => 'required|array',
            'folder_types.*' => [
                'integer',
                Rule::exists('folder_types', 'id'),
            ],
            'date' => 'required|date'
        ]);

        $project = Folder::create([
            'cluster_id' => $request->kluster,
            'project_name' => $request->project_name,
            'description' => $request->description,
            'year' => $request->year,
            'status' => Folder::INCOMPLETE,
            'status_date' => Folder::INPROGRESS,
            'tarikh_akhir' => $request->date,
            'is_trackable' => $request->is_trackable,
        ]);

        $directories = Storage::disk('public')->exists($request->project_name);

        if (!$directories) {
            Storage::disk('public')->makeDirectory($request->project_name);
        }

        $project->types()->attach($request->folder_types);
        $project->users()->attach($request->users);

        $users = User::whereIn('id', $request->users)->get();

        foreach ($users as $user) {
            activity()
                ->causedBy(auth()->id())
                ->performedOn($project)
                ->event('assign user')
                ->log($user->name . ' has been assigned to this project ' . $project->project_name);
        }

        Notification::send($users, new UserAssign($project));

        Alert::success('Berjaya', 'Projek berjaya ditambah!');

        return redirect()->route('admin.projects.index');
    }

    public function edit(Folder $folder)
    {
        $previousUrl = url()->previous();

        if ($previousUrl == route('login')) {
            $previousUrl = route('admin.projects.index');
        }

        session(['previous_url' => $previousUrl]);

        return view('project.edit', compact('folder'));
    }

    public function update(Request $request, Folder $folder)
    {
        $oldProjectName = $folder->project_name;

        $request->validate([
            'project_name' => 'required|string|min:5|unique:folders,project_name,' . $folder->id,
            'description' => 'required|string|min:10',
            'year' => 'required|numeric',
            'is_trackable' => 'required',
            'kluster' => 'required',
            'users' => 'required|array',
            'users.*' => [
                'integer',
                Rule::exists('users', 'id'),
            ],
            'folder_types' => 'required|array',
            'folder_types.*' => [
                'integer',
                Rule::exists('folder_types', 'id'),
            ],
            'date' => 'required|date'
        ]);

        if ($oldProjectName != $request->project_name) {
            $doesNewDirectoryExist = Storage::disk('public')->exists($request->project_name);
            if ($doesNewDirectoryExist) {
                return back()->withErrors(['project_name' => 'A directory with this name already exists.']);
            } else {
                // Rename in 'public'
                Storage::disk('public')->move($oldProjectName, $request->project_name);
                
                // Update file paths in the database
                $filesToUpdate = File::where('folder_id', $folder->id)->get();
                foreach ($filesToUpdate as $file) {
                    $newPath = str_replace($oldProjectName, $request->project_name, $file->path);
                    $file->update(['path' => $newPath]);
                }
                
                // Check if it exists in 'bin' and rename
                if (Storage::disk('public')->exists('bin/' . $oldProjectName)) {
                    Storage::disk('public')->move('bin/' . $oldProjectName, 'bin/' . $request->project_name);
                }
            }
        }
        
        $status = $folder->status;

        $currentDate = Carbon::now();

        // Conditions for setting the status_date
        if ($status == Folder::INCOMPLETE) {
            $status_date = ($currentDate->lte(Carbon::createFromFormat('Y-m-d', $request->date)->setTimezone("Asia/Kuala_Lumpur"))) 
                ? Folder::INPROGRESS : Folder::OVERDUE;
        } elseif ($status == Folder::COMPLETE) {
            $status_date = ($currentDate->lte(Carbon::createFromFormat('Y-m-d', $request->date)->setTimezone("Asia/Kuala_Lumpur"))) 
                ? Folder::ONTIME : Folder::OVERDUE;
        } else {
            // Handle any unexpected cases here
            $status_date = Folder::INPROGRESS; 
        }        

        $folder->update([
            'project_name' => $request->project_name,
            'cluster_id' => $request->kluster,
            'description' => $request->description,
            'bil' =>  (string) $folder->id . "/" . $request->year,
            'year' => $request->year,
            'tarikh_akhir' => $request->date,
            'is_trackable' => $request->is_trackable,
            'status_date' => $status_date
        ]);        

        $folder->types()->sync($request->folder_types);

        $folder->folders()->sync($request->departments);
        
        $usr = $folder->users;

        $users = User::whereIn('id', $request->users)->get();

        // Get the non-matching users
        $newUserAdd = $users->diff($usr);

        $userRemoved = $usr->diff($users);

        foreach ($newUserAdd as $data) {
            activity()
                ->causedBy(auth()->id())
                ->performedOn($folder)
                ->event('assign user')
                ->log($data->name . ' has been assigned to this project ' . $folder->project_name);
        }

        foreach ($userRemoved as $data) {
            activity()
                ->causedBy(auth()->id())
                ->performedOn($folder)
                ->event('assign user')
                ->log($data->name . ' has been removed from this project ' . $folder->project_name);
        }

        $folder->users()->sync($request->users);

        Alert::success('Berjaya', 'Projek berjaya dikemas kini!');

        // return redirect()->route('admin.projects.index', ['page' => $request->input('page', 1)]);

        return redirect(session('previous_url', default: route('admin.projects.index', ['page' => $request->input('page', 1)])));
    }

    public function show(Folder $folder)
    {
        $previousUrl = url()->previous();

        // If the previous URL was the login page, set a default fallback URL.
        if ($previousUrl == route('login')) {
            $previousUrl = route('admin.projects.index');
        }
    
        // Store the previous URL (or the fallback) in the session.
        session(['previous_url' => $previousUrl]);

        $folder->load('cluster', 'users', 'types', 'files');

        $countType = $folder->types->count();

        $count = 0;
        foreach ($folder->types as $type) {
            $check = File::where('status', File::APPROVED)->where('folder_id', $folder->id)->where('folder_type_id', $type->id)->first();

            if ($check) {
                $count++;
            }
        }

        if ($count > 0) {
            $progress = $count / $countType * 100;
        } else {
            $progress = 0;
        }

        return view('project.show', compact('folder', 'progress'));
    }
}
