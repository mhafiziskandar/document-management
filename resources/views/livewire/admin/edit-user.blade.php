<x-livewiremodal-modal>
    <!--begin::Input group-->
    <div class="row mb-5">
        <label class="required form-label">Role</label>
        <select wire:model.defer="role" class="form-control form-control-solid form-select">
            @foreach ($roles as $role)
                <option value="{{ $role->name }}">
                    {{ $role->name }}
                </option>
            @endforeach
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
