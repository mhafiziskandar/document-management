<form wire:submit.prevent="submit({{ $cluster->id }})">
    <x-livewiremodal-modal>
        <!--begin::Input group-->
        <div class="row mb-5">
            <!--begin::Input group-->
            <div class="mb-6 row">
                <!--begin::Label-->
                <label class="col-lg-3 col-form-label required fw-bold fs-6">Nama</label>
                <!--end::Label-->
                <!--begin::Col-->
                <div class="col-lg-9 fv-row fv-plugins-icon-container">
                    <input type="text" wire:model.defer="name" class="form-control form-control-lg form-control-solid">
                    @error('name')
                        <div class="fv-plugins-message-container invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <!--end::Col-->
            </div>
            <!--end::Input group-->
            <x-slot name="footer">
                <button class="btn btn-primary">Submit</button>
            </x-slot name="footer">
        </div>
        <!--begin::Actions-->
        <!--end::Actions-->
    </x-livewiremodal-modal>
</form>
