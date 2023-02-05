@extends('partials.template')
@section('main')
    <!-- BEGIN CONTENT -->
    <div class="app-content content ">
        <div class="content-overlay"></div>
        <div class="header-navbar-shadow"></div>
        <!-- BEGIN CONTENT WRAPPER -->
        <div class="content-wrapper">
            <!-- BEGIN CONTENT BODY -->
            <div class="content-body">
                <!-- BEGIN CARD -->
                <div class="card">
                    <!-- BEGIN CARD BODY -->
                    <div class="card-body">
                        <h3 class="font-weight-bold mb-2">Leave Detail Employee</h3>

                        <!-- BEGIN CUSTOM FILTER -->
                        <section class="row mb-2">
                            <div class="col-12">
                                <div class="shadow rounded p-2">
                                    <p class="employee hidden">{{ $employee->employee_id }}</p>
                                    <h5 class="mb-1">{{ $employee->first_name }} {{ $employee->last_name }}
                                        Statistic</h5>
                                    <div class="row">
                                        <div class="col-lg-3 col-md-6 col-sm-12 mb-lg-0 mb-1">
                                            <div class="row">
                                                <div class="col-3 text-center m-auto p-0">
                                                    <div class="avatar p-75 m-0" style="background-color: #7367F0">
                                                        <div class="avatar-content">
                                                            <svg width="22" height="20" viewBox="0 0 22 20"
                                                                fill="none" xmlns="http://www.w3.org/2000/svg">
                                                                <path d="M8.5 6V12L13.2 14.9L14 13.7L10 11.3V6H8.5Z"
                                                                    fill="#F6F5FB" />
                                                                <path
                                                                    d="M16.42 10C16.47 10.33 16.5 10.66 16.5 11C16.5 14.9 13.4 18 9.5 18C5.6 18 2.5 14.9 2.5 11C2.5 7.1 5.6 4 9.5 4C10.2 4 10.87 4.1 11.5 4.29V2.23C10.86 2.08 10.19 2 9.5 2C4.5 2 0.5 6 0.5 11C0.5 16 4.5 20 9.5 20C14.5 20 18.5 16 18.5 11C18.5 10.66 18.48 10.33 18.44 10H16.42Z"
                                                                    fill="#F6F5FB" />
                                                                <path d="M18.5 3V0H16.5V3H13.5V5H16.5V8H18.5V5H21.5V3H18.5Z"
                                                                    fill="#F6F5FB" />
                                                            </svg>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-9 p-0 my-auto">
                                                    <h4 class="m-0 font-weight-bolder">{{ count($annualLeave) }}</h4>
                                                    <p class="m-0">Annual Leave Balance</p>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-lg-3 col-md-6 col-sm-12 mb-lg-0 mb-1">
                                            <div class="row">
                                                <div class="col-3 text-center m-auto p-0">
                                                    <div class="avatar p-75 m-0" style="background-color: #FF9F43">
                                                        <div class="avatar-content">
                                                            <svg width="20" height="20" viewBox="0 0 20 20"
                                                                fill="none" xmlns="http://www.w3.org/2000/svg">
                                                                <path
                                                                    d="M18 4H14V2C14 0.9 13.1 0 12 0H8C6.9 0 6 0.9 6 2V4H2C0.9 4 0 4.9 0 6V18C0 19.1 0.9 20 2 20H18C19.1 20 20 19.1 20 18V6C20 4.9 19.1 4 18 4ZM8 2H12V4H8V2ZM18 18H2V6H18V18Z"
                                                                    fill="white" />
                                                                <path d="M11 8H9V11H6V13H9V16H11V13H14V11H11V8Z"
                                                                    fill="white" />
                                                            </svg>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-9 p-0 my-auto">
                                                    <h4 class="m-0 font-weight-bolder">{{ count($sickLeave) }}</h4>
                                                    <p class="m-0">Sick Leave</p>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-lg-3 col-md-6 col-sm-12 mb-lg-0 mb-1">
                                            <div class="row">
                                                <div class="col-3 text-center m-auto p-0">
                                                    <div class="avatar p-75 m-0" style="background-color: #28C76F">
                                                        <div class="avatar-content">
                                                            <svg width="14" height="16" viewBox="0 0 14 16"
                                                                fill="none" xmlns="http://www.w3.org/2000/svg">
                                                                <path
                                                                    d="M0 14H14V16H0V14ZM4.6 11.3L0 6.7L2 4.8L4.6 7.4L12 0L14 2L4.6 11.3Z"
                                                                    fill="white" />
                                                            </svg>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-9 p-0 my-auto">
                                                    <h4 class="m-0 font-weight-bolder">{{ count($approveLeave) }}</h4>
                                                    <p class="m-0">Number of Approved</p>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-lg-3 col-md-6 col-sm-12 mb-lg-0 mb-1">
                                            <div class="row">
                                                <div class="col-3 text-center m-auto p-0">
                                                    <div class="avatar p-75 m-0" style="background-color: #EA5455">
                                                        <div class="avatar-content">
                                                            <svg width="14" height="14" viewBox="0 0 14 14"
                                                                fill="none" xmlns="http://www.w3.org/2000/svg">
                                                                <path
                                                                    d="M14 1.41L12.59 0L7 5.59L1.41 0L0 1.41L5.59 7L0 12.59L1.41 14L7 8.41L12.59 14L14 12.59L8.41 7L14 1.41Z"
                                                                    fill="white" />
                                                            </svg>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-9 p-0 my-auto">
                                                    <h4 class="m-0 font-weight-bolder">{{ count($rejectLeave) }}</h4>
                                                    <p class="m-0">Number of Rejects</p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </section>
                        <!-- END CUSTOM FILTER -->

                        <!-- BEGIN TABLE -->
                        <section>
                            <div class="row">
                                <div class="table-responsive col-12 card-datatable table-rounded">
                                    <table class="table table-borderless table-hover" id="ajax-datatables">
                                        <thead>
                                            <tr>
                                                <th>Leave Type</th>
                                                <th>Total Days</th>
                                                <th>Start Date</th>
                                                <th>End Date</th>
                                                <th>Status</th>
                                                <th>Detail</th>
                                            </tr>
                                        </thead>
                                    </table>
                                </div>
                            </div>
                        </section>
                        <!-- END TABLE -->

                    </div>
                    <!-- END CARD BODY -->
                </div>
                <!-- END CARD -->
            </div>
            <!-- END CONTENT BODY -->
        </div>
        <!-- END CONTENT WRAPPER -->
    </div>
    <!-- END CONTENT -->

    <div class="modal fade text-left" id="modal-notes" tabindex="-1" role="dialog" aria-labelledby="modal-title"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="modal-title">Notes</h4>
                    <button type="button" class="close mr-0 mt-0" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="form-group mt-1">
                        <textarea id="text-notes" name="text-notes" cols="30" rows="5" class="form-control bg-white" disabled></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-primary" data-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade text-left" id="modal-files" tabindex="-1" role="dialog" aria-labelledby="modal-title"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="modal-title">Detail Files</h4>
                    <button type="button" class="close mr-0 mt-0" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="form-group mt-1 detail-files-container"></div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-primary" data-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('page_js')
    <script src="{{ url('app-assets/vendors/js/forms/validation/jquery.validate.min.js') }}"></script>
    <script src="{{ url('app-assets/vendors/js/extensions/sweetalert2.all.min.js') }}"></script>
