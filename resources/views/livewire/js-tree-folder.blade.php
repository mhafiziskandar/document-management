<div>
    <div class="mb-5 card">
        <!--begin::Card header-->
        <div class="border-0 cursor-pointer card-header">
            <div class="card-toolbar">
                <button wire:click="collapseAll" class="btn btn-sm btn-primary me-2">Collapse All</button>
                <button wire:click="expandAll" class="btn btn-sm btn-primary">Expand All</button>
            </div>
        </div>
        <div class="card-body border-top">
            <div wire:ignore>
                <div id="kt_docs_jstree_basic">
                </div>
            </div>
        </div>
    </div>
</div>

@push('script')
    <script>
        $('#kt_docs_jstree_basic').jstree({
            "core": {
                "themes": {
                    "responsive": false
                },
                "data": {!! json_encode($nodes) !!}
            },
            "types": {
                "default": {
                    "icon": "fa fa-folder"
                }
            },
            "plugins": ["types"],
        });

        window.addEventListener('reinitialize', event => {
            $('#kt_docs_jstree_basic').jstree('destroy');
            $('#kt_docs_jstree_basic').jstree({
                "core": {
                    "themes": {
                        "responsive": false
                    },
                    "data": event.detail.data
                },
                "types": {
                    "default": {
                        "icon": "fa fa-folder"
                    }
                },
                "plugins": ["types"],
            });

            $('#kt_docs_jstree_basic').on('select_node.jstree', function(e, data) {
                // Get the ID of the selected node
                var FolderId = data.node.data.folder_id;

                window.Livewire.emit('getFolderId', FolderId);
            });
        });

        $('#kt_docs_jstree_basic').on('select_node.jstree', function(e, data) {
            // Get the ID of the selected node
            var FolderId = data.node.data.folder_id;

            window.Livewire.emit('getFolderId', FolderId);
        });

        window.Livewire.on('jstree:collapseAll', function() {
            $('#kt_docs_jstree_basic').jstree('close_all');
        });

        window.Livewire.on('jstree:expandAll', function() {
            $('#kt_docs_jstree_basic').jstree('open_all');
        });
    </script>
@endpush
