@extends('partials.template')

@section('main')
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <!-- BEGIN: Content-->
    <div class="app-content content ">
        <div class="content-overlay"></div>
        <div class="header-navbar-shadow"></div>
        <!-- BEGIN: Content Wrapper -->
        <div class="content-wrapper">
            <!-- BEGIN: Body Content -->
            <div class="content-body">
                <!-- BEGIN: Line Chart Card -->
                <div class="row">

                    <!-- BEGIN: Widgets -->
                    <div id="widget" class="w-100">
                        <div class="col-12">
                            <div class="row">
                                <div class="col-sm-12 col-lg-6 my-1">
                                    <div class="card card-custom" style="height: 90%">
                                        <div class="card-header d-flex flex-row justify-content-between">
                                            <div class="d-flex flex-row align-items-center">
                                                <h2 class="title m-0 approval-dashboard-title font-weight-bolder date">
                                                    {{ date('d F Y') }}</h2>
                                            </div>
                                        </div>
                                        <div class="card-body">
                                            <div class="row">
                                                <div class="col-lg-8">
                                                    <div class="d-flex flex-column justify-content-center">
                                                        <h1 class="font-weight-bolder welcome welcome-header">Hello,
                                                            <span
                                                                class="text-primary">{{ $employeeName }}!</span>
                                                        </h1>
                                                        <p class="mt-1">Welcome Back, keep up a good work</p>

                                                        <div class="group-button mt-1">
                                                            <button class="btn btn-quick-check">Quick Check-in</button>
                                                            <button class="btn btn-outline-primary">Request Timeoff</button>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-lg-4 d-flex justify-content-center">
                                                    <img class="d-none d-lg-block d-xl-block img-fluid"
                                                        src="{{ url('img/dashboard/undraw_welcome_re_h3d9.svg') }}"
                                                        alt="">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-sm-12 col-lg-6 my-1">
                                    <div class="card card-custom" style="height: 90%">
                                        <div class="text-center pt-2">
                                            <b>
                                                <span class="text-primary custom-heading">Quotes</span>
                                            </b>
                                        </div>
                                        <div class="card-body card-max-height pb-0">
                                            <section class="text-center">
                                                <q class="custom-q">Change is inevitable. Change will always happen. but you
                                                    have to apply direction to change, and that's when it's progress.</q>
                                                <p class="text-primary custom-name mt-1">- Doug Baldwin -</p>
                                            </section>
                                            <div class="navigation">
                                                <div class="d-flex">
                                                    <img class="mr-auto p-2" src="{{ url('img/icons/left.svg') }}"
                                                        alt="">
                                                    <img class="p-2" src="{{ url('img/icons/right.svg') }}"
                                                        alt="">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                @foreach ($widgets as $widget)
                                    <!-- BEGIN : Attendance Widget -->
                                    @if ($widget->dashboard_id == 1 && $widget->is_active == 1)
                                        <div class="col-sm-12 col-lg-6 my-1">
                                            <div class="card card-custom" style="height: 90%">
                                                <div class="card-header d-flex flex-row justify-content-between">
                                                    <div class="d-flex flex-row align-items-center">
                                                        <i><img class="img-fluid"
                                                                src="{{ url('img/icons/drag_indicator.svg') }}"
                                                                alt=""></i>
                                                        <h2 class="title m-0 approval-dashboard-title">Attendance</h2>
                                                    </div>
                                                </div>
                                                <div class="card-body pb-0">
                                                    <div class="row attendance-dashboard-time">
                                                        <div class="col-4">
                                                            <div class="custom-title text-primary">Time</div>
                                                            <div class="d-flex align-items-baseline">
                                                                <img class="img-fluid timer-img"
                                                                    src="{{ url('img/icons/clock.svg') }}"
                                                                    alt="Access Time Filled">
                                                                <div class="d-flex flex-column time-statistics">
                                                                    <div class="attendance-timer" id="attendance-timer">
                                                                    </div>
                                                                    <div>{{ date('d M Y') }}</div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="col-4">
                                                            <div class="custom-title text-primary">Work Time</div>
                                                            <div class="d-flex">
                                                                <img class="img-fluid timer-img"
                                                                    src="{{ url('img/icons/timer.svg') }}" alt="Timer">
                                                                <div class="d-flex flex-column time-statistics">
                                                                    <div class="attendance-timer work-time">--:--</div>
                                                                    <div></div>
                                                                </div>
                                                            </div>
                                                            <span id="late_time"></span>
                                                        </div>
                                                        <div class="col-4">
                                                            <div class="d-flex flex-column">
                                                                <div class="custom-title text-primary">Check In Time</div>
                                                                <div class="check-time-statistics" id="in-time">--:--
                                                                </div>
                                                            </div>
                                                            <div class="d-flex flex-column check-out-time">
                                                                <div class="custom-title text-primary">Check Out Time</div>
                                                                <div class="check-time-statistics" id="out-time">--:--
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div style="margin-top: 12px; margin-bottom: 16px">
                                                        <form action="/attendance" method="POST" id="form-attendance">
                                                            {{ csrf_field() }}
                                                            <input type="hidden" name="user_id" id="user_id"
                                                                value="{{ Auth::user()->id }}">
                                                            <input type="hidden" name="attendance_id" id="attendance_id">
                                                            <div class="input-group">
                                                                <input type="text" class="form-control" name="note"
                                                                    id="note" placeholder="Add note">
                                                                <div class="input-group-append">
                                                                    <button type="button" class="btn btn-quick-check"
                                                                        name="check_in" id="check_in"
                                                                        style="transition: all 0.5s ease; width: 10rem"
                                                                        {{ $workday === null ? 'disabled' : '' }}>Check
                                                                        In</button>
                                                                </div>
                                                            </div>
                                                            @if ($workday === null)
                                                                <div class="form-text text-danger">Today is not a working
                                                                    day.</div>
                                                            @endif
                                                        </form>
                                                    </div>
                                                    <div class="d-flex dashboard-your-work-shift">
                                                        <div class="text-primary">Your Work Shift :</div>
                                                        <div id="attend-shift"></div>
                                                    </div>
                                                    <div class="d-flex flex-wrap" style="padding-bottom: 32px">
                                                        <div class="d-flex align-items-center" style="padding-top: 8px">
                                                            <img src="{{ url('img/icons/checkin-circle.svg') }}"
                                                                alt="Access Time">
                                                            <div class="d-flex flex-column attendance-dashboard-shift">
                                                                <div>Check In Time</div>
                                                                <div id="attend-checkin"></div>
                                                            </div>
                                                        </div>
                                                        <div class="d-flex align-items-center" style="padding-top: 8px">
                                                            <img src="{{ url('img/icons/checkout-dashboard-circle.svg') }}"
                                                                alt="Access Time">
                                                            <div class="d-flex flex-column attendance-dashboard-shift">
                                                                <div>Check Out Time</div>
                                                                <div id="attend-checkout"></div>
                                                            </div>
                                                        </div>
                                                        <div class="d-flex align-items-center" style="padding-top: 8px">
                                                            <img src="{{ url('img/icons/work-hour.svg') }}"
                                                                alt="Access Time">
                                                            <div class="d-flex flex-column attendance-dashboard-shift">
                                                                <div>Work Hour</div>
                                                                <div id="attend-workhour"></div>
                                                            </div>
                                                        </div>
                                                        <div class="d-flex align-items-center" style="padding-top: 8px">
                                                            <img src="{{ url('img/icons/working-days.svg') }}"
                                                                alt="Access Time">
                                                            <div class="d-flex flex-column attendance-dashboard-shift">
                                                                <div>Working Day</div>
                                                                <div id="attend-workday"></div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    @endif
                                    <!-- END : Attendance Widget -->

                                    <!-- BEGIN : Announcement Widget -->
                                    @if ($widget->dashboard_id == 2 && $widget->is_active == 1)
                                        <!-- BEGIN : Announcement Modal -->
                                        <div class="modal fade text-left" id="modal-view" tabindex="-1" role="dialog"
                                            aria-labelledby="modal-title" aria-hidden="true">
                                            <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h3 name="announcement_title" id="announcement_title"></h3>
                                                        <button type="button" class="close mr-0 mt-0"
                                                            data-dismiss="modal" aria-label="Close">
                                                            <span aria-hidden="true">&times;</span>
                                                        </button>
                                                    </div>
                                                    <div class="px-2" name="published_by" id="published_by"
                                                        style="background-color: #f8f8f8"></div>
                                                    <div class="px-2 pb-1" name="published_at" id="published_at"
                                                        style="background-color: #f8f8f8"></div>
                                                    <div class="dropdown-divider my-0 mx-2"></div>
                                                    <div class="modal-body justify-content-start">
                                                        <input type="hidden" name="announcement_id"
                                                            id="announcement_id">
                                                        <input type="hidden" name="announcement_status"
                                                            id="announcement_status" value="Published">
                                                        <img class="img-fluid pb-1" name="announcement_image"
                                                            id="announcement_image" />
                                                        <div name="announcement_content" id="announcement_content"></div>
                                                        <label class="file pt-1" for="Files">Files</label><br>
                                                        <a name="announcement_attachment"
                                                            id="announcement_attachment"></a>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <!-- END : Announcement Modal -->

                                        <!-- BEGIN : Announcement Content -->
                                        <div class="col-sm-12 col-lg-6 my-1">
                                            <div class="card card-custom" style="height: 90%">
                                                <div class="card-header d-flex flex-row justify-content-between">
                                                    <div class="d-flex flex-row align-items-center">
                                                        <i><img src="{{ url('img/icons/drag_indicator.svg') }}"
                                                                alt=""></i>
                                                        <h2 class="title m-0 approval-dashboard-title">Announcement</h2>
                                                    </div>
                                                </div>
                                                <div class="card-body card-max-height pb-0">
                                                    <div
                                                        class="overflow-auto pr-1 announcement-dashboard-content-container">
                                                        <div id="announcement-contents"></div>
                                                    </div>
                                                </div>
                                                <div class="card-footer card-footer-announcement text-center">
                                                    <a href="{{ url('announcements/viewmore') }}">
                                                        <span class="font-weight-bolder">View more</span>
                                                    </a>
                                                </div>
                                            </div>
                                        </div>
                                        <!-- END : Announcement Content -->
                                    @endif
                                    <!-- END : Announcement Widget -->

                                    <!--  BEGIN : Attendance History Widget -->
                                    {{-- @if ($widget->dashboard_id == 3 && $widget->is_active == 1)
                                        <div class="col-sm-12 col-lg-3 my-1">
                                            <div class="card card-custom h-100">
                                                <div class="card-header d-flex flex-row justify-content-between">
                                                    <div class="d-flex flex-row align-items-center">
                                                        <i><img src="{{ url('img/icons/drag_indicator.svg') }}"
                                                                alt=""></i>
                                                        <h2 class="title m-0 approval-dashboard-title">Attendance History
                                                        </h2>
                                                    </div>
                                                </div>
                                                <div class="card-body card-max-height pb-0">
                                                    <div class="overflow-content overflow-auto pr-1"
                                                        id="attendance_history">
                                                        <span>{{ date('d F Y') }}</span>
                                                    </div>
                                                </div>
                                                <div class="card-footer card-footer-announcement text-center">
                                                    <a href="#"><span class="font-weight-bolder">View
                                                            Details</span></a>
                                                </div>
                                            </div>
                                        </div>
                                    @endif --}}
                                    <!--  END : Attendance History Widget -->

                                    <!--  BEGIN : Presence Widget -->
                                    {{-- @if ($widget->dashboard_id == 4 && $widget->is_active == 1)
                                        <div class="col-sm-12 col-lg-3 my-1">
                                            <div class="card card-custom h-100">
                                                <div class="card-header d-flex flex-row justify-content-between">
                                                    <div class="d-flex flex-row align-items-center">
                                                        <i><img src="{{ url('img/icons/drag_indicator.svg') }}"
                                                                alt=""></i>
                                                        <h2 class="title m-0 approval-dashboard-title">Presence</h2>
                                                    </div>
                                                </div>
                                                <div class="card-body card-max-height pb-0">
                                                    <div class="overflow-content-long overflow-auto pr-1">
                                                        <span>{{ date('d F Y') }}</span>
                                                        <canvas
                                                            class="doughnut-chart-ex chartjs chartjs-render-monitor"></canvas>
                                                        <div class="d-flex justify-content-between mt-3 mb-1">
                                                            <div class="d-flex align-items-center">
                                                                <i
                                                                    class="fa-solid fa-circle font-medium-2 text-primary"></i>
                                                                <span class="ml-75 mr-25">Present</span>
                                                            </div>
                                                            <div>
                                                                <span class="font-weight-bold text-primary">92%</span>
                                                            </div>
                                                        </div>
                                                        <div class="d-flex justify-content-between mb-1">
                                                            <div class="d-flex align-items-center">
                                                                <i
                                                                    class="fa-solid fa-circle font-medium-2 text-warning"></i>
                                                                <span class="ml-75 mr-25">On Leave</span>
                                                            </div>
                                                            <div>
                                                                <span class="font-weight-bold text-warning">5%</span>
                                                            </div>
                                                        </div>
                                                        <div class="d-flex justify-content-between">
                                                            <div class="d-flex align-items-center">
                                                                <i
                                                                    class="fa-solid fa-circle font-medium-2 text-danger"></i>
                                                                <span class="ml-75 mr-25">Absent</span>
                                                            </div>
                                                            <div>
                                                                <span class="font-weight-bold text-danger">3%</span>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>

                                            </div>
                                        </div>
                                    @endif --}}
                                    <!--  END : Presence Widget -->

                                    <!-- BEGIN : Calendar Widget -->
                                    {{-- @if ($widget->dashboard_id == 5 && $widget->is_active == 1)
                                        <div class="col-sm-12 col-lg-6 my-1">
                                            <div class="card card-custom h-100">
                                                <div class="card-header d-flex flex-row justify-content-between">
                                                    <div class="d-flex flex-row align-items-center">
                                                        <i><img src="{{ url('img/icons/drag_indicator.svg') }}"
                                                                alt=""></i>
                                                        <h2 class="title m-0 approval-dashboard-title">Calendar & Events
                                                        </h2>
                                                    </div>
                                                </div>
                                                <div class="card-body card-max-height pb-0">
                                                    <div class="overflow-content-long overflow-auto pr-1">
                                                        <div class="row">
                                                            <div class="col-lg-6">
                                                                <div id='calendar'></div>
                                                            </div>
                                                            <div class="col-lg-6">
                                                                <div id='calendar'></div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    @endif --}}
                                    <!-- END : Calendar Widget -->

                                    <!-- BEGIN : Approval Widget -->
                                    @if ($widget->dashboard_id == 6 && $widget->is_active == 1)
                                        @canany(['leave_approval.approve', 'leave_approval.reject'])
                                            <div class="col-sm-12 col-lg-6 my-1">
                                                <div class="card card-custom approval-dashboard-outer-container">
                                                    <div class="card-header d-flex flex-row justify-content-between">
                                                        <div class="d-flex flex-row align-items-center">
                                                            <i><img src="{{ url('img/icons/drag_indicator.svg') }}"
                                                                    alt=""></i>
                                                            <h2 class="title m-0 approval-dashboard-title">Approval</h2>
                                                        </div>
                                                    </div>
                                                    <div class="card-body pb-0 approval-dashboard-container">
                                                        <ul class="nav nav-pills" id="pills-tab" role="tablist">
                                                            <li class="nav-item approval-dashboard-tab my-submissions"
                                                                role="presentation" style="border-bottom: 2px solid #7367f0">
                                                                <a class="nav-link active" id="pills-my-submissions-tab"
                                                                    data-toggle="pill" href="#pills-my-submissions"
                                                                    role="tab" aria-controls="pills-my-submissions"
                                                                    aria-selected="true">My Submissions</a>
                                                            </li>
                                                            <li class="nav-item approval-dashboard-tab employee-submissions"
                                                                role="presentation" style="border-bottom: 2px solid #babfc7">
                                                                <a class="nav-link" id="pills-employee-submissions-tab"
                                                                    data-toggle="pill" href="#pills-employee-submissions"
                                                                    role="tab" aria-controls="pills-employee-submissions"
                                                                    aria-selected="false">Employee Submissions</a>
                                                            </li>
                                                        </ul>
                                                        <div class="tab-content" id="pills-tabContent">
                                                            <div class="tab-pane fade show active" id="pills-my-submissions"
                                                                role="tabpanel" aria-labelledby="pills-my-submissions-tab">
                                                                @if (!$myLeaveSubmissions->isEmpty() || !$myAttendanceSubmissions->isEmpty() || !$myOvertimeSubmissions->isEmpty())
                                                                    @foreach ($myLeaveSubmissions as $myLeaveSubmission)
                                                                        <div
                                                                            class="row submissions-row d-flex align-items-baseline">
                                                                            <div class="col-6">
                                                                                <div class="d-flex align-items-center">
                                                                                    <span class="submissions-title">Leave
                                                                                        Application</span>
                                                                                    <img src="{{ url('img/icons/ellipse-20.svg') }}"
                                                                                        alt="Ellipse">
                                                                                    <span
                                                                                        class="submissions-description">{{ $myLeaveSubmission->leave_type_name }}</span>
                                                                                </div>
                                                                            </div>
                                                                            <div class="col-2 submissions-date">
                                                                                {{ \Carbon\Carbon::parse($myLeaveSubmission->application_date)->format('d/m/Y') }}
                                                                            </div>
                                                                            <div class="col-2 submissions-status">
                                                                                <span
                                                                                    class="badge badge-warning">Pending</span>
                                                                            </div>
                                                                            <div class="col-2">
                                                                                <a href="{{ url('self-leave-application') }}"
                                                                                    id="pending-status-cancel"
                                                                                    class="btn btn-outline-info round pending-status-cancel d-flex justify-content-center align-items-center">Details</a>
                                                                            </div>
                                                                        </div>
                                                                        <hr class="submissions-separator">
                                                                    @endforeach
                                                                    @foreach ($myAttendanceSubmissions as $myAttendanceSubmission)
                                                                        <div
                                                                            class="row submissions-row d-flex align-items-baseline">
                                                                            <div class="col-6">
                                                                                <div class="d-flex align-items-center">
                                                                                    <span class="submissions-title">Manual
                                                                                        Attendance</span>
                                                                                    <img src="{{ url('img/icons/ellipse-20.svg') }}"
                                                                                        alt="Ellipse">
                                                                                    <span class="submissions-description">for
                                                                                        {{ \Carbon\Carbon::parse($myAttendanceSubmission->check_in)->format('d/m/Y') }}</span>
                                                                                </div>
                                                                            </div>
                                                                            <div class="col-2 submissions-date">
                                                                                {{ \Carbon\Carbon::parse($myAttendanceSubmission->date)->format('d/m/Y') }}
                                                                            </div>
                                                                            <div class="col-2 submissions-status">
                                                                                <span
                                                                                    class="badge badge-warning">Pending</span>
                                                                            </div>
                                                                            <div class="col-2">
                                                                                <a href="{{ url('attendance') }}"
                                                                                    id="pending-status-cancel"
                                                                                    class="btn btn-outline-info round pending-status-cancel d-flex justify-content-center align-items-center">Details</a>
                                                                            </div>
                                                                        </div>
                                                                        <hr class="submissions-separator">
                                                                    @endforeach
                                                                    @foreach ($myOvertimeSubmissions as $myOvertimeSubmission)
                                                                        <div
                                                                            class="row submissions-row d-flex align-items-baseline">
                                                                            <div class="col-6">
                                                                                <div class="d-flex align-items-center">
                                                                                    <span
                                                                                        class="submissions-title">Overtime</span>
                                                                                    <img src="{{ url('img/icons/ellipse-20.svg') }}"
                                                                                        alt="Ellipse">
                                                                                    <span class="submissions-description">for
                                                                                        {{ \Carbon\Carbon::parse($myOvertimeSubmission->date)->format('d/m/Y') }}</span>
                                                                                </div>
                                                                            </div>
                                                                            <div class="col-2 submissions-date">
                                                                                {{ \Carbon\Carbon::parse($myOvertimeSubmission->created_at)->format('d/m/Y') }}
                                                                            </div>
                                                                            <div class="col-2 submissions-status">
                                                                                <span
                                                                                    class="badge badge-warning">Pending</span>
                                                                            </div>
                                                                            <div class="col-2">
                                                                                <a href="{{ url('overtimes') }}"
                                                                                    id="pending-status-cancel"
                                                                                    class="btn btn-outline-info round pending-status-cancel d-flex justify-content-center align-items-center">Details</a>
                                                                            </div>
                                                                        </div>
                                                                        <hr class="submissions-separator">
                                                                    @endforeach
                                                                @else
                                                                    You don't have submission.
                                                                @endif
                                                            </div>
                                                            <div class="tab-pane fade" id="pills-employee-submissions"
                                                                role="tabpanel"
                                                                aria-labelledby="pills-employee-submissions-tab">
                                                                @if (!$employeeLeaveSubmissions->isEmpty() || !$employeeAttendanceSubmissions->isEmpty() || !$employeeOvertimeSubmissions->isEmpty())
                                                                    @foreach ($employeeLeaveSubmissions as $employeeLeaveSubmission)
                                                                        <div
                                                                            class="row submissions-row d-flex align-items-baseline">
                                                                            <div class="col-6">
                                                                                <div class="d-flex align-items-center">
                                                                                    <span class="submissions-title">Leave
                                                                                        Application</span>
                                                                                    <img src="{{ url('img/icons/ellipse-20.svg') }}"
                                                                                        alt="Ellipse">
                                                                                    <span
                                                                                        class="submissions-description">{{ $employeeLeaveSubmission->leave_type_name }}</span>
                                                                                </div>
                                                                            </div>
                                                                            <div class="col-2 submissions-date">
                                                                                {{ \Carbon\Carbon::parse($employeeLeaveSubmission->application_date)->format('d/m/Y') }}
                                                                            </div>
                                                                            <div class="col-2 submissions-status">
                                                                                <span
                                                                                    class="badge badge-warning">Pending</span>
                                                                            </div>
                                                                            <div class="col-2">
                                                                                <a href="{{ url('approval/leave-application/details/' . $employeeLeaveSubmission->leave_application_id . '') }}"
                                                                                    id="pending-status-cancel"
                                                                                    class="btn btn-outline-info round pending-status-cancel d-flex justify-content-center align-items-center">Details</a>
                                                                            </div>
                                                                        </div>
                                                                        <hr class="submissions-separator">
                                                                    @endforeach
                                                                    @foreach ($employeeAttendanceSubmissions as $employeeAttendanceSubmission)
                                                                        <div
                                                                            class="row submissions-row d-flex align-items-baseline">
                                                                            <div class="col-6">
                                                                                <div class="d-flex align-items-center">
                                                                                    <span class="submissions-title">Manual
                                                                                        Attendance</span>
                                                                                    <img src="{{ url('img/icons/ellipse-20.svg') }}"
                                                                                        alt="Ellipse">
                                                                                    <span class="submissions-description">for
                                                                                        {{ \Carbon\Carbon::parse($employeeAttendanceSubmission->check_in)->format('d/m/Y') }}</span>
                                                                                </div>
                                                                            </div>
                                                                            <div class="col-2 submissions-date">
                                                                                {{ \Carbon\Carbon::parse($employeeAttendanceSubmission->date)->format('d/m/Y') }}
                                                                            </div>
                                                                            <div class="col-2 submissions-status">
                                                                                <span
                                                                                    class="badge badge-warning">Pending</span>
                                                                            </div>
                                                                            <div class="col-2">
                                                                                <a href="{{ url('approval/manual-attendance/details/' . $employeeAttendanceSubmission->id . '') }}"
                                                                                    id="pending-status-cancel"
                                                                                    class="btn btn-outline-info round pending-status-cancel d-flex justify-content-center align-items-center">Details</a>
                                                                            </div>
                                                                        </div>
                                                                        <hr class="submissions-separator">
                                                                    @endforeach
                                                                    @foreach ($employeeOvertimeSubmissions as $employeeOvertimeSubmission)
                                                                        <div
                                                                            class="row submissions-row d-flex align-items-baseline">
                                                                            <div class="col-6">
                                                                                <div class="d-flex align-items-center">
                                                                                    <span
                                                                                        class="submissions-title">Overtime</span>
                                                                                    <img src="{{ url('img/icons/ellipse-20.svg') }}"
                                                                                        alt="Ellipse">
                                                                                    <span class="submissions-description">for
                                                                                        {{ \Carbon\Carbon::parse($employeeOvertimeSubmission->date)->format('d/m/Y') }}</span>
                                                                                </div>
                                                                            </div>
                                                                            <div class="col-2 submissions-date">
                                                                                {{ \Carbon\Carbon::parse($employeeOvertimeSubmission->created_at)->format('d/m/Y') }}
                                                                            </div>
                                                                            <div class="col-2 submissions-status">
                                                                                <span
                                                                                    class="badge badge-warning">Pending</span>
                                                                            </div>
                                                                            <div class="col-2">
                                                                                <a href="{{ url('approval/overtime/' . $employeeOvertimeSubmission->overtime_id . '/details') }}"
                                                                                    id="pending-status-cancel"
                                                                                    class="btn btn-outline-info round pending-status-cancel d-flex justify-content-center align-items-center">Details</a>
                                                                            </div>
                                                                        </div>
                                                                        <hr class="submissions-separator">
                                                                    @endforeach
                                                                @else
                                                                    There is no submission.
                                                                @endif
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        @endcanany
                                        @if (Auth::user()->canany(['self_leave_application.create', 'self_attendance.create', 'self_overtime.create']) && Auth::user()->cannot(['leave_approval.approve', 'leave_approval.reject']))
                                            <div class="col-sm-12 col-lg-6 my-1">
                                                <div class="card card-custom approval-dashboard-outer-container">
                                                    <div class="card-header d-flex flex-row justify-content-between">
                                                        <div class="d-flex flex-row align-items-center">
                                                            <i><img src="{{ url('img/icons/drag_indicator.svg') }}"
                                                                    alt=""></i>
                                                            <div class="d-flex">
                                                                <h2 class="title m-0 my-approval-dashboard-title">Approval
                                                                </h2>
                                                                <img src="{{ url('img/icons/rectangle-1159.svg') }}"
                                                                    alt="Rectangle">
                                                                <h2 class="title m-0 my-approval-dashboard-subtitle">My
                                                                    Submissions</h2>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="card-body pb-0 approval-dashboard-container"
                                                        id="pills-my-submissions">
                                                        @if (!$myLeaveSubmissions->isEmpty() || !$myAttendanceSubmissions->isEmpty() || !$myOvertimeSubmissions->isEmpty())
                                                            @foreach ($myLeaveSubmissions as $myLeaveSubmission)
                                                                <div
                                                                    class="row submissions-row d-flex align-items-baseline">
                                                                    <div class="col-6">
                                                                        <div class="d-flex align-items-center">
                                                                            <span class="submissions-title">Leave
                                                                                Application</span>
                                                                            <img src="{{ url('img/icons/ellipse-20.svg') }}"
                                                                                alt="Ellipse">
                                                                            <span
                                                                                class="submissions-description">{{ $myLeaveSubmission->leave_type_name }}</span>
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-2 submissions-date">
                                                                        {{ \Carbon\Carbon::parse($myLeaveSubmission->application_date)->format('d/m/Y') }}
                                                                    </div>
                                                                    <div class="col-2 submissions-status">
                                                                        <span class="badge badge-warning">Pending</span>
                                                                    </div>
                                                                    <div class="col-2">
                                                                        <a href="{{ url('self-leave-application') }}"
                                                                            id="pending-status-cancel"
                                                                            class="btn btn-outline-info round pending-status-cancel d-flex justify-content-center align-items-center">Details</a>
                                                                    </div>
                                                                </div>
                                                                <hr class="submissions-separator">
                                                            @endforeach
                                                            @foreach ($myAttendanceSubmissions as $myAttendanceSubmission)
                                                                <div
                                                                    class="row submissions-row d-flex align-items-baseline">
                                                                    <div class="col-6">
                                                                        <div class="d-flex align-items-center">
                                                                            <span class="submissions-title">Manual
                                                                                Attendance</span>
                                                                            <img src="{{ url('img/icons/ellipse-20.svg') }}"
                                                                                alt="Ellipse">
                                                                            <span class="submissions-description">for
                                                                                {{ \Carbon\Carbon::parse($myAttendanceSubmission->check_in)->format('d/m/Y') }}</span>
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-2 submissions-date">
                                                                        {{ \Carbon\Carbon::parse($myAttendanceSubmission->date)->format('d/m/Y') }}
                                                                    </div>
                                                                    <div class="col-2 submissions-status">
                                                                        <span class="badge badge-warning">Pending</span>
                                                                    </div>
                                                                    <div class="col-2">
                                                                        <a href="{{ url('attendance') }}"
                                                                            id="pending-status-cancel"
                                                                            class="btn btn-outline-info round pending-status-cancel d-flex justify-content-center align-items-center">Details</a>
                                                                    </div>
                                                                </div>
                                                                <hr class="submissions-separator">
                                                            @endforeach
                                                            @foreach ($myOvertimeSubmissions as $myOvertimeSubmission)
                                                                <div
                                                                    class="row submissions-row d-flex align-items-baseline">
                                                                    <div class="col-6">
                                                                        <div class="d-flex align-items-center">
                                                                            <span class="submissions-title">Overtime</span>
                                                                            <img src="{{ url('img/icons/ellipse-20.svg') }}"
                                                                                alt="Ellipse">
                                                                            <span class="submissions-description">for
                                                                                {{ \Carbon\Carbon::parse($myOvertimeSubmission->date)->format('d/m/Y') }}</span>
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-2 submissions-date">
                                                                        {{ \Carbon\Carbon::parse($myOvertimeSubmission->created_at)->format('d/m/Y') }}
                                                                    </div>
                                                                    <div class="col-2 submissions-status">
                                                                        <span class="badge badge-warning">Pending</span>
                                                                    </div>
                                                                    <div class="col-2">
                                                                        <a href="{{ url('overtimes') }}"
                                                                            id="pending-status-cancel"
                                                                            class="btn btn-outline-info round pending-status-cancel d-flex justify-content-center align-items-center">Details</a>
                                                                    </div>
                                                                </div>
                                                                <hr class="submissions-separator">
                                                            @endforeach
                                                        @else
                                                            You don't have submission.
                                                        @endif
                                                    </div>
                                                </div>
                                            </div>
                                        @endif
                                    @endif
                                    <!-- END : Approval Widget -->

                                    <!-- BEGIN : Task Widget -->
                                    {{-- @if ($widget->dashboard_id == 7 && $widget->is_active == 1)
                                        <div class="col-sm-12 col-lg-6 my-1">
                                            <div class="card card-custom">
                                                <div class="card-header d-flex flex-row justify-content-between">
                                                    <div class="d-flex flex-row align-items-center">
                                                        <i><img src="{{ url('img/icons/drag_indicator.svg') }}"
                                                                alt=""></i>
                                                        <h2 class="title m-0 approval-dashboard-title">Task</h2>
                                                    </div>
                                                </div>
                                                <div class="card-body pb-0 task-dashboard-container"></div>
                                            </div>
                                        </div>
                                    @endif --}}
                                    <!-- END : Task Widget -->

                                    <!-- BEGIN : Quick Links Widget -->
                                    {{-- @if ($widget->dashboard_id == 8 && $widget->is_active == 1)
                                        <div class="col-sm-12 col-lg-3 my-1">
                                            <div class="card card-custom h-100">
                                                <div class="card-header d-flex flex-row justify-content-between">
                                                    <div class="d-flex flex-row align-items-center">
                                                        <i><img src="{{ url('img/icons/drag_indicator.svg') }}"
                                                                alt=""></i>
                                                        <h2 class="title m-0 approval-dashboard-title">Quick Links</h2>
                                                    </div>
                                                </div>
                                                <div class="card-body card-max-height pb-0">
                                                    <div class="overflow-content-long overflow-auto pr-1">
                                                        <p class="quicklink-sub-title font-weight-bold m-0 mb-1">Features
                                                        </p>
                                                        <div class="">
                                                            <div class="quicklink-content mb-1">
                                                                <i><img src="{{ url('img/icons/progress.svg') }}"
                                                                        alt=""></i>
                                                                <a href="#"
                                                                    class="ml-1 font-weight-bold">Progress</a>
                                                            </div>
                                                            <div class="quicklink-content mb-1">
                                                                <i><img src="{{ url('img/icons/transfer.svg') }}"
                                                                        alt=""></i>
                                                                <a href="#" class="ml-1 font-weight-bold">Transfer
                                                                    Employee</a>
                                                            </div>
                                                        </div>
                                                        <p class="quicklink-sub-title font-weight-bold m-0 my-1">URL Links
                                                        </p>
                                                        <div class="">
                                                            <div class="quicklink-content mb-1">
                                                                <i><img src="{{ url('img/icons/url-link.svg') }}"
                                                                        alt=""></i>
                                                                <a href="#"
                                                                    class="ml-1 font-weight-bold">Classroom</a>
                                                            </div>
                                                            <div class="quicklink-content mb-1">
                                                                <i><img src="{{ url('img/icons/url-link.svg') }}"
                                                                        alt=""></i>
                                                                <a href="#" class="ml-1 font-weight-bold">Google
                                                                    Drive</a>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    @endif --}}
                                    <!-- END : Quick Links Widget -->

                                    <!-- BEGIN : Today Teammate -->
                                    {{-- @if ($widget->dashboard_id == 9 && $widget->is_active == 1)
                                        <div class="col-sm-12 col-lg-3 my-1">
                                            <div class="card card-custom h-100">
                                                <div class="card-header d-flex flex-row justify-content-between">
                                                    <div class="d-flex flex-row align-items-center">
                                                        <i><img src="{{ url('img/icons/drag_indicator.svg') }}"
                                                                alt=""></i>
                                                        <div>
                                                            <h2 class="title m-0 approval-dashboard-title">Today Teammate
                                                            </h2>
                                                            <span class="approve-request">Project Name</span>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="card-body card-max-height pb-0">
                                                    <div class="overflow-content overflow-auto pr-1">
                                                        <div class="d-flex align-items-center">
                                                            <span class="avatar"><img class="round"
                                                                    src="{{ url('app-assets/images/portrait/small/avatar-s-11.jpg') }}"
                                                                    alt="avatar" height="30" width="30"></span>
                                                            <div class="d-flex flex-column pl-1 ">
                                                                <span
                                                                    class="approve-request font-weight-bold text-break">Muhammad
                                                                    Yusuf Nasrullah</span>
                                                                <span class="approve-request">Head of SBU</span>
                                                            </div>
                                                        </div>
                                                        <div class="d-flex align-items-center mt-1">
                                                            <span class="avatar"><img class="round"
                                                                    src="{{ url('app-assets/images/portrait/small/avatar-s-11.jpg') }}"
                                                                    alt="avatar" height="30" width="30"></span>
                                                            <div class="d-flex flex-column pl-1 ">
                                                                <span
                                                                    class="approve-request font-weight-bold text-break">Muhammad
                                                                    Yusuf Nasrullah</span>
                                                                <span class="approve-request">Head of SBU</span>
                                                            </div>
                                                        </div>
                                                        <div class="d-flex align-items-center mt-1">
                                                            <span class="avatar"><img class="round"
                                                                    src="{{ url('app-assets/images/portrait/small/avatar-s-11.jpg') }}"
                                                                    alt="avatar" height="30" width="30"></span>
                                                            <div class="d-flex flex-column pl-1 ">
                                                                <span
                                                                    class="approve-request font-weight-bold text-break">Muhammad
                                                                    Yusuf Nasrullah</span>
                                                                <span class="approve-request">Head of SBU</span>
                                                            </div>
                                                        </div>
                                                        <div class="d-flex align-items-center mt-1">
                                                            <span class="avatar"><img class="round"
                                                                    src="{{ url('app-assets/images/portrait/small/avatar-s-11.jpg') }}"
                                                                    alt="avatar" height="30" width="30"></span>
                                                            <div class="d-flex flex-column pl-1 ">
                                                                <span
                                                                    class="approve-request font-weight-bold text-break">Muhammad
                                                                    Yusuf Nasrullah</span>
                                                                <span class="approve-request">Head of SBU</span>
                                                            </div>
                                                        </div>
                                                        <div class="d-flex align-items-center mt-1">
                                                            <span class="avatar"><img class="round"
                                                                    src="{{ url('app-assets/images/portrait/small/avatar-s-11.jpg') }}"
                                                                    alt="avatar" height="30" width="30"></span>
                                                            <div class="d-flex flex-column pl-1 ">
                                                                <span
                                                                    class="approve-request font-weight-bold text-break">Muhammad
                                                                    Yusuf Nasrullah</span>
                                                                <span class="approve-request">Head of SBU</span>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="card-footer card-footer-announcement text-center">
                                                    <a href="#"><span class="font-weight-bolder">View
                                                            Details</span></a>
                                                </div>
                                            </div>
                                        </div>
                                    @endif --}}
                                    <!-- END : Today Teammate -->
                                @endforeach

                                <!-- BEGIN : Customize Widget -->
                                {{-- <div class="col-sm-12 col-lg-3 my-1">
                                    <div class="custom-widget h-100 d-flex align-items-center justify-content-center">
                                        <button type="button" data-toggle="modal" data-target="#widgetModal"
                                            class="btn">
                                            <div class="d-flex flex-column align-items-center">
                                                <i class="my-1"><img src="{{ url('img/icons/big-plus-circle.svg') }}"
                                                        alt=""></i>
                                                <p class="font-weight-bolder">Customize Widget</p>
                                            </div>
                                        </button>
                                    </div>
                                </div> --}}
                                <!-- END : Customize Widget -->
                            </div>
                        </div>
                    </div>
                    <!-- END: Widgets -->
                </div>
                <!-- END: Line Chart Card -->
            </div>
            <!-- END: Body Content -->
        </div>
        <!-- END: Content Wrapper -->
    </div>
    <!-- END: Content-->

    <!-- BEGIN : Widget Modal -->
    <div class="modal fade" id="widgetModal" tabindex="-1" aria-labelledby="widgetModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
            <div class="modal-content">
                <div class="modal-header bg-white border-bottom">
                    <h4 class="modal-title my-auto font-weight-bolder py-0" id="widgetModalLabel">Costumize Widget</h4>
                    <button type="button" class="close mr-0 mt-0 mb-0" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    @foreach ($widgets as $widget)
                        @foreach ($widgetsBuilder as $widgetBuilder)
                            @if ($widget->dashboard_id == $widgetBuilder->id)
                                <div class="row align-items-center mb-2">
                                    <i><img src="{{ url('img/icons/drag_indicator.svg') }}" alt=""></i>
                                    <p class="text-dark font-weight-bold m-0 ml-1">{{ $widgetBuilder->name }}</p>
                                    <div class="custom-control custom-switch ml-auto">
                                        <input type="checkbox" class="custom-control-input check"
                                            id="switches{{ $widgetBuilder->id }}"
                                            {{ $widget->is_active == 1 ? 'checked' : '' }}
                                            name="sitches{{ $widgetBuilder->id }}" value="{{ $widgetBuilder->id }}">
                                        <label class="custom-control-label"
                                            for="switches{{ $widgetBuilder->id }}"></label>
                                    </div>
                                </div>
                            @endif
                        @endforeach
                    @endforeach
                </div>
            </div>
        </div>
    </div>
    <!-- END : Widget Modal -->
