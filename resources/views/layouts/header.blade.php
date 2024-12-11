<div id="kt_header" class="header" data-kt-sticky="true" data-kt-sticky-name="header"
    data-kt-sticky-offset="{default: '200px', lg: '300px'}">
    <!--begin::Container-->
    <div class="container-xxl d-flex align-items-center justify-content-between" id="kt_header_container">
        <!--begin::Page title-->
        <div class="page-title d-flex flex-column align-items-start justify-content-center flex-wrap mt-n5 mt-lg-0 me-lg-2 pb-2 pb-lg-0"
            data-kt-swapper="true" data-kt-swapper-mode="prepend"
            data-kt-swapper-parent="{default: '#kt_content_container', lg: '#kt_header_container'}">
            <!--begin::Heading-->
            <h1 class="text-dark fw-bold my-0 fs-2">Hub Data</h1>
            <!--end::Heading-->
            <!--begin::Breadcrumb-->
            <ul class="breadcrumb fw-semibold fs-base my-1">
                <li class="breadcrumb-item text-muted">
                    <a href="{{ route('dashboard') }}" class="text-muted">Dashboard</a>
                </li>
                <li class="breadcrumb-item text-muted">{{ Breadcrumbs::render() }}</li>
            </ul>
            <!--end::Breadcrumb-->
        </div>
        <!--end::Page title=-->
        <!--begin::Wrapper-->
        <div class="d-flex d-lg-none align-items-center ms-n2 me-2">
            <!--begin::Aside mobile toggle-->
            <div class="btn btn-icon btn-active-icon-primary" id="kt_aside_mobile_toggle">
                <!--begin::Svg Icon | path: icons/duotune/abstract/abs015.svg-->
                <span class="svg-icon svg-icon-1">
                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none"
                        xmlns="http://www.w3.org/2000/svg">
                        <path d="M21 7H3C2.4 7 2 6.6 2 6V4C2 3.4 2.4 3 3 3H21C21.6 3 22 3.4 22 4V6C22 6.6 21.6 7 21 7Z"
                            fill="currentColor" />
                        <path opacity="0.3"
                            d="M21 14H3C2.4 14 2 13.6 2 13V11C2 10.4 2.4 10 3 10H21C21.6 10 22 10.4 22 11V13C22 13.6 21.6 14 21 14ZM22 20V18C22 17.4 21.6 17 21 17H3C2.4 17 2 17.4 2 18V20C2 20.6 2.4 21 3 21H21C21.6 21 22 20.6 22 20Z"
                            fill="currentColor" />
                    </svg>
                </span>
                <!--end::Svg Icon-->
            </div>
            <!--end::Aside mobile toggle-->
            <!--begin::Logo-->
            <a href="{{ route('dashboard') }}" class="d-flex align-items-center">
                <img alt="Logo" src="{{ asset('assets_admin/media/logos/hubdata.png') }}" class="h-25px" />
            </a>
            <!--end::Logo-->
        </div>
        <!--end::Wrapper-->
        <!--begin::Toolbar wrapper-->
        <div class="d-flex flex-shrink-0">
            <!--begin::Notifications-->
            <div class="d-flex align-items-center ms-3">
                <!--begin::Menu wrapper-->
                <div class="cursor-pointer symbol symbol-40px position-relative me-5" 
                    data-kt-menu-trigger="click" 
                    data-kt-menu-overflow="true"
                    data-kt-menu-placement="top-start" 
                    data-bs-toggle="tooltip" 
                    data-bs-placement="right"
                    data-bs-dismiss="click" 
                    title="{{ $pendingFoldersCount > 0 ? 'You have ' . $pendingFoldersCount . ' project(s) to check.' : 'No new notifications' }}">
                    <!--begin::Svg Icon | path: [path-to-your-icon-folder]/bell.svg-->
                    <span class="svg-icon svg-icon-1">
                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none"
                            xmlns="http://www.w3.org/2000/svg">
                            <path d="M12 22C13.1 22 14 21.1 14 20H10C10 21.1 10.9 22 12 22ZM18 16V11C18 7.93 16.36 5.36 13.5 4.68V4C13.5 3.45 13.05 3 12.5 3C11.95 3 11.5 3.45 11.5 4V4.68C8.64 5.36 7 7.92 7 11V16L5 18V19H19V18L17 16Z"
                                fill="currentColor"></path>
                        </svg>
                    </span>
                    <!--end::Svg Icon-->

                    @if($pendingFoldersCount > 0)
                    <!-- Badge for pending notifications -->
                    <span class="badge bg-danger position-absolute top-0 start-100 translate-middle">
                        {{ $pendingFoldersCount }}
                    </span>
                    @endif
                </div>
            </div>
            <!--end::Notifications-->
            <!--begin::Chat-->
            <div class="d-flex align-items-center ms-3">
                <!--begin::Menu wrapper-->
                <div class="cursor-pointer symbol symbol-40px" data-kt-menu-trigger="click" data-kt-menu-overflow="true"
                    data-kt-menu-placement="top-start" data-bs-toggle="tooltip" data-bs-placement="right"
                    data-bs-dismiss="click" title="User profile">
                    <img src="{{ auth()->user()?->profile_image ?? asset('assets_admin/media/avatars/blank.png') }}" alt="user-profile" class="img-fluid" />
                </div>
                <!--begin::User account menu-->
                <div class="menu menu-sub menu-sub-dropdown menu-column menu-rounded menu-gray-800 menu-state-bg menu-state-color fw-semibold py-4 fs-6 w-275px"
                    data-kt-menu="true">
                    <!--begin::Menu item-->
                    <div class="menu-item px-3">
                        <div class="menu-content d-flex align-items-center px-3">
                            <!--begin::Avatar-->
                            <div class="symbol symbol-50px me-5">
                                <img src="{{ auth()->user()?->profile_image ?? asset('assets_admin/media/avatars/blank.png') }}" alt="user-profile" class="img-fluid" />
                            </div>                            
                            <!--end::Avatar-->
                            <!--begin::Username-->
                            <div class="d-flex flex-column">
                                <div class="fw-bold d-flex align-items-center fs-5">{{ ucwords(strtolower(auth()->user()?->name)) }}</div>
                                <a href="javascript:void;"
                                    class="fw-semibold text-muted text-hover-primary fs-7">{{ auth()->user()?->email }}
                                </a>
                                <div class="fw-bold d-flex fs-5">
                                    <span class="badge badge-primary">{{ Str::upper(auth()->user()?->roles->implode('name', ', ')) }}</span>
                                </div>
                            </div>
                            <!--end::Username-->
                        </div>
                    </div>
                    <!--end::Menu item-->
                    <!--begin::Menu separator-->
                    <div class="separator my-2"></div>
                    <!--end::Menu separator-->
                    <!--begin::Menu item-->
                    <div class="menu-item px-5">
                        <a href="{{ route('logout') }}" class="menu-link px-5"
                            onclick="event.preventDefault();document.getElementById('logout-form').submit();">Sign Out
                        </a>
                        <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                            @csrf
                        </form>
                    </div>
                    <!--end::Menu item-->
                </div>
            </div>
            <!--end::Chat-->
        </div>
        <!--end::Toolbar wrapper-->
    </div>
    <!--end::Container-->
</div>
