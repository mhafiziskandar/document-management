<x-master-layout>
    <div class="card card-xxl-stretch mb-5 mb-xl-8">
        <!--begin::Card header-->
        <div class="card-header border-0">
            <!--begin::Title-->
            <div class="card-title align-items-start flex-column">
                <h3>Files Management</h3>
            </div>
            <!--end::Title-->
            <div class="card-toolbar">
                @livewire('upload-file')
            </div>
        </div>
        <!--end::Card header-->
        <!--begin::Card body-->
        <div class="card-body py-3">
            @role('admin')
                @livewire('admin.file-datatable')
            @endrole
            @role('member')
                @livewire('member.file-datatable')
            @endrole
        </div>
        <!--end::Card body-->
    </div>
</x-master-layout>
