<x-master-layout>
    <div class="row g-5 g-xl-10">
        <!--begin::Col-->
        <div class="col-lg-12">
            <!--begin::Timeline widget 3-->
            <div class="card h-md-100">
                <!--begin::Header-->
                <div class="card-header border-0 pt-5">
                    <h3 class="card-title align-items-start flex-column">
                        <span class="card-label fw-bold text-dark">Ringkasan Program</span>
                    </h3>
                    <!--begin::Toolbar-->
                    <div class="card-toolbar">
                        <!-- Button to trigger the collapse -->
                        <button class="btn btn-danger" type="button" data-bs-toggle="collapse" data-bs-target="#filter-form-wrapper" aria-expanded="false" aria-controls="filter-form-wrapper">Filter</button>
                    </div>
                    <!--end::Toolbar-->
                </div>
                <!--end::Header-->
                <div class="card-body">
                    <div class="collapse" id="filter-form-wrapper">
                        <form id="filter-form" action="{{ route('dashboard') }}">
                            <div class="row mb-5">
                                <div class="col-lg-4 col-md-4 col-sm-12 mb-5">
                                    <select name="statusProject[]" class="form-select me-5" data-control="select2" data-close-on-select="false" data-placeholder="Pemantauan" data-allow-clear="true" multiple="multiple">
                                        <option value="all" {{ in_array('all', request('statusProject', [])) ? 'selected' : '' }}>All</option>
                                        @foreach ($statusProjects as $statusProject)
                                            <option value="{{ $statusProject->id }}" {{ in_array($statusProject->id, request('statusProject', [])) ? 'selected' : '' }}>
                                                {{ $statusProject->name }}
                                            </option>
                                        @endforeach
                                    </select> 
                                </div>

                                <div class="col-lg-4 col-md-4 col-sm-12 mb-5">
                                    <select name="categories[]" class="form-select me-5" data-control="select2" data-close-on-select="false" data-placeholder="Kategori" data-allow-clear="true" multiple="multiple">
                                        <option value="all" {{ in_array('all', request('categories', [])) ? 'selected' : '' }}>All</option>
                                        @foreach ($categoryList as $category)
                                            <option value="{{ $category->id }}" {{ in_array($category->id, request('categories', [])) ? 'selected' : '' }}>
                                                {{ $category->name }}
                                            </option>
                                        @endforeach
                                    </select> 
                                </div>
                                    
                                <div class="col-lg-4 col-md-4 col-sm-12 mb-5">
                                    <select name="clusters[]" class="form-select me-5" data-control="select2" data-close-on-select="false" data-placeholder="Kluster" data-allow-clear="true" multiple="multiple">
                                        <option value="all" {{ in_array('all', request('clusters', [])) ? 'selected' : '' }}>All</option>
                                        @foreach ($clustersList as $cluster)
                                            <option value="{{ $cluster->id }}" {{ in_array($cluster->id, request('clusters', [])) ? 'selected' : '' }}>
                                                {{ $cluster->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <div class="row mb-5">
                                <div class="col-lg-4 col-md-4 col-sm-12 mb-5">
                                    <select name="statusDokumen[]" class="form-select me-5" data-control="select2" data-close-on-select="false" data-placeholder="Status Dokumen" data-allow-clear="true" multiple="multiple">
                                        <option value="all" {{ in_array('all', request('statusDokumen', [])) ? 'selected' : '' }}>All</option>
                                        @foreach ($statusDokumens as $statusDokumen)
                                            <option value="{{ $statusDokumen }}" {{ in_array($statusDokumen, request('statusDokumen', [])) ? 'selected' : '' }}>
                                                {{ $statusDokumen }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="col-lg-4 col-md-4 col-sm-12 mb-5">
                                    <select name="year" class="form-select me-5" data-control="select2" data-close-on-select="true" data-placeholder="Tahun">
                                        <option value="all" {{ request('year') == 'all' ? 'selected' : '' }}>All</option>
                                        @foreach ($years as $yr)
                                            <option value="{{ $yr }}" {{ request('year') == $yr ? 'selected' : '' }}>
                                                {{ $yr }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <div class="row mb-5">
                                <div class="col-lg-4 col-md-4 col-sm-12 mb-5">
                                    <input onclick="document.getElementById('filter-form').submit()" type="submit" value="Submit" class="btn btn-primary">
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
                <!--begin::Body-->
                <div class="card-body pt-7">
                    <!--begin::Tab Content (ishlamayabdi)-->
                    <div class="d-flex flex-row justify-content-evenly mb-10">
                        <div class="d-flex flex-column">
                            <div class="fs-3 text-muted">JUMLAH KESELURUHAN PROJEK</div>
                            <div class="fs-1 text-dark fw-bold text-center">{{ $countProjects }}</div>
                            <span class="text-gray-500 fw-semibold fs-6">{{ request('year') != 'all' ? request('year') : '' }}</span>
                            <div class="col">
                                <div class="separator separator-dashed my-3"></div>
                                <div class="d-flex flex-stack">
                                    <div class="text-gray-700 fw-semibold fs-6 me-2">Trackable</div>
                                    <div class="d-flex align-items-center">
                                        <span class="text-gray-900 fw-bolder fs-6">{{ $countTrackable }}</span>
                                    </div>
                                </div>
                                <div class="separator separator-dashed my-3"></div>
                            
                                <div class="d-flex flex-stack">
                                    <div class="text-gray-700 fw-semibold fs-6 me-2">Non-Trackable</div>
                                    <div class="d-flex align-items-center">
                                        <span class="text-gray-900 fw-bolder fs-6">{{ $countNonTrackable }}</span>
                                    </div>
                                </div>
                                <div class="separator separator-dashed my-3"></div>
                            </div> 
                        </div>
                        <div class="d-flex flex-column">
                            <div class="fs-3 text-muted">JUMLAH PENGGUNA</div>
                            <div class="fs-1 text-dark fw-bold text-center">{{ $countUsers }}</div>
                            <span class="text-gray-500 fw-semibold fs-6">{{ request('year') != 'all' ? request('year') : '' }}</span>
                            <div class="col">
                                <div class="separator separator-dashed my-3"></div>
                                <div class="d-flex flex-stack">
                                    <div class="text-gray-700 fw-semibold fs-6 me-2">Diterima</div>
                                    <div class="d-flex align-items-center">
                                        <span class="text-gray-900 fw-bolder fs-6">{{ $countApproved }}</span>
                                    </div>
                                </div>
                                <div class="separator separator-dashed my-3"></div>
                            
                                <div class="d-flex flex-stack">
                                    <div class="text-gray-700 fw-semibold fs-6 me-2">Ditolak</div>
                                    <div class="d-flex align-items-center">
                                        <span class="text-gray-900 fw-bolder fs-6">{{ $countRejected }}</span>
                                    </div>
                                </div>
                                <div class="separator separator-dashed my-3"></div>
                            
                                <div class="d-flex flex-stack">
                                    <div class="text-gray-700 fw-semibold fs-6 me-2">Dalam Proses</div>
                                    <div class="d-flex align-items-center">
                                        <span class="text-gray-900 fw-bolder fs-6">{{ $countPending }}</span>
                                    </div>
                                </div>
                                <div class="separator separator-dashed my-3"></div>
                            
                                <div class="d-flex flex-stack">
                                    <div class="text-gray-700 fw-semibold fs-6 me-2">Tidak Aktif</div>
                                    <div class="d-flex align-items-center">
                                        <span class="text-gray-900 fw-bolder fs-6">{{ $countDeleted }}</span>
                                    </div>
                                </div>
                                <div class="separator separator-dashed my-3"></div>
                            </div>                            
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-xl-6 col-lg-12 mb-5">
                            <div class="bg-gray-100 bg-opacity-70 rounded-2 px-6 py-5">
                                <!--begin::Symbol-->
                                <div class="fs-3 fw-bold me-5">
                                    Status Dokumen
                                </div>
                                <!--end::Symbol-->
                                <!--begin::Stats-->
                                <div class="m-0">
                                    <div id="lengkap" style="height: auto;"></div>
                                    <!--begin::Number-->
                                </div>
                                <!--end::Stats-->
                            </div>
                        </div>
                        <div class="col-xl-6 col-lg-12 mb-5">
                            <div class="bg-gray-100 bg-opacity-70 rounded-2 px-6 py-5">
                                <!--begin::Symbol-->
                                <div class="fs-3 fw-bold me-5">
                                    Status Tempoh Serahan
                                </div>
                                <!--end::Symbol-->
                                <!--begin::Stats-->
                                <div class="m-0">
                                    <div id="projek_tertunggak" style="height: auto;"></div>
                                    <!--begin::Number-->
                                </div>
                                <!--end::Stats-->
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-12 mb-5">
                            <div class="bg-gray-100 bg-opacity-70 rounded-2 px-6 py-5">
                                <!--begin::Symbol-->
                                <div class="fs-3 fw-bold me-5">
                                    Jumlah Projek Mengikut Kluster
                                </div>
                                <!--end::Symbol-->
                                <!--begin::Stats-->
                                <div class="m-0">
                                    <div id="cluster" style="height: 350px;"></div>
                                    <!--begin::Number-->
                                </div>
                                <!--end::Stats-->
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-12 mb-5">
                            <div class="bg-gray-100 bg-opacity-70 rounded-2 px-6 py-5">
                                <!--begin::Symbol-->
                                <div class="fs-3 fw-bold me-5">
                                    Jumlah Projek Mengikut Kategori Data
                                </div>
                                <!--end::Symbol-->
                                <!--begin::Stats-->
                                <div class="m-0">
                                    <div id="category" style="height: 350px;"></div>
                                    <!--begin::Number-->
                                </div>
                                <!--end::Stats-->
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-12 mb-5">
                            <div class="bg-gray-100 bg-opacity-70 rounded-2 px-6 py-5">
                                <!--begin::Symbol-->
                                <div class="fs-3 fw-bold me-5">
                                    Jumlah Projek Mengikut Jenis
                                </div>
                                <!--end::Symbol-->
                                <!--begin::Stats-->
                                <div class="m-0">
                                    <div id="type" style="height: 350px;"></div>
                                    <!--begin::Number-->
                                </div>
                                <!--end::Stats-->
                            </div>
                        </div>
                    </div>
                </div>
                <!--end: Card Body-->
            </div>
            <!--end::Timeline widget 3-->
        </div>
        <!--end::Col-->
    </div>
    <!--end::Row-->
    @push('script')
        <script>
            $(document).ready(function() {
                var dataTempohMuatNaik = @json($dataTempohMuatNaik);
                var labelTempohMuatNaik = @json($labelTempohMuatNaik);

                var dataStatus = @json($dataStatus);
                var labelStatus = @json($labelStatus);

                var categoryCounts = @json($categoryCounts);
                var categoryLabels = @json($categoryLabels);

                var clusterCounts = @json($clusterCounts);
                var clusterLabels = @json($clusterLabels);

                var typeCounts = @json($typeCounts);
                var typeLabels = @json($typeLabels);

                var labelColor = KTUtil.getCssVariableValue('--kt-gray-500');
                var borderColor = KTUtil.getCssVariableValue('--kt-gray-200');
                var baseColor = KTUtil.getCssVariableValue('--kt-info');
                var lightColor = KTUtil.getCssVariableValue('--kt-info-light');
                var dangerColor = KTUtil.getCssVariableValue('--kt-danger');
                var primaryColor = KTUtil.getCssVariableValue('--kt-primary');
                var warningColor = KTUtil.getCssVariableValue('--kt-warning');
                var secondaryColor = KTUtil.getCssVariableValue('--kt-gray-300');

                var projek_tunggak = {
                    series: dataTempohMuatNaik,
                    chart: {
                        type: 'donut',
                    },
                    labels: labelTempohMuatNaik,
                    responsive: [{
                        breakpoint: 380,
                        options: {
                            chart: {
                                width: 100
                            },
                            legend: {
                                position: 'bottom'
                            }
                        }
                    }],
                    colors: [primaryColor, dangerColor]
                };

                var projek_lengkap = {
                    series: dataStatus,
                    chart: {
                        type: 'donut',
                    },
                    labels: labelStatus,
                    responsive: [{
                        breakpoint: 380,
                        options: {
                            chart: {
                                width: 100
                            },
                            legend: {
                                position: 'bottom'
                            }
                        }
                    }],
                    colors: [primaryColor, dangerColor],
                };

                var category = {
                    series: [{
                        name: 'Bil. Projek',
                        data: categoryCounts
                    }],
                    chart: {
                        fontFamily: 'inherit',
                        type: 'area',
                        height: 350,
                        toolbar: {
                            show: false
                        }
                    },
                    plotOptions: {

                    },
                    legend: {
                        show: false
                    },
                    dataLabels: {
                        enabled: false
                    },
                    fill: {
                        type: 'solid',
                        opacity: 1
                    },
                    stroke: {
                        curve: 'smooth',
                        show: true,
                        width: 3,
                        colors: [baseColor]
                    },
                    xaxis: {
                        categories: categoryLabels,
                        axisBorder: {
                            show: false,
                        },
                        axisTicks: {
                            show: false
                        },
                        labels: {
                            style: {
                                colors: labelColor,
                                fontSize: '12px'
                            },
                            show: false
                        },
                        crosshairs: {
                            position: 'front',
                            stroke: {
                                color: baseColor,
                                width: 1,
                                dashArray: 3
                            }
                        },
                        tooltip: {
                            enabled: true,
                            formatter: undefined,
                            offsetY: 0,
                            style: {
                                fontSize: '12px'
                            }
                        }
                    },
                    yaxis: {
                        labels: {
                            style: {
                                colors: labelColor,
                                fontSize: '12px'
                            }
                        }
                    },
                    states: {
                        normal: {
                            filter: {
                                type: 'none',
                                value: 0
                            }
                        },
                        hover: {
                            filter: {
                                type: 'none',
                                value: 0
                            }
                        },
                        active: {
                            allowMultipleDataPointsSelection: false,
                            filter: {
                                type: 'none',
                                value: 0
                            }
                        }
                    },
                    tooltip: {
                        style: {
                            fontSize: '12px'
                        },
                        y: {
                            formatter: function(val) {
                                return val
                            }
                        }
                    },
                    colors: [lightColor],
                    grid: {
                        borderColor: borderColor,
                        strokeDashArray: 4,
                        yaxis: {
                            lines: {
                                show: true
                            }
                        }
                    },
                    markers: {
                        strokeColor: baseColor,
                        strokeWidth: 3
                    }
                };

                var cluster = {
                    series: [{
                        name: 'Bil. Projek',
                        data: clusterCounts
                    }],
                    chart: {
                        fontFamily: 'inherit',
                        type: 'bar',
                        height: 350,
                        toolbar: {
                            show: false
                        }
                    },
                    plotOptions: {
                        bar: {
                            horizontal: false,
                            columnWidth: ['30%'],
                            endingShape: 'rounded',
                            borderRadius: 10,
                            dataLabels: {
                                position: 'top', // top, center, bottom
                            },
                        },
                    },
                    legend: {
                        show: false
                    },
                    dataLabels: {
                        enabled: true,
                        formatter: function(val) {
                            return val;
                        },
                        offsetY: -20,
                        style: {
                            fontSize: '12px',
                            colors: ["#304758"]
                        }
                    },
                    stroke: {
                        show: true,
                        width: 2,
                        colors: ['transparent']
                    },
                    xaxis: {
                        categories: clusterLabels,
                        axisBorder: {
                            show: false,
                        },
                        axisTicks: {
                            show: false
                        },
                        labels: {
                            style: {
                                colors: labelColor,
                                fontSize: '12px'
                            }
                        },
                    },
                    yaxis: {
                        axisBorder: {
                            show: false
                        },
                        axisTicks: {
                            show: false,
                        },
                        labels: {
                            style: {
                                colors: labelColor,
                                fontSize: '12px'
                            }
                        }
                    },
                    fill: {
                        opacity: 1
                    },
                    states: {
                        normal: {
                            filter: {
                                type: 'none',
                                value: 0
                            }
                        },
                        hover: {
                            filter: {
                                type: 'none',
                                value: 0
                            }
                        },
                        active: {
                            allowMultipleDataPointsSelection: false,
                            filter: {
                                type: 'none',
                                value: 0
                            }
                        }
                    },
                    tooltip: {
                        style: {
                            fontSize: '12px'
                        },
                        y: {
                            formatter: function(val) {
                                return val
                            }
                        }
                    },
                    colors: [dangerColor],
                    grid: {
                        borderColor: borderColor,
                        strokeDashArray: 4,
                        yaxis: {
                            lines: {
                                show: true
                            }
                        }
                    }
                };

                var type = {
                    series: [{
                        name: 'Bil. Projek',
                        data: typeCounts
                    }],
                    chart: {
                        fontFamily: 'inherit',
                        type: 'bar',
                        height: 350,
                        toolbar: {
                            show: false
                        }
                    },
                    plotOptions: {
                        bar: {
                            horizontal: true,
                            endingShape: 'rounded',
                            borderRadius: 10,
                            dataLabels: {
                                position: 'top',
                            },
                        }
                    },
                    dataLabels: {
                        enabled: true,
                        offsetX: -6,
                        style: {
                            fontSize: '12px',
                            colors: ['#fff']
                        }
                    },
                    stroke: {
                        show: true,
                        width: 1,
                        colors: ['#fff']
                    },
                    tooltip: {
                        shared: true,
                        intersect: false
                    },
                    xaxis: {
                        categories: typeLabels,
                        axisBorder: {
                            show: false,
                        },
                        axisTicks: {
                            show: false
                        },
                        labels: {
                            style: {
                                colors: labelColor,
                                fontSize: '12px'
                            }
                        }
                    },
                    yaxis: {
                        labels: {
                            style: {
                                colors: labelColor,
                                fontSize: '12px'
                            }
                        }
                    },
                    fill: {
                        opacity: 1
                    },
                    states: {
                        normal: {
                            filter: {
                                type: 'none',
                                value: 0
                            }
                        },
                        hover: {
                            filter: {
                                type: 'none',
                                value: 0
                            }
                        },
                        active: {
                            allowMultipleDataPointsSelection: false,
                            filter: {
                                type: 'none',
                                value: 0
                            }
                        }
                    },
                    tooltip: {
                        style: {
                            fontSize: '12px'
                        },
                        y: {
                            formatter: function(val) {
                                return val
                            }
                        }
                    },
                    colors: [primaryColor],
                    grid: {
                        borderColor: borderColor,
                        strokeDashArray: 4,
                        yaxis: {
                            lines: {
                                show: true
                            }
                        }
                    }
                };

                new ApexCharts(document.getElementById('projek_tertunggak'), projek_tunggak).render();
                new ApexCharts(document.getElementById('lengkap'), projek_lengkap).render();
                new ApexCharts(document.getElementById('category'), category).render();
                new ApexCharts(document.getElementById('cluster'), cluster).render();
                new ApexCharts(document.getElementById('type'), type).render();

            });
        </script>
    @endpush
</x-master-layout>
