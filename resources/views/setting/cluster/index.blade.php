<x-master-layout>
    <div class="card card-xxl-stretch mb-5 mb-xl-8">
        <!--begin::Card header-->
        <div class="card-header border-0">
            <!--begin::Title-->
            <div class="card-title align-items-start flex-column">
                <h3>Senarai Kluster</h3>
            </div>
            <!--end::Title-->
            <div class="card-toolbar">
                <div x-data>
                    <button type="button" class="btn btn-sm btn-primary"
                        x-on:click='$dispatch("open-x-modal", {
                      title: "Tambah Kluster",
                      modal: "admin.add-cluster",
                      size: "m",
                      args: ""
                    })'>Tambah Kluster
                    </button>
                </div>
            </div>
        </div>
        <!--end::Card header-->
        <!--begin::Card body-->
        <div class="card-body py-3">
            @livewire('admin.cluster-datatable')
        </div>
        <!--end::Card body-->
    </div>
</x-master-layout>
