<?php

namespace App\Http\Livewire;

use App\Models\Category;
use App\Models\File;
use App\Models\Folder;
use App\Models\FolderType;
use Livewire\Component;
use Livewire\WithFileUploads;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Storage;

class EditFile extends Component
{
    use WithFileUploads;

    public $file_id, $jenis, $folder_id, $updated_at;
    public $file, $upload_file, $url, $is_shareable, $type, $description, $privacy, $classification, $category = [], $can_download;

    protected $rules = [
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
    ];

    public function mount()
    {
        $this->file = File::find($this->file_id)->load('categories');
        $this->folder_id = $this->file->folder_id;
        $this->is_shareable = $this->file->is_shareable;
        $this->type = $this->file->folder_type_id;
        $this->jenis = $this->file->type->type;
        $this->description = $this->file->description;
        $this->privacy = $this->file->privacy;
        $this->classification = $this->file->classification;
        $this->can_download = $this->file->can_download;
        $this->category = $this->file->categories->pluck('id')->toArray();
        $this->updated_at = $this->file->updated_at->format('Y-m-d H:i:s');
        $this->url = $this->file->url;
    }

    public function render()
    {
        $folderTypes = FolderType::all();
        $categories = Category::all();

        return view('livewire.edit-file', compact('folderTypes', 'categories'));
    }

    public function submit($file_id)
    {
        $this->validate([
            'upload_file' =>  ['sometimes', 'max:12288'],
            'url' => ['sometimes'],
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
            if (!empty($this->upload_file)) {
                $filename = $this->upload_file->getClientOriginalName();
                $extension = $this->upload_file->getClientOriginalExtension();

                $folder = Folder::find($this->folder_id);

                $directory = Storage::disk('public')->exists($folder->project_name);

                if ($directory) {
                    $path = $this->upload_file->storeAs(
                        ($folder->project_name),
                        $filename,
                        'public'
                    );

                    $size = Storage::disk('public')->size($path);
                } else {
                    $this->dispatchBrowserEvent('swal:modal', [
                        'type' => 'error',
                        'title' => 'Oops!',
                        'text' => 'It seems the folder that you are going to upload doesnt exist in the server!'
                    ]);
                }
            }

            $this->emit('editFileDatatable', [
                'file_id' => $this->file_id,
                'path' => $path ?? $this->file->path,
                'size' => $size ?? $this->file->size,
                'description' => $this->description ?? $this->file->description,
                'folder_id' => $this->folder_id,
                'folder_type_id' => $this->type,
                'filename' => $filename ?? $this->file->filename,
                'extension' => $extension ?? $this->file->extension,
                'can_download' => $this->can_download,
                'classification' => $this->classification,
                'is_shareable' => $this->is_shareable,
                'privacy' => $this->privacy,
                'category' => $this->category,
                'status' => FILE::PENDING,
                'updated_at' => $this->updated_at
            ]);
            
        } else {
            $this->emit('editFileDatatable', [
                'file_id' => $this->file_id,
                'url' => $this->url,
                'description' => $this->description,
                'folder_id' => $this->folder_id,
                'folder_type_id' => $this->type,
                'can_download' => $this->can_download,
                'classification' => $this->classification,
                'is_shareable' => $this->is_shareable,
                'privacy' => $this->privacy,
                'category' => $this->category,
                'status' => FILE::PENDING,
                'updated_at' => $this->updated_at
            ]);
        }

        $this->clear();
    }

    public function draft($file_id)
    {
        $this->validate([
            'upload_file' =>  ['sometimes', 'max:12288'],
            'url' => ['sometimes'],
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
            if (!empty($this->upload_file)) {
                $filename = $this->upload_file->getClientOriginalName();
                $extension = $this->upload_file->getClientOriginalExtension();

                $folder = Folder::find($this->folder_id);

                $directory = Storage::disk('public')->exists($folder->project_name);

                if ($directory) {
                    $path = $this->upload_file->storeAs(
                        ($folder->project_name),
                        $filename,
                        'public'
                    );

                    $size = Storage::disk('public')->size($path);
                } else {
                    $this->dispatchBrowserEvent('swal:modal', [
                        'type' => 'error',
                        'title' => 'Oops!',
                        'text' => 'It seems the folder that you are going to upload doesnt exist in the server!'
                    ]);
                }
            }

            $this->emit('editFileDatatable', [
                'file_id' => $this->file_id,
                'path' => $path ?? $this->file->path,
                'size' => $size ?? $this->file->size,
                'description' => $this->description ?? $this->file->description,
                'folder_id' => $this->folder_id,
                'folder_type_id' => $this->type,
                'filename' => $filename ?? $this->file->filename,
                'extension' => $extension ?? $this->file->extension,
                'can_download' => $this->can_download,
                'classification' => $this->classification,
                'is_shareable' => $this->is_shareable,
                'privacy' => $this->privacy,
                'category' => $this->category,
                'status' => FILE::DRAFT
            ]);
        } else {
            $this->emit('editFileDatatable', [
                'file_id' => $this->file_id,
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

        $this->clear();
    }

    public function clear()
    {
        $this->reset(['type', 'description', 'is_shareable', 'privacy', 'classification', 'can_download', 'category', 'jenis', 'upload_file']);
    }

    public function updateAttributes()
    {
        $folderType = FolderType::find($this->type);

        $this->jenis = $folderType->type;
    }
}
