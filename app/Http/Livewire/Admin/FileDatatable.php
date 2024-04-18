<?php

namespace App\Http\Livewire\Admin;

use App\Jobs\UpdateProjectStatus;
use Rappasoft\LaravelLivewireTables\DataTableComponent;
use Rappasoft\LaravelLivewireTables\Views\Column;
use App\Models\File;
use App\Models\User;
use App\Notifications\FileApproved;
use App\Notifications\FileRejected;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Storage;

class FileDatatable extends DataTableComponent
{
    protected $model = File::class;

    protected $listeners = ['updateFileDatatable', 'removeFile', 'rejectFile', 'editFileDatatable', 'acceptFile', 'draftFile'];

    public $folder_id;

    public array $bulkActions = [
        'approvedSelected' => 'Terima',
        //'rejectSelected' => 'Tolak'
    ];

    public function configure(): void
    {
        $this->setTableAttributes([
            'class' => 'table table-hover table-rounded gy-4 gx-4',
        ]);
        $this->setTheadAttributes([
            'class' => 'fw-bold fs-6',
        ]);
        $this->setPrimaryKey('id');
        $this->setDefaultSort('updated_at', 'desc');
    }

    public function columns(): array
    {
        return [
            Column::make("Name", "filename")->format(function ($value, $row) {
                $html = "";
                if (is_null($row->url)) {
                    $html .= "<span>" . $row->filename . "</span><br>";
                } else {
                    $html .= "<span>" .  $row->url . "</span><br>";
                }

                if ($row->privacy == FILE::PUBLIC) {
                    $html .= "<span class='badge badge-primary'>" . $row->privacy . "</span>&nbsp";
                } elseif ($row->privacy == FILE::PRIVATE) {
                    $html .= "<span class='badge badge-danger'>" . $row->privacy . "</span>&nbsp";
                }

                if ($row->is_shareable == FILE::YES) {
                    $html .= "<span class='badge badge-primary'>Boleh Dikongsi</span>";
                } elseif ($row->is_shareable == FILE::NO) {
                    $html .= "<span class='badge badge-danger'>Tidak Boleh Dikongsi</span>";
                }

                return $html;
            })->html()->sortable()->searchable(),
            Column::make("Size", "size")
                ->sortable()->format(function ($value) {
                    if (!is_null($value)) {
                        if ($value < 1024) {
                            return $value . ' B';
                        } elseif ($value < 1048576) {
                            return round($value / 1024, 2) . ' KB';
                        } else {
                            return round($value / 1048576, 2) . ' MB';
                        }
                    }
                })
                ->deselected(),
            Column::make("Jenis Fail", "type.name")->sortable(),
            Column::make("Penerangan", "description")->sortable()->searchable(),
            Column::make("Klasifikasi", "classification")->sortable()->searchable(),
            Column::make("Kategori")->label(fn ($row) => $row->categories->count() . ' Kategori')->html()->deselected(),
            Column::make("Reason", "reason")->sortable()->searchable()->deselected(),
            Column::make("Status", "status")->format(function ($value) {

                if ($value == File::APPROVED) {
                    $html = '<span class="badge badge-primary ms-2 fs-8 py-1 px-3">' . $value . '</span>';
                } elseif ($value == File::REJECTED) {
                    $html = '<span class="badge badge-danger ms-2 fs-8 py-1 px-3">' . $value . '</span>';
                } elseif ($value == File::DRAFT) {
                    $html = '<span class="badge badge-warning ms-2 fs-8 py-1 px-3">' . $value . '</span>';
                } else {
                    $html = '<span class="badge badge-success ms-2 fs-8 py-1 px-3">' . $value . '</span>';
                }

                return $html;
            })->html()->sortable()->searchable(),
            // Column::make("Privasi", "privacy")->sortable()->searchable(),
            Column::make("Action")->label(function ($row) {
                $defaultAction = '';
            
                if (is_null($row->url)) {
                    if (in_array($row->extension, ['docx', 'doc', 'xlsx', 'xls'])) {
                        $defaultAction = 'href="https://docs.google.com/gview?url=' . urlencode(url('storage/' . $row->path)) . '&embedded=true" target="_blank"';
                    } else {
                        $defaultAction = 'href="' . url('storage/' . $row->path) . '" target="_blank"';
                    }
                } else {
                    // Check if the provided URL has a protocol
                    if (!preg_match("~^(?:f|ht)tps?://~i", $row->url)) {
                        $row->url = "http://" . $row->url;
                    }
            
                    $defaultAction = 'href="' . $row->url . '" target="_blank"';
                }
            
                $html = '<div class="btn-group">';
                $html .= '<a class="btn btn-secondary btn-sm" ' . $defaultAction . '>View</a>'; 
                $html .= '<button type="button" class="btn btn-sm btn-success dropdown-toggle dropdown-toggle-split custom-dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">';
                $html .= '<span class="sr-only">Toggle Dropdown</span>';
                $html .= '</button>';
                $html .= '<div class="dropdown-menu">';
                
                // Fetch the folder type for the row
                $folderType = $row->type->type;

                if ($folderType == 'url') { // Assuming 'url' is one of the types in the FolderType table
                    $html .= '<a class="dropdown-item" href="' . $row->url . '" target="_blank">View</a>';
                } else {
                    if (is_null($row->url)) {
                        $html .= '<a class="dropdown-item" href="' . url('storage/' . $row->path) . '" download>Download</a>';
                    } else {
                        $html .= '<a class="dropdown-item" href="' . $row->url . '" target="_blank">View</a>';
                    }
                }               
            
                $html .= '<a class="dropdown-item" href="javascript:void;" wire:click="editFile(' . $row->id . ')">Edit</a>';
                $html .= '<a class="dropdown-item" href="javascript:void;" data-bs-toggle="tooltip" data-bs-placement="top" wire:click="confirmDelete(' . $row->id . ')">Delete</a>';
                
                if ($row->status == FILE::PENDING) {
                    $html .= '<a class="dropdown-item" href="javascript:void;" data-bs-toggle="tooltip" data-bs-placement="top" wire:click="confirmAccept(' . $row->id . ')">Diterima</a>';
                    $html .= '<a class="dropdown-item" href="javascript:void;" data-bs-toggle="tooltip" data-bs-placement="top" wire:click="confirmReject(' . $row->id . ')">Reject</a>';
                }
                
                if ($row->status == FILE::APPROVED) {
                    $html .= '<a class="dropdown-item" href="javascript:void;" data-bs-toggle="tooltip" data-bs-placement="top" wire:click="confirmDraft(' . $row->id . ')">Draf</a>';
                }
            
                $html .= '</div>';
                $html .= '</div>';
            
                return $html;
            })->html()                        
        ];
    }

