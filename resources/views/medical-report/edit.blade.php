@extends('partials.template')
@section('main')
    <div class="app-content content ">
        <div class="content-overlay"></div>
        <div class="header-navbar-shadow"></div>
        <div class="content-wrapper">
            <div class="content-header row">
            </div>
            <div class="content-body">
                <p class="d-flex align-items-center"><img src="{{ url('./img/icons/dashboard.svg') }}" alt="">
                    <a href="{{ url('dashboard') }}" class="dashboard-link">Dashboard</a>
                    <a href="{{ url('medical-reports') }}" class="leave-application-link">/ Medical Report</a>
                    <a href="{{ url("medical-reports/employee/$employee->employee_id") }}"
                        class="leave-application-link">/ Employee /
                        {{ $employee->employee_id }}</a>
                </p>
                <hr>
                <div class="card my-2">
                    <div class="card-body">
                        <h3>Medical Tracker Detail Employee</h3>
                        <section>
                            <div class="row">
                                <div class="col-lg-6 col-12">
                                    <div class="d-flex align-items-center shadow rounded px-2 py-3">
                                        <img class="rounded-circle" id="monthly_profile_image"
                                            src="{{ $employee->image ? asset('uploads/' . $employee->image) : asset('img/profile.png') }}"
                                            alt="avatar" height="127" width="127">
                                        <div class="flex-column align-items-left">
                                            <h3 id="name" class="mt-0 ml-2 mb-25 font-weight-bolder">
                                                {{ "$employee->first_name $employee->last_name" }}</h3>
                                            <h5 id="nip" class="ml-2 mb-2 font-weight">
                                                {{ $employee->nip }}</h5>
                                            <h4 id="job_class" class="mt-2 ml-2 font-weight-bolder">
                                                {{ $jobClass->job_class }}</h4>
                                        </div>
                                        <input type="hidden" name="employee_id" id="employee_id"
                                            value="{{ $employee->employee_id }}">
                                    </div>
                                </div>
                                <div class="col-lg-6 col-12">
                                    <div class="shadow rounded p-2">
                                        <p>Statistic</p>
                                        <div class="row mt-1">
                                            <div class="col-lg-6 col-12 mb-lg-0 my-1">
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
                                                                    <path
                                                                        d="M18.5 3V0H16.5V3H13.5V5H16.5V8H18.5V5H21.5V3H18.5Z"
                                                                        fill="#F6F5FB" />
                                                                </svg>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-9 p-0 my-auto">
                                                        <h4 class="m-0 font-weight-bolder">
                                                            {{ $employeeBalance->total_balance }}
                                                        </h4>
                                                        <p class="m-0">Reimbursement Balance</p>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="col-lg-6 col-12 mb-lg-0 my-1">
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
                                                        <h4 class="m-0 font-weight-bolder">
                                                            {{ $employeeBalance->used_balance }}</h4>
                                                        <p class="m-0">Total Expense</p>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="col-lg-6 col-12 mb-lg-0 my-1">
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
                                                        <h4 class="m-0 font-weight-bolder">{{ count($approved) }}
                                                        </h4>
                                                        <p class="m-0">Number of Approved</p>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="col-lg-6 col-12 mb-lg-0 my-1">
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
                                                        <h4 class="m-0 font-weight-bolder">{{ count($rejected) }}</h4>
                                                        <p class="m-0">Number of Rejects</p>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </section>
                        <hr>
                        <section>
                            <div class="row">
                                <div class="table-responsive col-12 table-rounded">
                                    <table class="table table-borderless" width="100%" id="ajax-datatables">
                                        <thead>
                                            <tr>
                                                <th scope="col">No</th>
                                                <th scope="col">Reimbursement Type</th>
                                                <th scope="col">Transaction Date</th>
                                                <th scope="col">Total Expense</th>
                                                <th scope="col">Total Reimbursement</th>
                                                <th scope="col">Status</th>
                                                <th scope="col">Action</th>
                                            </tr>
                                        </thead>
                                    </table>
                                </div>
                            </div>
                        </section>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('page_js')
    <script src="{{ url('app-assets/vendors/js/forms/validation/jquery.validate.min.js') }}"></script>
    <script src="{{ url('app-assets/vendors/js/extensions/sweetalert2.all.min.js') }}"></script>
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
    <script src="https://cdn.jsdelivr.net/npm/flatpickr@latest/dist/plugins/monthSelect/index.js"></script>
    <script src="{{ url('app-assets/js/scripts/components/components-popovers.js') }}"></script>
@endsection

