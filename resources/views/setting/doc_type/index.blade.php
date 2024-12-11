<x-master-layout>
    <div class="card card-xxl-stretch mb-5 mb-xl-8">
        <!--begin::Card header-->
        <div class="card-header border-0">
            <!--begin::Title-->
            <div class="card-title align-items-start flex-column">
                <h3>Senarai Jenis Dokumen</h3>
            </div>
            <!--end::Title-->
            <div class="card-toolbar">
                <div x-data>
                    <button type="button" class="btn btn-sm btn-primary"
                        x-on:click='$dispatch("open-x-modal", {
                      title: "Tambah Jenis Dokumen",
                      modal: "admin.add-folder-type",
                      size: "m",
                      args: ""
                    })'>Tambah Jenis Dokumen
                    </button>
                </div>
            </div>
        </div>
        <!--end::Card header-->
        <!--begin::Card body-->
        <div class="card-body py-3">
            @livewire('admin.doc-type-datatable')
        </div>
        <!--end::Card body-->
    </div>
</x-master-layout>
