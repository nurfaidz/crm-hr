@extends('partials.template')
@section('meta_header')
    <meta name="csrf-token" content="{{ csrf_token() }}">
@endsection
@section('main')
    <div class="app-content content ">
        <div class="content-overlay"></div>
        <div class="header-navbar-shadow"></div>
        <div class="content-wrapper">
            <div class="content-body">
                <div class="attendance">
                    <div class="card">
                        <div class="card-body">
                            <h3>Your Overtimes</h3>
                            <div class="card shadow mt-2">
                                <div class="card-body shadow p-2 bg-white rounded">
                                    <h4>Today Overtime</h4>
                                    <div class="d-flex justify-content-between align-items-center mt-2">

                                        <div class="d-flex">
                                            <i>
                                                <img class="attendance-icon carousel-icon"
                                                    src="{{ url('img/icons/checkin-circle.svg') }}" />
                                            </i>
                                            <div class="pl-1">
                                                <p class="carousel-capt m-0 font-weight-bolder">Overtime Start</p>
                                                <p>{{ $overtime ? $overtime->start_time : '-' }}</p>
                                            </div>
                                        </div>

                                        <div class="d-flex">
                                            <i>
                                                <img class="attendance-icon carousel-icon"
                                                    src="{{ url('img/icons/work-hour.svg') }}" />
                                            </i>
                                            <div class="pl-1">
                                                <p class="carousel-capt m-0 font-weight-bolder">Overtime End</p>
                                                <p>{{ $overtime ? $overtime->end_time : '-' }}</p>
                                            </div>
                                        </div>

                                        <div class="d-flex">
                                            <i>
                                                <img class="attendance-icon carousel-icon"
                                                    src="{{ url('img/icons/checkout-dashboard-circle.svg') }}" />
                                            </i>
                                            <div class="pl-1">
                                                <p class="carousel-capt m-0 font-weight-bolder">Duration</p>
                                                <p>{{ !($overtimeHours->h == 00 && $overtimeHours->m == 00) ? "{$overtimeHours->h} H {$overtimeHours->m} M" : '-' }}
                                                </p>
                                            </div>
                                        </div>

                                        <div class="d-flex">
                                            <i>
                                                <img class="attendance-icon carousel-icon"
                                                    src="{{ url('img/icons/working-days.svg') }}" />
                                            </i>
                                            <div class="pl-1">
                                                <p class="carousel-capt m-0 font-weight-bolder">Overtime Date</p>
                                                <p>{{ $overtime ? date('j F Y') : '-' }}</p>
                                            </div>
                                        </div>

                                        <div class="d-flex">
                                            <i>
                                                <img class="attendance-icon carousel-icon"
                                                    src="{{ url('img/icons/hourglass-red.svg') }}" />
                                            </i>
                                            <div class="pl-1">
                                                <p class="carousel-capt m-0 font-weight-bolder">Remaining Overtime</p>
                                                <p>{{ "{$remainingOvertime->h} H {$remainingOvertime->m} M" }}</p>
                                            </div>
                                        </div>

                                        <div class="d-flex align-items-start pb-2">
                                            <button class="btn btn-primary btn-custom" data-toggle="modal"
                                                data-target="#modal-request-overtime" id="overtime-submit">Request
                                                Overtime</button>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="row" class="border">
                                <div class="col-12 mt-2">
                                    <h4 class="card-title">Requested Overtimes</h4>
                                    <div class="card">
                                        <div class="table-responsive table-rounded">
                                            <table class="table table-borderless">
                                                <thead>
                                                    <tr>
                                                        <th class="">Date</th>
                                                        <th class="">Start Time</th>
                                                        <th class="">End Time</th>
                                                        <th class="">Work Time</th>
                                                        <th class="">Shift</th>
                                                        <th class="">Notes</th>
                                                        <th class="">Status</th>
                                                        <th class="">Action</th>
                                                    </tr>
                                                </thead>
                                                <tbody id="table-overtimeReq">
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>


                            <div class="card card-custom mt-">
                                <div class="d-flex justify-content-between align-items-center">
                                    <h4 class="m-0">Your Overtime History</h4>
                                    <input class="btn btn-sm btn-outline-primary" id="select-month">
                                </div>
                                <div class="card mb-2">
                                    <section id="ajax-datatable">
                                        <div class="row">
                                            <div class="col-12">
                                                <div class="card-datatable table-responsive table-rounded">
                                                    <table class="table table-borderless" id="table-overtimeData">
                                                        <thead>
                                                            <tr>
                                                                <th>Date</th>
                                                                <th>Start Time</th>
                                                                <th>End Time</th>
                                                                <th>Work Time</th>
                                                                <th class="text-center">Shift</th>
                                                                <th class="text-center">Notes</th>
                                                                <th>Status</th>
                                                                <th>Action</th>
                                                            </tr>
                                                        </thead>
                                                    </table>
                                                </div>
                                            </div>
                                        </div>
                                    </section>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade text-left" id="modal-request-overtime" tabindex="-1" role="dialog"
        aria-labelledby="modal-request-overtime" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="modal_title">Request Overtimes</h4>
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
                                            <h6>Remaining Overtime for this weeks</h6>
                                            <h4 class="text-primary-purple">
                                                {{ "{$remainingOvertime->h} H {$remainingOvertime->m} M" }}</h4>
                                        </div>

                                        <div class="col-md-5" style="border-left: 1px solid #7367F0">
                                            <div class="ml-1">
                                                <h6>Requesting Overtime</h6>
                                                <h4 class="text-primary-purple">
                                                    <span id="duration-h">0</span> H
                                                    <span id="duration-m">0</span> M
                                                </h4>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-12 mt-1">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <label>Shift: </label>
                                            <div class="form-group">
                                                <input type="text" class="form-control"
                                                    value="{{ $employee->shift_name }}" readonly />
                                            </div>
                                            <label>Overtime Date: </label><span class="text-danger">*</span></label>
                                            <div class="form-group">
                                                <input type="date" placeholder="Choose Start Date"
                                                    name="overtime_date" id="overtime_date" required
                                                    class="form-control" />
                                                <div class="invalid-feedback _error">Please enter appropriate value.</div>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <label>Start Time: </label><span class="text-danger">*</span></label>
                                            <div class="form-group">
                                                <input type="date" name="overtime_start_time" id="overtime_start_time"
                                                    class="form-control" required />
                                                <div class="invalid-feedback _error">Please enter appropriate value.</div>
                                            </div>
                                            <label>End Time: </label><span class="text-danger">*</span></label>
                                            <div class="form-group">
                                                <input type="date" name="overtime_end_time" id="overtime_end_time"
                                                    class="form-control" required />
                                                <div class="invalid-feedback _error">Please enter appropriate value.</div>
                                            </div>
                                        </div>
                                        <div class="col-md-12">
                                            <label>Overtime Note <span class="text-danger">*</span></label>
                                            <div class="form-group">
                                                <textarea type="text" name="overtime_note" class="form-control" required></textarea>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="submit" class="btn btn-primary">Submit Overtimes</button>
                                                <div class="invalid-feedback _error">Please enter appropriate value.</div>
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
    <div class="modal fade text-left" id="modal-form-notes" tabindex="-1" role="dialog"
        aria-labelledby="myModalLabel33" aria-hidden="true">
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
                        <textarea type="text" id="notes" style="height: 130px" required class="form-control"></textarea>
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
    <script src="{{ url('app-assets/js/scripts/components/components-popovers.js') }}"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.26.0/moment.min.js"></script>
