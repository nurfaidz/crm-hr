@extends('partials.template')
@section('main')
<!-- BEGIN: Content-->
<div class="app-content content ">
    <div class="content-overlay"></div>
    <div class="header-navbar-shadow"></div>
    <div class="content-wrapper">
        <div class="content-body">
            <h3 class="font-weight-bolder mb-1">Rawat Jalan Kasus Suspek</h3>
            <!-- Line Chart Card -->
            <div class="row">
                <div class="col-lg-6 col-12">
                    <div class="card">
                        <div class="card-header d-flex justify-content-between pb-0">
                            <h4 class="card-title mb-2">Prajurit TNI</h4>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-sm-2 col-12 d-flex flex-column flex-wrap text-center">
                                    <span class="font-weight-bolder">Total</span>
                                    <h1 class="font-large-2 font-weight-bolder mt-0 mb-0">163</h1>
                                    <small class="text-muted"><i data-feather="repeat" class="text-warning"></i> 0 Kasus</small>
                                </div>
                                <div class="col-sm-10 col-12 d-flex justify-content-center">
                                    <div id="rawat-prajurit"></div>
                                </div>
                            </div>
                            <div class="d-flex justify-content-between mt-1">
                                <div class="text-center">
                                    <h5 class="font-weight-normal">MABES</h5>
                                    <h4 class="font-weight-bold">29</h4>
                                    <small class="text-muted"><i data-feather="repeat" class="text-warning"></i> 0 Kasus</small>
                                </div>
                                <div class="text-center">
                                    <h5 class="font-weight-normal">TNI AD</h5>
                                    <h4 class="font-weight-bold">29</h4>
                                    <small class="text-muted"><i data-feather="repeat" class="text-warning"></i> 0 Kasus</small>
                                </div>
                                <div class="text-center">
                                    <h5 class="font-weight-normal">TNI AU</h5>
                                    <h4 class="font-weight-bold">29</h4>
                                    <small class="text-muted"><i data-feather="repeat" class="text-warning"></i> 0 Kasus</small>
                                </div>
                                <div class="text-center">
                                    <h5 class="font-weight-normal">TNI AL</h5>
                                    <h4 class="font-weight-bold">29</h4>
                                    <small class="text-muted"><i data-feather="repeat" class="text-warning"></i> 0 Kasus</small>
                                </div>
                                <div class="text-center">
                                    <h5 class="font-weight-normal">Keluarga</h5>
                                    <h4 class="font-weight-bold">29</h4>
                                    <small class="text-muted"><i data-feather="repeat" class="text-warning"></i> 0 Kasus</small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>


                <div class="col-lg-6 col-12">
                    <div class="card">
                        <div class="card-header d-flex justify-content-between pb-0">
                            <h4 class="card-title mb-2">PNS TNI</h4>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-sm-2 col-12 d-flex flex-column flex-wrap text-center">
                                    <span class="font-weight-bolder">Total</span>
                                    <h1 class="font-large-2 font-weight-bolder mt-0 mb-0">163</h1>
                                    <small class="text-muted"><i data-feather="repeat" class="text-warning"></i> 0 Kasus</small>
                                </div>
                                <div class="col-sm-10 col-12 d-flex justify-content-center">
                                    <div id="rawat-pns"></div>
                                </div>
                            </div>
                            <div class="d-flex justify-content-between mt-1">
                                <div class="text-center">
                                    <h5 class="font-weight-normal">MABES</h5>
                                    <h4 class="font-weight-bold">29</h4>
                                    <small class="text-muted"><i data-feather="repeat" class="text-warning"></i> 0 Kasus</small>
                                </div>
                                <div class="text-center">
                                    <h5 class="font-weight-normal">TNI AD</h5>
                                    <h4 class="font-weight-bold">29</h4>
                                    <small class="text-muted"><i data-feather="repeat" class="text-warning"></i> 0 Kasus</small>
                                </div>
                                <div class="text-center">
                                    <h5 class="font-weight-normal">TNI AU</h5>
                                    <h4 class="font-weight-bold">29</h4>
                                    <small class="text-muted"><i data-feather="repeat" class="text-warning"></i> 0 Kasus</small>
                                </div>
                                <div class="text-center">
                                    <h5 class="font-weight-normal">TNI AL</h5>
                                    <h4 class="font-weight-bold">29</h4>
                                    <small class="text-muted"><i data-feather="repeat" class="text-warning"></i> 0 Kasus</small>
                                </div>
                                <div class="text-center">
                                    <h5 class="font-weight-normal">Keluarga</h5>
                                    <h4 class="font-weight-bold">29</h4>
                                    <small class="text-muted"><i data-feather="repeat" class="text-warning"></i> 0 Kasus</small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!--/ Line Chart Card -->


            <h3 class="font-weight-bolder mb-1">Probable (Pengawasan)</h3>
            <!-- Line Chart Card -->
            <div class="row">
                <div class="col-lg-6 col-12">
                    <div class="card">
                        <div class="card-header d-flex justify-content-between pb-0">
                            <h4 class="card-title mb-2">Prajurit TNI</h4>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-sm-2 col-12 d-flex flex-column flex-wrap text-center">
                                    <span class="font-weight-bolder">Total</span>
                                    <h1 class="font-large-2 font-weight-bolder mt-0 mb-0">163</h1>
                                    <small class="text-muted"><i data-feather="repeat" class="text-warning"></i> 0 Kasus</small>
                                </div>
                                <div class="col-sm-10 col-12 d-flex justify-content-center">
                                    <div id="probable-prajurit"></div>
                                </div>
                            </div>
                            <div class="d-flex justify-content-between mt-1">
                                <div class="text-center">
                                    <h5 class="font-weight-normal">MABES</h5>
                                    <h4 class="font-weight-bold">29</h4>
                                    <small class="text-muted"><i data-feather="repeat" class="text-warning"></i> 0 Kasus</small>
                                </div>
                                <div class="text-center">
                                    <h5 class="font-weight-normal">TNI AD</h5>
                                    <h4 class="font-weight-bold">29</h4>
                                    <small class="text-muted"><i data-feather="repeat" class="text-warning"></i> 0 Kasus</small>
                                </div>
                                <div class="text-center">
                                    <h5 class="font-weight-normal">TNI AU</h5>
                                    <h4 class="font-weight-bold">29</h4>
                                    <small class="text-muted"><i data-feather="repeat" class="text-warning"></i> 0 Kasus</small>
                                </div>
                                <div class="text-center">
                                    <h5 class="font-weight-normal">TNI AL</h5>
                                    <h4 class="font-weight-bold">29</h4>
                                    <small class="text-muted"><i data-feather="repeat" class="text-warning"></i> 0 Kasus</small>
                                </div>
                                <div class="text-center">
                                    <h5 class="font-weight-normal">Keluarga</h5>
                                    <h4 class="font-weight-bold">29</h4>
                                    <small class="text-muted"><i data-feather="repeat" class="text-warning"></i> 0 Kasus</small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>


                <div class="col-lg-6 col-12">
                    <div class="card">
                        <div class="card-header d-flex justify-content-between pb-0">
                            <h4 class="card-title mb-2">PNS TNI</h4>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-sm-2 col-12 d-flex flex-column flex-wrap text-center">
                                    <span class="font-weight-bolder">Total</span>
                                    <h1 class="font-large-2 font-weight-bolder mt-0 mb-0">163</h1>
                                    <small class="text-muted"><i data-feather="repeat" class="text-warning"></i> 0 Kasus</small>
                                </div>
                                <div class="col-sm-10 col-12 d-flex justify-content-center">
                                    <div id="probable-pns"></div>
                                </div>
                            </div>
                            <div class="d-flex justify-content-between mt-1">
                                <div class="text-center">
                                    <h5 class="font-weight-normal">MABES</h5>
                                    <h4 class="font-weight-bold">29</h4>
                                    <small class="text-muted"><i data-feather="repeat" class="text-warning"></i> 0 Kasus</small>
                                </div>
                                <div class="text-center">
                                    <h5 class="font-weight-normal">TNI AD</h5>
                                    <h4 class="font-weight-bold">29</h4>
                                    <small class="text-muted"><i data-feather="repeat" class="text-warning"></i> 0 Kasus</small>
                                </div>
                                <div class="text-center">
                                    <h5 class="font-weight-normal">TNI AU</h5>
                                    <h4 class="font-weight-bold">29</h4>
                                    <small class="text-muted"><i data-feather="repeat" class="text-warning"></i> 0 Kasus</small>
                                </div>
                                <div class="text-center">
                                    <h5 class="font-weight-normal">TNI AL</h5>
                                    <h4 class="font-weight-bold">29</h4>
                                    <small class="text-muted"><i data-feather="repeat" class="text-warning"></i> 0 Kasus</small>
                                </div>
                                <div class="text-center">
                                    <h5 class="font-weight-normal">Keluarga</h5>
                                    <h4 class="font-weight-bold">29</h4>
                                    <small class="text-muted"><i data-feather="repeat" class="text-warning"></i> 0 Kasus</small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!--/ Line Chart Card -->
        </div>
    </div>
