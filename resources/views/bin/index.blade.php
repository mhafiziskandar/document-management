<x-master-layout>
    <div class="card card-xxl-stretch mb-5 mb-xl-8">
        <!--begin::Card header-->
        <div class="card-header border-0">
            <!--begin::Title-->
            <div class="card-title align-items-start flex-column">
                <h3>Bin Management</h3>
            </div>
        </div>
        <!--end::Card header-->
        <!--begin::Card body-->
        <div class="card-body py-3">
            @role('admin')
                @livewire('admin.bin-file-datatable')
            @endrole
        </div>
        <!--end::Card body-->
    </div>
</x-master-layout>
