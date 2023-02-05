<div class="main-menu menu-fixed menu-light menu-accordion menu-shadow" data-scroll-to-active="true">
    <div class="navbar-header">
        <ul class="nav navbar-nav flex-row">
            <li class="nav-item m-0 mr-auto">
                <a class="navbar-brand" href="/">
                    <span class="pr-2">
                        <img src="/img/proxsis-small.png" alt="proxsis" class="sidebar-logo" width="100%">
                    </span>
                    <img src="/img/proxsis-high.png" alt="proxsis" class="sidebar-logo-high">
                </a>
            </li>
            <li class="nav-item nav-toggle m-0">
                <a class="nav-link modern-nav-toggle pr-0" data-toggle="collapse">
                    <img src="/img/icons/ellipse-arrow.svg" alt="ellipse-arrow"
                        class="d-block d-xl-none text-primary toggle-icon mt-0">
                    <i class="d-none d-xl-block collapse-toggle-icon font-medium-4 text-primary" data-feather="disc"
                        data-ticon="disc"></i>
                </a>
            </li>
        </ul>
    </div>

    <div class="shadow-bottom"></div>

    <div class="main-menu-content mt-2">
        <ul class="navigation navigation-main" id="main-menu-navigation" data-menu="menu-navigation">
            <li class="nav-item">
                <a class="nav-link d-flex align-items-center" href="/">
                    <img src="/img/icons/home.svg" alt="home" class="pr-2">
                    <span data-i18n="Dashboard">Dashboard</span>
                </a>
            </li>

            @canany(['master_company.list', 'role_permission.list', 'branch_company.list', 'department_company.list',
                'job_position.list', 'job_class.list'])
                <li class=" navigation-header">
                    <span>Administration</span>
                    <i data-feather="more-horizontal"></i>
                </li>
            @endcanany

            @can('master_company.list')
                <li class="nav-item">
                    <a class="nav-link d-flex align-items-center" href="/companies">
                        <img src="/img/icons/corporate-fare.svg" alt="corporate-fare" class="pr-2">
                        <span>Holdings</span>
                    </a>
                </li>
            @endcan

            @can('branch_company.list')
                <li class="nav-item">
                    <a class="nav-link d-flex align-items-center" href="/branch">
                        <img src="/img/icons/business.svg" alt="business" class="pr-2">
                        <span>Entity</span>
                    </a>
                </li>
            @endcan

            @can('department_company.list')
                <li class="nav-item">
                    <a class="nav-link d-flex align-items-center" href="/departments">
                        <img src="/img/icons/home-work.svg" alt="home-work" class="pr-2">
                        <span>SBU</span>
                    </a>
                </li>
            @endcan

            @can('role_permission.list')
                <li class="nav-item">
                    <a class="nav-link d-flex align-items-center" href="/roles">
                        <img src="/img/icons/recent-actors.svg" alt="recent-actors" class="pr-2">
                        <span>Roles</span>
                    </a>
                </li>
            @endcan

            @can('job_class.list')
                <li class="nav-item">
                    <a class="nav-link d-flex align-items-center" href="/job-classes">
                        <img src="/img/icons/cases.svg" alt="cases" class="pr-2">
                        <span>Job Levels</span>
                    </a>
                </li>
            @endcan

            @can('job_position.list')
                <li class="nav-item">
                    <a class="nav-link d-flex align-items-center" href="/job-positions">
                        <img src="/img/icons/hail.svg" alt="hail" class="pr-2">
                        <span>Job Positions</span>
                    </a>
                </li>
            @endcan

            @canany(['master_employee.list', 'leave_report.list', 'manage_holiday.list', 'national_holiday.list',
                'leave_type.list', 'joint_holiday.list', 'leave_period.list', 'daily_attendance.list',
                'monthly_attendance.list', 'overall_attendance.list', 'overtime.history', 'master_workshift.list',
                'master_late.list', 'master_workday.list', 'medical_report.list', 'employee_balance.list',
                'approval_queue.list', 'master_announcement.list'])
                <li class=" navigation-header">
                    <span>Management</span>
                    <i data-feather="more-horizontal"></i>
                </li>
            @endcanany

            @canany(['master_employee.list'])
                <li class=" nav-item"><a class="d-flex align-items-center" href="#">
                        <img src="/img/icons/person-add.svg" alt="person-add" class="pr-2">
                        <span class="menu-title text-truncate">Employee Management</span></a>
                    <ul class="menu-content">
                        @can('master_employee.list')
                            <li class="nav-item">
                                <a class="nav-link d-flex align-items-center" href="{{ url('/employees') }}">
                                    <img src="/img/icons/ellipse.svg" alt="ellipse" class="pr-2">
                                    <span>Employee Lists</span>
                                </a>
                            </li>
                        @endcan
                    </ul>
                </li>
            @endcanany

            @canany(['leave_report.list', 'manage_holiday.list', 'national_holiday.list', 'leave_type.list',
                'joint_holiday.list', 'leave_period.list'])
                <li class="nav-item">
                    <a class="d-flex align-items-center" href="#">
                        <img src="/img/icons/apps-outage.svg" alt="apps-outage" class="pr-2">
                        <span class="menu-title text-truncate">Leave Management</span>
                    </a>
                    <ul class="menu-content">
                        @can('leave_balance.list')
                            <li class="nav-item">
                                <a class="nav-link d-flex align-items-center" href="/leave-balance">
                                    <img src="/img/icons/ellipse.svg" alt="ellipse" class="pr-2">
                                    <span>Employee Balance</span>
                                </a>
                            </li>
                        @endcan
                        @can('leave_report.list')
                            <li class="nav-item">
                                <a class="nav-link d-flex align-items-center" href="/leave-reports">
                                    <img src="/img/icons/ellipse.svg" alt="ellipse" class="pr-2">
                                    <span>Leave Reports</span>
                                </a>
                            </li>
                        @endcan

                        @canany(['manage_holiday.list', 'national_holiday.list', 'leave_type.list', 'joint_holiday.list',
                            'leave_period.list'])
                            <li class=" nav-item">
                                <a class="d-flex align-items-center" href="#">
                                    <img src="/img/icons/ellipse.svg" alt="ellipse" class="pr-2">
                                    <span>Leave Settings</span>
                                </a>
                                <ul class="menu-content">
                                    @can('manage_holiday.list')
                                        <li class="nav-item">
                                            <a class="nav-link d-flex align-items-center" href="/manage-holidays">
                                                <img src="/img/icons/ellipse.svg" alt="ellipse" class="pr-2">
                                                <span>List of Holidays</span>
                                            </a>
                                        </li>
                                    @endcan
                                    @can('national_holiday.list')
                                        <li class="nav-item">
                                            <a class="nav-link d-flex align-items-center" href="/national-holidays">
                                                <img src="/img/icons/ellipse.svg" alt="ellipse" class="pr-2">
                                                <span>National Holidays</span>
                                            </a>
                                        </li>
                                    @endcan
                                    @can('leave_type.list')
                                        <li class="nav-item">
                                            <a class="nav-link d-flex align-items-center" href="/leave-types">
                                                <img src="/img/icons/ellipse.svg" alt="ellipse" class="pr-2">
                                                <span>Types of Leave</span>
                                            </a>
                                        </li>
                                    @endcan
                                    @can('joint_holiday.list')
                                        <li class="nav-item">
                                            <a class="nav-link d-flex align-items-center" href="/joint-holidays">
                                                <img src="/img/icons/ellipse.svg" alt="ellipse" class="pr-2">
                                                <span>Joint Holidays</span>
                                            </a>
                                        </li>
                                    @endcan
                                    @can('leave_period.list')
                                        <li class="nav-item">
                                            <a class="nav-link d-flex align-items-center" href="/leave-periods">
                                                <img src="/img/icons/ellipse.svg" alt="ellipse" class="pr-2">
                                                <span>Leave Periods</span>
                                            </a>
                                        </li>
                                    @endcan
                                </ul>
                            </li>
                        @endcan
                    </ul>
                </li>
            @endcanany

            @canany(['daily_attendance.list', 'monthly_attendance.list', 'overall_attendance.list', 'overtime.history',
                'master_workshift.list', 'master_late.list'])
                <li class="nav-item">
                    <a class="nav-link d-flex align-items-center" href="#">
                        <img src="/img/icons/insert-chart-outlined.svg" alt="insert-chart-outlined" class="pr-2">
                        <span class="menu-title text-truncate">Attendances Management</span>
                    </a>
                    <ul class="menu-content">
                        @canany(['daily_attendance.list', 'monthly_attendance.list', 'overall_attendance.list',
                            'overtime.history'])
                            <li class="nav-item">
                                <a class="nav-link d-flex align-items-center" href="#">
                                    <img src="/img/icons/ellipse.svg" alt="ellipse" class="pr-2">
                                    <span>Attendance Reports</span>
                                </a>
                                <ul class="menu-content">
                                    @can('daily_attendance.list')
                                        <li class="nav-item">
                                            <a class="nav-link d-flex align-items-center" href="/daily-attendance">
                                                <span>Daily Recap</span>
                                            </a>
                                        </li>
                                    @endcan
                                    @can('monthly_attendance.list')
                                        <li class="nav-item">
                                            <a class="nav-link d-flex align-items-center" href="/monthly-attendance">
                                                <span>Monthly Recap</span>
                                            </a>
                                        </li>
                                    @endcan
                                    @can('overall_attendance.list')
                                        <li class="nav-item">
                                            <a class="nav-link d-flex align-items-center" href="/overall-attendance">
                                                <span>Overall Recap</span>
                                            </a>
                                        </li>
                                    @endcan
                                    @can('overtime.history')
                                        <li class="nav-item">
                                            <a class="nav-link d-flex align-items-center" href="/overtime-history">
                                                <span>Overtime</span>
                                            </a>
                                        </li>
                                    @endcan
                                </ul>
                            </li>
                        @endcanany

                        @canany(['master_workshift.list', 'master_late.list'])
                            <li class="nav-item">
                                <a class="nav-link d-flex align-items-center" href="#">
                                    <img src="/img/icons/ellipse.svg" alt="ellipse" class="pr-2">
                                    <span class="menu-title text-truncate">Attendance Settings</span>
                                </a>
                                <ul class="menu-content">
                                    @can('master_workshift.list')
                                        <li class="nav-item">
                                            <a class="nav-link d-flex align-items-center" href="/work-shifts">
                                                <img src="/img/icons/ellipse.svg" alt="ellipse" class="pr-2">
                                                <span>Work Shifts</span>
                                            </a>
                                        </li>
                                    @endcan
                                    @can('master_late.list')
                                        <li class="nav-item">
                                            <a class="nav-link d-flex align-items-center" href="/late">
                                                <img src="/img/icons/ellipse.svg" alt="ellipse" class="pr-2">
                                                <span>Late</span>
                                            </a>
                                        </li>
                                    @endcan
                                </ul>
                            </li>
                        @endcanany
                    </ul>
                </li>
            @endcanany

            @canany(['master_workday.list'])
                <li class="nav-item">
                    <a class="nav-link d-flex align-items-center" href="#">
                        <img src="/img/icons/work.svg" alt="insert-chart-outlined" class="pr-2">
                        <span class="menu-title text-truncate">Working Management</span>
                    </a>
                    <ul class="menu-content">
                        @can('master_workday.list')
                            <li class="nav-item">
                                <a class="nav-link d-flex align-items-center" href="/workdays">
                                    <img src="/img/icons/ellipse.svg" alt="ellipse" class="pr-2">
                                    <span>Working Tracker</span>
                                </a>
                            </li>
                        @endcan
                    </ul>
                </li>
            @endcanany

            @canany(['medical_report.list', 'employee_balance.list'])
                <li class="nav-item">
                    <a class="nav-link d-flex align-items-center" href="#">
                        <img src="/img/icons/request-page.svg" alt="request-page" class="pr-2">
                        <span class="menu-title text-truncate">Reimbursement Management</span>
                    </a>
                    <ul class="menu-content">
                        @canany(['medical_report.list', 'employee_balance.list'])
                            <li class="nav-item">
                                <a class="nav-link d-flex align-items-center" href="#">
                                    <img src="/img/icons/ellipse.svg" alt="ellipse" class="pr-2">
                                    <span class="text-truncate">Medical Management</span>
                                </a>
                                <ul class="menu-content">
                                    @can('medical_report.list')
                                        <li class="nav-item">
                                            <a class="nav-link d-flex align-items-center" href="/medical-reports">
                                                <span>Medical Report</span>
                                            </a>
                                        </li>
                                    @endcan
                                    @can('employee_balance.list')
                                        <li class="nav-item">
                                            <a class="nav-link d-flex align-items-center" href="/employee-balance">
                                                <span>Employee Balance</span>
                                            </a>
                                        </li>
                                    @endcan
                                </ul>
                            </li>
                        @endcanany
                    </ul>
                </li>
            @endcanany

            @can('approval_queue.list')
                <li class="nav-item">
                    <a class="nav-link d-flex align-items-center" href="{{ url('approval') }}">
                        <img src="/img/icons/stamp.svg" alt="cases" class="pr-2">
                        <span class="menu-title text-truncate">Approval Queue</span>
                    </a>
                </li>
            @endcan

            @can('master_announcement.list')
                <li class="nav-item">
                    <a class="nav-link d-flex align-items-center" href="{{ url('announcements') }}">
                        <img src="/img/icons/announcement.svg" alt="announcement" class="pr-2">
                        <span class="menu-title text-truncate">Announcement</span>
                    </a>
                </li>
            @endcan

            @canany(['self_attendance.list', 'self_overtime.list', 'self_profile.list', 'self_leave_application.list',
                'self_medical_reimbursement.list'])
                <li class="navigation-header">
                    <span>Self-service</span><i data-feather="more-horizontal"></i>
                </li>
            @endcanany

            @canany(['self_attendance.list', 'self_overtime.list'])
                <li class="nav-item">
                    <a class="nav-link d-flex align-items-center" href="#">
                        <img src="/img/icons/event-available.svg" alt="event-available" class="pr-2">
                        <span class="menu-title text-truncate">Attendances</span>
                    </a>
                    <ul class="menu-content">
                        @can('self_attendance.list')
                            <li class="nav-item">
                                <a class="nav-link d-flex align-items-center" href="{{ url('attendance') }}">
                                    <img src="/img/icons/ellipse.svg" alt="ellipse" class="pr-2">
                                    <span>Your Attendances</span>
                                </a>
                            </li>
                        @endcan
                        @can('self_overtime.list')
                            <li class="nav-item">
                                <a class="nav-link d-flex align-items-center" href="{{ url('overtimes') }}">
                                    <img src="/img/icons/ellipse.svg" alt="ellipse" class="pr-2">
                                    <span>Your Overtimes</span>
                                </a>
                            </li>
                        @endcan
                    </ul>
                </li>
            @endcanany

            @can('self_profile.list')
                <li class="nav-item">
                    <a class="nav-link d-flex align-items-center" href="/profile">
                        <img src="/img/icons/person.svg" alt="person" class="pr-2">
                        <span class="menu-title text-truncate">Profile</span>
                    </a>
                </li>
            @endcan

            @can('self_leave_application.list')
                <li class="nav-item">
                    <a class="nav-link d-flex align-items-center" href="/self-leave-application">
                        <img src="/img/icons/event-busy.svg" alt="event-busy" class="pr-2">
                        <span class="menu-title text-truncate">Leave Tracker</span>
                    </a>
                </li>
            @endcan

            @canany(['self_medical_reimbursement.list'])
                <li class="nav-item">
                    <a class="nav-link d-flex align-items-center" href="#">
                        <img src="/img/icons/account-balance.svg" alt="account-balance" class="pr-2">
                        <span class="menu-title text-truncate">Reimbursement</span>
                    </a>
                    <ul class="menu-content">
                        @can('self_medical_reimbursement.list')
                            <li class="nav-item">
                                <a class="nav-link d-flex align-items-center" href="{{ url('medical-reimbursement') }}">
                                    <img src="/img/icons/ellipse.svg" alt="ellipse" class="pr-2">
                                    <span>Medical Reimbursement</span>
                                </a>
                            </li>
                        @endcan
                    </ul>
                </li>
            @endcanany
        </ul>
        <div style="margin-bottom: 200px"></div>
    </div>
</div>
