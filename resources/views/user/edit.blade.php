<x-master-layout>
    <form class="form fv-plugins-bootstrap5 fv-plugins-framework" method="POST"
        action="{{ route('admin.users.update', $user) }}">
        @csrf
        @method('PUT')
        <x-card title="Edit User" footer='<button type="submit" class="btn btn-primary">Update</button>'>
            <!--begin::Input group-->
            <div class="mb-6 row">
                <!--begin::Label-->
                <label class="col-lg-4 col-form-label required fw-bold fs-6">Name</label>
                <!--end::Label-->
                <!--begin::Col-->
                <div class="col-lg-8 fv-row fv-plugins-icon-container">
                    <input type="text" name="name" value="{{ $user->name }}"
                        class="form-control form-control-solid form-control-lg">
                    @error('name')
                        <div class="fv-plugins-message-container invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <!--end::Col-->
            </div>
            <!--end::Input group-->
            <!--begin::Input group-->
            <div class="mb-6 row">
                <!--begin::Label-->
                <label class="col-lg-4 col-form-label required fw-bold fs-6">Email</label>
                <!--end::Label-->
                <!--begin::Col-->
                <div class="col-lg-8 fv-row fv-plugins-icon-container">
                    <input type="email" name="email" value="{{ $user->email }}"
                        class="form-control form-control-solid form-control-lg">
                    @error('email')
                        <div class="fv-plugins-message-container invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <!--end::Col-->
            </div>
            <!--end::Input group-->
            <!--begin::Input group-->
            <div class="mb-6 row">
                <!--begin::Label-->
                <label class="col-lg-4 col-form-label required fw-bold fs-6">Role</label>
                <!--end::Label-->
                <!--begin::Col-->
                <div class="col-lg-8 fv-row fv-plugins-icon-container">
                    <select name="role" class="form-control form-control-solid form-select">
                        @foreach ($roles as $rl)
                            <option value="{{ $rl->name }}"
                                {{ $rl->name == $user->roles()->first()->name ? 'selected' : 'name' }}>
                                {{ $rl->name }}</option>
                        @endforeach
                    </select>
                    @error('role')
                        <div class="fv-plugins-message-container invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <!--end::Col-->
            </div>
            <!--end::Input group-->
            <!--begin::Input group-->
            <div class="mb-6 row">
                <!--begin::Label-->
                <label class="col-lg-4 col-form-label required fw-bold fs-6">Folder</label>
                <!--end::Label-->
                <!--begin::Col-->
                <div class="col-lg-8 fv-row fv-plugins-icon-container">
                    <select name="folders[]" class="form-select form-select-lg form-select-solid" data-control="select2"
                        data-placeholder="Select Folders" data-allow-clear="true" multiple="multiple">
                        <option></option>
                        @foreach ($folders as $folder)
                            <option value="{{ $folder->id }}"
                                {{ in_array($folder->id,$user->folders()->pluck('folders.id')->toArray())? 'selected': '' }}>
                                {{ $folder->name }}</option>
                        @endforeach
                    </select>
                    @error('folders')
                        <div class="fv-plugins-message-container invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <!--end::Col-->
            </div>
            <!--end::Input group-->
        </x-card>
    </form>
</x-master-layout>
