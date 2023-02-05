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
                        <h3 class="font-weight-bold">Leave Report</h3>

                        <!-- BEGIN CUSTOM FILTER -->
                        <section>
                            <form action="" method="post">
                                @method('get')
                                @csrf
                                <div class="row">
                                    <div class="col-lg-3 col-md-6 col-sm-12">
                                        <div class="form-group">
                                            <label>Date</label>
                                            <div class="form-group">
                                                <input type="text" name="date" aria-controls="datatables-ajax"
                                                    id="select-date" value="{{ request('date') }}" required
                                                    class="form-control" placeholder="Select Date" />
                                                <div class="valid-feedback">Looks good!</div>
                                                <div class="invalid-feedback">Please select holiday start
                                                    date.</div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-lg-3 col-md-6 col-sm-12">
                                        <div class="form-group">
                                            <label>Leave Types</label>
                                            <select class="form-control" name="leave_types" id="leave_types"
                                                onchange="selectFunction()">
                                                <option selected disabled value="">Select Leave Type
                                                </option>
                                                @foreach ($leaveTypes as $leaveTypes)
                                                    <option value="{{ $leaveTypes->leave_type_id }}"
                                                        {{ request('leave_types') == $leaveTypes->leave_type_id ? 'selected' : '' }}>
                                                        {{ $leaveTypes->leave_type_name }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    @can('leave_report.view_all')
                                        <div class="col-lg-3 col-md-6 col-sm-12">
                                            <div class="form-group">
                                                <label>Entity</label>
                                                <select class="form-control" name="branch" id="branch"
                                                    onchange="selectFunction()">
                                                    <option selected disabled value="">Select Entity
                                                    </option>
                                                    @foreach ($branches as $item)
                                                        <option value="{{ $item->branch_id }}"
                                                            {{ request('branch') == $item->branch_id ? 'selected' : '' }}>
                                                            {{ $item->branch_name }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-lg-3 col-md-6 col-sm-12">
                                            <div class="form-group">
                                                <label>SBU</label>
                                                <select class="form-control" name="department" id="department"
                                                    onchange="selectFunction()" disabled>
                                                    <option selected disabled value="">Select SBU
                                                    </option>
                                                </select>
                                            </div>
                                        </div>
                                    @endcan
                                </div>
                            </form>
                        </section>
                        <!-- END CUSTOM FILTER -->

                        <!-- BEGIN TABLE -->
                        <section>
                            <hr class="my-0" />
                            <div class="row">
                                <div class="table-responsive col-12 card-datatable table-rounded">
                                    <table class="table table-borderless table-hover" id="ajax-datatables">
                                        <thead>
                                            <tr>
                                                <th>No</th>
                                                <th>Employee Name</th>
                                                <th>Entity</th>
                                                <th>SBU</th>
                                                <th>Leave Type</th>
                                                <th>Date of Filling</th>
                                                <th>Submission Duration</th>
                                                <th>Total Days</th>
                                                <th>Status</th>
                                                <th>Action</th>
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
        let date = new Date().toISOString().slice(0, 10);
        let branch = 0;
        let dep = 0;
        let lety = 0;

        /**
         * 
         * Fetch data and view datatable leavereport.
         * 
         */

        const dataTable_Ajax = (date, branch, dep, lety) => {
            const dt_ajax_table = $("#ajax-datatables");
            console.log(dt_ajax_table.length);
            if (dt_ajax_table.length) {
                const dt_ajax = dt_ajax_table.dataTable({
                    retrieve: true,
                    processing: true,
                    dom: '<"d-flex justify-content-between align-items-end mx-0 row"<"col"l><"col-md-auto"f><"col col-lg-2 p-0."<"#button.btn btn-md btn-outline-primary">>>t<"d-flex justify-content-between mx-0 row"<"col-sm-12 col-md-6"i><"col-sm-12 col-md-6"p>>',
                    ajax: `/leave-reports-list?date=${date}&branch=${branch}&department=${dep}&leavetype=${lety}`,
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
                            data: (data) =>
                                `<div class="d-flex align-items-center">
                                        <img src="${data.employee_image}" alt="Employee Profile Picture" class="rounded-circle" width="32px" height="32px">
                                        <div class="p-1">
                                            <a href="/leave-reports/employee/${data.employee_id}">${data.first_name} ${data.last_name}</a><br>
                                            ${data.nip}
                                        </div>
                                    </div>`,
                            name: 'employee_name'
                        },
                        {
                            data: 'branch_name',
                            name: 'branch_name'
                        },
                        {
                            data: 'department_name',
                            name: 'sbu_name'
                        },
                        {
                            data: 'leave_type_name',
                            name: 'leave_type_name'
                        },
                        {
                            data: 'application_date',
                            name: 'application_date'
                        },
                        {
                            data: (data) => {
                                let from = data.application_from_date.split(' ');
                                let to = data.application_to_date.split(' ');

                                let reverseFrom = from[0].split('-').reverse().join(
                                    '/');
                                let reverseTo = to[0].split('-').reverse().join(
                                    '/');
                                let reverseDate = data.application_date.split('-')
                                    .reverse()
                                    .join(
                                        '/');

                                return `${reverseFrom} <br><b>-</b><br> ${reverseTo}`;
                            },
                            name: 'end_date'
                        },
                        {
                            data: 'number_of_day',
                            name: 'number_of_day'
                        },
                        {
                            // data: 'short',
                            data: (data) => {
                                if (data.code == 'lpd') {
                                    return '<span class="badge badge-pill badge-light-warning">Pending</span>';
                                } else if (data.code == 'lhn') {
                                    return '<span class="badge badge-pill badge-light-danger">Rejected</span>';
                                } else if (data.code == 'lhy') {
                                    return '<span class="badge badge-pill badge-light-success">Approved</span>';
                                } else if (data.code == 'cls') {
                                    return '<span class="badge badge-pill badge-light-info">Finished</span>';
                                } else if (data.code == 'can') {
                                    return '<span class="badge badge-pill badge-light-secondary">Canceled</span>';
                                } else if (data.code == 'lmy') {
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
                            name: 'detail',
                        }
                    ],
                });
            }

            $("#button").html('Export');
            $("#button").attr('style', 'margin-bottom: 7px');
            $("#button").click(() => exportLeave());

            $('#select-date').flatpickr({
                altInput: true,
                dateFormat: "Y-m-d",
                defaultDate: date,
                maxDate: new Date().toISOString().slice(0, 10),
                onChange: function(selectedDates, dateStr) {
                    branch = $('#branch').val();
                    dep = $('#deparment').val();
                    lety = $('#leave_types').val();
                    (branch === null) ? branch = 0: branch;
                    (dep === null) ? dep = 0: dep;
                    (lety === null) ? lety = 0: lety;
                    $("#ajax-datatables").DataTable().destroy();
                    dataTable_Ajax(dateStr, branch, dep, lety);
                },
            });
        }

        dataTable_Ajax(date, branch, dep, lety);

        /**
         * 
         * function filter with select.
         * 
         */
        let entity = '';

        const selectFunction = () => {
            let department = document.querySelector('#department');
            date = $('#select-date').val();
            branch = $('#branch').val();
            dep = $('#department').val();
            lety = $('#leave_types').val();
            (branch === null) ? branch = 0: branch;
            (dep === null) ? dep = 0: dep;
            (lety === null) ? lety = 0: lety;
            if (branch != 0) {
                fetch(`/leave-reports?branches=${branch}`)
                    .then(response => response.json())
                    .then(response => {
                        department.removeAttribute('disabled');

                        if (dep == 0 || entity != branch) {
                            if (department.length > 0) {
                                for (let i = 0; i < department.length; i++) {
                                    $('#department')
                                        .find('option')
                                        .remove();
                                }
                            }

                            const opt = document.createElement('option');
                            opt.value = '';
                            opt.selected = 'selected';
                            opt.disabled = 'disabled';
                            opt.innerHTML = 'Select SBU';
                            department.appendChild(opt);
                            if (response.departments.length > 0) {
                                response.departments.forEach((item, index) => {
                                    const opt = document.createElement('option');
                                    opt.value = item.department_id;
                                    opt.innerHTML = item.department_name;
                                    department.appendChild(opt);
                                });
                            }

                            $(document).ready(() => {
                                $('#branch').select2();
                                $('#branch_id').select2();
                                $('#work_shift_id').select2();
                                $('#days_id').select2();
                            });

                            entity = branch;
                        }
                    });
            }
            $("#ajax-datatables").DataTable().destroy();
            dataTable_Ajax(date, branch, dep, lety);
        };


        /**
         * 
         * function export leave report to excel.
         * 
         */

        const exportLeave = () => {
            date = $('#select-date').val();
            branch = $('#branch').val();
            dep = $('#department').val();
            lety = $('#leave_types').val();
            (branch === null) ? branch = 0: branch;
            (dep === null) ? dep = 0: dep;
            (lety === null) ? lety = 0: lety;

            const data = {
                date: date,
                branch: branch,
                department: dep,
                leaveTypes: lety,
                export: 'export'
            };

            fetch(`/leave-reports?date=${date}&{branch}=${branch}&deparment=${dep}&lety=${lety}&export=export`, {
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
                });
        };

        $(document).ready(() => {
            $('#department').select2();
            $('#branch').select2();
            $('#leave_types').select2();
        });

        const show = (e) => {
            $(".date").html(e.attr("date"));
        };
    </script>
@endsection
