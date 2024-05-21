<?php

namespace App\Http\Controllers\Member;

use App\Http\Controllers\Controller;
use App\Models\File;
use App\Models\Folder;
use Illuminate\Http\Request;

class FolderController extends Controller
{
    public function index()
    {
        return view('member.project.index');
    }

    public function show(Folder $folder)
    {
        $folder->load(['cluster', 'users', 'departments', 'types', 'files']);

        $entityType = $folder->users->isNotEmpty() ? 'users' : 'departments';
        $entities = $folder->users->isNotEmpty() ? $folder->users : $folder->departments;

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

        return view('member.project.show', compact('folder', 'progress'));
    }
}
