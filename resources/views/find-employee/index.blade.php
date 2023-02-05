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
                                <h3 class="float-left mb-0">Tracker</h3>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <ul class="nav nav-tabs" role="tablist">
                            <li class="nav-item">
                                <a class="nav-link active" id="attendance-tab" data-toggle="tab" href="#attendance"
                                    aria-controls="attendance" role="tab" aria-selected="false">Find Working
                                    Employees</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" id="lapor-tab" data-toggle="tab" href="#lapor" aria-controls="lapor"
                                    role="tab" aria-selected="false">Employees Not Present</a>
                            </li>
                        </ul>

                        <div class="tab-content">

                            <div class="tab-pane active" id="attendance" aria-labelledby="attendance-tab" role="tabpanel">
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
                                                        <th>Job Level</th>
                                                        {{-- <th>Status</th> --}}
                                                    </tr>
                                                </thead>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="tab-pane" id="lapor" aria-labelledby="lapor-tab" role="tabpanel">
                                <div class="row">
                                    <div class="col-12">
                                        <div class="card-datatable table-rounded">
                                            <table class="table display nowrap" id="ajax-lapor">
                                                <thead>
                                                    <tr>
                                                        <th>No</th>
                                                        <th>Employee Name</th>
                                                        <th>Entity</th>
                                                        <th>SBU</th>
                                                        <th>Job Position</th>
                                                        <th>Job Level</th>
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

            attendance_ajax();
            lapor_ajax();


            $('a[data-toggle="tab"]').on('shown.bs.tab', (e) => {
                $($.fn.dataTable.tables(true)).DataTable()
                    .columns.adjust()
                    .responsive.recalc();
            });
        });

        const attendance_ajax = () => {
            let dt_ajax_manual_attendance = $("#datatables-ajax");
            console.log(dt_ajax_manual_attendance.length);
            if (dt_ajax_manual_attendance.length) {
                let dt_ajax_table = dt_ajax_manual_attendance.dataTable({
                    processing: true,
                    dom: '<"d-flex justify-content-between align-items-center mx-0 row"<"col-sm-12 col-md-6"l><"col-sm-12 col-md-6"f>>t<"d-flex justify-content-between mx-0 row"<"col-sm-12 col-md-6"i><"col-sm-12 col-md-6"p>>',
                    ajax: "{{ url('/find-employee-worker/data') }}",
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
                            data: 'job_class',
                            name: 'Job Class'
                        },
                        // {
                        //     data: 'status',
                        //     name: 'Status'
                        // },
                    ],
                    // columnDefs: [{
                    //     targets: 9,
                    //     className: 'text-center'
                    // }]
                });
            }
        }

        const lapor_ajax = () => {
            let dt_ajax_manual_attendance = $("#ajax-lapor");
            console.log(dt_ajax_manual_attendance.length);
            if (dt_ajax_manual_attendance.length) {
                let dt_ajax_table = dt_ajax_manual_attendance.dataTable({
                    processing: true,
                    dom: '<"d-flex justify-content-between align-items-center mx-0 row"<"col-sm-12 col-md-6"l><"col-sm-12 col-md-6"f>>t<"d-flex justify-content-between mx-0 row"<"col-sm-12 col-md-6"i><"col-sm-12 col-md-6"p>>',
                    ajax: "{{ url('/find-employee-worker/data2') }}",
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
                            data: 'job_class',
                            name: 'Job Class'
                        },
                    ],
                    // columnDefs: [{
                    //     targets: 9,
                    //     className: 'text-center'
                    // }]
                });
            }
        }
    </script>
@endsection
