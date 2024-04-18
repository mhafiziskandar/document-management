<form wire:submit.prevent="submit({{ $folder->id }})">
    <x-livewiremodal-modal>
        <!--begin::Input group-->
        <div class="row mb-5">
            <!--begin::Input group-->
            <div class="mb-6 row">
                <!--begin::Label-->
                <label class="col-lg-3 col-form-label required fw-bold fs-6">Penerangan</label>
                <!--end::Label-->
                <!--begin::Col-->
                <div class="col-lg-9 fv-row fv-plugins-icon-container">
                    <textarea wire:model.defer="description" class="form-control form-control-lg form-control-solid" rows="7"></textarea>
                    @error('description')
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
