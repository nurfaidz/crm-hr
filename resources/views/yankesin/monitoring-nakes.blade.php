@extends('partials.template') 

@section('page_style')
    <style>
        div.dataTables_wrapper div.dataTables_filter label,
        div.dataTables_wrapper div.dataTables_length label {
            margin-left: 1.5rem;
            margin-right: 1.5rem;
        }

        div.dataTables_wrapper div.dataTables_paginate ul.pagination {
            margin-right: 1.5rem;
        }

        div.dataTables_wrapper .dataTables_info {
            margin-left: 1.5rem;
        }
    </style>
@endsection

@section('main')   
    <!-- BEGIN: Content-->
    <div class="app-content content ">
        <div class="content-overlay"></div>
        <div class="header-navbar-shadow"></div>
        <div class="content-wrapper">
            <div class="content-body">
                <h3 class="font-weight-bolder mb-1">Rekapitulasi Spesialis</h3>
                <div class="row">
                    <div class="col-xl-3 col-md-4 col-sm-6">
                        <div class="card bg-success">
                            <div class="card-header d-flex justify-content-between align-items-center">
                                <h4 class="card-title text-white">TNI AD</h4>
                                <img src="{{ url('app-assets/images/ico/AL.png')}}" width="25" height="25" class="cursor-pointer text-white">
                            </div>
                            <div class="card-body p-0">
                                <div class="row text-center mx-0">
                                    <div class="col-6 py-1 pl-0">
                                        <p class="card-text mb-0 text-white">Spesialis</p>
                                        <h3 class="font-weight-bolder mb-0 text-white">20</h3>
                                        <p class="mb-0 text-white">Orang</p>
                                    </div>
                                    <div class="col-6 py-1">
                                        <p class="card-text mb-0 text-white">Subspesialis</p>
                                        <h3 class="font-weight-bolder mb-0 text-white">0</h3>
                                        <p class="mb-0 text-white">Orang</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-xl-3 col-md-4 col-sm-6">
                        <div class="card" style="background-color: rgb(29, 85, 224)">
                            <div class="card-header d-flex justify-content-between align-items-center">
                                <h4 class="card-title text-white">TNI AL</h4>
                                <img src="{{ url('app-assets/images/ico/AL.png')}}" width="25" height="25" class="cursor-pointer text-white">
                            </div>
                            <div class="card-body p-0">
                                <div class="row text-center mx-0">
                                    <div class="col-6 py-1 pl-0">
                                        <p class="card-text mb-0 text-white">Spesialis</p>
                                        <h3 class="font-weight-bolder mb-0 text-white">20</h3>
                                        <p class="mb-0 text-white">Orang</p>
                                    </div>
                                    <div class="col-6 py-1">
                                        <p class="card-text mb-0 text-white">Subspesialis</p>
                                        <h3 class="font-weight-bolder mb-0 text-white">0</h3>
                                        <p class="mb-0 text-white">Orang</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-xl-3 col-md-4 col-sm-6">
                        <div class="card bg-info">
                            <div class="card-header d-flex justify-content-between align-items-center">
                                <h4 class="card-title text-white">TNI AU</h4>
                                <img src="{{ url('app-assets/images/ico/AU.png')}}" width="25" height="25" class="cursor-pointer text-white">
                            </div>
                            <div class="card-body p-0">
                                <div class="row text-center mx-0">
                                    <div class="col-6 py-1 pl-0">
                                        <p class="card-text mb-0 text-white">Spesialis</p>
                                        <h3 class="font-weight-bolder mb-0 text-white">20</h3>
                                        <p class="mb-0 text-white">Orang</p>
                                    </div>
                                    <div class="col-6 py-1">
                                        <p class="card-text mb-0 text-white">Subspesialis</p>
                                        <h3 class="font-weight-bolder mb-0 text-white">0</h3>
                                        <p class="mb-0 text-white">Orang</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-xl-3 col-md-12 col-sm-12">
                        <div class="card">
                            <div class="card-header d-flex justify-content-between align-items-center">
                                <h4 class="card-title">Pendidikan Spesialis</h4>
                            </div>
                            <div class="card-body p-0">
                                <div class="row text-center mx-0">
                                    <div class="col-4 py-1">
                                        <p class="card-text text-muted mb-0">TNI AD</p>
                                        <h3 class="font-weight-bolder mb-0">20</h3>
                                        <p class="text-muted mb-0">Orang</p>
                                    </div>
                                    <div class="col-4 py-1">
                                        <p class="card-text text-muted mb-0">TNI AL</p>
                                        <h3 class="font-weight-bolder mb-0">20</h3>
                                        <p class="text-muted mb-0">Orang</p>
                                    </div>
                                    <div class="col-4 py-1">
                                        <p class="card-text text-muted mb-0">TNI AU</p>
                                        <h3 class="font-weight-bolder mb-0">20</h3>
                                        <p class="text-muted mb-0">Orang</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <h3 class="font-weight-bolder mb-1">Usulan RS PPDS</h3>
                <!-- Remote Data -->
                <div class="row">
                    <div class="col-md-4 mb-1">
                        <label>Wilayah</label>
                        <div class="form-group">
                            <select class="select2-data-ajax form-control" id="wilayah-ajax"></select>
                        </div>
                    </div>
                    <!-- Remote Data -->
                    <div class="col-md-4 mb-1">
                        <label>Kesdam</label>
                        <div class="form-group">
                            <select class="select2-data-ajax form-control" id="kesdam-ajax"></select>
                        </div>
                    </div>
                </div>
                <!-- Multilingual -->
                <section id="multilingual-datatable">
                    <div class="row">
                        <div class="col-12">
                            <div class="card">
                                <div class="card-header border-bottom">
                                    <h4 class="card-title">Nasional</h4>
                                </div>
                                <div class="card-datatable">
                                    <table class="dt-nasional table">
                                        <thead>
                                            <tr>
                                                <th>Nama Rumkit</th>
                                                <th>Tingkat Akreditas</th>
                                                <th>Program PPDS</th>
                                                <th>Calon Pengajar Internal</th>
                                                <th class="text-center">Aksi</th>
                                            </tr>
                                        </thead>
                                        <thead>
                                            <th colspan="4" class="bg-light">Kesdam I/BB</th>
                                            <th>
                                                <button type="button" class="btn btn-primary btn-sm waves-effect waves-float waves-light" data-toggle="modal" data-target="#xlarge">
                                                    Detail
                                                </button>
                                                <!-- Modal -->
                                                <div class="modal fade text-left" id="xlarge" tabindex="-1" role="dialog" aria-labelledby="myModalLabel16" aria-hidden="true">
                                                    <div class="modal-dialog modal-dialog-centered" role="document">
                                                        <div class="modal-content">
                                                            <div class="modal-header">
                                                                <h4 class="modal-title" id="myModalLabel16">Modal</h4>
                                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                    <span aria-hidden="true">&times;</span>
                                                                </button>
                                                            </div>
                                                            <div class="modal-body">
                                                                <p></p>
                                                            </div>
                                                            <div class="modal-footer">
                                                                <button type="button" class="btn btn-primary" data-dismiss="modal">Accept</button>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </th>
                                        </thead>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </section>
                <!--/ Multilingual -->
            </div>
        </div>
    </div>
    <!-- END: Content-->
