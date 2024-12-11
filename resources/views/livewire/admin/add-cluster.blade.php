<x-livewiremodal-modal>
    <!--begin::Input group-->
    <div class="row mb-5">
        <label class="required form-label">Nama kluster</label>
        <input type="text" class="form-control form-control-lg" wire:model.lazy="name" />
        @error('name')
            <span class="text-danger mt-2">{{ $message }}</span>
        @enderror
    </div>
    <!--end::Actions-->
    <x-slot name="footer">
        <button wire:click.prevent="submit" class="btn btn-primary">Submit</button>
    </x-slot>
</x-livewiremodal-modal>
