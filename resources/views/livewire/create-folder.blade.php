<div>
    <form class="form fv-plugins-bootstrap5 fv-plugins-framework" wire:submit.prevent="submit">
        <div class="mb-5 card mb-xl-10">
            <!--begin::Card header-->
            <div class="border-0 cursor-pointer card-header">
                <!--begin::Card title-->
                <div class="m-0 card-title">
                    <h3 class="m-0 fw-bolder"></h3>
                </div>
                <!--end::Card title-->
                <div class="card-toolbar">
                    <button class="btn btn-sm btn-info me-2" wire:click.prevent="updateRoot">Add Root Folder</button>
                    <button class="btn btn-sm btn-info" wire:click.prevent="updateSub" {{ $sub ? '' : 'disabled' }}>Add
                        Sub
                        Folder</button>
                </div>
            </div>
            <div class="card-body border-top">
                <!--begin::Input group-->
                <div class="mb-6 row">
                    <!--begin::Label-->
                    <label class="col-lg-4 col-form-label required fw-bold fs-6">Project Name</label>
                    <!--end::Label-->
                    <!--begin::Col-->
                    <div class="col-lg-8 fv-row fv-plugins-icon-container">
                        <input type="text" wire:model.defer="project_name"
                            class="form-control form-control-lg form-control-solid">
                        <div class="fv-plugins-message-container invalid-feedback"></div>
                    </div>
                    <!--end::Col-->
                </div>
                <!--end::Input group-->
                <!--begin::Input group-->
                <div class="mb-6 row">
                    <!--begin::Label-->
                    <label class="col-lg-4 col-form-label required fw-bold fs-6">Year</label>
                    <!--end::Label-->
                    <!--begin::Col-->
                    <div class="col-lg-8 fv-row fv-plugins-icon-container">
                        <input type="number" wire:model.defer="year"
                            class="form-control form-control-lg form-control-solid">
                        <div class="fv-plugins-message-container invalid-feedback"></div>
                    </div>
                    <!--end::Col-->
                </div>
                <!--end::Input group-->
                <!--begin::Input group-->
                <div class="mb-6 row">
                    <!--begin::Label-->
                    <label class="col-lg-4 col-form-label fw-bold fs-6">Bil</label>
                    <!--end::Label-->
                    <!--begin::Col-->
                    <div class="col-lg-8 fv-row fv-plugins-icon-container">
                        <input type="text" wire:model.defer="bil"
                            class="form-control form-control-lg form-control-solid" readonly>
                        <div class="fv-plugins-message-container invalid-feedback"></div>
                    </div>
                    <!--end::Col-->
                </div>
                <!--end::Input group-->
                <!--begin::Input group-->
                <div class="mb-6 row">
                    <!--begin::Label-->
                    <label class="col-lg-4 col-form-label required fw-bold fs-6">Folder Name</label>
                    <!--end::Label-->
                    <!--begin::Col-->
                    <div class="col-lg-8 fv-row fv-plugins-icon-container">
                        <input type="text" wire:model.defer="name"
                            class="form-control form-control-lg form-control-solid">
                        <div class="fv-plugins-message-container invalid-feedback"></div>
                    </div>
                    <!--end::Col-->
                </div>
                <!--end::Input group-->
                <!--begin::Input group-->
                <div class="mb-6 row">
                    <!--begin::Label-->
                    <label class="col-lg-4 col-form-label required fw-bold fs-6">Assign To All</label>
                    <!--end::Label-->
                    <!--begin::Col-->
                    <div class="col-lg-8 fv-row fv-plugins-icon-container">
                        <div class="form-check form-check-custom form-check-solid">
                            <input class="form-check-input" type="checkbox" wire:model="assign" />
                            <label class="form-check-label text-dark" for="flexCheckDefault">
                                Assign this new folder to all users.
                            </label>
                        </div>
                        <div class="fv-plugins-message-container invalid-feedback"></div>
                    </div>
                    <!--end::Col-->
                </div>
                <!--end::Input group-->
                <!--begin::Input group-->
                <div class="mb-6 row">
                    <!--begin::Label-->
                    <label class="col-lg-4 col-form-label required fw-bold fs-6">Status</label>
                    <!--end::Label-->
                    <!--begin::Col-->
                    <div class="col-lg-8 fv-row fv-plugins-icon-container">
                        <select wire:model.lazy="status" class="form-control form-control-solid form-select">
                            <option value="{{ \App\Models\Folder::PUBLISH }}">
                                Publish
                            </option>
                            <option value="{{ \App\Models\Folder::DRAFT }}">
                                Draft
                            </option>
                        </select>
                        <div class="fv-plugins-message-container invalid-feedback"></div>
                    </div>
                    <!--end::Col-->
                </div>
                <!--end::Input group-->
                
                <!--begin::Input group-->
                <div class="mb-6 row" wire:ignore>
                    <!--begin::Label-->
                    <label class="col-lg-4 col-form-label required fw-bold fs-6">Assign User</label>
                    <!--end::Label-->
                    <!--begin::Col-->
                    <div class="col-lg-8 fv-row fv-plugins-icon-container">
                        <select id="locationUsers" class="form-select form-select-lg form-select-solid"
                            data-control="select2" data-placeholder="Select users" data-allow-clear="true"
                            multiple="multiple">
                            <option></option>
                            @foreach ($users as $user)
                                <option value="{{ $user->id }}">{{ $user->name }}</option>
                            @endforeach
                        </select>
                        <div class="fv-plugins-message-container invalid-feedback"></div>
                    </div>
                    <!--end::Col-->
                </div>
                <!--end::Input group-->
                <!--begin::Input group-->
                {{-- <div class="mb-6 row">
                    <!--begin::Label-->
                    <label class="col-lg-12 col-form-label fw-bolder fs-6">Jenis Dokumen Diperlukan</label>
                    <!--end::Label-->
                    <div class="row">
                        <!--begin::Col-->
                        @foreach ($foldertypes as $key => $type)
                            <div class="col-lg-6 fv-row fv-plugins-icon-container">
                                <div class="form-check form-check-custom form-check-solid mb-2">
                                    <input class="form-check-input" type="checkbox" value="{{ $type->id }}"
                                        wire:model="checkboxes.{{ $key }}" />
                                    <label class="form-check-label text-dark" for="flexCheckDefault">
                                        {{ $type->name }}
                                    </label>
                                </div>
                            </div>
                        @endforeach
                        <!--end::Col-->
                    </div>
                </div> --}}
                <!--end::Input group-->
            </div>
            <div class="card-footer">
                <button type="submit" class="btn btn-primary">Submit</button>
                <button class="btn btn-danger" wire:click.prevent="delete">Delete</button>
            </div>
        </div>
    </form>
</div>

@push('script')
    <script>
        window.addEventListener('updateJsTree', event => {
            console.log('jadi');
            window.livewire.emit('reinit');
        });

        document.addEventListener('livewire:load', function() {
            $('#locationUsers').on('select2:close', (e) => {
                @this.emit('locationUsersSelected', $('#locationUsers').select2('val'));
            });

            $('#locationUsers').val(@this.get('locationUsers')).trigger('change');
        });
    </script>
@endpush
