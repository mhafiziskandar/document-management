<div>
    <div class="card card-xxl-stretch mb-5 mb-xl-8">
        <!--begin::Card header-->
        <div class="card-header border-0">
            <!--begin::Title-->
            @isset($title)
                <div class="card-title align-items-start flex-column">
                    <h3>{{ $title }}</h3>
                </div>
            @endisset
            <!--end::Title-->
            @if (!empty($toolbar))
                <div class="card-toolbar">
                    <a href="{{ $link }}" class="btn btn-sm btn-info" target="_blank">
                        {{ $toolbar }}
                    </a>
                </div>
            @endif
        </div>
        <!--end::Card header-->
        <!--begin::Card body-->
        <div class="card-body py-3">
            {{ $slot }}
        </div>
        <!--end::Card body-->

        @isset($footer)
            <div class="card-footer">
                {!! $footer !!}
            </div>
        @endisset
    </div>
</div>
