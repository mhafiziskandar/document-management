<?php

namespace App\Http\Controllers;

use App\Models\Folder;
use Illuminate\Http\Request;

class ProjectController extends Controller
{
    public function index()
    {
        return view('project.public');
    }

    public function show(Folder $folder)
    {
        $previousUrl = url()->previous();

        // If the previous URL was the login page, set a default fallback URL.
        if ($previousUrl == route('login')) {
            $previousUrl = route('projects.public');
        }
    
        // Store the previous URL (or the fallback) in the session.
        session(['previous_url' => $previousUrl]);

        $folder->load('users');
        return view('project.show_public', compact('folder'));
    }
}
