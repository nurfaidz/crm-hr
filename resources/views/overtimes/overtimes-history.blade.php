@extends('partials.template')
@section('meta_header')
    <meta name="csrf-token" content="{{ csrf_token() }}">
@endsection
@section('main')
    <div class="app-content content ">
        <div class="content-overlay"></div>
        <div class="header-navbar-shadow"></div>
        <div class="content-wrapper">
            <div class="content-header row"></div>
            <div class="content-body">
                <div class="card">
                    <div class="card-body">
                        <section id="ajax-datatable">
                            <div class="row">
                                <div class="col-12">
                                    <div class="card">
                                        <h3>Overtime</h3>
                                        <h5>Overtime History</h5>
                                        <div class="card-datatable table-responsive table-rounded">
                                            <table class="table table-borderless" id="datatables-ajax">
                                                <thead>
                                                    <tr>
                                                        <th>Employee Name</th>
                                                        <th>Holding</th>
                                                        <th>Entity</th>
                                                        <th>SBU</th>
                                                        <th>Job Position</th>
                                                        <th>Shift</th>
                                                        <th>Date</th>
                                                        <th>Start Time</th>
                                                        <th>End Time</th>
                                                        <th>Work Time</th>
                                                        <th class="text-center">Notes</th>
                                                        <th>Status</th>
                                                        <th>Action</th>
                                                    </tr>
                                                </thead>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </section>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade text-left" id="modal-form" tabindex="-1" role="dialog" aria-labelledby="modal-request-overtime"
        aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="modal_title">Set Overtime</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="container">
                    <form id="form_data" action="{{ url('overtimes-self-service') }}" class="form-data-validate"
                        novalidate method="POST" enctype="multipart/form-data">
                        {{ csrf_field() }}
                        <div class="modal-body">
                            <div class="row">
                                <div class="col-md-12 mt-1">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <label>Employee Name: <span class="text-danger">*</span></label>
                                            <div class="form-group">
                                                <select name="employee_id" id="employee_id" class="select2 form-control">
                                                    <option hidden disabled selected value>-- Select ---</option>
                                                    @foreach ($employee as $employeeResult)
                                                        <option value="{{ $employeeResult->employee_id }}">
                                                            {{ $employeeResult->first_name . ' ' . $employeeResult->last_name }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                                <div class="invalid-feedback employee_id_error">Please enter appropriate
                                                    value.</div>
                                            </div>
                                            <input type="hidden" id="shift_id" name="shift_id">
                                            <label>Shift: </label>
                                            <div class="form-group">
                                                <input type="text" class="form-control" id="shift" value=""
                                                    readonly />
                                            </div>
                                            <label>Overtime Date: </label><span class="text-danger">*</span></label>
                                            <div class="form-group">
                                                <input type="date" placeholder="Choose Start Date" name="date"
                                                    id="overtime_date" required class="form-control" />
                                                <div class="invalid-feedback date_error">Please enter appropriate value.
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <label>Start Time: </label><span class="text-danger">*</span></label>
                                            <div class="form-group">
                                                <input type="date" name="start_time" id="overtime_start_time"
                                                    class="form-control" required />
                                                <div class="invalid-feedback start_time_error">Please enter appropriate
                                                    value.</div>
                                            </div>
                                            <label>End Time: </label><span class="text-danger">*</span></label>
                                            <div class="form-group">
                                                <input type="date" name="end_time" id="overtime_end_time"
                                                    class="form-control" required />
                                                <div class="invalid-feedback end_time_error">Please enter appropriate value.
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-12">
                                            <label>Overtime Note <span class="text-danger">*</span></label>
                                            <div class="form-group">
                                                <textarea type="text" name="notes" class="form-control" required></textarea>
                                                <div class="invalid-feedback notes_error">Please enter appropriate value.
                                                </div>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="submit" class="btn btn-primary">Set Overtime</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade text-left" id="modal-form-notes" tabindex="-1" role="dialog" aria-labelledby="myModalLabel33"
        aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="modal_title">Notes Detail</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="form-group mt-1 details">
                        <textarea type="text" id="notes" style="height: 130px" required class="form-control" readonly></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-primary " data-dismiss="modal"
                        aria-label="Close">Close</button>
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
    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.26.0/moment.min.js"></script>
@endsection

@section('page_script')
    <script>
        let workdays = null;
        let overtimePending = null;
        let overtimeRemaining = null;
        let todayOvertimeStats = null;

        let date = "{{ date('Y-m-d') }}";

        $(document).ready(function() {
            $('.select2').select2();

            $('#modal-form').on('hidden.bs.modal', function(event) {
                let reset_form = $('#form_data')[0];

                $("#form_data").trigger("reset");
                $(reset_form).removeClass('was-validated');
                $("#modal_title").html("Set Overtime");
                $("#employee_id").val("").change();
                $("#form_data").trigger("reset");

            });

            flatpickr("#overtime_date", {
                altInput: true,
                altFormat: "j F Y",
                dateFormat: "Y-m-d",
                minDate: "today",
                allowInput: true,
                onReady: function(selectedDates, dateStr, instance) {
                    $(instance.altInput).prop('readonly', false);
                },
            });

            flatpickr("#overtime_start_time", {
                enableTime: true,
                noCalendar: true,
                dateFormat: "H:i",
                time_24hr: true,
                allowInput: true,
                onReady: function(selectedDates, dateStr, instance) {
                    $(instance.altInput).prop('readonly', false);
                },
            });

            flatpickr("#overtime_end_time", {
                enableTime: true,
                noCalendar: true,
                dateFormat: "H:i",
                time_24hr: true,
                allowInput: true,
                onReady: function(selectedDates, dateStr, instance) {
                    $(instance.altInput).prop('readonly', false);
                },
            });


            dataTable_Ajax(date);

            const employee = $('#employee_id').change(() => {
                const id = $('#employee_id option:selected').val();
                console.log(id)
                getEmployee(id);
            })

        });


        Array.prototype.filter.call($('#form_data'), function(form) {
            form.addEventListener('submit', function(event) {
                if (form.checkValidity() === false) {
                    form.classList.add('invalid');
                }
                form.classList.add('was-validated');
                event.preventDefault();

                const url = "{{ url('overtime-history') }}";

                $.ajax({
                    url: url,
                    type: 'POST',
                    data: $('#form_data').serialize(),
                    // contentType: 'application/json', 
                    processData: false,
                    success: (response) => {

                        if (response.error) {
                            $.each(response.error, function(prefix, val) {
                                $('div.' + prefix + '_error').text(val[0]);
                            });
                        } else {

                            setTimeout(() => {
                                $('#datatables-ajax').DataTable().ajax.reload();
                            }, 1000);

                            Swal.fire({
                                type: "success",
                                title: 'Success!',
                                text: response.message,
                                confirmButtonClass: 'btn btn-success',
                            });

                            let reset_form = $('#form_data')[0];
                            $(reset_form).removeClass('was-validated');
                            reset_form.reset();
                            $('#modal-form').modal('hide');
                            $("#modal_title").html("Add Data Entity")
                            $("#branch_id").val()
                        }

                    },
                });

            });

        });

        const dataTable_Ajax = (date) => {
            const dt_ajax_table = $("#datatables-ajax");
            const base_url = "{{ url('overtime-history/get-overtime') }}";
            console.log(dt_ajax_table.length);
            if (dt_ajax_table.length) {
                const dt_ajax = dt_ajax_table.dataTable({
                    processing: true,
                    ordering: false,
                    dom: '<"d-flex justify-content-between align-items-end mx-0 row"<"col"l><"#create-data"><"col-md-auto"f><"col col-lg-2 p-0."<"#create.btn btn-md btn-primary">>>t<"d-flex justify-content-between mx-0 row"<"col-sm-12 col-md-6"i><"col-sm-12 col-md-6"p>>',
                    ajax: `${base_url}/${date}`,
                    scrollX: true,
                    language: {
                        paginate: {
                            // remove previous & next text from pagination 
                            previous: '&nbsp;',
                            next: '&nbsp;'
                        },
                    },
                    columns: [{
                            data: (data) => {
                                return `<div class="d-flex align-items-center">
                                            <img src="${data.employee_image}" alt="Employee Profile Picture" class="rounded-circle" width="32px" height="32px">
                                        <div class="p-1">
                                            <b>${data.first_name} ${data.last_name}</b><br>
                                            ${data.nip}
                                        </div>
                                    </div>`
                            },
                            name: 'Employee Name'
                        },
                        {
                            data: 'holding',
                            name: 'holding'
                        },
                        {
                            data: 'entity',
                            name: 'action'
                        },
                        {
                            data: 'department',
                            name: 'sbu'
                        },
                        {
                            data: 'job_position',
                            name: 'Job Position'
                        },
                        {
                            data: 'shift_name',
                            name: 'shift'
                        },
                        {
                            data: 'date',
                            name: 'date'
                        },
                        {
                            data: 'start_time',
                            name: 'start time'
                        },
                        {
                            data: 'end_time',
                            name: 'end date'
                        },
                        {
                            data: (data) => {
                                return `${data.work_shift.h} H ${data.work_shift.m} M`
                            },
                            name: 'work time'
                        },
                        {
                            data: 'notes',
                            name: 'notes'
                        },
                        {
                            data: 'status',
                            name: 'status'
                        },
                        {
                            data: 'action',
                            name: 'action'
                        },
                    ],
                    columnDefs: [{
                        targets: 3,
                        className: 'text-center',
                    }]
                });
            }

            $("#create-data").html(`
                <div class="input-group">
                    <div class="input-group-prepend">
                        <div class="input-group-text"><img src="./img/icons/calendar-icon.svg" alt="calendar"></div>
                    </div>
                    <input type="date" class="form-control" id="select-date">
                </div>
            `);

            $("#create-data").attr('style', 'margin-bottom: 7px');

            $("#create").html('Set Overtime');
            $("#create").attr('style', 'margin-bottom: 8px');
            $("#create").attr('data-toggle', 'modal');
            $("#create").attr('data-target', '#modal-form');

            flatpickr("#select-date", {
                altInput: true,
                altFormat: "d F Y",
                dateFormat: 'Y-m-d',
                defaultDate: date,
                maxDate: 'today',
                onChange: (selectedDates, dateStr) => {
                    dt_ajax_table.DataTable().destroy();
                    dataTable_Ajax(dateStr);
                },
            });
        }

        const NotesDetail = (e) => {
            $('#modal-form-notes').modal('show');
            const base_url = "{{ url('overtime-history') }}";
            const id = e.attr('data-id');
            $.ajax({
                url: `${base_url}/${id}/details`,
                method: "GET",
                dataType: "json",
                success: (result) => {
                    $('#notes').val(result.data.notes);
                }
            });
        }

        const getEmployee = (id) => {
            const base_url = "{{ url('overtime-history/employee') }}";
            $.ajax({
                url: base_url,
                method: "POST",
                data: {
                    'employee_id': id,
                    '_token': $('input[name=_token]').val()
                },
                success: function(result) {
                    const {
                        employee,
                        overtimePending,
                        remainingOvertime,
                        todayOvertimeStats,
                        workday
                    } = result;

                    $('#shift_id').val(employee.work_shift_id);
                    $('#shift').val(employee.shift_name);

                    this.workdays = workday;
                    this.overtimePending = overtimePending.h * 60;
                    this.overtimeRemaining = remainingOvertime;
                    this.todayOvertimeStats = todayOvertimeStats;

                    setFirstStateFlatPickr(this.workdays, this.overtimePending, this.overtimeRemaining);

                }
            });
        }

        const setFirstStateFlatPickr = (workdays, overtimePending, overtimeRemaining) => {

            const disableDate = [];

            const minOvertimeSetting = {!! json_encode($minOvertime) !!};
            const maxOvertimeSetting = {!! json_encode($maxOvertime) !!};

            let maxOvertime = 0;
            if (overtimeRemaining < minOvertimeSetting) {
                $("#overtime-submit").attr('disabled', 'disabled');
                return;
            }

            if (overtimeRemaining > maxOvertimeSetting) {
                maxOvertime = maxOvertimeSetting
            } else if (overtimeRemaining <= maxOvertimeSetting && overtimeRemaining >= minOvertimeSetting) {
                maxOvertime = overtimeRemaining;
            }

            if (overtimePending.length > 0) {
                overtimePending.forEach((appr) => {
                    disableDate.push({
                        from: appr.date,
                        to: appr.date
                    });
                });
            }

            if (todayOvertimeStats !== null) {
                disableDate.push({
                    from: todayOvertimeStats.date,
                    to: todayOvertimeStats.date
                });
            }

            if (workdays.length > 0) {
                const listDays = [];

                workdays.forEach((day) => {
                    listDays.push(day.days_id - 1);

                    const d = new Date();
                    if (parseInt(d.getDay()) === parseInt(day.days_id) - 1) {
                        minTime = day.end_time.toString().substring(0, 5);
                        $("#overtime_start_time").val(minTime);
                    }
                });

                disableDate.push((date) => {
                    return (!listDays.includes(date.getDay()));
                })
            }

            flatpickr("#overtime_date", {
                altInput: true,
                altFormat: "j F Y",
                dateFormat: "Y-m-d",
                minDate: "today",
                disable: disableDate,
                allowInput: true,
                onReady: function(selectedDates, dateStr, instance) {
                    $(instance.altInput).prop('readonly', false);
                },
                onChange: (selectedDates, dateStr, instance) => {
                    workdays.forEach((day) => {
                        const d = new Date(selectedDates);

                        if (parseInt(d.getDay()) === parseInt(day.days_id) - 1) {
                            const minTime = moment(day.end_time, 'HH:mm:ss').format('HH:mm');
                            const minTimePlus = moment(minTime, 'HH:mm:ss').add(minOvertimeSetting,
                                'minutes').format('HH:mm');
                            const maxTimePlus = moment(minTime, 'HH:mm:ss').add(maxOvertime,
                                'minutes').format('HH:mm');

                            const tempMinTime = minTime.toString().split(':');
                            const tempMaxTime = maxTimePlus.toString().split(':');


                            st.set('minTime', minTime);
                            st.set('maxTime', '22:00')
                            st.set('defaultHour', tempMinTime[0]);
                            st.set('defaultMinutes', tempMinTime[1]);
                            $("#overtime_start_time").val(minTime);
                            $("#overtime_end_time").val(minTimePlus);

                            et.set('minTime', minTimePlus);
                            et.set('maxTime', maxTimePlus);
                            et.set('defaultHour', tempMaxTime[0]);
                            et.set('defaultMinutes', tempMaxTime[1]);
                        }
                    });
                }
            });

            const st = flatpickr("#overtime_start_time", {
                enableTime: true,
                noCalendar: true,
                dateFormat: "H:i",
                time_24hr: true,
                allowInput: true,
                onReady: function(selectedDates, dateStr, instance) {
                    $(instance.altInput).prop('readonly', false);
                },
                onChange: (selectedDates, dateStr, instance) => {
                    if (dateStr === "22:00") {
                        et.set('minTime', "23:00")
                        et.set('maxTime', "23:00")
                        return;
                    }

                    const minTimePlus = moment(dateStr, 'HH:mm:ss').add(minOvertimeSetting, 'minutes')
                        .format('HH:mm');
                    const maxTimePlus = moment(dateStr, 'HH:mm:ss').add(maxOvertime, 'minutes').format(
                        'HH:mm');

                    const tempMinTime = minTimePlus.toString().split(':');
                    const tempMaxTime = maxTimePlus.toString().split(':');

                    et.set('minTime', minTimePlus);
                    et.set('maxTime', maxTimePlus);
                    et.set('defaultHour', tempMaxTime[0]);
                    et.set('defaultMinutes', tempMaxTime[1]);

                    $("#overtime_end_time").val(minTimePlus);
                }
            });

            const et = flatpickr("#overtime_end_time", {
                enableTime: true,
                noCalendar: true,
                dateFormat: "H:i",
                time_24hr: true,
                allowInput: true,
                onReady: function(selectedDates, dateStr, instance) {
                    $(instance.altInput).prop('readonly', false);
                },
            });
        }
    </script>
@endsection
