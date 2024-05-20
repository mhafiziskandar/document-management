<?php

namespace App\Http\Livewire;

use App\Models\Category;
use App\Models\File;
use App\Models\Folder;
use App\Models\FolderType;
use App\Rules\FileValidationRule;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
use Livewire\Component;
use Livewire\WithFileUploads;

class UploadFile extends Component
{
    use WithFileUploads;

    public $folder_id, $folderTypes, $jenis;
    public $file, $url, $is_shareable = null, $type, $description, $privacy = null, $classification = File::PRIMARY, $category = [], $can_download = null;

    public function mount()
    {
        $folder = Folder::find($this->folder_id);

        $this->folderTypes = FolderType::all();

        $this->type = $folder->types->first()->id;

        $this->jenis = $folder->types->first()->type;
    }

    public function render()
    {
        $categories = Category::all();

        return view('livewire.upload-file', compact('categories'));
    }

    public function updateAttributes()
    {
        $folderType = FolderType::find($this->type);

        $this->jenis = $folderType->type;
    }

    public function empty()
    {
        $folder = Folder::find($this->folder_id);
        
        $this->file = null;
        $this->type = $folder->types->first()->id;
        $this->description = null;
        $this->is_shareable = null;
        $this->privacy = null;
        $this->classification = File::PRIMARY ;
        $this->category = [];
        $this->can_download = null;
        $this->url = null;
    }

    public function submit()
    {
        $this->validate([
            'file' =>  [Rule::requiredIf($this->jenis == FolderType::FILE), 'max:51200'],
            'url' => [Rule::requiredIf($this->jenis == FolderType::URL)],
            'is_shareable' => 'required',
            'type' => 'required',
            'description' => 'required|min:5|string',
            'privacy' => 'required',
            'can_download' => 'required',
            // 'classification' => 'required',
            // 'category' => 'required|array',
            'category.*' => [
                'integer'
            ],
        ]);

        if ($this->jenis == FolderType::FILE) {
            $filename = $this->file->getClientOriginalName();
            $extension = $this->file->getClientOriginalExtension();

            $folder = Folder::find($this->folder_id);

            $directory = Storage::disk('public')->exists($folder->project_name);

            if ($directory) {
                $path = $this->file->storeAs(
                    ($folder->project_name),
                    $filename,
                    'public'
                );

                $size = Storage::disk('public')->size($path);

                $this->emit('updateFileDatatable', [
                    'path' => $path,
                    'size' => $size,
                    'description' => $this->description,
                    'folder_id' => $this->folder_id,
                    'folder_type_id' => $this->type,
                    'filename' => $filename,
                    'extension' => $extension,
                    'can_download' => $this->can_download,
                    'classification' => $this->classification,
                    'is_shareable' => $this->is_shareable,
                    'privacy' => $this->privacy,
                    'category' => $this->category,
                    'status' => FILE::PENDING
                ]);
            } else {
                $this->dispatchBrowserEvent('swal:modal', [
                    'type' => 'error',
                    'title' => 'Oops!',
                    'text' => 'It seems the folder that you are going to upload doesnt exist in the server!'
                ]);
            }
        } else {
            $this->emit('updateFileDatatable', [
                'url' => $this->url,
                'description' => $this->description,
                'folder_id' => $this->folder_id,
                'folder_type_id' => $this->type,
                'can_download' => $this->can_download,
                'classification' => $this->classification,
                'is_shareable' => $this->is_shareable,
                'privacy' => $this->privacy,
                'category' => $this->category,
                'status' => FILE::PENDING
            ]);
        }

        $this->empty();
    }

    public function draf()
    {
        $this->validate([
            'file' =>  [Rule::requiredIf($this->jenis == FolderType::FILE), 'max:51200'],
            'url' => [Rule::requiredIf($this->jenis == FolderType::URL)],
            'is_shareable' => 'required',
            'type' => 'required',
            'description' => 'required|min:5|string',
            'privacy' => 'required',
            'can_download' => 'required',
            'classification' => 'required',
            'category' => 'required|array',
            'category.*' => [
                'integer'
            ],
        ]);

        if ($this->jenis == FolderType::FILE) {
            $filename = $this->file->getClientOriginalName();
            $extension = $this->file->getClientOriginalExtension();

            $folder = Folder::find($this->folder_id);

            $directory = Storage::disk('public')->exists($folder->project_name);

            if ($directory) {
                $path = $this->file->storeAs(
                    ($folder->project_name),
                    $filename,
                    'public'
                );

                $size = Storage::disk('public')->size($path);

                $this->emit('updateFileDatatable', [
                    'path' => $path,
                    'size' => $size,
                    'description' => $this->description,
                    'folder_id' => $this->folder_id,
                    'folder_type_id' => $this->type,
                    'filename' => $filename,
                    'extension' => $extension,
                    'can_download' => $this->can_download,
                    'classification' => $this->classification,
                    'is_shareable' => $this->is_shareable,
                    'privacy' => $this->privacy,
                    'category' => $this->category,
                    'status' => FILE::DRAFT
                ]);
            } else {
                $this->dispatchBrowserEvent('swal:modal', [
                    'type' => 'error',
                    'title' => 'Oops!',
                    'text' => 'It seems the folder that you are going to upload doesnt exist in the server!'
                ]);
            }
        } else {
            $this->emit('updateFileDatatable', [
                'url' => $this->url,
                'description' => $this->description,
                'folder_id' => $this->folder_id,
                'folder_type_id' => $this->type,
                'can_download' => $this->can_download,
                'classification' => $this->classification,
                'is_shareable' => $this->is_shareable,
                'privacy' => $this->privacy,
                'category' => $this->category,
                'status' => FILE::DRAFT
            ]);
        }

        $this->empty();
    }
}