@endsection

@section('page_js')
    <script src="{{ url('app-assets/vendors/js/forms/validation/jquery.validate.min.js') }}"></script>
    <script src="{{ url('app-assets/vendors/js/extensions/sweetalert2.all.min.js') }}"></script>
    <script src="{{ url('js/dateHelpers.js') }}"></script>
@endsection

@section('page_script')
    <script>
        $(document).ready(() => {
            getAnnouncements();
            getAttendances();
            timer();
            checkAttendance();
            getWorkshift();
        });

        $(document).on('click', '.my-submissions', function() {
            $('.my-submissions').css('border-bottom', '2px solid #7367f0');
            $('.employee-submissions').css('border-bottom', '2px solid #babfc7');
        });

        $(document).on('click', '.employee-submissions', function() {
            $('.my-submissions').css('border-bottom', '2px solid #babfc7');
            $('.employee-submissions').css('border-bottom', '2px solid #7367f0');
        });

        /**
         * * Fetch all data announcement and store into announcement-modal.
         * 
         **/
        const view_data = (e) => {
            $('#modal-view').modal('show');

            $.ajax({
                url: "{{ url('announcements/edit') }}" + "/" + e.attr('data-id'),
                method: "GET",
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                dataType: "json",
                success: function(result) {
                    let str = result.data.announcement_content;
                    let published_at = convertDateToIndo(result.data.published_at)
                    let file_url = result.file_url
                    let image_url = result.image_url
                    let parsed = $('<p>' + str + '</p>').text();
                    $('#announcement_id').val(result.data.announcement_id);
                    $('#announcement_title').html(result.data.announcement_title);
                    $('#announcement_content').html(result.data.announcement_content).text();
                    $('#announcement_image').attr("src", image_url);
                    $('#announcement_attachment').attr("href", file_url);
                    $('#announcement_attachment').html(file_url);
                    $('#announcement_attachment').click(function(e) {
                        e.preventDefault(); //stop the browser from following
                        window.location.href = file_url;
                    });
                    $('#published_at').html(published_at);
                    $('#published_by').html(result.published_by);
                }
            });
        };

        /**
         * * Fetch all published data annoucements.
         * 
         **/
        const getAnnouncements = () => {
            $.ajax({
                url: "{{ url('home/announcements') }}",
                method: "GET",
                dataType: "json",
                success: function(response) {
                    const announcements = response.data
                    const totalAnnouncements = response.total
                    let announcementContents = $("#announcement-contents")

                    if (totalAnnouncements !== 0) {
                        announcements.forEach((announcement) => {
                            // let published_at = convertDateToIndo(announcement.published_at);
                            let published_at = announcement.published_at;

                            if (`${announcement.announcement_content.length}` > 100) {
                                announcementContents.append(`
                                    <a type="button" onclick="view_data($(this))" data-id="${announcement.announcement_id}" id="view" data-toggle="modal-view" data-target="#modal-view" class="w-100"><div class="d-flex align-items-center"><img class="pb-1 mr-1 ml-1 pt-1 dash-announ" name="announcement_image" id="announcement_image" src="${announcement.media.image.disk}"><div class="announcement-dashboard-title mr-1"><span class="font-weight-bolder">${announcement.announcement_title}</span><div class="mt-1">${announcement.announcement_content.substr(0, 100) + '...'}</div></div><div<span class="badge badge-light-secondary">${published_at}</span</div></div></a><div class="dropdown-divider my-1"></div>
                                `);
                            } else {
                                announcementContents.append(`
                                    <a type="button" onclick="view_data($(this))" data-id="${announcement.announcement_id}" id="view" data-toggle="modal-view" data-target="#modal-view" class="w-100"><div class="d-flex align-items-center"><img class="pb-1 mr-1 ml-1 pt-1 dash-announ" name="announcement_image" id="announcement_image" src="${announcement.media.image.disk}"><div class="announcement-dashboard-title mr-1"><span class="font-weight-bolder">${announcement.announcement_title}</span><div class="mt-1"><div class="mt-1 announcement-dashboard-content">${announcement.announcement_content}</div></div></div><div><span class="badge badge-light-secondary">${published_at}</span></div></div></a><div class="dropdown-divider my-1"></div>
                                `);
                            }
                        })
                    } else {
                        announcementContents.append(`<div><span>There is no announcement.</span>`);
                    }
                }
            })
        };

        /**
         * * Fetch all check in employee attendances on the day.
         * 
         **/
        const getAttendances = () => {
            let url = "{{ url('attendance/get_attendance') }}"

            $.ajax({
                url: url,
                method: "GET",
                dataType: "json",
                success: (result) => {
                    if (result.length !== 0) {
                        for (let i in result) {
                            $('#attendance_history').append(
                                `<div class="d-flex align-items-center mt-1">
                                <div>
                                    <span class="avatar"><img class="round"src="{{ url('app-assets/images/portrait/small/avatar-s-11.jpg') }}" alt="avatar" height="30" width="30"></span>
                                    <span class="user-name">${result[i].user}</span>
                                </div>
                                <span class="ml-auto">${result[i].check_in}</span>
                            </div>`
                            );
                        }
                    } else {
                        $('#attendance_history').append(
                            `<div>
                            <span>No attendances for today</span
                        </div>`
                        )
                    }
                }
            })
        };

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
                        workshift,
                        workhours,
                        workdays
                    } = result
                    const workhour = `${workhours.h} H ${workhours.m} M`
                    $("#attend-checkin").append(workshift.start_time.substr(0, 5));
                    $("#attend-checkout").append(workshift.end_time.substr(0, 5));
                    $("#attend-shift").append(workshift.shift_name);
                    $("#attend-workhour").append(workhour);
                    $("#attend-workday").append(workdays.data);
                }
            });
        }

        const check = document.querySelectorAll(".check");
        check.forEach((element, index) => {
            element.addEventListener('change', function() {
                const data = {
                    id: this.value
                };
                fetch(`/dashboard/update/${this.value}`, {
                        method: 'PUT',
                        headers: {
                            'Content-Type': 'application/json',
                        },
                        body: JSON.stringify(data),
                    })
                    .then(response => response.json())
                    .then(data => {
                        console.log('Success:', data);
                        try {
                            if (data.error === true) {
                                throw data.message
                            }

                            setTimeout(function() {
                                // location.reload();
                                $("#widget").load(location.href + " #widget");
                            }, 1000);
                        } catch (error) {
                            console.error('Error:', error);
                        }
                    })
                    .catch((error) => {
                        console.error('Error:', error);
                    });
            });
        });

        let interval = null;

        const checkAttendance = () => {
            const id = document.querySelector('#user_id').value;
            fetch(`/attendance/today/${id}`)
                .then(response => response.json())
                .then(response => {
                    if (response.data !== null) {
                        if (response.late != null) {
                            let check_in_time = response.late
                            document.querySelector('#late_time').innerHTML = "Late By " + check_in_time
                        }
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
                            workTime.innerHTML = response.data.working_hour.slice(0, 5);
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

                                let h = hours < 10 ? '0' + hours : hours;
                                let m = minutes < 10 ? '0' + minutes : minutes;

                                workTime.innerHTML = `${h}:${m}`;
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
                    getAttendances();
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
            let attendanceTimer = document.querySelector('#attendance-timer');
            const today = new Date();
            let h = ('0' + today.getHours()).slice(-2);
            let m = today.getMinutes();
            let s = today.getSeconds();
            m = checkTime(m);
            s = checkTime(s);

            let time = `${h}:${m}`;

            attendanceTimer.innerHTML = time;
            setTimeout(timer, 0);
        }

        const checkTime = (i) => {
            if (i < 10) {
                i = '0' + i;
            }

            return i;
        }
    </script>
@endsection
