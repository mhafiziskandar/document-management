<x-master-layout>
    <form action="{{ route('admin.projects.update', $folder) }}" method="post">
        <x-card title="Kemaskini Projek" footer="<a href='{{ url()->previous() }}' class='btn btn-secondary me-2'>Cancel</a><button type='submit' class='btn btn-primary'>Submit</button>">
            @csrf
            @method('PUT')
            <!--begin::Input group-->
            <div class="mb-6 row">
                <!--begin::Label-->
                <label class="col-lg-4 col-form-label required fw-bold fs-6">Nama Projek</label>
                <!--end::Label-->
                <!--begin::Col-->
                <div class="col-lg-8 fv-row fv-plugins-icon-container">
                    <input type="text" name="project_name" value="{{ old('project_name', $folder->project_name) }}" class="form-control form-control-lg form-control-solid">
                    @error('project_name')
                        <div class="fv-plugins-message-container invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <!--end::Col-->
            </div>
            <!--end::Input group-->
            <!--begin::Input group-->
            <div class="mb-6 row">
                <!--begin::Label-->
                <label class="col-lg-4 col-form-label required fw-bold fs-6">Projek Deskripsi</label>
                <!--end::Label-->
                <!--begin::Col-->
                <div class="col-lg-8 fv-row fv-plugins-icon-container">
                    <textarea name="description" class="form-control form-control-lg form-control-solid" rows="6">{{ old('description', $folder->description) }}</textarea>
                    @error('description')
                        <div class="fv-plugins-message-container invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <!--end::Col-->
            </div>
            <!--end::Input group-->
            <!--begin::Input group-->
            <div class="mb-6 row">
                <!--begin::Label-->
                <label class="col-lg-4 col-form-label required fw-bold fs-6">Tahun</label>
                <!--end::Label-->
                <!--begin::Col-->
                <div class="col-lg-8 fv-row fv-plugins-icon-container">
                    <input type="number" name="year" value="{{ old('year', $folder->year) }}" class="form-control form-control-lg form-control-solid">
                    @error('year')
                        <div class="fv-plugins-message-container invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <!--end::Col-->
            </div>
            <!--end::Input group-->
            <!--begin::Input group-->
            <div class="mb-6 row">
                <!--begin::Label-->
                <label class="col-lg-4 col-form-label required fw-bold fs-6">Kluster</label>
                <!--end::Label-->
                <!--begin::Col-->
                <div class="col-lg-8 fv-row fv-plugins-icon-container">
                    <select name="kluster" class="form-select form-select-lg form-select-solid" data-control="select2" data-placeholder="Pilih Kluster" data-allow-clear="true">
                        <option></option>
                        @foreach ($categories as $category)
                            <option value="{{ $category->id }}" {{ $category->id == old('kluster', $folder->cluster->id) ? 'selected' : '' }}>
                                {{ $category->name }}</option>
                        @endforeach
                    </select>
                    @error('kluster')
                        <div class="fv-plugins-message-container invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <!--end::Col-->
            </div>
            <!--end::Input group-->
            <div class="mb-6 row">
                <!--begin::Label-->
                <label class="col-lg-4 col-form-label required fw-bold fs-6">Departments</label>
                <!--end::Label-->
                <!--begin::Col-->
                <div class="col-lg-8 fv-row fv-plugins-icon-container">
                    <select name="departments[]" class="form-select form-select-lg form-select-solid" data-control="select2" data-placeholder="Select departments" data-allow-clear="true" multiple="multiple">
                        <option></option>
                        @foreach ($departments as $department)
                            <option value="{{ $department->id }}" {{ in_array($department->id, old('departments', $folder->departments->folderable_id)) ? 'selected' : '' }}>
                                {{ $department->name }}
                            </option>
                        @endforeach
                    </select>
                    @error('departments')
                        <div class="fv-plugins-message-container invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <!--end::Col-->
            </div>
            <!--begin::Input group-->
            <div class="mb-6 row">
                <!--begin::Label-->
                <label class="col-lg-4 col-form-label required fw-bold fs-6">Pengguna</label>
                <!--end::Label-->
                <!--begin::Col-->
                <div class="col-lg-8 fv-row fv-plugins-icon-container">
                    <select name="users[]" class="form-select form-select-lg form-select-solid" data-control="select2" data-placeholder="Select users" data-allow-clear="true" multiple="multiple">
                        <option></option>
                        @foreach ($users as $user)
                            <option value="{{ $user->id }}" {{ in_array($user->id, old('users', $folder->users->pluck('id')->toArray())) ? 'selected' : '' }}>
                                {{ $user->name }}
                            </option>
                        @endforeach
                    </select>
                    @error('users')
                        <div class="fv-plugins-message-container invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <!--end::Col-->
            </div>
            <!--end::Input group-->
            <!--begin::Input group-->
            <div class="mb-6 row">
                <!--begin::Label-->
                <label class="col-lg-4 col-form-label required fw-bold fs-6">Projek ini dipantau ?</label>
                <!--end::Label-->
                <!--begin::Col-->
                <div class="col-lg-8 fv-row fv-plugins-icon-container mt-3">
                    <div class="form-check form-check-custom form-check-solid form-check-inline">
                        <input class="form-check-input" type="radio" name="is_trackable" value="{{ \App\Models\Folder::YA }}"
                            {{ $folder->is_trackable == \App\Models\Folder::YA ? 'checked' : '' }}>
                        <label class="form-check-label" for="inlineRadio1">Ya</label>
                    </div>
                    <div class="form-check form-check-custom form-check-solid form-check-inline">
                        <input class="form-check-input" type="radio" name="is_trackable" value="{{ \App\Models\Folder::TIDAK }}"
                            {{ $folder->is_trackable == \App\Models\Folder::TIDAK ? 'checked' : '' }}>
                        <label class="form-check-label" for="inlineRadio2">Tidak</label>
                    </div>
                    @error('is_trackable')
                        <div class="fv-plugins-message-container invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <!--end::Col-->
            </div>
            <!--end::Input group-->
            <!--begin::Input group-->
            <div class="mb-6 row">
                <!--begin::Label-->
                <label class="col-lg-12 col-form-label required fw-bolder fs-6">Jenis Dokumen Diperlukan</label>
                <!--end::Label-->
                <div class="row">
                    <!--begin::Col-->
                    @foreach ($foldertypes as $key => $type)
                        <div class="col-lg-6 fv-row fv-plugins-icon-container">
                            <div class="form-check form-check-custom form-check-solid mb-2">
                                <input class="form-check-input" name="folder_types[]" type="checkbox" value="{{ $type->id }}"
                                    {{ in_array($type->id, old('folder_types', $folder->types->pluck('id')->toArray())) ? 'checked' : '' }} />
                                <label class="form-check-label text-dark" for="flexCheckDefault">
                                    {{ $type->name }}
                                </label>
                            </div>
                        </div>
                    @endforeach
                    <!--end::Col-->
                </div>
                @error('folder_types')
                    <div class="fv-plugins-message-container invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
            <!--end::Input group-->
            <!--begin::Input group-->
            <div class="mb-6 row">
                <!--begin::Label-->
                <label class="col-lg-4 col-form-label required fw-bolder fs-6">Tarikh Akhir</label>
                <!--end::Label-->
                <div class="col-lg-8 fv-row fv-plugins-icon-container">
                    <input type="date" class="form-control form-control-lg form-control-solid" value="{{ old('date', \Carbon\Carbon::createFromFormat('d/m/Y', $folder->tarikh_akhir)->format('Y-m-d')) }}"
                        name="date">
                    @error('date')
                        <div class="fv-plugins-message-container invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>
            <!--end::Input group-->
        </x-card>
    </form>
</x-master-layout>
