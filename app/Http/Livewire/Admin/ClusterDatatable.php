<?php

namespace App\Http\Livewire\Admin;

use Rappasoft\LaravelLivewireTables\DataTableComponent;
use Rappasoft\LaravelLivewireTables\Views\Column;
use App\Models\Cluster;
use Illuminate\Database\Eloquent\Builder;

class ClusterDatatable extends DataTableComponent
{
    protected $model = Cluster::class;

    protected $listeners = ['storeCluster', 'clusterDelete', 'updateClusterDatatable'];

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
            Column::make("Created at", "created_at")
                ->sortable(),
            Column::make("Action")->label(function ($row) {

                $html = "<div class='btn-group'>";
                $html .= "<button wire:click='edit(" . $row . ")' class='btn btn-success btn-icon btn-sm'><i class='fas fa-pencil'></i></button>";
                $html .= "<button class='btn btn-icon btn-danger btn-sm' data-bs-toggle='tooltip' data-bs-placement='top' title='Delete Cluster' wire:click='confirmDelete(" . $row->id . ")'><i class='fas fa-trash'></i></button>";
                $html .= "</div>";

                return $html;
            })->html()
        ];
    }

    public function builder(): Builder
    {
        return Cluster::select('clusters.*');
    }

    public function edit($cluster)
    {
        $this->dispatchBrowserEvent('open-x-modal', [
            'title' => 'Edit Cluster',
            'modal' => 'admin.edit-cluster',
            'size' => 's',
            'args' => ['cluster' => $cluster]
        ]);
    }

    public function storeCluster($data)
    {
        Cluster::create([
            'name' => $data['name']
        ]);

        $this->dispatchBrowserEvent('swal:modal', [
            'type' => 'success',
            'title' => 'Great!',
            'text' => 'Cluster Created!'
        ]);

        $this->dispatchBrowserEvent('closeLivewireModal');
    }

    public function confirmDelete($id)
    {
        $this->dispatchBrowserEvent('swal:confirm', [
            'type' => 'question',
            'title' => 'cluster',
            'text' => 'Are you sure you want to delete this cluster ?',
            'id' => $id
        ]);
    }

    public function clusterDelete(Cluster $cluster)
    {
        $cluster->delete();

        $this->dispatchBrowserEvent('swal:modal', [
            'type' => 'success',
            'title' => 'Great!',
            'text' => 'Cluster Deleted!'
        ]);
    }

    public function updateClusterDatatable()
    {
        $this->dispatchBrowserEvent('swal:modal', [
            'type' => 'success',
            'title' => 'Great!',
            'text' => 'Cluster Name Updated!'
        ]);

        $this->dispatchBrowserEvent('closeLivewireModal');
    }
}
