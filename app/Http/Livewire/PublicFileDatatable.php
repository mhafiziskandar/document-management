<?php

namespace App\Http\Livewire;

use Rappasoft\LaravelLivewireTables\DataTableComponent;
use Rappasoft\LaravelLivewireTables\Views\Column;
use App\Models\File;
use Illuminate\Database\Eloquent\Builder;

class PublicFileDatatable extends DataTableComponent
{
    protected $model = File::class;

    protected $index = 0;

    public $folder_id;

    public function configure(): void
    {
        $this->setPrimaryKey('id');
    }

    public function columns(): array
    {
        return [
            Column::make("Bil.", "id")
                ->format(fn () => ++$this->index +  ($this->page - 1) * $this->perPage),
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
                    if ($value < 1024) {
                        return $value . ' B';
                    } elseif ($value < 1048576) {
                        return round($value / 1024, 2) . ' KB';
                    } else {
                        return round($value / 1048576, 2) . ' MB';
                    }
                }),
            Column::make("Jenis Fail", "type.name")->sortable(),
            Column::make("Penerangan", "description")->sortable()->searchable(),
            Column::make("Klasifikasi", "classification")->sortable()->searchable(),
            Column::make("Kategori")->label(fn ($row) => $row->categories->count() . ' Kategori')->html(),
            Column::make("Status", "status")->format(function ($value) {

                if ($value == File::APPROVED) {
                    $html = '<span class="badge badge-primary ms-2 fs-8 py-1 px-3">' . $value . '</span>';
                } elseif ($value == File::REJECTED) {
                    $html = '<span class="badge badge-danger ms-2 fs-8 py-1 px-3">' . $value . '</span>';
                } else {
                    $html = '<span class="badge badge-secondary ms-2 fs-8 py-1 px-3">' . $value . '</span>';
                }

                return $html;
            })->html()->sortable()->searchable(),
            Column::make("Action")->label(function ($row) {
                $html = "<div class='btn-group'>";
                $html .= "<livewire:upload-file :folder_id='" . $this->folder_id . "'/>";
            
                $defaultAction = '';
            
                if (is_null($row->url)) {
                    if ($row->can_download || $row->user_id == auth()->id()) {
                        $html .= "<a href='" . url('storage/' . $row->path) . "' class='btn btn-success btn-icon btn-sm' download><i class='fas fa-download'></i></a>";
                        
                        if (in_array($row->extension, ['docx', 'doc', 'xlsx', 'xls'])) {
                            $defaultAction = 'href="https://docs.google.com/gview?url=' . urlencode(url('storage/' . $row->path)) . '&embedded=true" target="_blank"';
                        } else {
                            $defaultAction = 'href="' . url('storage/' . $row->path) . '" target="_blank"';
                        }
                    }
                } else {
                    $defaultAction = 'href="' . $row->url . '" target="_blank"';
                }
            
                if (!empty($defaultAction)) {
                    $html .= '<a ' . $defaultAction . ' class="btn btn-warning btn-icon btn-sm"><i class="fas fa-eye"></i></a>';
                }
            
                $html .= "</div>";
                return $html;
            })
            ->html()                        
        ];
    }

    public function builder(): Builder
    {
        return File::query()
            ->select('files.*')
            ->with(['folder', 'user', 'type'])
            ->where('folder_id', $this->folder_id)
            ->where('privacy', File::PUBLIC);
    }
}
