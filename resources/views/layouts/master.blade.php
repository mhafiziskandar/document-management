<!DOCTYPE html>
<html lang="en">
<!--begin::Head-->

<head>
    <base href="{{ config('app.url') }}">
    <title>{{ config('app.name') }}</title>
    <meta charset="utf-8" />
    <meta name="description" content="Online File Manager" />
    <meta name="keywords" content="iyres,hub,hub data,data" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <meta property="og:locale" content="en_US" />
    <meta property="og:type" content="article" />
    <meta property="og:title" content="" />
    <meta property="og:url" content="{{ config('app.url') }}" />
    <meta property="og:site_name" content="{{ config('app.name') }}" />
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="canonical" href="{{ config('app.url') }}" />
    <link rel="shortcut icon" href="{{ asset('assets_admin/media/logos/favicon.ico') }}" />
    <!--begin::Fonts(mandatory for all pages)-->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Inter:300,400,500,600,700" />
    <!--end::Fonts-->
    <!--begin::Vendor Stylesheets(used for this page only)-->
    <link href="{{ asset('assets_admin/plugins/custom/fullcalendar/fullcalendar.bundle.css') }}" rel="stylesheet" type="text/css" />

    <link href="{{ asset('assets_admin/plugins/custom/datatables/datatables.bundle.css') }}" rel="stylesheet" type="text/css" />
    <!--end::Vendor Stylesheets-->
    <!--begin::Global Stylesheets Bundle(mandatory for all pages)-->
    <link href="{{ asset('assets_admin/plugins/global/plugins.bundle.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('assets_admin/css/style.bundle.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('assets_admin/plugins/custom/jstree/jstree.bundle.css') }}" rel="stylesheet" type="text/css" />
    <!--end::Global Stylesheets Bundle-->
    @livewireStyles
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
    @stack('css')
</head>
<!--end::Head-->
<!--begin::Body-->

