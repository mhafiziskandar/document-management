<?php

namespace App\Http\Livewire\Member;

use App\Jobs\UpdateProjectStatus;
use Rappasoft\LaravelLivewireTables\DataTableComponent;
use Rappasoft\LaravelLivewireTables\Views\Column;
use App\Models\File;
use App\Models\User;
use App\Notifications\UserUpload;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Notification;

class FileDatatable extends DataTableComponent
{
    protected $model = File::class;

    protected $listeners = ['updateFileDatatable', 'editFileDatatable'];

    public $folder_id;

    public function configure(): void
    {
        $this->setPrimaryKey('id');
        $this->setDefaultSort('updated_at', 'desc');
        $this->setTableAttributes([
            'class' => 'table table-hover table-rounded gy-4 gx-4',
        ]);
        $this->setTheadAttributes([
            'class' => 'fw-bold fs-6',
        ]);
    }

    public function columns(): array
    {
        return [
            Column::make("Name")->label(function ($row) {

                $html = "";
                if(is_null($row->url)){
                    $html .= "<span>" . $row->filename . "</span><br>";
                }
                else {
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
            })->html(),
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
                }),
            Column::make("Jenis Fail", "type.name")->sortable(),
            Column::make("Penerangan", "description")->sortable()->searchable(),
            Column::make("Klasifikasi", "classification")->sortable()->searchable(),
            Column::make("Kategori")->label(fn ($row) => $row->categories->count() . ' Kategori')->html(),
            Column::make("Reason", "reason")->sortable()->searchable(),
            Column::make("Status", "status")->format(function ($value) {

                $html = "";
                if ($value == File::APPROVED) {
                    $html = '<span class="badge badge-primary ms-2 fs-8 py-1 px-3">Selesai</span>';
                } elseif ($value == File::REJECTED) {
                    $html = '<span class="badge badge-danger ms-2 fs-8 py-1 px-3">Kuiri</span>';
                } elseif ($value == File::DRAFT) {
                    $html = '<span class="badge badge-warning ms-2 fs-8 py-1 px-3">' . $value . '</span>';
                } elseif ($value == File::PENDING) {
                    $html = '<span class="badge badge-success ms-2 fs-8 py-1 px-3">Dihantar</span>';
                }

                return $html;
            })->html()->sortable()->searchable(),
            //Column::make("Privasi", "privacy")->sortable()->searchable(),
            Column::make("Action")->label(function ($row) {
                $defaultAction = '';
                
                if (is_null($row->url)) {
                    if ($row->can_download || $row->user_id == auth()->id()) {
                        if (in_array($row->extension, ['docx', 'doc', 'xlsx', 'xls'])) {
                            $defaultAction = 'href="https://docs.google.com/gview?url=' . urlencode(url('storage/' . $row->path)) . '&embedded=true" target="_blank"';
                        } else {
                            $defaultAction = 'href="' . url('storage/' . $row->path) . '" target="_blank"';
                        }
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
                
                if (is_null($row->url)) {
                    $html .= '<a class="dropdown-item" href="' . url('storage/' . $row->path) . '" download>Download</a>';
                }
                
                if ($row->status == FILE::DRAFT || $row->status == FILE::REJECTED) {
                    $html .= '<a class="dropdown-item" href="javascript:void(0);" wire:click="editFile(' . $row->id . ')">Edit</a>';
                }
                
                $html .= '</div>';
                $html .= '</div>';
                
                return $html;
            })
            ->html()                                 
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

        $users = User::role('admin')->get();

        if ($data['status'] == File::PENDING) {
            Notification::send($users, new UserUpload($file));
        }

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
            'reason' => null
        ]);

        $users = User::role('admin')->get();

        if ($data['status'] == File::PENDING) {
            Notification::send($users, new UserUpload($file));
        }

        $file->categories()->sync(array_values($data['category']));

        $this->dispatchBrowserEvent('swal:modal', [
            'type' => 'success',
            'title' => 'Great!',
            'text' => 'File Updated!'
        ]);

        $this->dispatchBrowserEvent('closeLivewireModal');
    }
}
