<?php

namespace App\Http\Livewire\Admin;

use Livewire\Component;

class AddCluster extends Component
{
    public $name;

    public function render()
    {
        return view('livewire.admin.add-cluster');
    }

    public function submit()
    {
        $this->validate([
            'name' => 'required|string|min:3'
        ]);
        
        $this->emit('storeCluster', [
            'name' => $this->name,
        ]);
    }
}
