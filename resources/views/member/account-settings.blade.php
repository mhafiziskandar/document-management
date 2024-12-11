<x-master-layout>
    <form action="{{ route('member.update-account') }}" method="post">
        <x-card title="Kemaskini Akaun" footer="<a href='{{ url()->previous() }}' class='btn btn-secondary me-2'>Cancel</a><button type='submit' class='btn btn-primary'>Submit</button>">
            @csrf
            <!--begin::Input group for Email-->
            <div class="mb-6 row">
                <!--begin::Label-->
                <label class="col-lg-4 col-form-label required fw-bold fs-6">Email</label>
                <!--end::Label-->
                <!--begin::Col-->
                <div class="col-lg-8 fv-row fv-plugins-icon-container">
                    <input type="email" name="email" value="{{ old('email', auth()->user()->email) }}" class="form-control form-control-lg form-control-solid">
                    @error('email')
                        <div class="fv-plugins-message-container invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <!--end::Col-->
            </div>
            <!--end::Input group for Email-->

            <!--begin::Input group for Password-->
            <div class="mb-6 row">
                <!--begin::Label-->
                <label class="col-lg-4 col-form-label required fw-bold fs-6">Kata Laluan</label>
                <!--end::Label-->
                <!--begin::Col-->
                <div class="col-lg-8 fv-row fv-plugins-icon-container">
                    <input type="password" name="password" class="form-control form-control-lg form-control-solid">
                    @error('password')
                        <div class="fv-plugins-message-container invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <!--end::Col-->
            </div>
            <!--end::Input group for Password-->

            <!--begin::Input group for Password Confirmation-->
            <div class="mb-6 row">
                <!--begin::Label-->
                <label class="col-lg-4 col-form-label required fw-bold fs-6">Sahkan Kata Laluan</label>
                <!--end::Label-->
                <!--begin::Col-->
                <div class="col-lg-8 fv-row fv-plugins-icon-container">
                    <input type="password" name="password_confirmation" class="form-control form-control-lg form-control-solid">
                    @error('password_confirmation')
                        <div class="fv-plugins-message-container invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <!--end::Col-->
            </div>
            <!--end::Input group for Password Confirmation-->
        </x-card>
    </form>
</x-master-layout>