<body id="kt_body" style="background-image: url()" class="header-fixed header-tablet-and-mobile-fixed aside-fixed aside-secondary-enabled">
    <!--begin::Theme mode setup on page load-->
    <script>
        var defaultThemeMode = "light";
        var themeMode;
        if (document.documentElement) {
            if (document.documentElement.hasAttribute("data-theme-mode")) {
                themeMode = document.documentElement.getAttribute("data-theme-mode");
            } else {
                if (localStorage.getItem("data-theme") !== null) {
                    themeMode = localStorage.getItem("data-theme");
                } else {
                    themeMode = defaultThemeMode;
                }
            }
            if (themeMode === "system") {
                themeMode = window.matchMedia("(prefers-color-scheme: dark)").matches ? "dark" : "light";
            }
            document.documentElement.setAttribute("data-theme", themeMode);
        }
    </script>
    <!--end::Theme mode setup on page load-->
    <!--begin::Main-->
    <!--begin::Root-->
    <div class="d-flex flex-column flex-root">
        <!--begin::Page-->
        <div class="page d-flex flex-row flex-column-fluid">
            <!--begin::Aside-->
            @include('layouts.aside')
            <!--end::Aside-->
            <!--begin::Wrapper-->
            <div class="wrapper d-flex flex-column flex-row-fluid" id="kt_wrapper">
                <!--begin::Header-->
                @include('layouts.header')
                <!--end::Header-->
                <!--begin::Content-->
                <div class="content d-flex flex-column flex-column-fluid" id="kt_content">
                    <!--begin::Container-->
                    <div class="container-xxl" id="kt_content_container">
                        {{ $slot }}
                    </div>
                    <!--end::Container-->
                </div>
                <!--end::Content-->
                <!--begin::Footer-->
                <div class="footer py-4 d-flex flex-lg-column" id="kt_footer">
                    <!--begin::Container-->
                    <div class="container-xxl d-flex flex-column flex-md-row flex-stack">
                        <!--begin::Copyright-->
                        <div class="text-dark order-2 order-md-1">
                            <span class="text-gray-400 fw-semibold me-1">{{ date('Y') }} ©</span>
                            <a href="javascript:void;" target="_blank" class="text-muted text-hover-primary fw-semibold me-2 fs-6">Iyres Hub Data</a>
                        </div>
                        <!--end::Copyright-->
                    </div>
                    <!--end::Container-->
                </div>
                <!--end::Footer-->
            </div>
            <!--end::Wrapper-->
        </div>
        <!--end::Page-->
    </div>
    <!--end::Root-->
    <!--begin::Scrolltop-->
    <div id="kt_scrolltop" class="scrolltop" data-kt-scrolltop="true">
        <!--begin::Svg Icon | path: icons/duotune/arrows/arr066.svg-->
        <span class="svg-icon">
            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                <rect opacity="0.5" x="13" y="6" width="13" height="2" rx="1" transform="rotate(90 13 6)" fill="currentColor" />
                <path
                    d="M12.5657 8.56569L16.75 12.75C17.1642 13.1642 17.8358 13.1642 18.25 12.75C18.6642 12.3358 18.6642 11.6642 18.25 11.25L12.7071 5.70711C12.3166 5.31658 11.6834 5.31658 11.2929 5.70711L5.75 11.25C5.33579 11.6642 5.33579 12.3358 5.75 12.75C6.16421 13.1642 6.83579 13.1642 7.25 12.75L11.4343 8.56569C11.7467 8.25327 12.2533 8.25327 12.5657 8.56569Z"
                    fill="currentColor" />
            </svg>
        </span>
        <!--end::Svg Icon-->
    </div>
    <!--end::Scrolltop-->
    <!--begin::Javascript-->
    <script>
        var hostUrl = "assets/";
    </script>
    <!--begin::Global Javascript Bundle(mandatory for all pages)-->
    <script src="{{ asset('assets_admin/plugins/global/plugins.bundle.js') }}"></script>
    <script src="{{ asset('assets_admin/js/scripts.bundle.js') }}"></script>
    <!--end::Global Javascript Bundle-->
    <!--begin::Vendors Javascript(used for this page only)-->
    <script src="{{ asset('assets_admin/plugins/custom/fullcalendar/fullcalendar.bundle.js') }}"></script>
    <script src="{{ asset('assets_admin/plugins/custom/datatables/datatables.bundle.js') }}"></script>
    <script src="{{ asset('assets_admin/plugins/custom/formrepeater/formrepeater.bundle.js') }}"></script>
    <script src="{{ asset('assets_admin/plugins/custom/tinymce/tinymce.bundle.js') }}"></script>
    <script src="{{ asset('assets_admin/plugins/custom/fslightbox/fslightbox.bundle.js') }}"></script>
    <script src="{{ asset('assets_admin/plugins/custom/jstree/jstree.bundle.js') }}"></script>
    <!--end::Vendors Javascript-->
    <x-livewiremodal-base />
    @livewireScripts
    <!--end::Custom Javascript-->
    <!--end::Javascript-->
    @include('sweetalert::alert')
    @stack('script')

    <script>
        window.addEventListener('swal:modal', event => {
            Swal.fire({
                icon: event.detail.type,
                title: event.detail.title,
                text: event.detail.text,
                showConfirmButton: false,
                timer: 3000
            });
        });

        window.addEventListener('swal:confirm', event => {
            Swal.fire({
                icon: event.detail.type,
                text: event.detail.text,
                showCancelButton: true,
                confirmButtonText: "Submit",
                cancelButtonText: "Cancel",
                reverseButtons: true
            }).then(function(result) {
                if (result.value) {
                    if (event.detail.title == 'file') {
                        window.livewire.emit('removeFile', event.detail.id);
                    } else if (event.detail.title == 'bin') {
                        window.livewire.emit('recoverFile', event.detail.id);
                    } else if (event.detail.title == 'folder') {
                        window.livewire.emit('removeProject', event.detail.id);
                    } else if (event.detail.title == 'project') {
                        window.livewire.emit('recoverProject', event.detail.id);
                    } else if (event.detail.title == 'permenant_delete_project') {
                        window.livewire.emit('deleteProject', event.detail.id);
                    } else if (event.detail.title == 'permenant_delete_file') {
                        window.livewire.emit('deleteFile', event.detail.id);
                    } else if (event.detail.title == 'folderDelete') {
                        window.livewire.emit('folderDelete', event.detail.id);
                    } else if (event.detail.title == 'cluster') {
                        window.livewire.emit('clusterDelete', event.detail.id);
                    } else if (event.detail.title == 'folderType') {
                        window.livewire.emit('folderTypeDelete', event.detail.id);
                    } else if (event.detail.title == 'acceptFile') {
                        window.livewire.emit('acceptFile', event.detail.id);
                    } else if (event.detail.title == 'draftFile') {
                        window.livewire.emit('draftFile', event.detail.id);
                    }
                    // result.dismiss can be "cancel", "overlay",
                    // "close", and "timer"
                } else if (result.dismiss === "cancel") {
                    // window.livewire.emit('nooverride', event.detail.id);
                }
            });
        });

        window.addEventListener('swal:input', event => {
            Swal.fire({
                icon: event.detail.type,
                text: event.detail.text,
                input: 'textarea',
                showCancelButton: true,
                confirmButtonText: "Submit",
                cancelButtonText: "Cancel",
                reverseButtons: true
            }).then(function(result) {
                if (result.value) {
                    window.livewire.emit(event.detail.title, event.detail.id, result.value);

                    // "close", and "timer"
                } else if (result.dismiss === "cancel") {
                    // window.livewire.emit('nooverride', event.detail.id);
                }
            });
        });

        window.addEventListener('closeLivewireModal', event => {
            $("#x-modal").modal('hide');
        })
    </script>
</body>
<!--end::Body-->

</html>
