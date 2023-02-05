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
                    <div class="ml-1">
                        <h3><b>Your Attendance</b></h3>
                    </div>
                    <div class="row">
                        <!-- Attendance Widgets -->
                        <div class="col-md-12 col-lg-8 my-1">
                            <div class="card card-custom" style="height: 90%">
                                <div class="card-body shadow p-2 bg-white rounded d-flex flex-column">
                                    <span class=text-right>{{ date('d F Y') }}</span>
                                    <div class="row employee-check p-0 mt-0 mt-md-1">
                                        <!-- Time -->
                                        <div class="col-12 col-sm-3">
                                            <div class="d-flex flex-column h-100">
                                                <h2 class="attendance-title font-weight-bolder mb-1 mb-md-0">Time</h2>
                                                <div class="d-flex align-items-center flex-grow-1">
                                                    <div>
                                                        <i><img class="attendance-icon"
                                                                src="{{ url('img/icons/clock.svg') }}" alt=""></i>
                                                    </div>

                                                    <div class="pl-1">
                                                        <span class="attendance-timer self-attendance"
                                                            id="attendance-timer"></span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <!-- End Time -->

                                        <!-- Work Time -->
                                        <div class="col-12 col-sm-5 mt-1 mt-md-0 border-left pl-1">
                                            <div class="d-flex flex-column h-100">
                                                <h2 class="attendance-title font-weight-bolder mb-1 mb-md-0">Work Time
                                                </h2>
                                                <div class="d-flex align-items-center flex-grow-1">
                                                    <div>
                                                        <i><img class="attendance-icon"
                                                                src="{{ url('img/icons/timer.svg') }}" alt=""></i>
                                                    </div>

                                                    <div class="pl-1">
                                                        <span
                                                            class="attendance-timer self-attendance work-time">00:00:00</span><br>
                                                        <span class="attendance-late-by mt-5" id="late_time"></span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <!-- End Work Time -->

                                        <!-- Check In Time -->
                                        <div class="col-12 col-sm-4 mt-1 mt-md-0 border-left pl-1">
                                            <div class="d-flex align-items-center">
                                                <div>
                                                    <i><img class="attendance-icon"
                                                            src="{{ url('img/icons/timeout.svg') }}" alt=""></i>
                                                </div>

                                                <div class="ml-1">
                                                    <h2 class="attendance-title font-weight-bolder m-0">Check In Time
                                                    </h2>
                                                    <span class="attendance-timer self-attendance"
                                                        id="in-time">--:--</span><br>
                                                    <span class="attendance-late-by mt-5"></span>
                                                    <h2 class="attendance-title font-weight-bolder mt-1 mb-0">Check Out
                                                        Time
                                                    </h2>
                                                    <span class="attendance-timer self-attendance"
                                                        id="out-time">--:--</span><br>
                                                    <span class="attendance-late-by mt-5"></span>
                                                </div>
                                            </div>
                                        </div>
                                        <!-- Check In and Out Time -->
                                    </div>

                                    <div class="row mb-3 mb-md-0 mt-md-1 ">
                                        <div class="col-lg-8">
                                            <form id="form-attendance" method="post" action="{{ url('attendance') }}">
                                                {{ csrf_field() }}
                                                <input type="hidden" name="user_id" id="user_id"
                                                    value="{{ Auth::user()->id }}">
                                                <input type="hidden" name="attendance_id" id="attendance_id"
                                                    value="">
                                                <div class="input-group mb-2">
                                                    <input type="text" class="form-control" name="note" id="note"
                                                        {{ $workday === null ? 'disabled' : '' }} placeholder="Add Note">
                                                    <div class="input-group-append">
                                                        <button type="button" class="btn btn-quick-check" id="check_in"
                                                            style="background-color: ; transition: all 0.5s ease; width:10rem "
                                                            {{ $workday === null ? 'disabled' : '' }}
                                                            name="check_in">Check In</button>
                                                    </div>
                                                </div>
                                                @if ($workday === null)
                                                    <div class="form-text text-danger">Today is not a working day.</div>
                                                @endif
                                            </form>
                                        </div>
                                        <div class="col-lg-4">
                                            <button class="btn btn-outline-primary btn-custom" id="manual-attendance"
                                                data-toggle="modal" data-target="#modal-form">Manual Attendance</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- End Attendance Widgets -->

                        <!-- Workshift Widgets -->
                        <div class="col-md-12 col-lg-4 my-1">
                            <div class="card card-custom" style="height: 90%">
                                <div class="card-body shadow p-2 bg-white rounded">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <h4 class="m-0 month-title">Your Work Shift :</h4>
                                        <h4 class="m-0 title-workshift head-title-workshift font-weight-bolder">
                                            {{ $employee->shift_name }}</h4>
                                    </div>

                                    <div class="row mt-2">
                                        <div class="col-6 d-flex">
                                            <i><img src="{{ url('img/icons/checkin-circle.svg') }}" alt=""></i>
                                            <div class="pl-1">
                                                <p class="m-0 font-weight-bold">Check In Time</p>
                                                <p>{{ $employee->start_time }}</p>
                                            </div>
                                        </div>
                                        <div class="col-6 d-flex">
                                            <i><img src="{{ url('img/icons/work-hour.svg') }}" alt=""></i>
                                            <div class="pl-1">
                                                <p class="m-0 font-weight-bold">Work Hour</p>
                                                <p class="m-0" id="attend-workhour"></p>
                                            </div>
                                        </div>
                                        <div class="col-6 d-flex mt-2">
                                            <i><img src="{{ url('img/icons/checkout-dashboard-circle.svg') }}"
                                                    alt=""></i>
                                            <div class="pl-1">
                                                <p class="m-0 font-weight-bold">Check Out Time</p>
                                                <p>{{ $employee->end_time }}</p>
                                            </div>
                                        </div>

                                        <div class="col-6 d-flex mt-2">
                                            <i><img src="{{ url('img/icons/working-days.svg') }}" alt=""></i>
                                            <div class="pl-1">
                                                <p class="m-0 font-weight-bold">Working Day</p>
                                                <p class="m-0" id="attend-workday"></p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- End Workshift Widgets -->
                    </div>
                    <div class="row" id="table-borderless">
                        <div class="col-12">
                            <h4 class="card-title">Requested Attendance</h4>
                            <div class="card">
                                {{-- <div class="card-header">
                                    <h4 class="card-title">Borderless Table</h4>
                                </div>
                                <div class="card-body">
                                    <p class="card-text">
                                        Add <code>.table-borderless</code> for a table without borders. It can also be used
                                        on dark tables.
                                    </p>
                                </div> --}}
                                <div class="table-responsive table-rounded">
                                    <table class="table table-borderless">
                                        <thead>
                                            <tr>
                                                <th class="p-2">Attendance Date</th>
                                                <th class="p-2">Check In Time</th>
                                                <th class="p-2">Check Out Time</th>
                                                <th class="p-2">Shift</th>
                                                <th class="p-2">Notes</th>
                                                <th class="p-2">Attachment</th>
                                                <th class="p-2">Status</th>
                                                <th class="p-2">Action</th>
                                            </tr>
                                        </thead>
                                        <tbody id="table-data">
                                            {{-- <tr></tr> --}}
                                            {{-- @foreach ($attendance as $attendance_item)
                                                <tr>
                                                    <td>{{ date('d/m/Y', strtotime($attendance_item->date)) }}</td>
                                                    <td>{{ date('H:i', strtotime($attendance_item->check_in)) }}</td>
                                                    <td>{{ date('H:i', strtotime($attendance_item->check_out)) }}</td>
                                                    <td>{{ $attendance_item->shift_name }}</td>
                                                    <td><a onclick=notes($(this)) data-id="{{ $attendance_item->id }}"
                                                            class="edit btn p-0 mr-md-1"><img
                                                                src="./img/icons/note-green.svg" alt="note"></a></td> --}}
                                            {{-- <td><a onclick=notes($(this)) data-id="{{ $attendance_item->id }}"
                                                            class="edit btn p-0 mr-md-1"><img
                                                                src="./img/icons/attachment.svg" alt="note"></a></td> --}}
                                            {{-- <td><span
                                                            class="badge badge-pill badge-light-warning mr-1">Pending</span>
                                                    </td>
                                                    <td><button onclick=cancelAction($(this))
                                                            data-id="{{ $attendance_item->id }}"
                                                            class=" btn btn-outline-danger rounded-pill btn-sm button-rounded">Cancel</button>
                                                    </td>
                                                </tr>
                                            @endforeach --}}
                                            {{-- <tr>
                                                <td>30/05/2022</td>
                                                <td>09 : 00</td>
                                                <td>17 : 00</td>
                                                <td>Shift Normal</td>
                                                <td><a onclick=notes($(this)) data-id="' . $row->id . '"  class="edit btn p-0 mr-md-1"><img src="./img/icons/note-green.svg" alt="note"></a></td>
                                                <td><a onclick=notes($(this)) data-id="' . $row->id . '"  class="edit btn p-0 mr-md-1"><img src="./img/icons/attachment.svg" alt="note"></a></td>
                                                <td><span class="badge badge-pill badge-light-warning mr-1">Pending</span></td>
                                                <td><button onclick=ManualAttendanceCancelAction($(this)) data-id="' . $data->id . '"  class=" btn btn-outline-danger rounded-pill btn-sm button-rounded">Cancel</button></td>
                                            </tr>
                                            <tr>
                                                <td>30/05/2022</td>
                                                <td>09 : 00</td>
                                                <td>17 : 00</td>
                                                <td>Shift Normal</td>
                                                <td><a onclick=notes($(this)) data-id="' . $row->id . '"  class="edit btn p-0 mr-md-1"><img src="./img/icons/note-green.svg" alt="note"></a></td>
                                                <td><a onclick=notes($(this)) data-id="' . $row->id . '"  class="edit btn p-0 mr-md-1"><img src="./img/icons/attachment.svg" alt="note"></a></td>
                                                <td><span class="badge badge-pill badge-light-warning mr-1">Pending</span></td>
                                                <td><button onclick=ManualAttendanceCancelAction($(this)) data-id="' . $data->id . '"  class=" btn btn-outline-danger rounded-pill btn-sm button-rounded">Cancel</button></td>
                                            </tr> --}}
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Monthly Statistics -->
                    <div class="card card-custom">
                        <div class="card-body shadow p-2 bg-white rounded d-flex flex-column">
                            <div class="d-flex justify-content-between align-items-center">
                                <h4 class="m-0">Monthly Statistic</h4>
                                <input class="btn btn-sm btn-outline-primary" id="select-month">
                            </div>
                            <div class="row mt-3">
                                <div class="col-6 col-md-2">
                                    <div class="d-flex">
                                        <i>
                                            <img class="attendance-icon carousel-icon"
                                                src="./img/icons/calendar-circle.svg" />
                                        </i>
                                        <div class="pl-1">
                                            <p class="m-0"><b id="attend-total-days">-</b></p>
                                            <p class="carousel-capt m-0">Work Day</p>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-6 col-md-2">
                                    <div class="d-flex" id="attend-popover" data-toggle="popover" data-content="88%"
                                        data-trigger="hover" data-original-title="Total Attend" data-placement="bottom">
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
                                <div class="col-6 col-md-2 mt-2 mt-md-0">
                                    <div class="d-flex">
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
                                </div>
                                <div class="col-6 col-md-2 mt-2 mt-md-0">
                                    <div class="d-flex" id="leave-balance-popover" data-toggle="popover"
                                        data-content="0 Days" data-trigger="hover" data-original-title="Remaining Leave"
                                        data-placement="bottom">
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
                                </div>
                                <div class="col-6 col-md-2 mt-2 mt-md-0">
                                    <div class="d-flex" data-toggle="popover" id="attend-absence-popover"
                                        data-content="0 Days" data-trigger="hover" data-original-title="Total Absence"
                                        data-placement="bottom">
                                        <i>
                                            <img class="attendance-icon carousel-icon"
                                                src="./img/icons/absence-circle.svg" />

                                        </i>
                                        <div class="pl-1">
                                            <p class="m-0"><b id="attend-absence">-</b></p>
                                            <p class="carousel-capt">Absence</p>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-6 col-md-2 mt-2 mt-md-0">
                                    <div class="d-flex">
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
                            <div class="card mt-3 mb-2">
                                <section id="ajax-datatable">
                                    <h4 class="your-attendance">Your Attendance History</h4>
                                    <div class="row">
                                        <div class="col-12">
                                            <div class="card-datatable table-responsive table-rounded">
                                                <table class="table table-borderless" id="datatables-ajax">
                                                    <thead>
                                                        <tr>
                                                            <th>Date</th>
                                                            <th>Day</th>
                                                            <th>Check In</th>
                                                            <th>Check Out</th>
                                                            <th>Work Time</th>
                                                            <th>Lateness</th>
                                                            <th>Late Time</th>
                                                            <th>Overtime</th>
                                                            <th>Shift</th>
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
    <footer class="footer footer-static footer-light">
        <p class="clearfix mb-0"><span class="float-md-left d-block d-md-inline-block mt-25">COPYRIGHT © 2022
                <a class="ml-25" href="https://technoinfinity.co.id" target="_blank">Techno Infinity</a></span>
        </p>
    </footer>

    <div class="modal fade text-left" id="modal-notes" tabindex="-1" role="dialog" aria-labelledby="myModalLabel33"
        aria-hidden="true">
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

    <div class="modal fade text-left" id="modal-pending-status-files" tabindex="-1" role="dialog"
        aria-labelledby="modal-title" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="modal-title">Detail Files</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
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

    <div class="modal fade text-left" id="modal-form" tabindex="-1" role="dialog" aria-labelledby="myModalLabel33"
        aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="modal_title">Manual Attendance</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="container">
                    <form id="form_data" action="{{ url('attendance-self-service') }}" class="form-data-validate"
                        novalidate method="POST" enctype="multipart/form-data">
                        {{ csrf_field() }}
                        <div class="modal-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <label>Shift<span class="red-asterisk"> *</span></label>
                                    <div class="form-group">
                                        <input type="text" class="form-control" name="shift"
                                            value="{{ $employee->shift_name }}" readonly />
                                    </div>
                                    <label>Attendance Date<span class="red-asterisk"> *</span> </label>
                                    <div class="form-group">
                                        <input type="text" placeholder="Attendance Date" name="att_date"
                                            id="att_date" required class="form-control bg-white" />
                                        <div class="invalid-feedback att_date_error"></div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <label>Check In Time<span class="red-asterisk"> *</span></label>
                                    <div class="form-group">
                                        <input type="text" name="att_check_in" id="att_check_in"
                                            class="form-control" />
                                        <div class="invalid-feedback att_check_in_error"></div>
                                    </div>
                                    <label>Check Out Time<span class="red-asterisk"> *</span></label>
                                    <div class="form-group">
                                        <input type="text" name="att_check_out" id="att_check_out"
                                            class="form-control" />
                                        <div class="invalid-feedback att_check_out_error"></div>
                                    </div>
                                </div>
                            </div>
                            <label>Attendance Note<span class="red-asterisk"> *</span> </label>
                            <div class="form-group">
                                <textarea type="text" name="att_note" id="att_note" class="form-control" required></textarea>
                                <div class="invalid-feedback att_note_error"></div>
                            </div>
                            <label>Attachment Evidence <span class="red-asterisk"> *</span></label>
                            <div class="form-group">
                                <input type="file" name="attachment" id="attachment" placeholder="Choose files"
                                    class="form-control" required>
                                <div class="invalid-feedback attachment_error"></div>
                            </div>
                            <div class="modal-footer">
                                <button type="submit" class="btn btn-primary">Request Attendance</button>
                            </div>
                    </form>
                    {{-- <label>Attendance Evidence </label>
                <form action="#" class="dropzone dropzone-area dropzone-custom" id="dpz-single-file">
                    <div class="dz-message">Drop files here or click to upload.</div>
                </form> --}}
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
        const workdays = {!! json_encode($workdayDate) !!};
        const disableDate = [];
        let date = "{{ date('Y-m') }}";

        $(document).ready(function() {

            $('.carousel').carousel('pause');
            $('#modal-form').on('hidden.bs.modal', function(event) {
                $('#form_data')[0].reset()
                const reset_form = $('#form_data')[0];
                const reset_form_edit = $('#form_edit_data')[0];
                $(reset_form).removeClass('was-validated');
                $(reset_form_edit).removeClass('was-validated');
                let uniqueField = ["att_date", "att_check_in", "att_check_out", "attachment"]
                for (let i = 0; i < uniqueField.length; i++) {
                    $("#" + uniqueField[i]).removeClass('was-validated');
                    $("#" + uniqueField[i]).removeClass("is-invalid");
                    $("#" + uniqueField[i]).removeClass("invalid-more");
                }

            });
            // let start_time = "{{ $employee->start_time }}"
            // start_time.
            // console.log("{{ $employee->start_time }}")
            $('#modal-form').on('shown.bs.modal', function() {
                flatpickr("#att_check_in", {
                    enableTime: true,
                    noCalendar: true,
                    dateFormat: "H:i",
                    defaultDate: "{{ $employee->start_time }}",
                    time_24hr: true
                });
                flatpickr("#att_check_out", {
                    enableTime: true,
                    noCalendar: true,
                    dateFormat: "H:i",
                    time_24hr: true,
                    defaultDate: "{{ $employee->end_time }}",
                });
            })
            dataTable_Ajax(date);

            if (workdays.length > 0) {
                const listDays = [];

                workdays.forEach((day) => {
                    listDays.push(day.days_id - 1);
                });

                disableDate.push((date) => {
                    return (!listDays.includes(date.getDay()));
                })
            }

            flatpickr("#att_date", {
                altInput: true,
                altFormat: "j F Y",
                dateFormat: "Y-m-d",
                maxDate: "today",
                disable: disableDate,
                onChange: (selectedDates, dateStr, instance) => {
                    workdays.forEach((day) => {
                        const d = new Date(selectedDates);

                    });
                }
            });

            timer();
            checkAttendance();
            getWorkshift();
            statistics(date);
            attendancePending();
        });

        const dataTable_Ajax = (date) => {
            let base_url = "{{ url('attendance-date-filter') }}";
            var dt_ajax_table = $("#datatables-ajax");
            // console.log(dt_ajax_table.length);
            if (dt_ajax_table.length) {
                var dt_ajax = dt_ajax_table.dataTable({
                    processing: true,
                    ordering: false,
                    dom: '<"d-flex justify-content-between align-items-end mx-0 row"<"col"l><"col-md-auto"f><"col col-lg-2 p-0.">>t<"d-flex justify-content-between mx-0 row"<"col-sm-12 col-md-6"i><"col-sm-12 col-md-6"p>>',
                    // drawCallback: function() {
                    //     $(this.api().table().header()).hide();
                    // },
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
                            data: 'day',
                            name: 'day'
                        },
                        {
                            data: 'check_in',
                            name: 'check in'
                        },
                        {
                            data: 'check_out',
                            name: 'check out'
                        },
                        {
                            data: 'working_hour',
                            name: 'work time'
                        },
                        {
                            data: 'lateness',
                            name: 'lateness'
                        },
                        {
                            data: 'late_duration',
                            name: 'late time'
                        },
                        {
                            data: 'overtime_duration',
                            name: 'overtime'
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
                            data: 'status',
                            name: 'Status'
                        },
                        {
                            data: (data) => {
                                return `<a class="btn btn-outline-info round waves-effect button-rounded" href="/daily-attendance/${data.id}"> Details </a>`
                            },
                            name: 'detail',
                        }

                    ],
                    columnDefs: [{
                        targets: 9,
                        className: 'text-center',
                    }],
                    rowCallback: function(hlRow, hlData, hlIndex) {
                        // if (hlData.status_code == 'Approved') {
                        //     $('td', hlRow).css('background', '#28c76f1f');
                        // } 
                        if (hlData.status_code == 'aab' || hlData.status_code == 'arj') {
                            $('td', hlRow).css('background', '#ea54551f');
                        } else if (hlData.status_code == 'ado' || hlData.status_code == 'asc') {
                            $('td', hlRow).css('background', '#6c757d1f');
                        } else if (hlData.status_code == 'apm') {
                            $('td', hlRow).css('background', '#00CFE81F');
                        }
                    }
                });

                statistics(date);

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
                        $('#datatables-ajax').DataTable().destroy();
                        statistics(dateStr);
                        dataTable_Ajax(dateStr);
                    },
                });
            }
        }

        Array.prototype.filter.call($('#form_data'), function(form) {
            form.addEventListener('submit', function(event) {
                if (form.checkValidity() === false) {
                    form.classList.add('invalid');
                }
                form.classList.add('was-validated');
                event.preventDefault();
                let formData = new FormData(this);

                const url = "{{ url('attendance-self-service') }}";
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

                            let fields = ["att_date", "att_check_in", "att_check_out",
                                "attachment"
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
                                attendancePending();
                                $('#datatables-ajax').DataTable().ajax.reload();
                            }, 1000);

                            Swal.fire({
                                type: "success",
                                title: 'Success!',
                                text: response.message,
                                confirmButtonClass: 'btn btn-success',
                            });

                            $('#modal-form').modal('hide');

                        }

                    },
                    error: function(xhr) {
                        // console.log(xhr.responseText);
                    }
                });

            });

        });

        // $(document).on('click', '#pending-status-files', function() {

        // });

        // $('#modal-pending-status-files').on('hidden.bs.modal', function(e) {
        //     $('.detail-files-container').html('');
        // });

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

        const attachment = (e) => {
            $('#modal-pending-status-files').modal('show');
            const id = e.attr('data-id');
            $.ajax({
                url: "{{ url('attendance-self-service') }}" + "/" + id,
                method: "GET",
                dataType: "json",
                success: function(result) {
                    if (result.data.image !== null) {

                        $('#modal-pending-status-files .detail-files-container').html(
                            `<div class="input-group"><div class="input-group-prepend"><span class="input-group-text bg-white" id="file-copy"><img src="{{ url('./img/icons/file-copy.svg') }}" alt="File Copy"></span></div><input type="text" name="detail-files" id="detail-files" class="form-control bg-white border-left-0 detail-files" disabled><div class="input-group-append"><a href="attendance/attachment/download/${result.data.image}" class="btn btn-primary">Download</a></div></div>`
                        );

                        $('#modal-pending-status-files .detail-files').attr('placeholder', result.data
                            .image);
                    } else {
                        $('#modal-pending-status-files .detail-files-container').html(
                            '<div class="input-group"><div class="input-group-prepend"><span class="input-group-text bg-white" id="file-copy"><img src="{{ url('./img/icons/file-copy.svg') }}" alt="File Copy"></span></div><input type="text" placeholder="No file available" name="detail-files" id="detail-files" class="form-control bg-white border-left-0 detail-files" disabled><div class="input-group-append"><button class="btn btn-primary" disabled>Download</button></div></div>'
                        );

                    }

                }
            });


            // let isMultiple = $(this).data('sick-letter').includes('|');

            // if (isMultiple) {
            //     // let files = $(this).data('sick-letter').split('|');
            //     // let array = [];

            //     // files.forEach(element => {
            //     //     array.push(findBaseName(element));
            //     // });

            //     // for (let i = 0; i < array.length; i++) {
            //     // }

            //     // $('#modal-pending-status-files .detail-files').each(function(j) {
            //     //     $(this).attr('placeholder', array[j]);
            //     // });
            // } else {
            //     let file = findBaseName($(this).data('sick-letter'));

            //     if (file != '') {
            //         $('#modal-pending-status-files .detail-files-container').append(
            //             '<div class="input-group"><div class="input-group-prepend"><span class="input-group-text bg-white" id="file-copy"><img src="{{ url('./img/icons/file-copy.svg') }}" alt="File Copy"></span></div><input type="text" name="detail-files" id="detail-files" class="form-control bg-white border-left-0 detail-files" disabled><div class="input-group-append"><a href="self-leave-application/download/' +
            //             file + '" class="btn btn-primary">Download</a></div></div>');

            //         $('#modal-pending-status-files .detail-files').attr('placeholder', file);
            //     } else {
            //         $('#modal-pending-status-files .detail-files-container').append(
            //             '<div class="input-group"><div class="input-group-prepend"><span class="input-group-text bg-white" id="file-copy"><img src="{{ url('./img/icons/file-copy.svg') }}" alt="File Copy"></span></div><input type="text" placeholder="No file available" name="detail-files" id="detail-files" class="form-control bg-white border-left-0 detail-files" disabled><div class="input-group-append"><button class="btn btn-primary" disabled>Download</button></div></div>'
            //         );
            //     }
            // }
        }

        const attendancePending = () => {
            $.ajax({
                url: "{{ url('attendance/data/pending') }}",
                method: 'GET',
                dataType: 'json',
                success: (result) => {
                    // console.log(result.data);
                    let tr = '';
                    if (result.data.length) {
                        result.data.forEach(element => {
                            tr += `
                            <tr>
                                <td>${element.date}</td>
                                <td>${element.check_in}</td>
                                <td>${element.check_out}</td>
                                <td>${element.shift_name}</td>
                                <td><a onclick=notes($(this)) data-id="${element.id}"
                                    class="edit btn p-0 mr-md-1"><img src="./img/icons/note-green.svg" alt="note"></a</td>
                                <td><a onclick=attachment($(this)) data-id="${element.id}"
                                    class="edit btn p-0 mr-md-1"><img src="./img/icons/attachment.svg" alt="note"></a></td>
                                <td><span class="badge badge-pill badge-light-warning mr-1">Pending</span></td>
                                <td><button onclick=cancelAction($(this)) data-id="${element.id}" 
                                class=" btn btn-outline-danger rounded-pill btn-sm button-rounded">Cancel</button>
                                <button onclick=detailRedirect(${element.id}) class="btn btn-outline-info round waves-effect button-rounded">
                                            Details
                                        </button></td>
                            </tr>
                        `;
                        });

                    } else {
                        tr += `
                            <tr>
                                <td class="text-center" colspan="8">No data available in table</td>
                            </tr>
                        `;
                    }
                    $('#table-data').html(tr);
                }
            })
        }

        const detailRedirect = (id) => {
            const url = "{{ url('daily-attendance') }}"
            return location.replace(`${url}/${id}`);
        }

        const cancelAction = (e) => {
            swal.fire({
                title: 'Sure you want to cancel?',
                text: "Are you sure want to cancel this?",
                type: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                cancelButtonClass: 'btn btn-outline-primary ml-1',
                cancelButtonText: 'No, Cancel',
                confirmButtonText: 'Yes, Confirm',
                confirmButtonClass: 'btn btn-primary',
                showCloseButton: true,
                buttonsStyling: false,
            }).then((result) => {
                if (result.value) {
                    const id = e.attr('data-id');
                    const base_url = "{{ url('attendance') }}";
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
                                    attendancePending();
                                    $('#datatables-ajax').DataTable().ajax
                                        .reload();
                                }, 1000);

                                Swal.fire({
                                    type: "success",
                                    icon: 'success',
                                    title: 'Cancelled!',
                                    confirmButtonClass: 'btn btn-success',
                                    confirmButtonClass: 'btn btn-primary',
                                    showConfirmButton: false,
                                    html: ` <p>${result.message}</p>
                                            <div class="row mt-2 pb-2">
                                                <div class="col-12">
                                                    <button type="button" onclick="window.location.href='{{ url('dashboard') }}'" class="btn btn-primary col-12" tabindex="0">Dashboard</button><br>
                                                </div>
                                            </div>`,
                                    showCloseButton: true,
                                })
                            }
                        }
                    })
                }
            })
        }

        let interval = null;

        const checkAttendance = () => {
            const id = document.querySelector('#user_id').value;
            fetch(`/attendance/today/${id}`)
                .then(response => response.json())
                .then(response => {
                    if (response.data !== null) {
                        // console.log(response.late)
                        if (response.late != null) {
                            let check_in_time = response.late
                            document.querySelector('#late_time').innerHTML = "Late By " + check_in_time
                        }

                        // console.log()
                        if (response.data.check_in !== null && response.data.check_out !== null) {
                            let checkIn = document.querySelector('#check_in');
                            let note = document.querySelector('#note');
                            let workTime = document.querySelector('.work-time');
                            let inTime = document.querySelector('#in-time');
                            let outTime = document.querySelector('#out-time');

                            if (interval !== null) {
                                clearInterval(interval);
                            }
                            checkIn.setAttribute("disabled", "disabled");
                            note.setAttribute("disabled", "disabled");
                            workTime.innerHTML = response.data.working_hour;
                            checkIn.innerHTML = "Checked Out";
                            checkIn.style.backgroundColor = "#EA5455";
                            inTime.innerHTML = response.data.check_in.substring(11, 16);
                            outTime.innerHTML = response.data.check_out.substring(11, 16);
                        }

                        if (response.data.check_in !== null && response.data.check_out === null) {
                            let checkIn = document.querySelector('#check_in');
                            let id = document.querySelector('#attendance_id');
                            let note = document.querySelector('#note');
                            let workTime = document.querySelector('.work-time');
                            let inTime = document.querySelector('#in-time');

                            checkIn.innerHTML = "Check Out";
                            checkIn.style.backgroundColor = "#EA5455";
                            checkIn.setAttribute('onclick', 'checkOutFunction()');
                            inTime.innerHTML = response.data.check_in.substring(11, 16);
                            id.value = response.data.id;

                            // work time run when refresh page
                            let timeNow = new Date((new Date()).toISOString().slice(0, 19).replace(/-/g, "/")
                                .replace("T", " "));
                            let checkInTime = new Date(timeNow - new Date(response.data.check_in));
                            let milliseconds = 0;
                            let seconds = checkInTime.getSeconds();
                            let minutes = checkInTime.getMinutes();
                            let hours = checkInTime.getHours();

                            interval = setInterval(() => {
                                milliseconds += 10;

                                if (milliseconds == 1000) {
                                    milliseconds = 0;
                                    seconds++;

                                    if (seconds == 60) {
                                        seconds = 0;
                                        minutes++;

                                        if (minutes == 60) {
                                            minutes = 0;
                                            hours++;
                                        }
                                    }
                                }
                                let h = hours < 10 ? "0" + hours : hours;
                                let m = minutes < 10 ? "0" + minutes : minutes;
                                let s = seconds < 10 ? "0" + seconds : seconds;

                                workTime.innerHTML = `${h}:${m}:${s}`;
                            }, 10);
                        }
                    }

                    if (response.data === null) {
                        let checkIn = document.querySelector('#check_in');
                        checkIn.setAttribute('onclick', 'checkInFunction()');
                    }
                })
                .catch((error) => {
                    console.error('Error:', error);
                });
        };

        const checkInFunction = () => {
            const formData = document.querySelector('#form-attendance');
            const request = new FormData(formData);

            const data = {
                _token: request.get('_token'),
                user_id: request.get('user_id'),
                note: request.get('note')
            };

            fetch('/attendance', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                    },
                    body: JSON.stringify(data),
                })
                .then(response => response.json())
                .then(response => {
                    if (response.error) {
                        throw response.message;
                    }

                    let note = document.querySelector('#note');
                    note.value = '';

                    checkAttendance();
                    $('#datatables-ajax').DataTable().ajax
                        .reload();
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

        const checkOutFunction = () => {
            const formData = document.querySelector('#form-attendance');
            const request = new FormData(formData);
            const id = document.querySelector('#attendance_id').value;

            const data = {
                _token: request.get('_token'),
                user_id: request.get('user_id'),
                note: request.get('note')
            };

            fetch(`/attendance/${id}`, {
                    method: 'PUT',
                    headers: {
                        'Content-Type': 'application/json',
                    },
                    body: JSON.stringify(data),
                })
                .then(response => response.json())
                .then(response => {
                    if (response.error) {
                        throw response.message;
                    }
                    let note = document.querySelector('#note');
                    note.value = '';

                    checkAttendance();
                    $('#datatables-ajax').DataTable().ajax
                        .reload();
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

        const timer = () => {
            let attendanceTimer = document.querySelector("#attendance-timer");
            const today = new Date();
            let h = today.getHours();
            let m = today.getMinutes();
            let s = today.getSeconds();
            m = checkTime(m);
            s = checkTime(s);

            let time = `${h}:${m}`;

            attendanceTimer.innerHTML = time;
            setTimeout(timer, 1000);
        }

        const checkTime = (i) => {
            if (i < 10) {
                i = "0" + i
            }; // add zero in front of numbers < 10
            return i;
        }


        /**
         * * Fetch Statistics
         * 
         **/
        const statistics = (date) => {
            $.ajax({
                url: "{{ url('attendance-statistic/') }}" + "/" + date,
                method: "GET",
                dataType: "json",
                success: function(result) {
                    const {
                        late_hours,
                        days_absence,
                        leave_balance,
                        overtime,
                        attend
                    } = result.data;
                    // console.log(result.data)
                    const lateHours = `${late_hours.h} H ${late_hours.m} M`
                    const overTimes = `${overtime.h} H ${overtime.m} M`
                    const totalWorkDays = attend + days_absence
                    $("#attend-total-days").html(totalWorkDays)
                    $("#attend-days").html(attend)
                    $("#attend-popover").attr("data-content", attend + " Days")
                    $("#late_hours").html(lateHours)
                    $("#leave-balance").html(leave_balance)
                    $("#leave-balance-popover").attr("data-content", leave_balance + " Days")
                    $("#attend-absence").html(days_absence)
                    $("#attend-absence-popover").attr("data-content", days_absence + " Days")
                    $("#attend-overtime").html(overTimes)
                }
            });
        }

        /**
         * * Fetch workshift
         * 
         **/
        const getWorkshift = () => {
            $.ajax({
                url: "{{ url('dashboard/workshifts') }}",
                method: "GET",
                dataType: "json",
                success: (result) => {
                    const {
                        workhours,
                        workdays
                    } = result
                    const workhour = `${workhours.h} H ${workhours.m} M`
                    $("#attend-workhour").append(workhour)
                    $("#attend-workday").append(workdays.data)


                }
            });
        }
    </script>
@endsection
