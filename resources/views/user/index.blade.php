<x-master-layout>
    <div class="card card-xxl-stretch mb-5 mb-xl-8">
        <!--begin::Card header-->
        <div class="card-header border-0">
            <!--begin::Title-->
            <div class="card-title align-items-start flex-column">
                <h3>User Management</h3>
            </div>
            <!--end::Title-->
            <div class="card-toolbar">
                {{-- @livewire('admin.user-sync') --}}
                @livewire('admin.add-user')
            </div>
        </div>
        <!--end::Card header-->
        <!--begin::Card body-->
        <div class="card-body py-3">
            @livewire('admin.user-datatable')
        </div>
        <!--end::Card body-->
    </div>
</x-master-layout>
