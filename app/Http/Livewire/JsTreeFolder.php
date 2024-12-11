<?php

namespace App\Http\Livewire;

use App\Models\Folder;
use Livewire\Component;

class JsTreeFolder extends Component
{
    protected $listeners = ['reinit'];

    public $nodes = [];

    public function collapseAll()
    {
        $this->emit('jstree:collapseAll');
    }

    public function expandAll()
    {
        $this->emit('jstree:expandAll');
    }

    public function reinit()
    {
        $this->nodes = [];
        // Generate jsTree nodes for the root folders (i.e., those with no parent)
        $root_folders = Folder::whereNull('parent_id')->get();
        foreach ($root_folders as $folder) {
            $node = [
                'id' => $folder->id,
                'text' => $folder->name,
                'data' => [
                    'folder_id' => $folder->id
                ],
                'children' => $this->generateNodes($folder->id) // recursively generate child nodes
            ];
            array_push($this->nodes, $node);
        }

        $this->dispatchBrowserEvent('reinitialize', ['data' => $this->nodes]);
    }

    public function mount()
    {
        // Generate jsTree nodes for the root folders (i.e., those with no parent)
        $root_folders = Folder::whereNull('parent_id')->get();
        foreach ($root_folders as $folder) {
            $node = [
                'id' => $folder->id,
                'text' => $folder->name,
                'data' => [
                    'folder_id' => $folder->id
                ],
                'children' => $this->generateNodes($folder->id) // recursively generate child nodes
            ];
            array_push($this->nodes, $node);
        }
    }

    // Recursive function to generate jsTree nodes for a given parent folder
    public function generateNodes($parent_id)
    {
        // Get child folders from database
        $folders = Folder::where('parent_id', $parent_id)->get();

        // Create an array of jsTree nodes
        $nodes = [];
        foreach ($folders as $folder) {
            $node = [
                'id' => $folder->id,
                'text' => $folder->name,
                'data' => [
                    'folder_id' => $folder->id
                ],
                'children' => $this->generateNodes($folder->id) // recursively generate child nodes
            ];
            array_push($nodes, $node);
        }

        return $nodes;
    }


    public function render()
    {
        return view('livewire.js-tree-folder');
    }
}
