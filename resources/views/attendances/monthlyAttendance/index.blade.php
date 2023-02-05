@extends('partials.template')
@section('main')
    <div class="app-content content ">
        <div class="content-overlay"></div>
        <div class="header-navbar-shadow"></div>
        <div class="content-wrapper">
            <div class="content-header row">
            </div>
            <div class="content-body">
                <p class="d-flex align-items-center"><img src="{{ url('./img/icons/dashboard.svg') }}" alt=""><a
                        href="{{ url('dashboard') }}" class="dashboard-link">Dashboard</a><a
                        href="{{ url('monthly-attendance') }}" class="leave-application-link">/ Attendance Report</a>
                </p>
                <div class="card">
                    <div class="card-body">
                        <h3>Monthly Recap</h3>
                        <section>
                            <div class="row">
                                <div class="col-lg-12 col-md-12 col-sm-12">
                                    <div class="row">
                                        <div class="col-lg-2 col-md-12 col-sm-12">
                                            <div class="form-group">
                                                <label>Entity :</label>
                                                <select class="select2-data-ajax custom-select" name="branch"
                                                    id="branch" onchange="selectFunction()">
                                                    <option selected disabled value=''>Select Entity</option>
                                                    @foreach ($branches as $branch)
                                                        <option value='{{ $branch->branch_id }}'>
                                                            {{ $branch->branch_name }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-lg-auto col-md-12 col-sm-12">
                                            <div class="form-group">
                                                <label>SBU
                                                    :</label>
                                                <select class="form-control" name="sbu" id="sbu" disabled
                                                    onchange="selectFunction()">
                                                    <option selected disabled value="">Select SBU</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-lg-auto col-md-12 col-sm-12">
                                            <div class="form-group">
                                                <label>Job Position
                                                    :</label>
                                                <select class="form-control" name="job_position" id="job_position" disabled
                                                    onchange="selectFunction()">
                                                    <option selected disabled value="">Select Job Position</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-lg-auto col-md-12 col-sm-12">
                                            <div class="form-group">
                                                <label>Employee
                                                    :</label>
                                                <select class="form-control" name="employee" id="employee" disabled
                                                    onchange="selectFunction()">
                                                    <option selected disabled value="">Select Employee</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-lg-auto col-md-12 col-sm-12">
                                            <div class="form-group">
                                                <label>Date
                                                    :</label>
                                                <div class="form-group">
                                                    <input type="date" name="date" id="date"
                                                        value="{{ request('date') }}" required class="form-control" />
                                                    {{-- <div class="valid-feedback">Looks good!</div> --}}
                                                    <div class="invalid-feedback">Please select date.</div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <hr>
                            <div class="row">
                                <!-- Attendance Widgets -->
                                <div class="col-md-12 col-lg-6 my-1">
                                    <div class="card card-custom" style="height: 90%">
                                        <div class="card-body shadow p-2 bg-white rounded d-flex flex-column">
                                            <div class="row employee-check p-0 mt-0 mt-md-1">

                                                <!-- Check In Time -->
                                                <div class="col-12 mt-1 mt-md-0 ml-2 pl-1">
                                                    <div class="d-flex align-items-center">
                                                        <img class="rounded-circle" id="monthly_profile_image"
                                                            src="{{ asset('img/profile.png') }}" alt="avatar"
                                                            height="120" width="120">
                                                        <div class="d-flex flex-column align-items-left">
                                                            <h4 id="name" class="mt-1 ml-2 font-weight-bolder">
                                                                -</h4>
                                                            <p id="nip" class="ml-2 font-weight">
                                                                -</p>
                                                            <h5 id="job_class" class="mt-1 ml-2 font-weight-bolder">
                                                                -</h5>
                                                        </div>
                                                    </div>

                                                </div>
                                                <!-- Check In and Out Time -->
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <!-- End Attendance Widgets -->

                                <!-- Monthly Statistic Widgets -->
                                <div class="col-md-12 col-lg-6 my-1">
                                    <div class="card card-custom" style="height: 90%">
                                        <div class="card-body shadow p-2 bg-white rounded">
                                            <div class="d-flex justify-content-between align-items-center">
                                                <h4 class="m-0 month-title">Monthly Statistic :</h4>
                                                <h4 class="m-0 title-workshift head-title-workshift font-weight-bolder">
                                                    {{ date(' F Y') }}</h4>
                                            </div>

                                            <div class="row mt-2">
                                                <div class="col-4 d-flex">
                                                    <i>
                                                        <img class="attendance-icon carousel-icon"
                                                            src="./img/icons/calendar-circle.svg" />
                                                    </i>
                                                    <div class="pl-1">
                                                        <p class="m-0"><b id="attend-total-days">-</b></p>
                                                        <p class="carousel-capt m-0">Work Day</p>
                                                    </div>
                                                </div>
                                                <div class="col-4 d-flex">
                                                    <i>
                                                        <img class="attendance-icon carousel-icon"
                                                            src="./img/icons/checkout-circle.svg" />

                                                    </i>
                                                    <div class="pl-1">
                                                        <p class="m-0 font-weight-bolder">
                                                            <span id="late_hours">- H - M</span>
                                                        </p>
                                                        <p class="carousel-capt">Total Late In</p>
                                                    </div>
                                                </div>
                                                <div class="col-4 d-flex ">
                                                    <i>
                                                        <img class="attendance-icon carousel-icon"
                                                            src="./img/icons/absence-circle.svg" />

                                                    </i>
                                                    <div class="pl-1">
                                                        <p class="m-0"><b id="attend-absence">-</b></p>
                                                        <p class="carousel-capt">Absence</p>
                                                    </div>
                                                </div>

                                                <div class="col-4 d-flex mt-2">
                                                    <i>
                                                        <img class="attendance-icon carousel-icon"
                                                            src="./img/icons/leave-circle.svg" />
                                                    </i>
                                                    <div class="pl-1">
                                                        <p class="m-0 font-weight-bolder">
                                                            <span id="leave-balance">-</span>
                                                        </p>
                                                        <p class="carousel-capt">Leave Balance</p>
                                                    </div>
                                                </div>
                                                <div class="col-4 d-flex mt-2">
                                                    <div class="d-flex" id="attend-popover" data-toggle="popover"
                                                        data-content="88%" data-trigger="hover"
                                                        data-original-title="Total Attend" data-placement="bottom">
                                                        <i>
                                                            <img class="attendance-icon carousel-icon"
                                                                src="./img/icons/location-circle.svg" />

                                                        </i>
                                                        <div class="pl-1">
                                                            <p class="m-0"><b id="attend-days">-</b></p>
                                                            <p class="carousel-capt">Attend</p>
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="col-4 d-flex mt-2">
                                                    <i>
                                                        <img class="attendance-icon carousel-icon"
                                                            src="./img/icons/add-time-circle.svg" />
                                                    </i>
                                                    <div class="pl-1">
                                                        <p class="m-0 font-weight-bolder">
                                                            <span id="attend-overtime">- H - M</span>
                                                        </p>
                                                        <p class="carousel-capt">Overtime</p>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- End Monthly Statistic Widgets -->
                                </div>
                        </section>
                        <hr>
                        <section>
                            <div class="row">
                                <div class="table-responsive col-12 table-rounded">
                                    <table class="table table-borderless" width="100%" id="datatables-ajax">
                                        <thead>
                                            <tr>
                                                <th scope="col">Date</th>
                                                <th scope="col">Day</th>
                                                <th scope="col">Check In</th>
                                                <th scope="col">Check Out</th>
                                                <th scope="col">Work Time</th>
                                                <th scope="col">Lateness</th>
                                                <th scope="col">Late Time</th>
                                                <th scope="col">Overtime</th>
                                                <th scope="col">Shift</th>
                                                <th scope="col">Notes</th>
                                                <th scope="col">Status</th>
                                                <th scope="col">Action</th>
                                            </tr>
                                        </thead>
                                    </table>
                                </div>
                            </div>
                        </section>
                        <div class="modal fade text-left" id="modal-notes" tabindex="-1" role="dialog"
                            aria-labelledby="myModalLabel33" aria-hidden="true">
                            <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable" role="document">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h4 class="modal-title" id="modal_title-note">Notes Attendance</h4>
                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                            <span aria-hidden="true">&times;</span>
                                        </button>
                                    </div>
                                    <div class="modal-body mb-2">
                                        <div class="mb-1">
                                            <span class="note_date"></span>
                                        </div>
                                        <label id="label_check_in">Note Check In: </label>
                                        <div class="form-group">
                                            <textarea class="form-control" id="note_check_in" readonly></textarea>
                                            <div class="invalid-feedback">Please enter appropriate value.</div>
                                        </div>

                                        <label id="label_check_out">Note Check Out: </label>
                                        <div class="form-group">
                                            <textarea type="text" class="form-control" id="note_check_out" readonly></textarea>
                                            <div class="invalid-feedback"></div>
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
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
    <script src="https://cdn.jsdelivr.net/npm/flatpickr@latest/dist/plugins/monthSelect/index.js"></script>
    <script src="{{ url('app-assets/js/scripts/components/components-popovers.js') }}"></script>
@endsection

@section('page_script')
    <script>
        let entity = 0;
        let employee = 0;
        let sbu = 0;
        let job_position = 0;

        let date = "{{ date('Y-m') }}";

        $(document).ready(() => {
            $('#branch').select2();
            // statistics(date, entity, employee);
        });

        flatpickr("#date", {
            // defaultDate: ["today"],
            plugins: [
                new monthSelectPlugin({
                    shorthand: true, //defaults to false
                    dateFormat: "Y-m", //defaults to "F Y"
                    altFormat: "F Y", //defaults to "F Y"
                    theme: "light" // defaults to "light"
                })
            ],
            altInput: true,
            defaultDate: date,
            onChange: (selectedDates, dateStr) => {
                employee = $('#employee').val();
                entity = $('#branch').val();
                (employee === null) ? employee = 0: employee;
                (entity === null) ? entity = 0: entity;
                $('#datatables-ajax').DataTable().destroy();
                dataTable_Ajax(dateStr, entity, employee, sbu, job_position);
                statistics(dateStr, entity, employee, sbu, job_position);
            },
        });

        const dataTable_Ajax = (date, entity, sbu, job_position, employee) => {
            var dt_ajax_table = $("#datatables-ajax");
            // console.log(dt_ajax_table.length);
            if (dt_ajax_table.length) {
                const dt_ajax = dt_ajax_table.dataTable({
                    processing: true,
                    ordering: false,
                    dom: '<"d-flex justify-content-between align-items-end mx-0 row"<"col"l><"col-md-auto"f><"col col-lg-2 p-0."<"#button.btn btn-md btn-outline-primary">>>t<"d-flex justify-content-between mx-0 row"<"col-sm-12 col-md-6"i><"col-sm-12 col-md-6"p>>',
                    // drawCallback: function() {
                    //     $(this.api().table().header()).hide();
                    // },
                    ajax: {
                        url: `/monthly-attendance-list?date=${date}&entity=${entity}&sbu=${sbu}&job_position=${job_position}&employee=${employee}`,
                        method: 'GET'
                    },
                    scrollX: true,
                    language: {
                        paginate: {
                            // remove previous & next text from pagination 
                            previous: '&nbsp;',
                            next: '&nbsp;'
                        },
                    },
                    columns: [{
                            data: 'date',
                            name: 'date'
                        },
                        {
                            data: 'day',
                            name: 'day'
                        },
                        {
                            data: (data) => {
                                if (data.check_in) {
                                    const checkIn = data.check_in.split(' ');
                                    return checkIn[1].substr(0, 5);
                                }

                                return '-';
                            },
                            name: 'check-in'
                        },
                        {
                            data: (data) => {
                                if (data.check_out) {
                                    const checkOut = data.check_out.split(' ');
                                    return checkOut[1].substr(0, 5);
                                }

                                return '-';
                            },
                            name: 'check-out'
                        },
                        {
                            data: (data) => {
                                if (data.working_hour !== null) {
                                    return `${parseInt(data.working_hour.substr(0, 2))} H ${parseInt(data.working_hour.substr(4, 6))} M`;
                                }

                                return '-';
                            },
                            name: 'work_time'
                        },
                        {
                            data: (data) => {
                                if (data.late_duration > 0) {
                                    return '<span class="badge badge-pill badge-light-danger">Yes</span>';
                                }

                                return '<span class="badge badge-pill badge-light-success">No</span>';
                            },
                            name: 'lateness'
                        },
                        {
                            data: (data) => {
                                if (data.late_duration > 0) {
                                    return `${Math.floor(data.late_duration / 60)} H ${data.late_duration % 60} M`;
                                }

                                return '-';
                            },
                            name: 'late_duration'
                        },
                        {
                            data: (data) => {
                                if (data.overtime_duration > 0) {
                                    return `${Math.floor(data.overtime_duration / 60)} H ${data.overtime_duration % 60} M`;
                                }

                                return '-';
                            },
                            name: 'overtime_duration'
                        },
                        {
                            data: 'shift',
                            name: 'shift'
                        },
                        {
                            data: 'notes',
                            name: 'notes'
                        },
                        {
                            data: (data) => {
                                switch (data.status) {
                                    case 'acm':
                                        return '<div class="badge badge-pill badge-light-success">Present</div>';
                                        break;
                                    case 'acw':
                                        return '<div class="badge badge-pill badge-light-success">Present</div>';
                                        break;
                                    case 'amw':
                                        return '<div class="badge badge-pill badge-light-success">Present</div>';
                                        break;
                                    case 'amm':
                                        return '<div class="badge badge-pill badge-light-success">Present</div>';
                                        break;
                                    case 'aab':
                                        return '<div class="badge badge-pill badge-light-danger">Absent</div>';
                                        break;
                                    case 'asc':
                                        return '<div class="badge badge-pill badge-light-secondary">Sick</div>';
                                        break;
                                    case 'apm':
                                        return '<div class="badge badge-pill badge-light-info">Permission</div>';
                                        break;
                                    case 'ado':
                                        return '<div class="badge badge-pill badge-light-secondary">Day Off</div>';
                                        break;
                                    case 'arj':
                                        return '<div class="badge badge-pill badge-light-danger">Rejected</div>';
                                        break;
                                    case 'can':
                                        return '<div class="badge badge-pill badge-light-secondary">Cancelled</div>';
                                        break;
                                    default:
                                        return '<div class="badge badge-pill badge-light-warning">Pending</div>';
                                        break;
                                }
                            },
                            name: 'status'
                        },
                        {
                            data: (data) => {
                                return `<a class="btn btn-outline-info round waves-effect button-rounded" href="/daily-attendance/${data.id}"> Details </a>`
                            },
                            name: 'detail',
                        }

                    ],
                    columnDefs: [{
                        targets: 10,
                        className: 'text-center',
                    }],
                    rowCallback: function(hlRow, hlData, hlIndex) {

                        if (hlData.status_code == 'aab' || hlData.status_code == 'arj') {
                            $('td', hlRow).css('background', '#ea54551f');
                        } else if (hlData.status_code == 'ado' || hlData.status_code == 'asc') {
                            $('td', hlRow).css('background', '#6c757d1f');
                        } else if (hlData.status_code == 'apm') {
                            $('td', hlRow).css('background', '#00CFE81F');
                        }
                    }
                });
            }
            $("#button").html('Export');
            $("#button").attr('style', 'margin-bottom: 7px');
            $("#button").click(() => exportAttendance());

        }

        let branchId = '';
        let dep = '';
        let job = '';

        const selectFunction = () => {
            let branch = document.querySelector('#branch');
            let employeeId = document.querySelector('#employee');
            let sbuId = document.querySelector('#sbu');
            let jobPositionId = document.querySelector('#job_position');
            date = $('#date').val();
            employee = $('#employee').val();
            entity = $('#branch').val();
            sbu = $('#sbu').val();
            job_position = $('#job_position').val();
            (date == '') ? date = 0: date;
            (employee === null) ? employee = 0: employee;
            (entity === null) ? entity = 0: entity;
            (sbu === null) ? sbu = 0: sbu;
            (job_position === null) ? job_position = 0: job_position;

            if (entity != 0) {
                fetch(`/monthly-attendance?branch=${entity}`)
                    .then(response => response.json())
                    .then(response => {
                        sbuId.removeAttribute('disabled');

                        if (sbu == 0 || branchId != entity) {
                            if (sbuId.length > 0) {
                                for (let i = 0; i < sbuId.length; i++) {
                                    $('#sbu')
                                        .find('option')
                                        .remove();
                                }
                            }

                            const opt = document.createElement('option');
                            opt.value = '';
                            opt.selected = 'selected';
                            opt.disabled = 'disabled';
                            opt.innerHTML = 'Select SBU';
                            sbuId.appendChild(opt);
                            if (response.sbu.length > 0) {
                                response.sbu.forEach((item, index) => {
                                    const opt = document.createElement('option');
                                    opt.value = item.department_id;
                                    opt.innerHTML = `${item.department_name}`;
                                    sbuId.appendChild(opt);
                                });
                            }

                            $(document).ready(() => {
                                $('#branch').select2();
                                $('#sbu').select2();
                                $('#job_position').select2();
                                $('#employee').select2();
                            });

                            branchId = entity;
                        }
                    });
            }

            if (sbu != 0) {
                fetch(`/monthly-attendance?sbu=${sbu}`)
                    .then(response => response.json())
                    .then(response => {
                        jobPositionId.removeAttribute('disabled');

                        if (job_position == 0 || dep != sbu) {
                            if (jobPositionId.length > 0) {
                                for (let i = 0; i < jobPositionId.length; i++) {
                                    $('#job_position')
                                        .find('option')
                                        .remove();
                                }
                            }

                            const opt = document.createElement('option');
                            opt.value = '';
                            opt.selected = 'selected';
                            opt.disabled = 'disabled';
                            opt.innerHTML = 'Select Job Position';
                            jobPositionId.appendChild(opt);
                            if (response.job_position.length > 0) {
                                response.job_position.forEach((item, index) => {
                                    const opt = document.createElement('option');
                                    opt.value = item.job_position_id;
                                    opt.innerHTML = `${item.job_position}`;
                                    jobPositionId.appendChild(opt);
                                });
                            }

                            $(document).ready(() => {
                                $('#branch').select2();
                                $('#sbu').select2();
                                $('#job_position').select2();
                                $('#employee').select2();
                            });

                            dep = sbu;
                        }
                    });
            }
            if (job_position != 0) {
                fetch(`/monthly-attendance?job_position=${job_position}`)
                    .then(response => response.json())
                    .then(response => {
                        employeeId.removeAttribute('disabled');

                        if (employee == 0 || job != job_position) {
                            if (employeeId.length > 0) {
                                for (let i = 0; i < employeeId.length; i++) {
                                    $('#employee')
                                        .find('option')
                                        .remove();
                                }
                            }

                            const opt = document.createElement('option');
                            opt.value = '';
                            opt.selected = 'selected';
                            opt.disabled = 'disabled';
                            opt.innerHTML = 'Select Employee';
                            employeeId.appendChild(opt);
                            if (response.employees.length > 0) {
                                response.employees.forEach((item, index) => {
                                    const opt = document.createElement('option');
                                    opt.value = item.employee_id;
                                    opt.innerHTML = `${item.first_name} ${item.last_name}`;
                                    employeeId.appendChild(opt);
                                });
                            }

                            $(document).ready(() => {
                                $('#branch').select2();
                                $('#sbu').select2();
                                $('#job_position').select2();
                                $('#employee').select2();
                            });

                            job = job_position;
                        }
                    });
            }

            $("#datatables-ajax").DataTable().destroy();
            dataTable_Ajax(date, entity, employee, sbu, job_position);
            statistics(date, entity, employee, sbu, job_position);
        };

        /**
         * * Fetch Statistics
         * 
         **/
        const statistics = (date, entity, employee, sbu, job_position) => {
            fetch(
                    `/monthly-attendance-statistic?date=${date}&entity=${entity}&employee=${employee}&sbu=${sbu}&job_position=${job_position}`
                )
                .then(response => response.json())
                .then(response => {

                    $("#name").html(response.employee);
                    $("#nip").html(response.nip);
                    $("#job_class").html(response.jobclass);
                    document.getElementById("monthly_profile_image").src = response.image;
                    $("#attend-total-days").html(response.workday);
                    $("#attend-days").html(response.attend);
                    $("#late_hours").html(response.late);
                    $("#leave-balance").html(response.leave);
                    $("#attend-absence").html(response.absence);
                    $("#attend-overtime").html(response.overtime);
                });
        };

        const exportAttendance = () => {
            date = $('#date').val();
            employee = $('#employee').val();
            entity = $('#branch').val();
            (employee === null) ? employee = 0: employee;
            (entity === null) ? entity = 0: entity;

            const data = {
                date: date,
                employee: employee,
                branch: entity,
                export: 'export'
            };

            fetch(`/monthly-attendance-list?date=${date}&entity=${entity}&employee=${employee}&export=export`, {
                    method: 'GET'
                })
                .then(response => response.blob())
                .then(data => {
                    console.log('Success:', data);
                    const url = window.URL.createObjectURL(data);
                    const a = document.createElement('a');
                    a.href = url;
                    a.download = "monthly_attendance.xlsx";
                    document.body.appendChild(
                        a); // we need to append the element to the dom -> otherwise it will not work in firefox
                    a.click();
                    a.remove(); //afterwards we remove the element again  
                })
                .catch((error) => {
                    console.error('Error:', error);
                });
        };

        const notes = (e) => {
            $('#modal-notes').modal('show');
            const id = e.attr('data-id');
            $.ajax({
                url: "{{ url('attendance-self-service') }}" + "/" + id,
                method: "GET",
                dataType: "json",
                success: function(result) {
                    if (result.data.status === 'acw' || result.data.status === 'acm') {
                        $('#note_check_out').show();
                        $('#note_check_in').attr('rows', 2);
                        $('#label_check_out').html('Note Check Out: ');
                        $('#label_check_in').html('Note Check In: ');
                        $('.note_date').html(`<b>${result.data.date}</b>`);
                        $('#note_check_in').html(result.data.note_check_in);
                        $('#note_check_out').html(result.data.note_check_out);

                    } else {
                        $('#label_check_out').html('');
                        $('#label_check_in').html('Notes');
                        $('#note_check_in').attr('rows', 10);
                        $('.note_date').html(`<b>${result.data.date}</b>`);
                        $('#note_check_in').html(result.data.note);
                        $('#note_check_out').hide();
                    }

                }
            });
        }

        dataTable_Ajax(date, entity, employee, sbu, job_position);
        statistics(date, entity, employee, sbu, job_position);
    </script>
@endsection
