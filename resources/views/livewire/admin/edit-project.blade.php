<div>
    <form wire:submit.prevent="saveProject">
        <div class="mb-6 row">
            <label class="col-lg-4 col-form-label required fw-bold fs-6">Nama Projek</label>
            <div class="col-lg-8 fv-row fv-plugins-icon-container">
                <input type="text" wire:model="projectName" class="form-control form-control-lg form-control-solid">
                @error('projectName')
                    <div class="fv-plugins-message-container invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
        </div>
        <div class="mb-6 row">
            <label class="col-lg-4 col-form-label required fw-bold fs-6">Projek Deskripsi</label>
            <div class="col-lg-8 fv-row fv-plugins-icon-container">
                <textarea wire:model="description" class="form-control form-control-lg form-control-solid" rows="6"></textarea>
                @error('description')
                    <div class="fv-plugins-message-container invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
        </div>
        <div class="mb-6 row">
            <label class="col-lg-4 col-form-label required fw-bold fs-6">Tahun</label>
            <div class="col-lg-8 fv-row fv-plugins-icon-container">
                <input type="number" wire:model="year" class="form-control form-control-lg form-control-solid">
                @error('year')
                    <div class="fv-plugins-message-container invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
        </div>
        {{-- <div class="mb-6 row">
            <label class="col-lg-4 col-form-label required fw-bold fs-6">Kluster</label>
            <div class="col-lg-8 fv-row fv-plugins-icon-container">
                <select wire:model="kluster" class="form-select form-select-lg form-select-solid" id="klusterSelect">
                    <option></option>
                    @foreach ($categories as $category)
                        <option value="{{ $category->id }}">
                            {{ $category->name }}
                        </option>
                    @endforeach
                </select>
                @error('kluster')
                    <div class="fv-plugins-message-container invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
        </div> --}}

        {{-- Departments --}}
        <div class="mb-6 row">
            <label class="col-lg-4 col-form-label fw-bold fs-6">Departments</label>
            <div class="col-lg-8">
                <select id="departmentSelect" wire:model="selectedDepartments" multiple class="form-select form-select-lg form-select-solid">
                    @foreach ($departments as $department)
                        <option value="{{ $department->id }}">{{ $department->name }}</option>
                    @endforeach
                </select>
                @error('selectedDepartments')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
        </div>

        {{-- Users --}}
        <div class="mb-6 row">
            <label class="col-lg-4 col-form-label fw-bold fs-6">Pengguna</label>
            <div class="col-lg-8 fv-row fv-plugins-icon-container">
                <select id="userSelect" wire:model="selectedUsers" multiple class="form-select form-select-lg form-select-solid">
                    @foreach ($users as $user)
                        <option value="{{ $user->id }}">{{ $user->name }}</option>
                    @endforeach
                </select>
                @error('selectedUsers')
                    <div class="fv-plugins-message-container invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
        </div>

        <div class="mb-6 row">
            <label class="col-lg-4 col-form-label required fw-bold fs-6">Projek ini dipantau?</label>
            <div class="col-lg-8 fv-row fv-plugins-icon-container mt-3">
                <div class="form-check form-check-custom form-check-solid form-check-inline">
                    <input class="form-check-input" type="radio" wire:model="isTrackable" value="{{ \App\Models\Folder::YA }}">
                    <label class="form-check-label">Ya</label>
                </div>
                <div class="form-check form-check-custom form-check-solid form-check-inline">
                    <input class="form-check-input" type="radio" wire:model="isTrackable" value="{{ \App\Models\Folder::TIDAK }}">
                    <label class="form-check-label">Tidak</label>
                </div>
                @error('isTrackable')
                    <div class="fv-plugins-message-container invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
        </div>

        {{-- <div class="mb-6 row">
            <label class="col-lg-12 col-form-label required fw-bolder fs-6">Jenis Dokumen Diperlukan</label>
            <div class="row">
                @foreach ($foldertypes as $key => $type)
                    <div class="col-lg-6 fv-row fv-plugins-icon-container">
                        <div class="form-check form-check-custom form-check-solid mb-2">
                            <input class="form-check-input" wire:model="selectedFolderTypes" type="checkbox" value="{{ $type->id }}" />
                            <label class="form-check-label text-dark">
                                {{ $type->name }}
                            </label>
                        </div>
                    </div>
                @endforeach
            </div>
            @error('selectedFolderTypes')
                <div class="fv-plugins-message-container invalid-feedback">{{ $message }}</div>
            @enderror
        </div> --}}

        <div class="mb-6 row">
            <label class="col-lg-4 col-form-label required fw-bolder fs-6">Tarikh Akhir</label>
            <div class="col-lg-8 fv-row fv-plugins-icon-container">
                <input type="date" wire:model="endDate" class="form-control form-control-lg form-control-solid">
                @error('endDate')
                    <div class="fv-plugins-message-container invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
        </div>

        <div class="row">
            <div class="col-lg-8 offset-lg-4">
                <a href="{{ $previousUrl }}" class="btn btn-secondary">Cancel</a>
                <button type="submit" class="btn btn-primary me-2">Save Project</button>
            </div>
        </div>
    </form>

    <script>
        document.addEventListener('livewire:load', function () {
            initializeSelect2();

            Livewire.hook('message.processed', (message, component) => {
                initializeSelect2();
            });

            function initializeSelect2() {
                $('#departmentSelect, #userSelect').select2({
                    placeholder: function() {
                        $(this).data('placeholder');
                    },
                    allowClear: true
                }).on('change', function (e) {
                    var elementId = $(this).attr('id');
                    var data = $(this).val();
                    if (elementId === 'departmentSelect') {
                        @this.set('selectedDepartments', data);
                    } else if (elementId === 'userSelect') {
                        @this.set('selectedUsers', data);
                    }
                });
            }
        });
    </script>
</div>