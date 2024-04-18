<x-livewiremodal-modal>
    <!--begin::Input group-->
    <div class="row mb-5">
        <label class="required form-label">Nama jenis dokumen</label>
        <input type="text" class="form-control form-control-solid form-control-lg" wire:model.lazy="name" />
        @error('name')
            <span class="text-danger mt-2">{{ $message }}</span>
        @enderror
    </div>
    <!--end::Actions-->
    <!--begin::Input group-->
    <div class="row mb-5">
        <label class="required form-label">Jenis dokumen</label>
        <select wire:model.defer="type" class="form-control form-control-solid form-select">
            <option value="fail">
                Fail
            </option>
            <option value="url">
                URL
            </option>
        </select>
        @error('type')
            <span class="text-danger mt-2">{{ $message }}</span>
        @enderror
    </div>
    <!--end::Actions-->
    <x-slot name="footer">
        <button wire:click.prevent="submit" class="btn btn-primary">Submit</button>
    </x-slot>
</x-livewiremodal-modal>