@section('page_script')
    <script>
        let date = 0;
        let status = null;

        $(document).ready(() => {
            $('#code').select2();
        });

        const dataTableAjax = (status, date) => {
            const employeeId = document.getElementById('employee_id');
            const dt_ajax_table = $("#ajax-datatables");
            console.log(dt_ajax_table.length);
            if (dt_ajax_table.length) {
                const dt_ajax = dt_ajax_table.dataTable({
                    retrieve: true,
                    processing: true,
                    dom: '<"d-flex justify-content-between align-items-end mx-0 row"<"col"l><"#status.form-group col-lg-2 mt-1 mb-50"><"#date.form-group col-lg-3 mt-1 mb-50"><"col-md-auto"f><"col col-lg-2 p-0."<"#button.btn btn-md btn-outline-primary">>>t<"d-flex justify-content-between mx-0 row"<"col-sm-12 col-md-6"i><"col-sm-12 col-md-6"p>>',
                    ajax: `/medical-reports/employee/${employeeId.value}?status=${status}&date=${date}`,
                    scrollX: true,
                    language: {
                        paginate: {
                            // remove previous & next text from pagination
                            previous: '&nbsp;',
                            next: '&nbsp;'
                        },
                    },
                    columns: [{
                            data: 'DT_RowIndex',
                            name: 'DT_RowIndex'
                        },
                        {
                            data: (data) => data.category == 0 ? 'Inpatient' : 'Outpatient',
                            name: 'category'
                        },
                        {
                            data: 'reimbursement_date',
                            name: 'reimbursement_date'
                        },
                        {
                            data: (data) => new Intl.NumberFormat("id-ID", {
                                style: "currency",
                                currency: "IDR"
                            }).format(data.expenses),
                            name: 'expenses'
                        },
                        {
                            data: (data) => new Intl.NumberFormat("id-ID", {
                                style: "currency",
                                currency: "IDR"
                            }).format(data.total_reimburse),
                            name: 'total_reimburse'
                        },
                        {
                            data: (data) => {
                                if (data.approve_by_finance > 0) {
                                    return '<span class="badge badge-pill badge-light-success">Approved</span>';
                                } else if (data.reject_by > 0) {
                                    return '<span class="badge badge-pill badge-light-danger">Rejected</span>';
                                } else if (data.cancel_by > 0) {
                                    return '<span class="badge badge-pill badge-light-secondary">Canceled</span>';
                                } else {
                                    return '<span class="badge badge-pill badge-light-warning">Pending</span>';
                                }
                            },
                            name: 'status'
                        },
                        {
                            data: (data) => {
                                return `<a class="btn btn-outline-info round waves-effect button-rounded" href="/medical-reports/${data.medical_reimbursement_id}"> Details </a>`
                            },
                            name: 'action',
                        }
                    ],
                });
            }

            $("#status").append(
                `<select class="form-control" name="code" id="code" onchange="selectFunction()"><option selected disabled value="">Select Status</option><option value="rejected">Rejected</option><option value="approved">Approved</option><option value="canceled">Canceled</option></select>`
            );
            $("#status").css('margin-bottom', '0.5rem !important');

            if (status != null) {
                $("#code").val(status);
            }

            $("#date").append(
                `<input type="text" name="date" aria-controls="ajax-datatables" id="select-date" value="{{ request('date') }}" class="form-control" placeholder="Select Date" />`
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
                        dataTableAjax(status, dateStr);
                    }
                },
            });

            $("#button").html('Export');
            $("#button").attr('style', 'margin-bottom: 7px');
            $("#button").click(() => exportReport());

            $(document).ready(() => {
                $('#code').select2();
            });
        };

        dataTableAjax(status, date);

        let entity = '';
        const selectFunction = () => {
            date = $('#select-date').val();
            status = $('#code').val();
            (date == 0) ? date = 0: date;
            (status === null) ? status = null: status;
            $("#ajax-datatables").DataTable().destroy();
            dataTableAjax(status, date);
        };

        const exportReport = () => {
            const employeeId = document.getElementById('employee_id');
            date = $('#select-date').val();
            status = $('#code').val();
            (date == 0) ? date = 0: date;
            (status === null) ? status = null: status;

            const data = {
                date: date,
                status: status,
                export: 'export'
            };

            fetch(`/medical-reports/employee/${employeeId.value}?date=${date}&status=${status}&export=export`, {
                    method: 'GET'
                })
                .then(response => response.blob())
                .then(data => {
                    console.log('Success:', data);
                    const url = window.URL.createObjectURL(data);
                    const a = document.createElement('a');
                    a.href = url;
                    a.download = "medical_report.xlsx";
                    document.body.appendChild(
                        a); // we need to append the element to the dom -> otherwise it will not work in firefox
                    a.click();
                    a.remove(); //afterwards we remove the element again
                })
                .catch((error) => {
                    console.error('Error:', error);
                });
        };
    </script>
@endsection