@endsection    

@section("page_script")
<script>
$( document ).ready(function() {
    $('#wilayah').select2({
    ajax: {
        url: '{{url("referensi/wilayah")}}',
        dataType: 'json',
        type: "GET",
        data:function(result){
            console.log("hasilnya "+result)
        }
    }
    });

    donat_chart("#bor-covid",[20,50],["Tersedia","Terisi"])
    donat_chart("#tt-icu",[70,20],["Tersedia","Terisi"])
    donat_chart("#tt-isolasi",[20,40],["Tersedia","Terisi"])

    bar_chart(".bor-covid-rs")
    bar_chart(".tt-covid-rs")

});


var flatPicker = $('.flat-picker'),
    isRtl = $('html').attr('data-textdirection') === 'rtl',
    grid_line_color = 'rgba(200, 200, 200, 0.2)',
    labelColor = '#6e6b7b',
    tooltipShadow = 'rgba(0, 0, 0, 0.25)',
    successColorShade = '#28dac6',
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
    pie:{
        terisi:'#1D55E0',
        tersedia:'#FF9F42'
    },
    area: {
        series3: '#a4f8cd',
        series2: '#60f2ca',
        series1: '#2bdac7'
    },
    line:{
        red:"#ff4961",
        grey:"#4F5D70",
        grey_light:"#EDF1F4",
        sky_blue:"#2b9bf4",
        blue:"#1D55E0",
        pink:"#F8D3FF",
        gray_blue:"#ACBBEA",
        success:"#2bdac7"
    }
};

function donat_chart(selector,series,labels){
        var bor_covid_element = document.querySelector(selector),
        bor_covid_config = {
        chart: {
            height: 250,
            type: 'pie'
        },
        colors: [chartColors.pie.tersedia, chartColors.pie.terisi],
        plotOptions: {
            radialBar: {
            size: 185,
            hollow: {
                size: '25%'
            },
            track: {
                margin: 15
            },
            dataLabels: {
                name: {
                    fontSize: '2rem',
                    fontFamily: 'Montserrat'
                },
                value: {
                    fontSize: '1rem',
                    fontFamily: 'Montserrat'
                },
                total: {
                    show: true,
                    fontSize: '1rem',
                    label: 'Comments',
                    formatter: function (w) {
                        return '80%';
                    }
                }
            }
            }
        },
        grid: {
            padding: {
            top: 10,
            right:-20
            }
        },
        legend: {
            show: true,
            position: 'right'
        },
        stroke: {
            lineCap: 'round'
        },
        series: series,
        labels: labels
        };
    if (typeof bor_covid_element !== undefined && bor_covid_element !== null) {
        var radialChart = new ApexCharts(bor_covid_element, bor_covid_config);
        radialChart.render();
    }
}


function bar_chart(selector) {
    var bar_chart_element = $(selector);

    if(bar_chart_element.length){
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
            xAxes: [
                {
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
                }
            ],
            yAxes: [
                {
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
                }
            ]
            }
        },
        data: {
            labels: ["Kesdam I/BB","Kesdam II/SWJ", "Kesdam III/SLW", "Kesdam IV/DIP", "Kesdam V/BRW","Kesdam VI/MLW","Kesdam IX/UDY", "Kesdam XII/TPR"],
            datasets: [
            {
                label:"Keterisian TT Covid",
                data: [275, 90, 190, 205, 125, 85, 55, 87],
                backgroundColor: chartColors.line.blue,
                borderColor: 'transparent'
            },
            {
                label:"Jumlah TT Covid",
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