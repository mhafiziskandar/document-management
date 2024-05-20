<x-master-layout>
    <!--begin::Navbar-->
    <div class="card mb-5 mb-xl-10">
        <div class="card-body pt-9 pb-0">
            <div class="row">
                @if ($folder->is_trackable)
                    <div class="col-xl-3 col-lg-12 col-md-12 col-sm-12">
                        <div id="chart"></div>
                    </div>
                @endif
                <div class="{{ $folder->is_trackable ? 'col-xl-9' : 'col-xl-12' }} col-lg-12 col-md-12 col-sm-12">
                    <!--begin::Details-->
                    <div class="d-flex bd-highlight mb-1">
                        <div class="me-auto p-2 bd-highlight"> <a href="javascript:void" class="text-gray-900 text-hover-primary fs-2 fw-bold me-1 mb-2">[{{ $folder->bil }}]
                                {{ $folder->project_name }}</a>
                            @if ($folder->status == \App\Models\Folder::COMPLETE)
                                <span class="badge badge-primary ms-2 fs-8 py-1 px-3 me-10">{{ $folder->status }}</span>
                            @elseif($folder->status == \App\Models\Folder::INCOMPLETE)
                                <span class="badge badge-danger ms-2 fs-8 py-1 px-3 me-10">{{ $folder->status }}</span>
                            @endif
                        </div>
                        <div class="p-2 bd-highlight">@livewire('admin.delete-project', ['folder' => $folder])</div>
                        <div class="p-2 bd-highlight"> <a href="{{ route('admin.projects.edit', $folder) }}" class="btn btn-sm btn-success mb-1" style="float: right;">Kemaskini</a></div>
                        <!-- Close Button -->
                        <div class="p-2 bd-highlight">
                            @php
                                $backUrl = session('previous_url', default: route('admin.projects.index'));
                            @endphp
                            <a href="{{ $backUrl }}" class="btn btn-sm btn-secondary mb-1" style="float: right;">Tutup</a>
                        </div>                        
                    </div>
                    <!--begin::Title-->
                    <div class="d-flex align-items-start flex-wrap mb-2">
                        <div class="d-flex flex-column">
                            <!--begin::Info-->
                            <div class="d-flex flex-wrap fw-semibold fs-6 mb-4 pe-2">
                                <a href="javascript:void" class="d-flex align-items-center text-gray-800 text-hover-primary me-5 mb-2">
                                    <!--begin::Svg Icon | path: icons/duotune/communication/com006.svg-->
                                    <span class="svg-icon svg-icon-4 me-1">
                                        <svg width="18" height="18" viewBox="0 0 18 18" fill="none" xmlns="http://www.w3.org/2000/svg">
                                            <path opacity="0.3" d="M16.5 9C16.5 13.125 13.125 16.5 9 16.5C4.875 16.5 1.5 13.125 1.5 9C1.5 4.875 4.875 1.5 9 1.5C13.125 1.5 16.5 4.875 16.5 9Z"
                                                fill="currentColor" />
                                            <path
                                                d="M9 16.5C10.95 16.5 12.75 15.75 14.025 14.55C13.425 12.675 11.4 11.25 9 11.25C6.6 11.25 4.57499 12.675 3.97499 14.55C5.24999 15.75 7.05 16.5 9 16.5Z"
                                                fill="currentColor" />
                                            <rect x="7" y="6" width="4" height="4" rx="2" fill="currentColor" />
                                        </svg>
                                    </span>
                                    <!--end::Svg Icon-->{{ $folder->users->implode('name', ', ') }}
                                </a>
                                <a href="javascript:void" class="d-flex align-items-center text-gray-800 text-hover-primary me-5 mb-2">
                                    <!--begin::Svg Icon | path: /var/www/preview.keenthemes.com/kt-products/docs/metronic/html/releases/2023-03-24-172858/core/html/src/media/icons/duotune/general/gen066.svg-->
                                    <span class="svg-icon svg-icon-4 me-1"><svg width="25" height="28" viewBox="0 0 25 28" fill="none" xmlns="http://www.w3.org/2000/svg">
                                            <path
                                                d="M24.0259 11.4401H1.97259C1.69436 11.4505 1.43123 11.5693 1.2394 11.7711C1.04757 11.9729 0.942247 12.2417 0.945922 12.5201V20.0801C0.933592 21.0248 1.10836 21.9627 1.46016 22.8395C1.81196 23.7164 2.33382 24.515 2.99568 25.1892C3.65754 25.8635 4.4463 26.4001 5.3165 26.7681C6.1867 27.1361 7.12112 27.3282 8.06592 27.3334H17.9993C19.8855 27.288 21.6778 26.5012 22.988 25.1436C24.2983 23.7859 25.0208 21.9667 24.9993 20.0801V12.5201C25.0037 12.2504 24.9057 11.989 24.7251 11.7886C24.5445 11.5882 24.2947 11.4637 24.0259 11.4401ZM8.73259 21.8401C8.51017 21.84 8.29271 21.7744 8.1073 21.6515C7.92189 21.5287 7.77672 21.354 7.68989 21.1492C7.60306 20.9444 7.5784 20.7186 7.61899 20.5C7.65957 20.2813 7.76361 20.0794 7.91813 19.9194C8.07266 19.7594 8.27084 19.6484 8.48798 19.6003C8.70513 19.5522 8.93164 19.569 9.1393 19.6487C9.34695 19.7283 9.52658 19.8673 9.65578 20.0484C9.78499 20.2294 9.85807 20.4445 9.86592 20.6668C9.86241 20.965 9.74146 21.2499 9.5293 21.4595C9.31714 21.6692 9.03087 21.7868 8.73259 21.7868V21.8401ZM8.73259 17.5868C8.50844 17.5868 8.28932 17.5203 8.10294 17.3958C7.91657 17.2712 7.77131 17.0942 7.68553 16.8871C7.59975 16.6801 7.5773 16.4522 7.62103 16.2323C7.66476 16.0125 7.7727 15.8105 7.9312 15.652C8.0897 15.4935 8.29164 15.3856 8.51149 15.3419C8.73133 15.2981 8.95921 15.3206 9.1663 15.4064C9.37339 15.4921 9.55039 15.6374 9.67492 15.8238C9.79945 16.0102 9.86592 16.2293 9.86592 16.4534C9.86771 16.6028 9.83962 16.7509 9.7833 16.8892C9.72697 17.0276 9.64356 17.1532 9.53796 17.2588C9.43236 17.3644 9.30672 17.4478 9.1684 17.5041C9.03009 17.5605 8.88192 17.5886 8.73259 17.5868ZM12.9993 21.8401C12.701 21.8331 12.4175 21.7088 12.2104 21.4941C12.0032 21.2794 11.889 20.9917 11.8926 20.6934C11.8926 20.3964 12.0106 20.1115 12.2206 19.9015C12.4307 19.6914 12.7155 19.5734 13.0126 19.5734C13.3096 19.5734 13.5945 19.6914 13.8045 19.9015C14.0146 20.1115 14.1326 20.3964 14.1326 20.6934C14.1291 20.9917 14.0081 21.2765 13.796 21.4862C13.5838 21.6959 13.2975 21.8135 12.9993 21.8134V21.8401ZM12.9993 17.5868C12.701 17.5798 12.4175 17.4555 12.2104 17.2408C12.0032 17.0261 11.889 16.7384 11.8926 16.4401C11.8926 16.1431 12.0106 15.8582 12.2206 15.6481C12.4307 15.4381 12.7155 15.3201 13.0126 15.3201C13.3096 15.3201 13.5945 15.4381 13.8045 15.6481C14.0146 15.8582 14.1326 16.1431 14.1326 16.4401C14.1326 16.7384 14.015 17.0246 13.8054 17.2368C13.5957 17.449 13.3109 17.5699 13.0126 17.5734L12.9993 17.5868ZM17.2393 21.8401C16.9387 21.8401 16.6504 21.7207 16.4379 21.5082C16.2253 21.2956 16.1059 21.0073 16.1059 20.7068C16.1059 20.4062 16.2253 20.1179 16.4379 19.9054C16.6504 19.6928 16.9387 19.5734 17.2393 19.5734C17.5398 19.5734 17.8281 19.6928 18.0406 19.9054C18.2532 20.1179 18.3726 20.4062 18.3726 20.7068C18.3726 21.0073 18.2532 21.2956 18.0406 21.5082C17.8281 21.7207 17.5398 21.8401 17.2393 21.8401ZM17.2393 17.5868C16.9387 17.5868 16.6504 17.4674 16.4379 17.2548C16.2253 17.0423 16.1059 16.754 16.1059 16.4534C16.1059 16.1529 16.2253 15.8646 16.4379 15.652C16.6504 15.4395 16.9387 15.3201 17.2393 15.3201C17.5398 15.3201 17.8281 15.4395 18.0406 15.652C18.2532 15.8646 18.3726 16.1529 18.3726 16.4534C18.3726 16.754 18.2532 17.0423 18.0406 17.2548C17.8281 17.4674 17.5398 17.5868 17.2393 17.5868ZM24.6393 8.13343C24.7349 8.40774 24.7203 8.7085 24.5984 8.9722C24.4765 9.2359 24.2569 9.44192 23.9859 9.54677C23.8703 9.58813 23.7487 9.61063 23.6259 9.61343H2.62592C2.2723 9.61343 1.93316 9.47296 1.68311 9.22291C1.43306 8.97286 1.29259 8.63372 1.29259 8.2801C1.28883 8.11525 1.32066 7.95153 1.38592 7.8001C1.77683 6.84295 2.37003 5.98161 3.12487 5.27509C3.87972 4.56858 4.77837 4.03358 5.75926 3.70677V1.62677C5.75926 1.3863 5.85478 1.15569 6.02481 0.985655C6.19485 0.815622 6.42546 0.720099 6.66592 0.720099C6.90639 0.720099 7.137 0.815622 7.30703 0.985655C7.47707 1.15569 7.57259 1.3863 7.57259 1.62677V3.33343H12.3059V1.62677C12.2904 1.49938 12.3021 1.37015 12.3402 1.24761C12.3783 1.12508 12.442 1.01204 12.5271 0.915961C12.6122 0.819883 12.7167 0.74296 12.8337 0.690277C12.9507 0.637594 13.0776 0.610352 13.2059 0.610352C13.3343 0.610352 13.4611 0.637594 13.5781 0.690277C13.6952 0.74296 13.7997 0.819883 13.8847 0.915961C13.9698 1.01204 14.0335 1.12508 14.0716 1.24761C14.1098 1.37015 14.1215 1.49938 14.1059 1.62677V3.33343H18.3326V1.62677C18.3171 1.49938 18.3287 1.37015 18.3669 1.24761C18.405 1.12508 18.4687 1.01204 18.5538 0.915961C18.6389 0.819883 18.7434 0.74296 18.8604 0.690277C18.9774 0.637594 19.1043 0.610352 19.2326 0.610352C19.3609 0.610352 19.4878 0.637594 19.6048 0.690277C19.7218 0.74296 19.8263 0.819883 19.9114 0.915961C19.9965 1.01204 20.0602 1.12508 20.0983 1.24761C20.1364 1.37015 20.1481 1.49938 20.1326 1.62677V3.70677C21.1713 4.05261 22.1173 4.63121 22.8984 5.39839C23.6794 6.16557 24.2749 7.10105 24.6393 8.13343Z"
                                                fill="currentColor" />
                                        </svg>
                                    </span>
                                    <!--end::Svg Icon-->
                                    {{ $folder->year }}
                                </a>
                                <a href="javascript:void" class="d-flex align-items-center text-gray-800 text-hover-primary me-5 mb-2">
                                    <!--begin::Svg Icon | path: /var/www/preview.keenthemes.com/kt-products/docs/metronic/html/releases/2023-03-24-172858/core/html/src/media/icons/duotune/general/gen005.svg-->
                                    <span class="svg-icon svg-icon-4 me-1"><svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                            <path opacity="0.3"
                                                d="M19 22H5C4.4 22 4 21.6 4 21V3C4 2.4 4.4 2 5 2H14L20 8V21C20 21.6 19.6 22 19 22ZM12.5 18C12.5 17.4 12.6 17.5 12 17.5H8.5C7.9 17.5 8 17.4 8 18C8 18.6 7.9 18.5 8.5 18.5L12 18C12.6 18 12.5 18.6 12.5 18ZM16.5 13C16.5 12.4 16.6 12.5 16 12.5H8.5C7.9 12.5 8 12.4 8 13C8 13.6 7.9 13.5 8.5 13.5H15.5C16.1 13.5 16.5 13.6 16.5 13ZM12.5 8C12.5 7.4 12.6 7.5 12 7.5H8C7.4 7.5 7.5 7.4 7.5 8C7.5 8.6 7.4 8.5 8 8.5H12C12.6 8.5 12.5 8.6 12.5 8Z"
                                                fill="currentColor" />
                                            <rect x="7" y="17" width="6" height="2" rx="1" fill="currentColor" />
                                            <rect x="7" y="12" width="10" height="2" rx="1" fill="currentColor" />
                                            <rect x="7" y="7" width="6" height="2" rx="1" fill="currentColor" />
                                            <path d="M15 8H20L14 2V7C14 7.6 14.4 8 15 8Z" fill="currentColor" />
                                        </svg>
                                    </span>
                                    <!--end::Svg Icon-->
                                    {{ $folder->description }}
                                </a>
                                <a href="javascript:void;" class="d-flex align-items-center text-gray-800 text-hover-primary me-5 mb-2">
                                    <!--begin::Svg Icon | path: /var/www/preview.keenthemes.com/kt-products/docs/metronic/html/releases/2023-03-24-172858/core/html/src/media/icons/duotune/abstract/abs027.svg-->
                                    <span class="svg-icon svg-icon-4 me-1">
                                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                            <path opacity="0.3" d="M6 22H4V3C4 2.4 4.4 2 5 2C5.6 2 6 2.4 6 3V22Z" fill="currentColor" />
                                            <path d="M18 14H4V4H18C18.8 4 19.2 4.9 18.7 5.5L16 9L18.8 12.5C19.3 13.1 18.8 14 18 14Z" fill="currentColor" />
                                        </svg>
                                    </span>
                                    <!--end::Svg Icon-->
                                    {{-- {{ $folder->cluster->name }} --}}
                                </a>

                            </div>
                            <!--end::Info-->
                            @if ($folder->is_trackable)
                                <div class="d-flex align-items-center flex-wrap justify-content-start mb-2">
                                    @foreach ($folder->types as $type)
                                        <span
                                            class="badge {{ $folder->files()->where('status', \App\Models\File::APPROVED)->whereHas('type', function ($query) use ($type) {$query->where('name', $type->name);})->exists() ? 'badge-primary' : ($folder->files()->where('status', \App\Models\File::PENDING)->whereHas('type', function ($query) use ($type) {$query->where('name', $type->name);})->exists() ? 'badge-success' : 'badge-danger') }} me-3 mb-1">{{ $type->name }}</span>
                                    @endforeach
                                </div>
                            @else
                                <div class="d-flex align-items-center flex-wrap justify-content-start mb-2">
                                    <span class="badge badge-danger me-3 mb-1">Non-trackable Project</span>

                                </div>
                            @endif
                        </div>
                    </div>
                    <!--end::Title-->
                    <!--begin::Stats-->
                    <div class="d-flex flex-wrap flex-stack">
                        <!--begin::Wrapper-->
                        <div class="d-flex flex-column flex-grow-1 pe-8">
                            <!--begin::Stats-->
                            <div class="d-flex flex-wrap">
                                <!--begin::Stat-->
                                <div class="border border-gray-300 border-dashed rounded min-w-125px py-3 px-4 me-6 mb-3">
                                    <!--begin::Number-->
                                    <div class="d-flex align-items-center">
                                        <div class="fs-2 fw-bold" data-kt-countup="true" data-kt-countup-value="{{ $folder->files->count() }}">
                                            {{ $folder->files->count() }}</div>
                                    </div>
                                    <!--end::Number-->
                                    <!--begin::Label-->
                                    <div class="fw-semibold fs-6 text-gray-400">Jumlah Fail</div>
                                    <!--end::Label-->
                                </div>
                                <!--end::Stat-->
                                <!--begin::Stat-->
                                <div class="border border-gray-300 border-dashed rounded min-w-125px py-3 px-4 me-6 mb-3">
                                    <!--begin::Number-->
                                    <div class="d-flex align-items-center">
                                        <div class="fs-2 fw-bold">
                                            {{ round($folder->files->sum('size') / 1048576, 2) }}
                                            MB
                                        </div>
                                    </div>
                                    <!--end::Number-->
                                    <!--begin::Label-->
                                    <div class="fw-semibold fs-6 text-gray-400">Jumlah Saiz</div>
                                    <!--end::Label-->
                                </div>
                                <!--end::Stat-->
                                <!--begin::Stat-->
                                <div class="border border-gray-300 border-dashed rounded min-w-125px py-3 px-4 me-6 mb-3">
                                    <!--begin::Number-->
                                    <div class="d-flex align-items-center">
                                        <!--end::Svg Icon-->
                                        <div class="fs-2 fw-bold" data-kt-countup="true" data-kt-countup-value="{{ $folder->users->count() }}">
                                            {{ $folder->users->count() }}</div>
                                    </div>
                                    <!--end::Number-->
                                    <!--begin::Label-->
                                    <div class="fw-semibold fs-6 text-gray-400">Pengguna</div>
                                    <!--end::Label-->
                                </div>
                                <!--end::Stat-->
                                <div class="border border-gray-300 border-dashed rounded min-w-125px py-3 px-4 me-6 mb-3">
                                    <!--begin::Number-->
                                    <div class="d-flex align-items-center">
                                        <!--end::Svg Icon-->
                                        <div class="fs-2 fw-bold">
                                            {{ $folder->tarikh_akhir }}</div>
                                    </div>
                                    <!--end::Number-->
                                    <!--begin::Label-->
                                    <div class="fw-semibold fs-6 text-gray-400">Tarikh Akhir</div>
                                    <!--end::Label-->
                                </div>
                                <!--end::Stat-->
                            </div>
                            <!--end::Stats-->
                        </div>
                        <!--end::Wrapper-->
                    </div>
                    <!--end::Stats-->
                    <!--end::Details-->
                </div>
            </div>

            <!--begin::Navs-->
            <ul class="nav nav-stretch nav-line-tabs nav-line-tabs-2x border-transparent fs-5 fw-bold mb-5">
                <!--begin::Nav item-->
                <li class="nav-item mt-2">
                    <a class="nav-link text-active-primary ms-0 me-10 py-5 active" data-bs-toggle="tab" href="#fail_projek">Fail Projek</a>
                </li>
                <!--end::Nav item-->
                <!--begin::Nav item-->
                <li class="nav-item mt-2">
                    <a class="nav-link text-active-primary ms-0 me-10 py-5" data-bs-toggle="tab" href="#log">Aktiviti
                        Projek</a>
                </li>
                <!--end::Nav item-->
            </ul>
            <!--begin::Navs-->

            <div class="tab-content" id="myTabContent">
                <div class="tab-pane fade show active" id="fail_projek" role="tabpanel">
                    <div class="card shadow-sm card-xxl-stretch mb-5 mb-xl-8">
                        <!--begin::Card header-->
                        <div class="card-header border-0">
                            <!--begin::Title-->
                            <div class="card-title align-items-start flex-column">
                                <h3>Fail Projek</h3>
                            </div>
                            <!--end::Title-->
                            <div class="card-toolbar">
                                @livewire('upload-file', ['folder_id' => $folder->id])
                            </div>
                        </div>
                        <!--end::Card header-->
                        <!--begin::Card body-->
                        <div class="card-body py-3">
                            @livewire('admin.file-datatable', ['folder_id' => $folder->id])
                        </div>
                        <!--end::Card body-->
                    </div>
                </div>
                <div class="tab-pane fade" id="log" role="tabpanel">
                    <div class="card shadow-sm card-xxl-stretch mb-5 mb-xl-8">
                        <!--begin::Card header-->
                        <div class="card-header border-0">
                            <!--begin::Title-->
                            <div class="card-title align-items-start flex-column">
                                <h3>Log Aktiviti</h3>
                            </div>
                            <!--end::Title-->
                        </div>
                        <!--end::Card header-->
                        <!--begin::Card body-->
                        <div class="card-body py-3">
                            @livewire('admin.activity-log-datatable', ['folder_id' => $folder->id])
                        </div>
                        <!--end::Card body-->
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!--end::Navbar-->
    @push('script')
        <script>
            window.Livewire.on('closeModal', () => {
                $('#kt_modal_1').modal('hide');
            });

            var progress = @json(round($progress, 2));

            var options = {
                chart: {
                    height: 275,
                    type: 'radialBar',
                },
                series: [progress],
                labels: ['Progress'],
            }

            var chart = new ApexCharts(document.querySelector("#chart"), options);

            chart.render();

            $('.custom-dropdown-toggle').on('click', function(e) {
                e.stopPropagation();
                var isActive = $(this).hasClass('show');

                $('.custom-dropdown-toggle').dropdown('hide');
                $('.custom-dropdown-toggle, .dropdown-menu').removeClass('show');

                if(!isActive) {
                    $(this).dropdown('toggle');
                }
            });

            $(document).on('show.bs.dropdown', '.custom-dropdown-toggle', function() {
                var dropdown = $(this).next('.dropdown-menu');
                $('body').append(dropdown.detach());
                
                var position = $(this).offset();
                dropdown.css({
                    'position': 'absolute',
                    'top': position.top + $(this).outerHeight(),
                    'left': position.left,
                    'z-index': 1001
                });
            }).on('hide.bs.dropdown', '.custom-dropdown-toggle', function() {
                $(this).append($(this).next('.dropdown-menu').detach());
            });

            $(document).on('hide.bs.dropdown', '.custom-dropdown-toggle', function() {
                $(this).append($(this).next('.dropdown-menu').detach());
                $(this).removeClass('show');
                $(this).next('.dropdown-menu').removeClass('show');
                $(this).blur();
            });
        </script>
    @endpush

</x-master-layout>