@endsection

@section('page_script')
    @if (session()->has('flash_message'))
        <script>
            $(document).ready(function() {
                Swal.fire({
                    title: "{{ session('flash_message') }}",
                    text: "Suceess message sent!!",
                    icon: "success",
                    button: "Ok",
                    timer: 2000
                });
            });
        </script>
    @endif

    <script>
        let status = 0;
        let date = 0;

        /**
         * 
         * Fetch data and view datatable leavereport.
         * 
         */
        const employeeId = document.querySelector('.employee').textContent;

        const dataTable_Ajax = (status, date) => {
            const dt_ajax_table = $("#ajax-datatables");
            if (dt_ajax_table.length) {
                const dt_ajax = dt_ajax_table.dataTable({
                    retrieve: true,
                    processing: true,
                    dom: '<"d-flex justify-content-between align-items-end mx-0 row"<"col"l><"#status.form-group col-lg-2 mt-1 mb-50"><"#date.form-group col-lg-3 mt-1 mb-50"><"col-md-auto"f><"col col-lg-2 p-0."<"#button.btn btn-md btn-outline-primary">>>t<"d-flex justify-content-between mx-0 row"<"col-sm-12 col-md-6"i><"col-sm-12 col-md-6"p>>',
                    ajax: `/leave-reports/employee/${employeeId}?status=${status}&date=${date}`,
                    scrollX: true,
                    language: {
                        paginate: {
                            // remove previous & next text from pagination
                            previous: '&nbsp;',
                            next: '&nbsp;'
                        },
                    },
                    columns: [{
                            data: 'leave_type_name',
                            name: 'leave_type_name'
                        },
                        {
                            data: (data) => `${data.number_of_day} Days`,
                            name: 'number_of_day'
                        },
                        {
                            data: (data) => data.application_from_date.substring(0, 10),
                            name: 'application_from_date'
                        },
                        {
                            data: (data) => data.application_to_date.substring(0, 10),
                            name: 'application_to_date'
                        },
                        {
                            data: (data) => {
                                if (data.status == 'lpd') {
                                    return '<span class="badge badge-pill badge-light-warning">Pending</span>';
                                } else if (data.status == 'lhn') {
                                    return '<span class="badge badge-pill badge-light-danger">Rejected</span>';
                                } else if (data.status == 'lhy') {
                                    return '<span class="badge badge-pill badge-light-success">Approved</span>';
                                } else if (data.status == 'cls') {
                                    return '<span class="badge badge-pill badge-light-info">Finished</span>';
                                } else if (data.status == 'can') {
                                    return '<span class="badge badge-pill badge-light-secondary">Canceled</span>';
                                } else if (data.status == 'lmy') {
                                    return '<span class="badge badge-pill badge-light-success">Acc by Manager</span>';
                                } else {
                                    return '<span class="badge badge-pill badge-light-danger">Rejected</span>';
                                }
                            },
                            name: 'status'
                        },
                        {
                            data: (data) => {
                                return `<a class="btn btn-outline-info round waves-effect button-rounded" href="/leave-reports/${data.id}"> Details </a>`
                            },
                            name: 'detail'
                        }
                    ],
                });
            }

            $("#status").append(
                `<select class="form-control" name="code" id="code" onchange="selectFunction()"><option selected disabled value="">Select Status</option><option value="lhy">Approved</option><option value="lhn">Rejected</option><option value="cls">Finished</option><option value="can">Canceled</option></select>`
            );
            $("#status").css('margin-bottom', '0.5rem !important');

            if (status.length > 0) {
                $("#code").val(status);
            }

            $("#date").append(
                `<input type="text" name="date" aria-controls="datatables-ajax" id="select-date" value="{{ request('date') }}" class="form-control" placeholder="Select Date" />`
            );
            $("#date").css('margin-bottom', '0.5rem !important');

            $('#select-date').flatpickr({
                altInput: true,
                dateFormat: "Y-m-d",
                mode: "range",
                defaultDate: (date.length > 9) ? date : '',
                maxDate: new Date().toISOString().slice(0, 10),
                onChange: function(selectedDates, dateStr) {
                    if (dateStr.length > 9) {
                        $("#ajax-datatables").DataTable().destroy();
                        dataTable_Ajax(status, dateStr);
                    }
                },
            });

            $("#button").html('Export');
            $("#button").attr('style', 'margin-bottom: 7px');
            $("#button").click(() => exportLeave());
        };

        dataTable_Ajax(status, date);

        const selectFunction = () => {
            date = $('#select-date').val();
            status = $('#code').val();
            (date == '') ? date = 0: date;
            (status === null) ? status = 0: status;
            $("#ajax-datatables").DataTable().destroy();
            dataTable_Ajax(status, date);
        };

        /**
         * 
         * function export leave report to excel.
         * 
         */

        const exportLeave = () => {
            date = $('#select-date').val();
            status = $('#code').val();
            (date == '') ? date = 0: date;
            (status === null) ? status = 0: status;

            const data = {
                date: date,
                status: status
            };

            fetch(`/leave-reports/${employeeId}?status=${status}&date=${date}&export=export`, {
                    method: 'GET'
                })
                .then(response => response.blob())
                .then(data => {
                    console.log('Success:', data);
                    const url = window.URL.createObjectURL(data);
                    const a = document.createElement('a');
                    a.href = url;
                    a.download = "leave_reports.xlsx";
                    document.body.appendChild(
                        a); // we need to append the element to the dom -> otherwise it will not work in firefox
                    a.click();
                    a.remove(); //afterwards we remove the element again
                })
                .catch((error) => {
                    console.error('Error:', error);
                    Swal.fire({
                        type: "error",
                        title: 'Oops...',
                        text: `${error}`,
                        confirmButtonClass: 'btn btn-success',
                    });
                });
        };

        $(document).ready(() => {
            $('#code').select2();
        });

        const show = (e) => {
            $(".date").html(e.attr("date"));
        };

        $(document).on('click', '#notes', function() {
            $('#text-notes').text($(this).data('purpose'));
        });

        $(document).on('click', '#files', function() {
            let isMultiple = $(this).data('sick-letter').includes('|');
            let array = [];

            if (isMultiple) {
                let files = $(this).data('sick-letter').split('|');
                let folder = $(this).data('sick-letter').split('/');

                files.map((element, index) => {
                    if (index > 0) {
                        element = `${folder[0]}/${element}`;
                    }
                    array.push(element);
                });

                for (let i = 0; i < array.length; i++) {
                    $('.detail-files-container').append(
                        `<div class="input-group mb-1"><div class="input-group-prepend"><span class="input-group-text bg-white" id="file-copy"><img src="{{ url('./img/icons/file-copy.svg') }}" alt="File Copy"></span></div><input type="text" placeholder="Date Range" name="detail-files" id="detail-files" class="form-control bg-white border-left-0 detail-files" disabled><div class="input-group-append"><a href="{{ url('uploads/${array[i]}') }}" class="btn btn-primary" download>Download</a></div></div>`
                    );
                }

                $('.detail-files').each(function(j) {
                    $(this).attr('placeholder', array[j]);
                });
            } else {
                let file = $(this).data('sick-letter');

                if (file != '') {
                    $('.detail-files-container').append(
                        `<div class="input-group"><div class="input-group-prepend"><span class="input-group-text bg-white" id="file-copy"><img src="{{ url('./img/icons/file-copy.svg') }}" alt="File Copy"></span></div><input type="text" placeholder="Date Range" name="detail-files" id="detail-files" class="form-control bg-white border-left-0 detail-files" disabled><div class="input-group-append"><a href="{{ url('uploads/${file}') }}" class="btn btn-primary" download>Download</a></div></div>`
                    );

                    $('.detail-files').attr('placeholder', file);
                } else {
                    $('.detail-files-container').append(
                        '<div class="input-group"><div class="input-group-prepend"><span class="input-group-text bg-white" id="file-copy"><img src="{{ url('./img/icons/file-copy.svg') }}" alt="File Copy"></span></div><input type="text" placeholder="No file available" name="detail-files" id="detail-files" class="form-control bg-white border-left-0 detail-files" disabled><div class="input-group-append"><button class="btn btn-primary" disabled>Download</button></div></div>'
                    );
                }
            }
        });

        $('#modal-files').on('hidden.bs.modal', function(e) {
            $('.detail-files-container').html('');
        });
    </script>
@endsection