</div>
<!-- END: Content-->
@endsection

@section("page_script")
<script>
    $(document).ready(function() {
        $('#wilayah').select2({
            ajax: {
                url: '{{url("referensi/wilayah")}}',
                dataType: 'json',
                type: "GET",
                data: function(result) {
                    console.log("hasilnya " + result)
                }
            }
        });

        line_chart("#rawat-prajurit", [20, 50], ["Tersedia", "Terisi"])
        line_chart("#rawat-pns", [20, 50], ["Tersedia", "Terisi"])
        line_chart("#probable-prajurit", [20, 50], ["Tersedia", "Terisi"])
        line_chart("#probable-pns", [20, 50], ["Tersedia", "Terisi"])

        bar_chart(".bor-covid-rs")
        bar_chart(".tt-covid-rs")

    });


    var flatPicker = $('.flat-picker'),
        isRtl = $('html').attr('data-textdirection') === 'rtl',
        grid_line_color = 'rgba(200, 200, 200, 0.2)',
        labelColor = '#6e6b7b',
        tooltipShadow = 'rgba(0, 0, 0, 0.25)',
        successColorShade = '#28dac6',
        $trackBgColor = '#EBEBEB',
        chartColors = {
            column: {
                series1: '#826af9',
                series2: '#d2b0ff',
                bg: '#f8d3ff'
            },
            success: {
                shade_100: '#7eefc7',
                shade_200: '#06774f'
            },
            donut: {
                series1: '#ffe700',
                series2: '#00d4bd',
                series3: '#826bf8',
                series4: '#2b9bf4',
                series5: '#FFA1A1'
            },
            pie: {
                terisi: '#1D55E0',
                tersedia: '#FF9F42'
            },
            area: {
                series3: '#a4f8cd',
                series2: '#60f2ca',
                series1: '#2bdac7'
            },
            line: {
                red: "#ff4961",
                grey: "#4F5D70",
                grey_light: "#EDF1F4",
                sky_blue: "#2b9bf4",
                blue: "#1D55E0",
                pink: "#F8D3FF",
                gray_blue: "#ACBBEA",
                success: "#2bdac7"
            }
        };

    function line_chart(selector, series, labels) {
        var bor_covid_element = document.querySelector(selector),
            bor_covid_config = {
                chart: {
                    height: 100,
                    type: 'line',
                    toolbar: {
                        show: false
                    },
                    zoom: {
                        enabled: false
                    }
                },
                grid: {
                    borderColor: $trackBgColor,
                    strokeDashArray: 5,
                    xaxis: {
                        lines: {
                            show: true
                        }
                    },
                    yaxis: {
                        lines: {
                            show: false
                        }
                    },
                    padding: {
                        top: -30,
                        bottom: -10
                    }
                },
                stroke: {
                    width: 3
                },
                colors: [window.colors.solid.info],
                series: [{
                    data: [0, 20, 5, 30, 15, 45,88,23,5,34,21,57,43,2]
                }],
                markers: {
                    size: 2,
                    colors: window.colors.solid.info,
                    strokeColors: window.colors.solid.info,
                    strokeWidth: 2,
                    strokeOpacity: 1,
                    strokeDashArray: 0,
                    fillOpacity: 1,
                    discrete: [{
                        seriesIndex: 0,
                        dataPointIndex: 13,
                        fillColor: '#ffffff',
                        strokeColor: window.colors.solid.info,
                        size: 5
                    }],
                    shape: 'circle',
                    radius: 2,
                    hover: {
                        size: 3
                    }
                },
                xaxis: {
                    labels: {
                        show: true,
                        style: {
                            fontSize: '0px'
                        }
                    },
                    axisBorder: {
                        show: false
                    },
                    axisTicks: {
                        show: false
                    }
                },
                yaxis: {
                    show: false
                },
                tooltip: {
                    x: {
                        show: false
                    }
                }
            };
        if (typeof bor_covid_element !== undefined && bor_covid_element !== null) {
            var radialChart = new ApexCharts(bor_covid_element, bor_covid_config);
            radialChart.render();
        }
    }


    function bar_chart(selector) {
        var bar_chart_element = $(selector);

        if (bar_chart_element.length) {
            var barChartExample = new Chart(bar_chart_element, {
                type: 'horizontalBar',
                options: {
                    elements: {
                        rectangle: {
                            borderWidth: 2,
                            borderSkipped: 'right'
                        }
                    },
                    responsive: true,
                    maintainAspectRatio: false,
                    responsiveAnimationDuration: 500,
                    legend: {
                        display: true,

                    },
                    tooltips: {
                        // Updated default tooltip UI
                        shadowOffsetX: 1,
                        shadowOffsetY: 1,
                        shadowBlur: 8,
                        shadowColor: tooltipShadow,
                        backgroundColor: window.colors.solid.white,
                        titleFontColor: window.colors.solid.black,
                        bodyFontColor: window.colors.solid.black
                    },
                    scales: {
                        xAxes: [{
                            barThickness: 15,
                            display: true,
                            gridLines: {
                                display: true,
                                color: grid_line_color,
                                zeroLineColor: grid_line_color
                            },
                            scaleLabel: {
                                display: false
                            },
                            ticks: {
                                fontColor: labelColor
                            }
                        }],
                        yAxes: [{
                            display: true,
                            gridLines: {
                                color: grid_line_color,
                                zeroLineColor: grid_line_color
                            },
                            ticks: {
                                stepSize: 100,
                                min: 0,
                                max: 400,
                                fontColor: labelColor
                            }
                        }]
                    }
                },
                data: {
                    labels: ["Kesdam I/BB", "Kesdam II/SWJ", "Kesdam III/SLW", "Kesdam IV/DIP", "Kesdam V/BRW", "Kesdam VI/MLW", "Kesdam IX/UDY", "Kesdam XII/TPR"],
                    datasets: [{
                            label: "Keterisian TT Covid",
                            data: [275, 90, 190, 205, 125, 85, 55, 87],
                            backgroundColor: chartColors.line.blue,
                            borderColor: 'transparent'
                        },
                        {
                            label: "Jumlah TT Covid",
                            data: [34, 33, 21, 34, 56, 78, 212, 34],
                            backgroundColor: chartColors.line.red,
                            borderColor: 'transparent'
                        }
                    ]
                }
            });
        }
    }
</script>
@endsection