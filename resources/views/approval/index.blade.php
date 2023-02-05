@extends('partials.template')
@section('main')
    <div class="app-content content">
        <div class="content-overlay"></div>
        <div class="header-navbar-shadow"></div>
        <div class="content-wrapper">
            <div class="content-body">
                <div class="card">
                    <div class="card-header">
                        <div class="row breadcrumbs-top">
                            <div class="col-12">
                                <h3 class="float-left mb-0">Approval Queue</h3>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <ul class="nav nav-tabs" role="tablist">
                            <li class="nav-item">
                                <a class="nav-link active" id="leave-tab" data-toggle="tab" href="#leave_application"
                                    aria-controls="leave_application" role="tab" aria-selected="true">Leave
                                    Application</a>
                            </li>
                            @can('approval_queue.manual_attendance')
                                <li class="nav-item">
                                    <a class="nav-link" id="attendance-tab" data-toggle="tab" href="#attendance"
                                        aria-controls="attendance" role="tab" aria-selected="false">Manual Attendance</a>
                                </li>
                            @else
                                <li class="nav-item">
                                    <a class="nav-link disabled" id="attendance-tab" data-toggle="tab" href="#attendance"
                                        aria-controls="attendance" role="tab" aria-selected="false">Manual Attendance</a>
                                </li>
                            @endcan

                            @can('approval_queue.overtime')
                                <li class="nav-item">
                                    <a class="nav-link" id="overtime-tab" data-toggle="tab" href="#overtime"
                                        aria-controls="overtime" role="tab" aria-selected="false">Overtime</a>
                                </li>
                            @else
                                <li class="nav-item">
                                    <a class="nav-link disabled" id="overtime-tab" data-toggle="tab" href="#overtime"
                                        aria-controls="overtime" role="tab" aria-selected="false">Overtime</a>
                                </li>
                            @endcan
                            @can('approval_queue.update_data')
                                <li class="nav-item">
                                    <a class="nav-link " id="update-tab" data-toggle="tab" href="#update" aria-controls="update"
                                        role="tab" aria-selected="false">Updating Data</a>
                                </li>
                            @else
                                <li class="nav-item">
                                    <a class="nav-link disabled" id="update-tab" data-toggle="tab" href="#update"
                                        aria-controls="update" role="tab" aria-selected="false">Updating Data</a>
                                </li>
                            @endcan
                            <li class="nav-item">
                                <a class="nav-link" id="Reimbursement-tab" data-toggle="tab" href="#reimbursement"
                                    aria-controls="reimbursement" role="tab" aria-selected="false">Reimbursement</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" id="savingandloan-tab" data-toggle="tab" href="#savingandloan"
                                    aria-controls="savingandloan" role="tab" aria-selected="false">Saving and Loan</a>
                            </li>
                        </ul>
                        <div class="tab-content">
                            @can('approval_queue.leave_application')
                                <div class="tab-pane active" id="leave_application" aria-labelledby="leave_application"
                                    role="tabpanel">
                                    <div class="row">
                                        <div class="col-12">
                                            <div class="card-datatable table-rounded-outline">
                                                <table class="table table-borderless nowrap" id="datatables-ajax">
                                                    <thead>
                                                        <tr>
                                                            <th>No</th>
                                                            <th>Employee Name</th>
                                                            <th>Entity</th>
                                                            <th>SBU</th>
                                                            <th>Job Position</th>
                                                            <th>Type of Leave</th>
                                                            <th>Start Date</th>
                                                            <th>End Date</th>
                                                            <th>Status</th>
                                                            <th>Actions</th>
                                                        </tr>
                                                    </thead>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endcan

                            @can('approval_queue.manual_attendance')
                                <div class="tab-pane" id="attendance" aria-labelledby="attendance-tab" role="tabpanel">
                                    <div class="row">
                                        <div class="col-12">
                                            <div class="card-datatable table-rounded">
                                                <table class="table display nowrap" id="ajax-attendance">
                                                    <thead>
                                                        <tr>
                                                            <th>No</th>
                                                            <th>Employee Name</th>
                                                            <th>Entity</th>
                                                            <th>SBU</th>
                                                            <th>Job Position</th>
                                                            <th>Date</th>
                                                            <th>Attendancec Date</th>
                                                            <th>Check In Time</th>
                                                            <th>Check Out Time</th>
                                                            <th>Status</th>
                                                            <th class="text-center">Actions</th>
                                                        </tr>
                                                    </thead>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endcan

                            @can('approval_queue.overtime')
                                <div class="tab-pane" id="overtime" aria-labelledby="overtime-tab" role="tabpanel">
                                    <div class="row">
                                        <div class="col-12">
                                            <div class="card-datatable table-rounded">
                                                <table class="table display nowrap" id="datatables-ajax-overtime">
                                                    <thead>
                                                        <tr>
                                                            <th>No</th>
                                                            <th>Employee Name</th>
                                                            <th>Entity</th>
                                                            <th>SBU</th>
                                                            <th>Job Position</th>
                                                            <th>Date</th>
                                                            <th>Overtime Date</th>
                                                            <th>Start Time</th>
                                                            <th>End Time</th>
                                                            <th>Work Time</th>
                                                            <th>Status</th>
                                                            <th class="text-center">Actions</th>
                                                        </tr>
                                                    </thead>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endcan

                            @can('approval_queue.update_data')
                                <div class="tab-pane" id="update" aria-labelledby="update-tab" role="tabpanel">
                                    <div class="row">
                                        <div class="col-12">
                                            <div class="card-datatable table-rounded">
                                                <table class="table display nowrap" width="100%" id="ajax-update">
                                                    <thead>
                                                        <tr>
                                                            <th>No</th>
                                                            <th>Employee Name</th>
                                                            <th>Entity</th>
                                                            <th>SBU</th>
                                                            <th>Job Position</th>
                                                            <th>Employee Information</th>
                                                            <th>Category Information</th>
                                                            <th>Status</th>
                                                            <th class="text-center">Actions</th>
                                                        </tr>
                                                    </thead>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endcan

                            <div class="tab-pane" id="reimbursement" aria-labelledby="reimbursement-tab"
                                role="tabpanel">
                                <div class="row">
                                    <div class="col-12">
                                        <div class="card-datatable table-rounded">
                                            <table class="table display nowrap" width="100%" id="ajax-reimbursement">
                                                <thead>
                                                    <tr>
                                                        <th>No</th>
                                                        <th>Employee Name</th>
                                                        <th>Entity</th>
                                                        <th>SBU</th>
                                                        <th>Job Position</th>
                                                        <th>Category</th>
                                                        <th>Transaction Date</th>
                                                        <th>Amount</th>
                                                        <th>Status</th>
                                                        <th class="text-center">Actions</th>
                                                    </tr>
                                                </thead>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="tab-pane" id="savingandloan" aria-labelledby="savingandloan-tab"
                                role="tabpanel">
                                <div class="row">
                                    <div class="col-12">
                                        <div class="card-datatable table-rounded">
                                            <table class="table display nowrap" width="100%" id="ajax-savingandloan">
                                                <thead>
                                                    <tr>
                                                        <th>No</th>
                                                        <th>Employee Name</th>
                                                        <th>Entity</th>
                                                        <th>SBU</th>
                                                        <th>Job Position</th>
                                                        <th>Type</th>
                                                        <th>Transaction Date</th>
                                                        <th>Amount</th>
                                                        <th>Status</th>
                                                        <th class="text-center">Actions</th>
                                                    </tr>
                                                </thead>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>
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
    <script>
        $(document).ready(function() {
            $('#modal-form').on('hidden.bs.modal', function(event) {
                const reset_form = $('#form_data')[0];

                $("#form_data").trigger("reset");
                $(reset_form).removeClass('was-validated');
                $("#form_data").trigger("reset");

            });

            leave_application_ajax();
            manual_attendance_ajax();
            overtimes_ajax();
            reimbursement_ajax();
            savingand_loan_ajax();

            $('a[data-toggle="tab"]').on('shown.bs.tab', (e) => {
                $($.fn.dataTable.tables(true)).DataTable()
                    .columns.adjust()
                    .responsive.recalc();
            });
        });

        const leave_application_ajax = () => {
            let dt_ajax_table = $("#datatables-ajax");
            console.log(dt_ajax_table.length);
            if (dt_ajax_table.length) {
                let dt_ajax = dt_ajax_table.dataTable({
                    processing: true,
                    dom: '<"d-flex justify-content-between align-items-end mx-0 row"<"col"l><"col-md-auto p-0."f><"#leave-history-filter.btn btn-md btn-primary"><"col col-lg-2 p-0.">>t<"d-flex justify-content-between mx-0 row"<"col-sm-12 col-md-6"i><"col-sm-12 col-md-6"p>>',
                    ajax: "{{ url('approval/leave-application') }}",
                    ordering: false,
                    scrollX: true,
                    scrollCollapse: true,
                    language: {
                        paginate: {
                            previous: '&nbsp;',
                            next: '&nbsp;'
                        },
                    },
                    columns: [{
                            data: 'DT_RowIndex',
                            name: 'DT_RowIndex'
                        },
                        {
                            data: (data) => {
                                return `
                                    <div class="d-flex align-items-center">
                                        <img src="${data.employee_image}" alt="Employee Profile Picture" class="rounded-circle" width="32px" height="32px">
                                        <div class="p-1">
                                            <b>${data.first_name} ${data.last_name}</b><br>
                                            ${data.nip}
                                        </div>
                                    </div>
                                `;
                            },
                            name: 'Employee Name'
                        },
                        {
                            data: 'branch_name',
                            name: 'Entity'
                        },
                        {
                            data: 'department_name',
                            name: 'SBU'
                        },
                        {
                            data: 'job_position',
                            name: 'Job Position'
                        },
                        {
                            data: 'leave_type_name',
                            name: 'Type of Leave'
                        },
                        {
                            data: (data) => {
                                let startDate = data.application_from_date.split(' ');

                                return startDate[0].split('-').reverse().join('/');
                            },
                            name: 'Start Date'
                        },
                        {
                            data: (data) => {
                                let endDate = data.application_to_date.split(' ');

                                return endDate[0].split('-').reverse().join('/');
                            },
                            name: 'End Date'
                        },
                        {
                            data: 'status',
                            name: 'Status'
                        },
                        {
                            data: 'action',
                            name: 'action'
                        },
                    ],
                    columnDefs: [{
                        targets: [8, 9],
                        className: 'text-center',
                    }]
                });

                $("#leave-history-filter").html(
                    '<div>Filter</div><img src="{{ url('./img/icons/angle-down.svg') }}">');
                $("#leave-history-export").html(
                    '<div><img src="{{ url('./img/icons/open-in-new.svg') }}"><a href="{{ url('approval/leave-application/export') }}" style="color: inherit;">Export</a></div><img src="{{ url('./img/icons/angle-down.svg') }}" id="angle-down">'
                );
            }
        }

        const manual_attendance_ajax = () => {
            let dt_ajax_manual_attendance = $("#ajax-attendance");
            console.log(dt_ajax_manual_attendance.length);
            if (dt_ajax_manual_attendance.length) {
                let dt_ajax_table = dt_ajax_manual_attendance.dataTable({
                    processing: true,
                    dom: '<"d-flex justify-content-between align-items-center mx-0 row"<"col-sm-12 col-md-6"l><"col-sm-12 col-md-6"f>>t<"d-flex justify-content-between mx-0 row"<"col-sm-12 col-md-6"i><"col-sm-12 col-md-6"p>>',
                    ajax: "{{ url('approval/manual-attendance') }}",
                    ordering: false,
                    scrollX: true,
                    scrollCollapse: true,
                    language: {
                        paginate: {
                            previous: '&nbsp;',
                            next: '&nbsp;'
                        },
                    },
                    columns: [{
                            data: 'DT_RowIndex',
                            name: 'DT_RowIndex'
                        },
                        {
                            data: (data) => {
                                return `
                                    <div class="d-flex align-items-center">
                                        <img src="${data.employee_image}" alt="Employee Profile Picture" class="rounded-circle" width="32px" height="32px">
                                        <div class="p-1">
                                            <b>${data.first_name} ${data.last_name}</b><br>
                                            ${data.nip}
                                        </div>
                                    </div>
                                `;
                            },
                            name: 'Employee Name'
                        },
                        {
                            data: 'branch_name',
                            name: 'Entity'
                        },
                        {
                            data: 'department_name',
                            name: 'SBU'
                        },
                        {
                            data: 'job_position',
                            name: 'Job Position'
                        },
                        {
                            data: 'status_date',
                            name: 'status date'
                        },
                        {
                            data: 'date',
                            name: 'Date'
                        },
                        {
                            data: 'check_in',
                            name: 'Check In'
                        },
                        {
                            data: 'check_out',
                            name: 'Check Out'
                        },
                        {
                            data: 'status',
                            name: 'Status'
                        },
                        {
                            data: 'action',
                            name: 'action'
                        },
                    ],
                    columnDefs: [{
                        targets: 9,
                        className: 'text-center'
                    }]
                });
                // $('#ajax-attendance_filter').append(
                //     `<a href="{{ url('approval/manual-attendance/export') }}" id="export" class="btn btn-outline-secondary ml-1"><i data-feather="external-link"></i> Export</a>`
                // );
            }
        }

        const overtimes_ajax = () => {
            const dt_ajax_overtimes = $("#datatables-ajax-overtime");
            console.log(dt_ajax_overtimes.length);
            if (dt_ajax_overtimes.length) {
                let dt_ajax_table = dt_ajax_overtimes.dataTable({
                    processing: true,
                    dom: '<"d-flex justify-content-between align-items-center mx-0 row"<"col-sm-12 col-md-6"l><"col-sm-12 col-md-6"f>>t<"d-flex justify-content-between mx-0 row"<"col-sm-12 col-md-6"i><"col-sm-12 col-md-6"p>>',
                    ajax: "{{ url('approval/overtime') }}",
                    ordering: false,
                    scrollX: true,
                    scrollCollapse: true,
                    language: {
                        paginate: {
                            previous: '&nbsp;',
                            next: '&nbsp;'
                        },
                    },
                    columns: [{
                            data: 'DT_RowIndex',
                            name: 'DT_RowIndex'
                        },
                        {
                            data: (data) => {
                                return `
                                    <div class="d-flex align-items-center">
                                        <img src="${data.employee_image}" alt="Employee Profile Picture" class="rounded-circle" width="32px" height="32px">
                                        <div class="p-1">
                                            <b>${data.first_name} ${data.last_name}</b><br>
                                            ${data.nip}
                                        </div>
                                    </div>
                                `;
                            },
                            name: 'Employee Name'
                        },
                        {
                            data: 'branch_name',
                            name: 'Entity'
                        },
                        {
                            data: 'department_name',
                            name: 'SBU'
                        },
                        {
                            data: 'job_position',
                            name: 'Job Position'
                        },
                        {
                            data: 'application_date',
                            name: 'Application Date'
                        },
                        {
                            data: 'overtime_date',
                            name: 'Overtime Date'
                        },
                        {
                            data: 'start_time',
                            name: 'Start Time'
                        },
                        {
                            data: 'end_time',
                            name: 'End Time'
                        },
                        {
                            data: (data) => {
                                return `${data.work_shift.h} H ${data.work_shift.m} M`
                            },
                            name: 'Work Time'
                        },
                        {
                            data: 'status',
                            name: 'Status'
                        },
                        {
                            data: 'action',
                            name: 'action'
                        },
                    ],
                    columnDefs: [{
                        targets: 9,
                        className: 'text-center'
                    }]
                });

                // $('#datatables-ajax-overtime_filter').append(
                //     `<a href="{{ url('approval/manual-attendance/export') }}" id="export" class="btn btn-outline-secondary ml-1"><i data-feather="external-link"></i> Export</a>`
                // );
            }
        }

        const update_data_ajax = () => {
            var dt_ajax_update_data = $("#ajax-update");
            console.log(dt_ajax_update_data.length);
            if (dt_ajax_update_data.length) {
                var dt_ajax_update = dt_ajax_update_data.dataTable({
                    processing: true,
                    dom: '<"d-flex justify-content-between align-items-center mx-0 row"<"col-sm-12 col-md-6"l><"col-sm-12 col-md-6"f>>t<"d-flex justify-content-between mx-0 row"<"col-sm-12 col-md-6"i><"col-sm-12 col-md-6"p>>',
                    ajax: "{{ url('approval/update-data') }}",
                    ordering: false,
                    scrollX: true,
                    scrollCollapse: true,
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
                            data: (data) => {
                                return `
                                    <div class="d-flex align-items-center">
                                        <img src="${data.employee_image}" alt="Employee Profile Picture" class="rounded-circle" width="32px" height="32px">
                                        <div class="p-1">
                                            <b>${data.first_name} ${data.last_name}</b><br>
                                            ${data.nip}
                                        </div>
                                    </div>
                                `;
                            },
                            name: 'Employee Name'
                        },
                        {
                            data: 'branch_name',
                            name: 'Entity'
                        },
                        {
                            data: 'department_name',
                            name: 'SBU'
                        },
                        {
                            data: 'job_position',
                            name: 'Job Position'
                        },
                        {
                            data: 'employee_information',
                            name: 'Employee Information'
                        },
                        {
                            data: 'category_information',
                            name: 'Category Information'
                        },
                        {
                            data: 'status',
                            name: 'Status'
                        },
                        {
                            data: 'action',
                            name: 'action'
                        }
                    ],
                    columnDefs: [{
                        targets: 8,
                        className: 'text-center',
                    }]
                });

                // $('#ajax-update_filter').append(
                //     `<a href="{{ url('approval/update-data/export') }}" id="export" class="btn btn-outline-secondary ml-1"><i data-feather="external-link"></i> Export</a>`
                // );
            }
        }

        const reimbursement_ajax = () => {
            var dt_ajax_reimbursement_data = $("#ajax-reimbursement");
            console.log(dt_ajax_reimbursement_data.length);
            if (dt_ajax_reimbursement_data.length) {
                var dt_ajax_reimbursement = dt_ajax_reimbursement_data.dataTable({
                    processing: true,
                    dom: '<"d-flex justify-content-between align-items-center mx-0 row"<"col-sm-12 col-md-6"l><"col-sm-12 col-md-6"f>>t<"d-flex justify-content-between mx-0 row"<"col-sm-12 col-md-6"i><"col-sm-12 col-md-6"p>>',
                    ajax: "{{ url('approval/medical-reimbursement') }}",
                    ordering: false,
                    scrollX: true,
                    scrollCollapse: true,
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
                            data: (data) => {
                                return `
                                    <div class="d-flex align-items-center">
                                        <img src="${data.employee_image}" alt="Employee Profile Picture" class="rounded-circle" width="32px" height="32px">
                                        <div class="p-1">
                                            <b>${data.first_name} ${data.last_name}</b><br>
                                            ${data.nip}
                                        </div>
                                    </div>
                                `;
                            },
                            name: 'Employee Name'
                        },
                        {
                            data: 'branch_name',
                            name: 'Entity'
                        },
                        {
                            data: 'department_name',
                            name: 'SBU'
                        },
                        {
                            data: 'job_position',
                            name: 'Job Position'
                        },
                        {
                            data: 'category',
                            name: 'Category'
                        },
                        {
                            data: 'transaction_date',
                            name: 'Transaction Date'
                        },
                        {
                            data: (data) => {
                                if (data.amount == null) {
                                    return '-';
                                } else {
                                    return 'Rp' + data.amount.toLocaleString('id-ID');
                                }
                            },
                            name: 'Amount'
                        },
                        {
                            data: 'status',
                            name: 'Status'
                        },
                        {
                            data: 'action',
                            name: 'action'
                        }
                    ],
                    columnDefs: [{
                        targets: 8,
                        className: 'text-center',
                    }]
                });

                // $('#ajax-reimbursement_filter').append(
                //     `<a href="{{ url('approval/update-data/export') }}" id="export" class="btn btn-outline-secondary ml-1"><i data-feather="external-link"></i> Export</a>`
                // );
            }
        }

        const savingand_loan_ajax = () => {
            let dt_ajax_savingand_loan = $("#ajax-savingandloan");
            console.log(dt_ajax_savingand_loan.length);
            if (dt_ajax_savingand_loan.length) {
                let dt_ajax_table = dt_ajax_savingand_loan.dataTable({
                    processing: true,
                    dom: '<"d-flex justify-content-between align-items-center mx-0 row"<"col-sm-12 col-md-6"l><"col-sm-12 col-md-6"f>>t<"d-flex justify-content-between mx-0 row"<"col-sm-12 col-md-6"i><"col-sm-12 col-md-6"p>>',
                    ajax: "{{ url('approval/saving-and-loan') }}",
                    ordering: false,
                    scrollX: true,
                    scrollCollapse: true,
                    language: {
                        paginate: {
                            previous: '&nbsp;',
                            next: '&nbsp;'
                        },
                    },
                    columns: [{
                            data: 'DT_RowIndex',
                            name: 'DT_RowIndex'
                        },
                        {
                            data: (data) => {
                                return `
                                    <div class="d-flex align-items-center">
                                        <img src="${data.employee_image}" alt="Employee Profile Picture" class="rounded-circle" width="32px" height="32px">
                                        <div class="p-1">
                                            <b>${data.first_name} ${data.last_name}</b><br>
                                            ${data.nip}
                                        </div>
                                    </div>
                                `;
                            },
                            name: 'Employee Name'
                        },
                        {
                            data: 'branch_name',
                            name: 'Entity'
                        },
                        {
                            data: 'department_name',
                            name: 'SBU'
                        },
                        {
                            data: 'job_position',
                            name: 'Job Position'
                        },
                        {
                            data: 'type',
                            name: 'Type'
                        },
                        {
                            data: 'transaction_date',
                            name: 'Transaction Date'
                        },
                        {
                            data: (data) => {
                                if (data.amount == null) {
                                    return '-';
                                } else {
                                    return 'Rp' + data.amount.toLocaleString('id-ID');
                                }
                            },
                            name: 'Amount'
                        },
                        {
                            data: 'status',
                            name: 'Status'
                        },
                        {
                            data: 'action',
                            name: 'action'
                        }
                    ],
                    // columnDefs: [{
                    //     targets: 9,
                    //     className: 'text-center'
                    // }]
                });
            }
        }

        update_data_ajax();
    </script>
@endsection
