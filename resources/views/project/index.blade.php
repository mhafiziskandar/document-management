<x-master-layout>
    <div class="card card-xxl-stretch mb-5 mb-xl-8">
        <!--begin::Card header-->
        <div class="card-header border-0">
            <!--begin::Title-->
            <div class="card-title align-items-start flex-column">
                <h3>Senarai Projek</h3>
            </div>
            <!--end::Title-->
            @role('admin')
                <div class="card-toolbar">
                    <a href="{{ route('admin.projects.create') }}" class="btn btn-primary btn-sm">
                        Tambah Projek
                    </a>
                </div>
            @endrole
        </div>
        <!--end::Card header-->
        <!--begin::Card body-->
        <div class="card-body py-3">
            @livewire('admin.project-datatable')
        </div>
        <!--end::Card body-->
    </div>
</x-master-layout>