    public function builder(): Builder
    {
        return File::select('files.*')->with(['folder', 'user', 'type'])->where('folder_id', $this->folder_id);
    }

    public function editFile($file_id)
    {
        $this->dispatchBrowserEvent('open-x-modal', [
            'title' => 'Edit File',
            'modal' => 'edit-file',
            'size' => 'xl',
            'args' => ['file_id' => $file_id]
        ]);
    }

    public function confirmDelete($id)
    {
        $this->dispatchBrowserEvent('swal:confirm', [
            'type' => 'question',
            'title' => 'file',
            'text' => 'Are you sure you want to delete this ?',
            'id' => $id
        ]);
    }

    public function confirmReject($id)
    {
        $this->dispatchBrowserEvent('swal:input', [
            'type' => 'info',
            'title' => 'rejectFile',
            'text' => 'Please provide a reason to reject this file',
            'id' => $id
        ]);
    }

    public function rejectFile($id, $reason)
    {
        $file = File::find($id);

        $file->update([
            'reason' => $reason,
            'status' => File::REJECTED
        ]);

        // if ($file->folder->is_trackable) {
            UpdateProjectStatus::dispatch($this->folder_id);
        // }

        $user = User::find($file->user_id);

        $user->notify(new FileRejected($file->folder));

        $this->dispatchBrowserEvent('swal:modal', [
            'type' => 'success',
            'title' => 'Great!',
            'text' => 'File Rejected!'
        ]);
    }

    public function confirmAccept($id)
    {
        $this->dispatchBrowserEvent('swal:confirm', [
            'type' => 'warning',  
            'message' => 'Are you sure?', 
            'title' => 'acceptFile',
            'text' => 'Are you sure you want to proceed with this action ?',
            'id' => $id
        ]);
    }

    public function acceptFile($id)
    {
        $file = File::find($id);

        $file->update([
            'status' => File::APPROVED
        ]);

        // if ($file->folder->is_trackable) {
            UpdateProjectStatus::dispatch($this->folder_id);
        // }

        $this->dispatchBrowserEvent('swal:modal', [
            'type' => 'success',
            'title' => 'Great!',
            'text' => 'File Accepted!'
        ]);
    }

    public function confirmDraft($id)
    {
        $this->dispatchBrowserEvent('swal:confirm', [
            'type' => 'warning',  
            'message' => 'Are you sure?', 
            'title' => 'draftFile',
            'text' => 'Are you sure you want to proceed with this action ?',
            'id' => $id
        ]);
    }

