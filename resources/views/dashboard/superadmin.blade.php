@extends('partials.superadmin.superadmin')

@section('main')
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <!-- BEGIN: Content-->
    <div class="content-custom">
        <div class="content-overlay"></div>
        <div class="header-navbar-shadow"></div>
        <!-- BEGIN: Content Wrapper -->
        <div class="content-wrapper">
            <!-- BEGIN: Body Content -->
            <div class="content-body">
                <!-- BEGIN: Line Chart Card -->
                <div class="row">
                    <!-- BEGIN: Welcome Card -->
                    <div class="col-12">
                        <div class="card card-jumbotron p-2">
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-lg-8">
                                        <div class="d-flex flex-column justify-content-center">
                                            <p class="font-weight-bolder date">{{ date('d F Y') }}</p>
                                            <h1 class="font-weight-bolder welcome">Welcome,
                                                <span>{{ $employeeName }}</span>
                                            </h1>
                                            <p class="mt-1"><q class="custom-q">Change is inevitable. Change will always
                                                    happen. but you
                                                    have to apply direction to change, and that's when it's progress.</q>
                                                <span class="text-primary">- Doug Baldwin -</span>
                                            </p>
                                        </div>
                                    </div>
                                    <div class="col-lg-4 d-flex justify-content-center">
                                        <img class="d-none d-lg-block d-xl-block"
                                            src="{{ url('img/dashboard/undraw_working_remotely_jh40.svg') }}"
                                            alt="">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-12">
                        <div class="card">
                            <div class="card-header">
                                <h4>Administration</h4>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-3">
                                        <div class="shadow p-1 mb-2 bg-white rounded">
                                            <a class="d-flex align-items-center" href="/companies">
                                                <img src="/img/icons/corporate-fare.svg" alt="corporate-fare"
                                                    class="pr-2">
                                                <span class="text-secondary">Holdings</span>
                                            </a>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="shadow p-1 mb-2 bg-white rounded">
                                            <a class="d-flex align-items-center" href="/roles">
                                                <img src="/img/icons/recent-actors.svg" alt="recent-actors" class="pr-2">
                                                <span class="text-secondary">Roles</span>
                                            </a>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="shadow p-1 mb-2 bg-white rounded">
                                            <a class="d-flex align-items-center" href="/branch">
                                                <img src="/img/icons/business.svg" alt="business" class="pr-2">
                                                <span class="text-secondary">Entity</span>
                                            </a>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="shadow p-1 mb-2 bg-white rounded">
                                            <a class="d-flex align-items-center" href="/departments">
                                                <img src="/img/icons/home-work.svg" alt="home-work" class="pr-2">
                                                <span class="text-secondary">SBU</span>
                                            </a>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="shadow p-1 mb-2 bg-white rounded">
                                            <a class="d-flex align-items-center" href="/job-positions">
                                                <img src="/img/icons/hail.svg" alt="hail" class="pr-2">
                                                <span class="text-secondary">Job Positions</span>
                                            </a>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="shadow p-1 mb-2 bg-white rounded">
                                            <a class="d-flex align-items-center" href="/job-classes">
                                                <img src="/img/icons/cases.svg" alt="cases" class="pr-2">
                                                <span class="text-secondary">Job Classes</span>
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="card-header">
                                <h4>Management</h4>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-3">
                                        <div class="shadow mb-2 bg-white rounded">
                                            <a class="btn-flat-secondary dropdown-toggle waves-effect nav-link d-flex align-items-center p-1"
                                                type="button" id="dropdownMenuButton100" data-toggle="dropdown"
                                                aria-haspopup="true" aria-expanded="false" href="#">
                                                <img src="/img/icons/person-add.svg" alt="person-add" class="pr-2">
                                                <span class="menu-title text-truncate">Employee Management</span></a>
                                            </a>
                                            <div class="dropdown-menu" aria-labelledby="dropdownMenuButton100"
                                                style="width: 90%">
                                                <a class="dropdown-item" href="{{ url('/employees') }}">
                                                    <img src="/img/icons/ellipse.svg" alt="ellipse" class="pr-2">
                                                    <span>Employee Lists</span></a>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-md-3">
                                        <div class="shadow mb-2 bg-white rounded">
                                            <a class="btn-flat-secondary dropdown-toggle waves-effect nav-link d-flex align-items-center p-1"
                                                type="button" id="dropdownMenuButton100" data-toggle="dropdown"
                                                aria-haspopup="true" aria-expanded="false" href="#">
                                                <img src="/img/icons/apps-outage.svg" alt="apps-outage" class="pr-2">
                                                <span class="menu-title text-truncate">Leave Management</span></a>
                                            </a>
                                            <div class="dropdown-menu" aria-labelledby="dropdownMenuButton100"
                                                style="width: 90%">
                                                <a class="dropdown-item" href="{{ url('/leave-balance') }}">
                                                    <img src="/img/icons/ellipse.svg" alt="ellipse" class="pr-2">
                                                    <span>Employee Balance</span>
                                                </a>
                                                <a class="dropdown-item" href="{{ url('/leave-reports') }}">
                                                    <img src="/img/icons/ellipse.svg" alt="ellipse" class="pr-2">
                                                    <span>Leave Reports</span>
                                                </a>
                                                <section id="collapsible">
                                                    <div class="row">
                                                        <div class="col-sm-12">
                                                            <div class="collapse-icon">
                                                                <div class="collapse-default">
                                                                    <div id="headingCollapse1" class="card-header"
                                                                        data-toggle="collapse" role="button"
                                                                        data-target="#collapse1" aria-expanded="false"
                                                                        aria-controls="collapse1"
                                                                        style="padding: .65rem 1.28rem">

                                                                        <span class="menu-title text-truncate"><img
                                                                                src="/img/icons/ellipse.svg"
                                                                                alt="ellipse" class="pr-2">Leave
                                                                            Settings </span>
                                                                    </div>
                                                                    <div id="collapse1" role="tabpanel"
                                                                        aria-labelledby="headingCollapse1"
                                                                        class="collapse">
                                                                        <div class="card"
                                                                            style="padding: .65rem 1.28rem">
                                                                            <a class="nav-link d-flex align-items-center pl-1 pr-1"
                                                                                href="/manage-holidays">
                                                                                <span>List of Holidays</span>
                                                                            </a>
                                                                            <a class="nav-link d-flex align-items-center pl-1 pr-1"
                                                                                href="/national-holidays">
                                                                                <span>National Holidays</span>
                                                                            </a>
                                                                            <a class="nav-link d-flex align-items-center pl-1 pr-1"
                                                                                href="/leave-types">
                                                                                <span>Types of Leave</span>
                                                                            </a>
                                                                            <a class="nav-link d-flex align-items-center pl-1 pr-1"
                                                                                href="/joint-holidays">
                                                                                <span>Joint Holidays</span>
                                                                            </a>
                                                                            <a class="nav-link d-flex align-items-center pl-1 pr-1"
                                                                                href="/leave-periods">
                                                                                <span>Leave Periods</span>
                                                                            </a>
                                                                        </div>
                                                                    </div>

                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </section>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-md-3">
                                        <div class="shadow mb-2 bg-white rounded">
                                            <a class="btn-flat-secondary dropdown-toggle waves-effect nav-link d-flex align-items-center p-1"
                                                type="button" id="dropdownMenuButton100" data-toggle="dropdown"
                                                aria-haspopup="true" aria-expanded="false" href="#">
                                                <img src="/img/icons/apps-outage.svg" alt="apps-outage" class="pr-2">
                                                <span class="menu-title text-truncate">Attendance Management</span></a>
                                            </a>
                                            <div class="dropdown-menu" aria-labelledby="dropdownMenuButton100"
                                                style="width: 90%">
                                                <section id="collapsible">
                                                    <div class="row">
                                                        <div class="col-sm-12">
                                                            <div class="collapse-icon">
                                                                <div class="collapse-default">
                                                                    <div id="headingCollapse1" class="card-header"
                                                                        data-toggle="collapse" role="button"
                                                                        data-target="#collapse1" aria-expanded="false"
                                                                        aria-controls="collapse1"
                                                                        style="padding: .65rem 1.28rem">

                                                                        <span class="menu-title text-truncate"><img
                                                                                src="/img/icons/ellipse.svg"
                                                                                alt="ellipse" class="pr-2">Attendance
                                                                            Reports </span>
                                                                    </div>
                                                                    <div id="collapse1" role="tabpanel"
                                                                        aria-labelledby="headingCollapse1"
                                                                        class="collapse">
                                                                        <div class="card"
                                                                            style="padding: .65rem 1.28rem">
                                                                            <a class="nav-link d-flex align-items-center"
                                                                                href="/daily-attendance">
                                                                                <span>Daily Recap</span>
                                                                            </a>
                                                                            <a class="nav-link d-flex align-items-center"
                                                                                href="/monthly-attendance">
                                                                                <span>Monthly Recap</span>
                                                                            </a>
                                                                            <a class="nav-link d-flex align-items-center"
                                                                                href="/overall-attendance">
                                                                                <span>Overall Recap</span>
                                                                            </a>
                                                                            <a class="nav-link d-flex align-items-center"
                                                                                href="/overtime-history">
                                                                                <span>Overtime</span>
                                                                            </a>
                                                                        </div>
                                                                    </div>

                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </section>

                                                <section id="collapsible">
                                                    <div class="row">
                                                        <div class="col-sm-12">
                                                            <div class="collapse-icon">
                                                                <div class="collapse-default">
                                                                    <div id="headingCollapse1" class="card-header"
                                                                        data-toggle="collapse" role="button"
                                                                        data-target="#attendance-set"
                                                                        aria-expanded="false" aria-controls="collapse1"
                                                                        style="padding: .65rem 1.28rem">

                                                                        <span class="menu-title text-truncate"><img
                                                                                src="/img/icons/ellipse.svg"
                                                                                alt="ellipse" class="pr-2">Attendance
                                                                            Settings </span>
                                                                    </div>
                                                                    <div id="attendance-set" role="tabpanel"
                                                                        aria-labelledby="headingCollapse1"
                                                                        class="collapse">
                                                                        <div class="card"
                                                                            style="padding: .65rem 1.28rem">
                                                                            <a class="nav-link d-flex align-items-center"
                                                                                href="/work-shifts">
                                                                                <span>Work Shifts</span>
                                                                            </a>
                                                                            <a class="nav-link d-flex align-items-center"
                                                                                href="/late">
                                                                                <span>Late</span>
                                                                            </a>
                                                                        </div>
                                                                    </div>

                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </section>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-md-3">
                                        <div class="shadow mb-2 bg-white rounded">
                                            <a class="btn-flat-secondary dropdown-toggle waves-effect nav-link d-flex align-items-center p-1"
                                                type="button" id="dropdownMenuButton100" data-toggle="dropdown"
                                                aria-haspopup="true" aria-expanded="false" href="#">
                                                <img src="/img/icons/work.svg" alt="insert-chart-outlined"
                                                    class="pr-2">
                                                <span class="menu-title text-truncate">Working Management</span></a>
                                            </a>
                                            <div class="dropdown-menu" aria-labelledby="dropdownMenuButton100"
                                                style="width: 90%">
                                                <a class="dropdown-item" href="{{ url('/workdays') }}">
                                                    <img src="/img/icons/ellipse.svg" alt="ellipse" class="pr-2">
                                                    <span class="menu-title text-truncate">Working Tracker</span></a>
                                            </div>
                                        </div>
                                    </div>


                                    <div class="col-md-3">
                                        <div class="shadow mb-2 bg-white rounded">
                                            <a class="btn-flat-secondary dropdown-toggle waves-effect nav-link d-flex align-items-center p-1"
                                                type="button" id="dropdownMenuButton100" data-toggle="dropdown"
                                                aria-haspopup="true" aria-expanded="false" href="#">
                                                <img src="/img/icons/request-page.svg" alt="request-page" class="pr-2">
                                                <span class="menu-title text-truncate">Reimbursement Management</span></a>
                                            </a>
                                            <div class="dropdown-menu" aria-labelledby="dropdownMenuButton100"
                                                style="width: 90%">
                                                <section id="collapsible">
                                                    <div class="row">
                                                        <div class="col-sm-12">
                                                            <div class="collapse-icon">
                                                                <div class="collapse-default">
                                                                    <div id="headingCollapse1" class="card-header"
                                                                        data-toggle="collapse" role="button"
                                                                        data-target="#collapse1" aria-expanded="false"
                                                                        aria-controls="collapse1"
                                                                        style="padding: .65rem 1.28rem">

                                                                        <span class="menu-title text-truncate"><img
                                                                                src="/img/icons/ellipse.svg"
                                                                                alt="ellipse" class="pr-2">Medical
                                                                            Reimbursement </span>
                                                                    </div>
                                                                    <div id="collapse1" role="tabpanel"
                                                                        aria-labelledby="headingCollapse1"
                                                                        class="collapse">
                                                                        <div class="card"
                                                                            style="padding: .65rem 1.28rem">
                                                                            <a class="nav-link d-flex align-items-center"
                                                                                href="/medical-reports">
                                                                                <span>Medical Report</span>
                                                                            </a>
                                                                            <a class="nav-link d-flex align-items-center"
                                                                                href="/employee-balance">
                                                                                <span>Employee Balance</span>
                                                                            </a>
                                                                        </div>
                                                                    </div>

                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </section>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-md-3">
                                        <div class="shadow p-1 mb-2 bg-white rounded">
                                            <a class="d-flex align-items-center" href="{{ url('approval') }}">
                                                <img src="/img/icons/stamp.svg" alt="cases" class="pr-2">
                                                <span class="menu-title text-truncate text-secondary">Approval Queue</span>
                                            </a>
                                        </div>
                                    </div>

                                    <div class="col-md-3">
                                        <div class="shadow p-1 mb-2 bg-white rounded">
                                            <a class="d-flex align-items-center" href="{{ url('announcements') }}">
                                                <img src="/img/icons/announcement.svg" alt="announcement" class="pr-2">
                                                <span class="menu-title text-truncate text-secondary">Announcement</span>
                                            </a>
                                        </div>
                                    </div>

                                </div>
                            </div>
                        </div>
                    </div>



                    <!-- END: Welcome Card -->

                    <!-- BEGIN: Widgets -->

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
    <!-- END : Widget Modal -->
@endsection

@section('page_js')
    <script src="{{ url('app-assets/vendors/js/forms/validation/jquery.validate.min.js') }}"></script>
    <script src="{{ url('app-assets/vendors/js/extensions/sweetalert2.all.min.js') }}"></script>
    <script src="{{ url('js/dateHelpers.js') }}"></script>
@endsection

@section('page_script')
    <script>
        $(document).on(
            'click.bs.dropdown.data-api',
            '[data-toggle="collapse"]',
            function(e) {
                e.stopPropagation()
            }
        );
    </script>
@endsection
