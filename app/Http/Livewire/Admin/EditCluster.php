<?php

namespace App\Http\Livewire\Admin;

use App\Models\Cluster;
use Livewire\Component;

class EditCluster extends Component
{
    public $cluster, $name;

    public function mount(Cluster $cluster)
    {
        $this->cluster = $cluster;
        $this->name = $cluster->name;
    }

    public function render()
    {
        return view('livewire.admin.edit-cluster');
    }

    public function submit(Cluster $cluster)
    {
        $this->validate([
            'name' => 'required'
        ]);
        
        $cluster->update([
            'name' => $this->name
        ]);

        $this->emit('updateClusterDatatable');
    }
}
