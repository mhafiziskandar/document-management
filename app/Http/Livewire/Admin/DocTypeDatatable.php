<?php

namespace App\Http\Livewire\Admin;

use Rappasoft\LaravelLivewireTables\DataTableComponent;
use Rappasoft\LaravelLivewireTables\Views\Column;
use App\Models\FolderType;
use Illuminate\Database\Eloquent\Builder;

class DocTypeDatatable extends DataTableComponent
{
    protected $model = FolderType::class;

    protected $listeners = ['storeFolderType', 'folderTypeDelete', 'updateDocTypeDatatable'];

    public function configure(): void
    {
        $this->setPrimaryKey('id');
    }

    public function columns(): array
    {
        return [
            Column::make("Id", "id")
                ->sortable(),
            Column::make("Name", "name")
                ->sortable()->searchable(),
            Column::make("Type", "type")
                ->format(fn ($value) => $value == FolderType::FILE ? '<span class="badge badge-primary">' . $value . '</span>' : '<span class="badge badge-danger">' . $value . '</span>')->html()
                ->sortable()->searchable(),
            Column::make("Created at", "created_at")
                ->sortable(),
            Column::make("Action")->label(function ($row) {

                $html = "<div class='btn-group'>";
                $html .= "<button wire:click='edit(" . $row . ")' class='btn btn-success btn-icon btn-sm'><i class='fas fa-pencil'></i></button>";
                $html .= "<button class='btn btn-icon btn-danger btn-sm' data-bs-toggle='tooltip' data-bs-placement='top' title='Delete Folder Type' wire:click='confirmDelete(" . $row->id . ")'><i class='fas fa-trash'></i></button>";
                $html .= "</div>";

                return $html;
            })->html()
        ];
    }

    public function builder(): Builder
    {
        return FolderType::select('folder_types.*');
    }

    public function storeFolderType($data)
    {
        FolderType::create([
            'name' => $data['name'],
            'type' => $data['type']
        ]);

        $this->dispatchBrowserEvent('swal:modal', [
            'type' => 'success',
            'title' => 'Great!',
            'text' => 'Folder Type Created!'
        ]);

        $this->dispatchBrowserEvent('closeLivewireModal');
    }

    public function confirmDelete($id)
    {
        $this->dispatchBrowserEvent('swal:confirm', [
            'type' => 'question',
            'title' => 'folderType',
            'text' => 'Are you sure you want to delete this folder type ?',
            'id' => $id
        ]);
    }

    public function folderTypeDelete(FolderType $cluster)
    {
        $cluster->delete();

        $this->dispatchBrowserEvent('swal:modal', [
            'type' => 'success',
            'title' => 'Great!',
            'text' => 'Folder Type Deleted!'
        ]);
    }

    public function edit($folderType)
    {
        $this->dispatchBrowserEvent('open-x-modal', [
            'title' => 'Edit Folder Type',
            'modal' => 'admin.edit-folder-type',
            'size' => 's',
            'args' => ['folderType' => $folderType]
        ]);
    }

    public function updateDocTypeDatatable()
    {
        $this->dispatchBrowserEvent('swal:modal', [
            'type' => 'success',
            'title' => 'Great!',
            'text' => 'Jenis Dokumen Updated!'
        ]);

        $this->dispatchBrowserEvent('closeLivewireModal');
    }
}
