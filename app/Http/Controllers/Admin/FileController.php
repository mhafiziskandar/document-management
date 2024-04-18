<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\File;
use App\Models\Folder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class FileController extends Controller
{
    public function index()
    {
        return view('file.index');
    }

    public function upload(Request $request, Folder $folder)
    {
        $files = $request->file('file');

        foreach ($files as $file) {
            $filename = $file->getClientOriginalName();
            $extension = $file->getClientOriginalExtension();
            $directory = Storage::disk('public')->exists($folder->name);

            if ($directory) {
                $path = $file->store($folder->name, 'public', $filename);

                $size = Storage::disk('public')->size($path);

                File::create([
                    'user_id' => auth()->user()->id,
                    'folder_id' => $folder->id,
                    'filename' => $filename,
                    'path' => $path,
                    'extension' => $extension,
                    'size' => $size
                ]);
            }
        }

        return response()->json(['success' => true]);
    }

    public function viewFile($path, $filename)
    {
        $file = Storage::disk('public')->get($path);
        $mime = Storage::disk('public')->mimeType($filename);

        return response($file, 200)->header('Content-Type', $mime);
    }
}