@endsection

@section('page_script')
    <script>
        const workdays = {!! json_encode($workday) !!};
        const overtimePending = {!! json_encode($overtimePending) !!};
        const overtimeRemaining = {!! json_encode($remainingOvertime->h * 60) !!};
        const todayOvertimeStats = {!! json_encode($todayOvertimeStats) !!};

        let date = "{{ date('Y-m') }}";

        $(document).ready(function() {
            dataTable_Ajax(date);
            fetchPendingOvertimes();
            setFirstStateFlatPickr(workdays, overtimePending, overtimeRemaining);

            $('#modal-request-overtime').on('hidden.bs.modal', function(event) {
                const reset_form = $('#form_data')[0];
                const reset_form_edit = $('#form_edit_data')[0];
                $(reset_form).removeClass('was-validated');
                $(reset_form_edit).removeClass('was-validated');
                let uniqueField = ["overtime_date", "start_time", "overtime_note", "attachment"]
                for (let i = 0; i < uniqueField.length; i++) {
                    $("#" + uniqueField[i]).removeClass('was-validated');
                    $("#" + uniqueField[i]).removeClass("is-invalid");
                    $("#" + uniqueField[i]).removeClass("invalid-more");
                }
            });

            flatpickr("#start_time", {
                enableTime: true,
                noCalendar: true,
                dateFormat: "H:i",
                time_24hr: true
            });
            flatpickr("#man_att_checkout", {
                enableTime: true,
                noCalendar: true,
                dateFormat: "H:i",
                time_24hr: true
            });
        });

        const dataTable_Ajax = (date) => {
            let base_url = "{{ url('overtimes-date-filter') }}";
            let dt_ajax_table = $("#table-overtimeData");
            if (dt_ajax_table.length) {
                let dt_ajax = dt_ajax_table.dataTable({
                    processing: true,
                    ordering: false,
                    dom: '<"d-flex justify-content-between align-items-end mx-0 row"<"col"l><"col-md-auto"f><"col col-lg-2 p-0.">>t<"d-flex justify-content-between mx-0 row"<"col-sm-12 col-md-6"i><"col-sm-12 col-md-6"p>>',
                    ajax: {
                        url: `${base_url}/${date}`,
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
                            data: 'start_time',
                            name: 'start_time'
                        },
                        {
                            data: 'end_time',
                            name: 'end time'
                        },
                        {
                            data: 'work_time',
                            name: 'work time'
                        },
                        {
                            data: 'shift_name',
                            name: 'shift'
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
                            name: 'Action'
                        },

                    ],
                    columnDefs: [{
                        targets: [5, 6, 7],
                        className: 'text-center',
                    }]
                });

                flatpickr("#select-month", {
                    plugins: [
                        new monthSelectPlugin({
                            shorthand: true, //defaults to false
                            dateFormat: "Y-m", //defaults to "F Y"
                            altFormat: "F Y", //defaults to "F Y"
                            theme: "light" // defaults to "light"
                        })
                    ],
                    altInput: true,
                    dateFormat: 'Y-m',
                    defaultDate: date,
                    maxDate: 'today',
                    onChange: (selectedDates, dateStr) => {
                        dt_ajax_table.DataTable().destroy();
                        dataTable_Ajax(dateStr);
                    },
                });
            }
        }

        Array.prototype.filter.call($('#form_data'), function(form) {
            form.addEventListener('change', (event) => {
                let start = $("#overtime_start_time").val();
                let end = $("#overtime_end_time").val();

                $("#duration-h").text(0)
                $("#duration-m").text(0)

                if (isNaN(start) && isNaN(end)) {
                    const startTime = moment(start, "hh:mm");
                    const endTime = moment(end, "hh:mm");

                    const minutesDiff = endTime.diff(startTime, 'minutes');

                    const hours = Math.floor(minutesDiff / 60);
                    const minutes = Math.floor(minutesDiff % 60);

                    $("#duration-h").text(hours);
                    $("#duration-m").text(minutes);
                }

            });

            form.addEventListener('submit', function(event) {
                if (form.checkValidity() === false) {
                    form.classList.add('invalid');
                }
                form.classList.add('was-validated');
                event.preventDefault();
                let formData = new FormData(this);

                const url = "{{ url('overtimes') }}";
                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });
                $.ajax({
                    url: url,
                    type: 'post',
                    data: formData,
                    contentType: false,
                    processData: false,
                    success: function(response) {

                        if (response.error) {
                            $.each(response.error, function(prefix, val) {
                                $('div.' + prefix + '_error').text(val[0]);
                            });

                            let fields = ["overtime_date", "overtime_note",
                                "overtime_start_time",
                            ]

                            for (let i = 0; i < fields.length; i++) {
                                let error = (response.error.hasOwnProperty(fields[i])) ?
                                    true : false
                                if (!error) {
                                    $("#" + fields[i]).removeClass("is-invalid");
                                    $("#" + fields[i]).removeClass("invalid-more");
                                } else {
                                    $("#" + fields[i]).removeClass('was-validated');
                                    $("#" + fields[i]).addClass("is-invalid");
                                    $("#" + fields[i]).addClass("invalid-more");
                                }
                            }
                        } else {
                            setTimeout(function() {
                                $('#table-overtimeData').DataTable().ajax.reload();
                            }, 1000);

                            Swal.fire({
                                type: "success",
                                title: 'Success!',
                                text: response.message,
                                confirmButtonClass: 'btn btn-success',
                            });

                            $('#modal-request-overtime').modal('hide');

                        }
                        // fetchPendingOvertimes();
                        // setFirstStateFlatPickr(workdays, overtimePending);
                        location.reload();
                    },
                    error: function(xhr) {
                        console.log(123);
                    }
                });

            });

        });

        const fetchPendingOvertimes = () => {
            const url = "{{ url('overtimes-pending') }}";
            $.ajax({
                url: url,
                method: "GET",
                dataType: "json",
                success: (result) => {
                    let tr;
                    if (result.length) {
                        result.forEach(element => {
                            const date = element.date.split('-').reverse().join('/');

                            tr += `<tr>
                                    <td>${date}</td>
                                    <td>${element.start_time}</td>
                                    <td>${element.end_time}</td>
                                    <td>${element.work_shift.h} H ${element.work_shift.m} M</td>
                                    <td>${element.work_shift_name}</td>
                                    <td>
                                        <a onclick=NotesDetail($(this)) data-id="${element.overtime_id}" class="edit btn p-0 mr-md-1">
                                            <img src="./img/icons/note.svg" alt="note">
                                        </a
                                    </td>
                                    <td>
                                        <div class="badge badge-pill badge-light-warning"> Pending </div> 
                                    </td>
                                    <td>
                                        <button onclick=cancelAction($(this)) data-id="${element.overtime_id}" class="btn btn-outline-danger rounded-pill btn-sm button-rounded">Cancel</button>
                                        <button onclick=detailRedirect(${element.overtime_id}) class="btn btn-outline-info round waves-effect button-rounded">
                                            Details
                                        </button>
                                    </td>
                                </tr>`;
                        });
                    } else {
                        tr += `
                        <tr>
                            <td class="text-center" colspan="8">No data available in table</td>
                        </tr>`;
                    }

                    $('#table-overtimeReq').html(tr);
                }
            });
        }

        const NotesDetail = (e) => {
            $('#modal-form-notes').modal('show');
            const base_url = "{{ url('overtimes') }}";
            const id = e.attr('data-id');
            $.ajax({
                url: `${base_url}/${id}/details`,
                method: "GET",
                dataType: "json",
                success: (result) => {
                    // $('.details').val(result.data.purpose);
                    $('.details').html(
                        `<textarea type="text" id="notes" style="height: 130px" required class="form-control" readonly>${result.data.notes}</textarea>`
                    );
                }
            });
        }

        const detailRedirect = (id) => {
            const url = "{{ url('overtimes') }}"
            return location.replace(`${url}/${id}/show`);
        }

        const cancelAction = (e) => {
            swal.fire({
                title: 'Are you sure?',
                text: "You won’t be able to revert this!",
                type: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                cancelButtonClass: 'btn btn-outline-primary ml-1',
                cancelButtonText: 'No',
                confirmButtonText: 'Yes, Cancel it',
                confirmButtonClass: 'btn btn-primary',
                showCloseButton: true,
                buttonsStyling: false,
            }).then((result) => {
                if (result.value) {
                    const id = e.attr('data-id');
                    const base_url = "{{ url('overtimes') }}";
                    $.ajax({
                        url: `${base_url}/${id}/cancel`,
                        type: 'DELETE',
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                        success: (result) => {
                            if (result.error) {
                                Swal.fire({
                                    type: "error",
                                    title: 'Oops...',
                                    text: result.message,
                                    confirmButtonClass: 'btn btn-primary',
                                })
                            } else {
                                setTimeout(function() {
                                    // fetchPendingOvertimes();
                                    // $('#table-overtimeData').DataTable().ajax.reload();
                                    location.reload();
                                }, 1000);


                                Swal.fire({
                                    type: "success",
                                    icon: 'success',
                                    title: 'Cancelled!',
                                    confirmButtonClass: 'btn btn-success',
                                    confirmButtonClass: 'btn btn-primary',
                                    showConfirmButton: false,
                                    // html: ` <p>${result.message}</p>
                                //         <div class="row mt-2 pb-2">
                                //             <div class="col-12">
                                //                 <button type="button" onclick="window.location.href='{{ url('dashboard') }}'" class="btn btn-primary col-12" tabindex="0">Dashboard</button><br>
                                //             </div>
                                //         </div>`,
                                })
                            }
                        }
                    })
                }
            })
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
            });
        }
    </script>
@endsection
