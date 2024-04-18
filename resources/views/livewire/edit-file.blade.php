<x-livewiremodal-modal>
    <!--begin::Input group-->
    <div class="row mb-5">
        <!--begin::Input group-->
        <div class="mb-6 row">
            <!--begin::Label-->
            <label class="col-lg-3 col-form-label required fw-bold fs-6">Jenis Fail</label>
            <!--end::Label-->
            <!--begin::Col-->
            <div class="col-lg-9 fv-row fv-plugins-icon-container">
                <select wire:model.lazy="type" class="form-control form-control-solid form-select" wire:change="updateAttributes">
                    @forelse ($folderTypes as $type)
                        <option value="{{ $type->id }}">
                            {{ $type->name }}
                        </option>
                    @empty
                    @endforelse
                </select>
                @error('type')
                    <div class="fv-plugins-message-container invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
            <!--end::Col-->
        </div>
        <!--end::Input group-->
        @if ($jenis == 'fail')
            <!--begin::Input group-->
            <div class="mb-6 row">
                <!--begin::Label-->
                <label class="col-lg-3 col-form-label required fw-bold fs-6">Muat Naik Fail</label>
                <!--end::Label-->
                <!--begin::Col-->
                <div class="col-lg-9 fv-row fv-plugins-icon-container">
                    <input type="text" value="{{ $file->filename }}" class="form-control form-control-lg" readonly>
                    <input type="file" wire:model="upload_file" class="form-control form-control-lg">
                    <small class="form-text text-muted">Please upload a file below 50MB.</small>
                    @error('upload_file')
                        <div class="fv-plugins-message-container invalid-feedback">{{ $message }}
                        </div>
                    @enderror
                </div>
                <!--end::Col-->
            </div>
            <!--end::Input group-->
        @elseif($jenis == 'url')
            <!--begin::Input group-->
            <div class="mb-6 row">
                <!--begin::Label-->
                <label class="col-lg-3 col-form-label required fw-bold fs-6">Masukkan URL</label>
                <!--end::Label-->
                <!--begin::Col-->
                <div class="col-lg-9 fv-row fv-plugins-icon-container">
                    <div class="input-group">
                        <span class="input-group-text">URL</span>
                        <input type="text" wire:model.defer="url" value="{{ $file->url }}" class="form-control form-control-lg">
                        @error('url')
                            <div class="fv-plugins-message-container invalid-feedback">{{ $message }}
                            </div>
                        @enderror
                    </div>
                </div>
                <!--end::Col-->
            </div>
            <!--end::Input group-->
        @endif
        <!--begin::Input group-->
        <div class="mb-6 row">
            <!--begin::Label-->
            <label class="col-lg-3 col-form-label required fw-bold fs-6">Penerangan</label>
            <!--end::Label-->
            <!--begin::Col-->
            <div class="col-lg-9 fv-row fv-plugins-icon-container">
                <textarea wire:model.defer="description" class="form-control form-control-lg form-control-solid"></textarea>
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
            <label class="col-lg-3 col-form-label required fw-bold fs-6">Kerahsiaan
                <span data-bs-toggle="tooltip" data-bs-placement="top"
                    title="Umum: Boleh dipaparkan kepada semua pengguna IYRES. Sulit: Hanya dipaparkan untuk ahli pasukan projek sahaja.">
                    <i class="fa-sharp fa-solid fa-circle-info text-primary ms-1"></i>
                </span>
            </label>
            <!--end::Label-->
            <!--begin::Col-->
            <div class="col-lg-9 fv-row fv-plugins-icon-container mt-3">
                <div class="form-check form-check-custom form-check-solid form-check-inline">
                    <input class="form-check-input" type="radio" wire:model.lazy="privacy" value="{{ \App\Models\File::PUBLIC }}">
                    <label class="form-check-label" for="inlineRadio1">{{ \App\Models\File::PUBLIC }}</label>
                </div>
                <div class="form-check form-check-custom form-check-solid form-check-inline">
                    <input class="form-check-input" type="radio" wire:model.lazy="privacy" value="{{ \App\Models\File::PRIVATE }}">
                    <label class="form-check-label" for="inlineRadio2">{{ \App\Models\File::PRIVATE }}</label>
                </div>
                @error('privacy')
                    <div class="fv-plugins-message-container invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
            <!--end::Col-->
        </div>
        <!--end::Input group-->
        <!--begin::Input group-->
        <div class="mb-6 row">
            <!--begin::Label-->
            <label class="col-lg-3 col-form-label required fw-bold fs-6">Adakah data/maklumat ini boleh dikongsi kepada pihak luar ?</label>
            <!--end::Label-->
            <!--begin::Col-->
            <div class="col-lg-9 fv-row fv-plugins-icon-container mt-3">
                <div class="form-check form-check-custom form-check-solid form-check-inline">
                    <input class="form-check-input" type="radio" wire:model.lazy="is_shareable" value="{{ \App\Models\File::YES }}">
                    <label class="form-check-label" for="inlineRadio1">Ya</label>
                </div>
                <div class="form-check form-check-custom form-check-solid form-check-inline">
                    <input class="form-check-input" type="radio" wire:model.lazy="is_shareable" value="{{ \App\Models\File::NO }}">
                    <label class="form-check-label" for="inlineRadio2">Tidak</label>
                </div>
                @error('is_shareable')
                    <div class="fv-plugins-message-container invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
            <!--end::Col-->
        </div>
        <!--end::Input group-->
        <!--begin::Input group-->
        <div class="mb-6 row">
            <!--begin::Label-->
            <label class="col-lg-3 col-form-label required fw-bold fs-6">Muat Turun
                <span data-bs-toggle="tooltip" data-bs-placement="top" title="Menentukan samada dokumen ini BOLEH dimuat turun oleh pengguna IYRES yang lain atau pun TIDAK.">
                    <i class="fa-sharp fa-solid fa-circle-info text-primary ms-1"></i>
                </span>
            </label>
            <!--end::Label-->
            <!--begin::Col-->
            <div class="col-lg-9 fv-row fv-plugins-icon-container">
                <div class="form-check form-check-custom form-check-solid form-check-inline">
                    <input class="form-check-input" type="radio" wire:model.lazy="can_download" value="{{ \App\Models\File::YES }}">
                    <label class="form-check-label" for="inlineRadio1">Ya</label>
                </div>
                <div class="form-check form-check-custom form-check-solid form-check-inline">
                    <input class="form-check-input" type="radio" wire:model.lazy="can_download" value="{{ \App\Models\File::NO }}">
                    <label class="form-check-label" for="inlineRadio2">Tidak</label>
                </div>
                @error('can_download')
                    <div class="fv-plugins-message-container invalid-feedback">{{ $message }}
                    </div>
                @enderror
            </div>
            <!--end::Col-->
        </div>
        <!--end::Input group-->
        <!--begin::Input group-->
        <div class="mb-6 row">
            <!--begin::Label-->
            <label class="col-lg-3 col-form-label required fw-bold fs-6">Klasifikasi</label>
            <!--end::Label-->
            <!--begin::Col-->
            <div class="col-lg-9 fv-row fv-plugins-icon-container">
                <select wire:model.defer="classification" class="form-control form-control-solid form-select">
                    <option value="{{ \App\Models\File::PRIMARY }}">
                        Primer
                    </option>
                    <option value="{{ \App\Models\File::SECONDARY }}">
                        Sekunder
                    </option>
                </select>
                @error('classification')
                    <div class="fv-plugins-message-container invalid-feedback">{{ $message }}
                    </div>
                @enderror
            </div>
            <!--end::Col-->
        </div>
        <!--end::Input group-->
        @role('admin')
        <div class="mb-6 row">
            <!--begin::Label-->
            <label class="col-lg-3 col-form-label required fw-bold fs-6">Tarikh Kemaskini</label>
            <!--end::Label-->
            <!--begin::Col-->
            <div class="col-lg-9 fv-row fv-plugins-icon-container">
                <input type="datetime-local" class="form-control form-control-solid" wire:model.lazy="updated_at">
                @error('updated_at')
                    <div class="fv-plugins-message-container invalid-feedback">{{ $message }}
                    </div>
                @enderror
            </div>
            <!--end::Col-->
        </div>
        <!--end::Input group-->
        @endrole
        <!--begin::Input group-->
        <div class="mb-6 row">
            <!--begin::Label-->
            <label class="col-lg-3 col-form-label required fw-bold fs-6">Kategori</label>
            <!--end::Label-->
            <!--begin::Col-->
            <div class="col-lg-9 fv-row fv-plugins-icon-container">
                <div class="row">
                    @foreach ($categories as $key => $cat)
                        <div class="col-lg-6 fv-row fv-plugins-icon-container">
                            <div class="form-check form-check-custom form-check-solid form-check-inline mb-2">
                                <input class="form-check-input" wire:model.defer="category" type="checkbox" value="{{ $cat->id }}" />
                                <label class="form-check-label text-dark" for="flexCheckDefault">
                                    {{ $cat->name }}
                                </label>
                            </div>
                        </div>
                    @endforeach
                </div>
                @error('category')
                    <div class="fv-plugins-message-container invalid-feedback">{{ $message }}
                    </div>
                @enderror
            </div>
            <!--end::Col-->
        </div>
        <!--end::Input group-->
    </div>
    <!--begin::Actions-->

    <x-slot name="footer">
        <button class="btn btn-primary" wire:click.prevent="submit({{ $file_id }})">Submit</button>
        @role('member')
        <button class="btn btn-warning" wire:click.prevent="draft({{ $file_id }})">Draft</button>
        @endrole
    </x-slot>
    
    <!--end::Actions-->
</x-livewiremodal-modal>