    public function draftFile($id)
    {
        $file = File::find($id);

        $file->update([
            'status' => File::DRAFT
        ]);

        // if ($file->folder->is_trackable) {
            UpdateProjectStatus::dispatch($this->folder_id);
        // }

        $this->dispatchBrowserEvent('swal:modal', [
            'type' => 'success',
            'title' => 'Great!',
            'text' => 'File Status Changed!'
        ]);
    }

    public function removeFile(File $file)
    {
        // Check if it's a file in storage
        if ($file->path && Storage::disk('public')->exists($file->path)) {
            // Move the file to the bin directory
            Storage::disk('public')->move($file->path, 'bin/' . $file->path);
        } 
        
        // Delete the database record regardless of the URL or file existence
        $file->delete();

        // Dispatch the update project status event
        UpdateProjectStatus::dispatch($this->folder_id);

        // Display the success message
        $this->dispatchBrowserEvent('swal:modal', [
            'type' => 'success',
            'title' => 'Great!',
            'text' => 'File Deleted!'
        ]);
    }

    public function updateFileDatatable($data)
    {
        $file = File::create([
            'user_id' => auth()->user()->id,
            'folder_id' => $data['folder_id'],
            'description' => $data['description'],
            'folder_type_id' => $data['folder_type_id'],
            'url' => $data['url'] ?? null,
            'filename' => $data['filename'] ?? null,
            'path' => $data['path'] ?? null,
            'extension' => $data['extension'] ?? null,
            'size' => $data['size'] ?? null,
            'status' => $data['status'],
            'can_download' => $data['can_download'],
            'classification' => $data['classification'],
            'privacy' => $data['privacy'],
            'is_shareable' => $data['is_shareable'],
        ]);

        $file->categories()->attach(array_values($data['category']));

        $this->dispatchBrowserEvent('swal:modal', [
            'type' => 'success',
            'title' => 'Great!',
            'text' => 'File Created!'
        ]);

        $this->emit('closeModal');
    }

    public function editFileDatatable($data)
    {
        $file = File::find($data['file_id']);

        $oldFolderTypeId = $file->folder_type_id;

        $file->update([
            'user_id' => auth()->user()->id,
            'folder_id' => $data['folder_id'],
            'description' => $data['description'],
            'folder_type_id' => $data['folder_type_id'],
            'url' => $data['url'] ?? null,
            'filename' => $data['filename'] ?? null,
            'path' => $data['path'] ?? null,
            'extension' => $data['extension'] ?? null,
            'size' => $data['size'] ?? null,
            'status' => $data['status'],
            'can_download' => $data['can_download'],
            'classification' => $data['classification'],
            'privacy' => $data['privacy'],
            'is_shareable' => $data['is_shareable'],
            'updated_at' => $data['updated_at']
        ]);

        if ($file->status == File::APPROVED) {
            if ($oldFolderTypeId == $data['folder_type_id']) {
                UpdateProjectStatus::dispatch($file->folder_id);
            }
        }

        $file->categories()->sync(array_values($data['category']));

        $this->dispatchBrowserEvent('swal:modal', [
            'type' => 'success',
            'title' => 'Great!',
            'text' => 'File Updated!'
        ]);

        $this->dispatchBrowserEvent('closeLivewireModal');
    }

    public function approvedSelected()
    {
        foreach ($this->getSelected() as $item) {
            $file = File::find($item);

            if ($file->status == FILE::DRAFT || $file->status == FILE::APPROVED) {
                $this->dispatchBrowserEvent('swal:modal', [
                    'type' => 'error',
                    'title' => 'Oops!',
                    'text' => 'The selected files cannot be taken action because it is still in draft or it has already approved!'
                ]);

                $this->clearSelected();
            } else {
                $file->update(['status' => File::APPROVED]);

                $user = User::find($file->user_id);

                $user->notify(new FileApproved($file->folder));

                $this->dispatchBrowserEvent('swal:modal', [
                    'type' => 'success',
                    'title' => 'Great!',
                    'text' => 'File Approved!'
                ]);

                $this->clearSelected();

                UpdateProjectStatus::dispatch($this->folder_id);
            }
        }
    }

    public function rejectSelected()
    {
        foreach ($this->getSelected() as $item) {
            File::find($item)->update(['status' => File::REJECTED]);
        }

        $this->dispatchBrowserEvent('swal:modal', [
            'type' => 'success',
            'title' => 'Great!',
            'text' => 'File Rejected!'
        ]);

        $this->clearSelected();

        UpdateProjectStatus::dispatch($this->folder_id);
    }
}
