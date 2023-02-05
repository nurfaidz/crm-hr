@extends('partials.template')
@section('main')
    <div class="app-content content ">
        <div class="content-overlay"></div>
        <div class="header-navbar-shadow"></div>
        <div class="content-wrapper">
            <div class="content-header row">

            </div>
            <div class="content-body">
                <div class="card">
                    <div class="card-body">
                        <h3 class="font-weight-bold">Daily Recap</h3>
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
                                                <div class="invalid-feedback">Please select holiday start date.</div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-lg-3 col-md-6 col-sm-12">
                                        <div class="form-group">
                                            <label>Entity</label>
                                            <select class="form-control" name="branch" id="branch"
                                                onchange="selectFunction()">
                                                <option selected disabled value="">Select Entity</option>
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
                                            <select class="form-control" name="department" id="department" disabled
                                                onchange="selectFunction()">
                                                <option selected disabled value="">Select SBU</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-lg-3 col-md-6 col-sm-12">
                                        <div class="form-group">
                                            <label>Job Position</label>
                                            <select class="form-control" name="position" id="position" disabled
                                                onchange="selectFunction()">
                                                <option selected disabled value="">Select Job Position</option>
                                            </select>
                                        </div>
                                    </div>
                            </form>
                        </section>
                        <section>
                            <div class="row">
                                <div class="table-responsive col-12 card-datatable table-rounded">
                                    <table class="table table-borderless table-hover" id="datatables-ajax">
                                        <thead>
                                            <tr>
                                                <th scope="col">No</th>
                                                <th scope="col">Employee Name</th>
                                                <th scope="col">Entity</th>
                                                <th scope="col">SBU</th>
                                                <th scope="col">Job Position</th>
                                                <th scope="col">Date</th>
                                                <th scope="col">Check-In</th>
                                                <th scope="col">Check-Out</th>
                                                <th scope="col">Work Time</th>
                                                <th scope="col">Lateness</th>
                                                <th scope="col">Late Time</th>
                                                <th scope="col">Overtime</th>
                                                <th scope="col">Notes</th>
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

    <div class="modal fade text-left" id="modal-notes" tabindex="-1" role="dialog" aria-labelledby="modal-title"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="modal-title">Notes Attendance</h4>
                    <button type="button" class="close mr-0 mt-0" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="content-body">
                    <div class="card">
                        <div class="card-body">
                            <h5 class="card-title date"></h5>
                            <div id="notes"></div>
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
        let date = new Date().toISOString().slice(0, 10);
        let entity = 0;
        let sbu = 0;
        let jobPosition = 0;

        const dataTable_Ajax = (date, entity, sbu, position) => {
            const dt_ajax_table = $("#datatables-ajax");
            console.log(dt_ajax_table.length);
            if (dt_ajax_table.length) {
                const dt_ajax = dt_ajax_table.dataTable({
                    retrieve: true,
                    processing: true,
                    dom: '<"d-flex justify-content-between align-items-end mx-0 row"<"col"l><"col-md-auto"f><"col col-lg-2 p-0."<"#button.btn btn-md btn-outline-primary">>>t<"d-flex justify-content-between mx-0 row"<"col-sm-12 col-md-6"i><"col-sm-12 col-md-6"p>>',
                    ajax: `/daily-attendance-list/${date}/${entity}/${sbu}/${position}`,
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
                                            <b>${data.first_name} ${data.last_name}</b><br>
                                            ${data.nip}
                                        </div>
                                    </div>`,
                            name: 'employee_name'
                        },
                        {
                            data: 'branch_name',
                            name: 'entity'
                        },
                        {
                            data: 'department_name',
                            name: 'sbu'
                        },
                        {
                            data: 'job_position',
                            name: 'job_position'
                        },
                        {
                            data: 'date',
                            name: 'date'
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
                                    return '<span class="badge badge-pill badge-light-danger">Ya</span>';
                                }

                                return '<span class="badge badge-pill badge-light-success">Tidak</span>';
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
                            data: 'action',
                            name: 'action'
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
                        targets: 13,
                        className: 'text-center'
                    }]
                });
            }

            $("#button").html('Export');
            $("#button").attr('style', 'margin-bottom: 7px');
            $("#button").click(() => exportAttendance());

            $('#select-date').flatpickr({
                altInput: true,
                dateFormat: "Y-m-d",
                defaultDate: date,
                maxDate: new Date().toISOString().slice(0, 10),
                onChange: function(selectedDates, dateStr) {
                    entity = $('#branch').val();
                    sbu = $('#department').val();
                    jobPosition = $('#position').val();
                    (entity === null) ? entity = 0: entity;
                    (sbu === null) ? sbu = 0: sbu;
                    (jobPosition === null) ? jobPosition = 0: jobPosition;
                    $("#datatables-ajax").DataTable().destroy();
                    dataTable_Ajax(dateStr, entity, sbu, jobPosition);
                },
            });
        }

        dataTable_Ajax(date, entity, sbu, jobPosition);

        let branch = '';
        let dep = '';

        const selectFunction = () => {
            let department = document.querySelector('#department');
            let position = document.querySelector('#position');
            date = $('#select-date').val();
            entity = $('#branch').val();
            sbu = $('#department').val();
            jobPosition = $('#position').val();
            (entity === null) ? entity = 0: entity;
            (sbu === null) ? sbu = 0: sbu;
            (jobPosition === null) ? jobPosition = 0: jobPosition;
            if (entity != 0) {
                fetch(`/daily-attendance?branches=${entity}`)
                    .then(response => response.json())
                    .then(response => {
                        department.removeAttribute('disabled');

                        if (sbu == 0 || branch != entity) {
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

                            const opt2 = document.createElement('option');
                            opt2.value = '';
                            opt2.selected = 'selected';
                            opt2.disabled = 'disabled';
                            opt2.innerHTML = 'Select Job Position';
                            position.appendChild(opt2);
                            position.setAttribute('disabled', 'disabled');

                            $(document).ready(() => {
                                $('#department').select2();
                                $('#branch').select2();
                                $('#position').select2();
                            });

                            branch = entity;
                        }
                    });
            }

            if (sbu != 0) {
                fetch(`/daily-attendance?departments=${sbu}`)
                    .then(response => response.json())
                    .then(response => {
                        position.removeAttribute('disabled');

                        if (jobPosition == 0 || dep != sbu) {
                            if (position.length > 0) {
                                for (let i = 0; i < position.length; i++) {
                                    $('#position')
                                        .find('option')
                                        .remove();
                                }
                            }

                            const opt = document.createElement('option');
                            opt.value = '';
                            opt.selected = 'selected';
                            opt.disabled = 'disabled';
                            opt.innerHTML = 'Select Job Position';
                            position.appendChild(opt);
                            if (response.positions.length > 0) {
                                response.positions.forEach((item, index) => {
                                    const opt = document.createElement('option');
                                    opt.value = item.job_position_id;
                                    opt.innerHTML = item.job_position;
                                    position.appendChild(opt);
                                });
                            }

                            $(document).ready(() => {
                                $('#department').select2();
                                $('#branch').select2();
                                $('#position').select2();
                            });

                            dep = sbu;
                        }
                    });
            }
            $("#datatables-ajax").DataTable().destroy();
            dataTable_Ajax(date, entity, sbu, jobPosition);
        };

        const exportAttendance = () => {
            date = $('#select-date').val();
            entity = $('#branch').val();
            sbu = $('#department').val();
            jobPosition = $('#position').val();
            (entity === null) ? entity = 0: entity;
            (sbu === null) ? sbu = 0: sbu;
            (jobPosition === null) ? jobPosition = 0: jobPosition;

            const data = {
                date: date,
                branch: entity,
                department: sbu,
                position: jobPosition,
                export: 'export'
            };

            fetch(`/daily-attendance?date=${date}&branch=${entity}&department=${sbu}&position=${jobPosition}&export=export`, {
                    method: 'GET'
                })
                .then(response => response.blob())
                .then(data => {
                    console.log('Success:', data);
                    const url = window.URL.createObjectURL(data);
                    const a = document.createElement('a');
                    a.href = url;
                    a.download = "daily_attendance.xlsx";
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
            $('#position').select2();
        });

        const show = (e) => {
            $(".date").html(e.attr("date"));
            console.log(e.attr("note"));
            if (e.attr("note") != '') {
                $("#notes").html(`<div class="form-group">
                                    <label>Note Manual Attendance</label>
                                    <div class="form-group">
                                        <textarea class="form-control note-check-in" id="exampleFormControlTextarea1" rows="3" readonly>${e.attr("note")}</textarea>
                                    </div>
                                </div>`);
            } else {
                $("#notes").html(`<div class="form-group">
                                    <label>Check In Note</label>
                                    <div class="form-group">
                                        <textarea class="form-control note-check-in" id="exampleFormControlTextarea1" rows="3" readonly>${e.attr("in")}</textarea>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label>Check Out Note</label>
                                    <div class="form-group">
                                        <textarea class="form-control note-check-in" id="exampleFormControlTextarea1" rows="3" readonly>${e.attr("Out")}</textarea>
                                    </div>
                                </div>`);
            }
        };
    </script>
@endsection
