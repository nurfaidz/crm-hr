<?php

use App\Http\Controllers\ApprovalController;
use App\Http\Controllers\AttendanceController;
use App\Http\Controllers\Auth\ForgotPasswordController;
use App\Http\Controllers\Auth\ResetPasswordController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\BankController;
use App\Http\Controllers\DashboardBuilderController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\EmployeeController;
use App\Http\Controllers\EmployeeEducationController;
use App\Http\Controllers\EmployeeEmergencyContactController;
use App\Http\Controllers\EmployeeExperienceController;
use App\Http\Controllers\EmployeeSkillController;
use App\Http\Controllers\EventsController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\JobClassController;
use App\Http\Controllers\JobPositionController;
use App\Http\Controllers\JointHolidayController;
use App\Http\Controllers\LeaveApplicationApprovalController;
use App\Http\Controllers\LeaveTypeController;
use App\Http\Controllers\ManageHolidayController;
use App\Http\Controllers\NationalHolidayController;
use App\Http\Controllers\LeavePeriodController;
use App\Http\Controllers\LeaveReportController;
use App\Http\Controllers\ManualAttendanceApprovalController;
use App\Http\Controllers\MedicalReimbursementApprovalController;
use App\Http\Controllers\MedicalReimbursementController;
use App\Http\Controllers\OvertimeApprovalController;
use App\Http\Controllers\OvertimeController;
use App\Http\Controllers\OvertimeHistoryController;
use App\Http\Controllers\SelfLeaveApplicationController;
use App\Http\Controllers\UpdateDataApprovalController;
use App\Http\Controllers\WorkDaysController;
use App\Http\Controllers\WorkShiftController;
use App\Http\Controllers\EmployeeBalanceController;
use App\Http\Controllers\MedicalReportController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\LeaveBalanceController;
use App\Http\Controllers\SortingController;
use App\Http\Controllers\FindEmployeeController;
use App\Http\Controllers\SavingandLoanController;
use App\Http\Controllers\SavingandLoanApprovalController;
use Illuminate\Support\Facades\Auth,
    Illuminate\Support\Facades\Route,
    App\Http\Controllers\CustomersController,
    App\Http\Controllers\CompaniesController,
    App\Http\Controllers\BranchesController,
    App\Http\Controllers\UsersController,
    App\Http\Controllers\RoleController,
    App\Http\Controllers\LateMealController,
    App\Http\Controllers\AnnouncementsController,
    App\Http\Controllers\DepartmentController,
    App\Http\Controllers\AttendanceReportController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', [App\Http\Controllers\HomeController::class, 'index']);

Auth::routes();

/*Forget Password*/
Route::get('forgotpass', [ForgotPasswordController::class, 'getEmail'])->name('forgotpass');
Route::post('forgotpass', [ForgotPasswordController::class, 'postEmail'])->name('forgotpass');

// Reset Password
Route::get('otp/{token}', [ResetPasswordController::class, 'getPassword']);
Route::post('otp', [ResetPasswordController::class, 'updatePassword'])->name('resetpass');

Route::get('logout', [LoginController::class, 'logout'])->name('logout');

Route::group(['middleware' => ['auth']], function () {
    Route::prefix('home')->group(function () {
        Route::get('/', [HomeController::class, 'index'])->name('home');
        Route::get('/announcements/', [HomeController::class, 'getAnnouncements']);
    });

    Route::get('notifications/employees', [NotificationController::class, 'headerNotification']);
    Route::delete('notifications/clears', [NotificationController::class, 'clearAllNotification']);
    Route::post('notifications/marks', [NotificationController::class, 'markAllAsRead']);

    Route::put('/dashboard/update/{id}', [DashboardBuilderController::class, 'update']);

    Route::resource('roles/auth', RoleController::class);
    Route::resource('users/auth', UsersController::class);
    Route::resource('customers/auth', CustomersController::class);

    Route::get('/dashboard', [HomeController::class, 'index']);
    Route::get('/dashboard/workshifts', [DashboardController::class, 'workshifts']);

    // Route Users
    Route::get('/users', [UsersController::class, 'index'])->name('users.index');
    Route::get('/users/create', [UsersController::class, 'create']);
    Route::post('/users/store', [UsersController::class, 'store']);
    Route::get('/users/edit/{id}', [UsersController::class, 'edit']);
    Route::post('/users/update/{id}', [UsersController::class, 'update']);
    Route::delete('/users/delete/{id}', [UsersController::class, 'destroy']);

    Route::get('/changePassword', 'App\Http\Controllers\ChangePasswordController@showChangePasswordForm');
    Route::post('/changePassword', 'App\Http\Controllers\ChangePasswordController@changePassword')->name('changePassword');

    // Route Roles
    Route::get('/roles/list', [RoleController::class, 'get_role_ajax']);
    Route::get('/roles/select', [RoleController::class, 'select']);
    Route::resource('/roles', RoleController::class)->except('create', 'show');

    // Route Department
    Route::get('/departments/list', [DepartmentController::class, 'get_department_ajax']);
    Route::get('/departments/select', [DepartmentController::class, 'select_department_ajax']);
    Route::get('/departments/select/{id}', [DepartmentController::class, 'select_department_ajax_by_branch']);
    Route::post('/departments/manager_list', [DepartmentController::class, 'manager_list']);
    Route::resource('/departments', DepartmentController::class)->except('create', 'show');

    // Route Customers
    Route::get('/customers', [CustomersController::class, 'index'])->name('customers.index');
    Route::get('/customers/create', [CustomersController::class, 'create']);
    Route::post('/customers/store', [CustomersController::class, 'store']);
    Route::get('/customers/edit/{id}', [CustomersController::class, 'edit']);
    Route::post('/customers/update/{id}', [CustomersController::class, 'update']);
    Route::delete('/customers/delete/{id}', [CustomersController::class, 'destroy']);
    Route::get('/customers/view/{id}', [CustomersController::class, 'show']);

    // Route Events
    Route::prefix('events')->group(function () {
        Route::get('/', [EventsController::class, 'index'])->name('events.index');
        Route::get('/dor', [EventsController::class, 'dor']);
        Route::get('/create', [EventsController::class, 'create']);
        Route::post('/store', [EventsController::class, 'store']);
        Route::get('/edit/{id}', [EventsController::class, 'edit']);
        Route::post('/update/{id}', [EventsController::class, 'update']);
        Route::delete('/delete/{id}', [EventsController::class, 'destroy']);
    });

    //Route Branch
    Route::get('/branch', [BranchesController::class, 'index']);
    Route::get('/branch/get_list', [BranchesController::class, 'get_list']);
    route::delete('/branch/delete/{id}', [BranchesController::class, 'destroy']);
    Route::post('/branch/store', [BranchesController::class, 'store']);
    Route::get('/branch/edit/{id}', [BranchesController::class, 'edit']);
    Route::post('/branch/update/{id}', [BranchesController::class, 'update']);
    Route::get('/branch/select', [BranchesController::class, 'select']);

    // Route Companies
    Route::get('/companies/select', [CompaniesController::class, 'select']);
    Route::resource('companies', CompaniesController::class)->except('create', 'show');

    //Route Announcement
    Route::get('/announcements', [AnnouncementsController::class, 'lists'])->name('announcements.lists');
    Route::get('/announcements/viewmore', [AnnouncementsController::class, 'viewmore']);
    Route::get('/announcements/view/{id}', [AnnouncementsController::class, 'view']);
    Route::get('/announcements/create', [AnnouncementsController::class, 'create']);
    Route::post('/announcements/store', [AnnouncementsController::class, 'store']);
    Route::get('/announcements/edit/{id}', [AnnouncementsController::class, 'edit']);
    Route::post('/announcements/publish/{id}', [AnnouncementsController::class, 'publish']);
    Route::post('/announcements/update/{id}', [AnnouncementsController::class, 'update']);
    Route::delete('/announcements/delete/{id}', [AnnouncementsController::class, 'destroy']);

    // Route List of Holidays
    Route::get('manage-holidays/show/{id}', [ManageHolidayController::class, 'show']);
    Route::resource('manage-holidays', ManageHolidayController::class)->except('create', 'show');

    // Route National Holidays
    Route::resource('national-holidays', NationalHolidayController::class)->except('create', 'show');

    // Route Attendance
    Route::get('/attendance/get_attendance', [AttendanceController::class, 'get_attendance']);
    Route::get('/attendance-date-filter/{date}', [AttendanceController::class, 'attendanceList']);
    Route::get('/attendance-statistic/{date}', [AttendanceController::class, 'attendanceStatistics']);
    Route::get('/attendance-self-service/{id}', [AttendanceController::class, 'attendanceDetail']);
    Route::post('/attendance-self-service', [AttendanceController::class, 'employee_attendance']);
    Route::resource('attendance', AttendanceController::class);
    Route::get('attendance/{id}', [AttendanceController::class, 'singleAttendance']);
    Route::get('attendance/today/{id}', [AttendanceController::class, 'getTodayAttendance']);
    Route::get('attendance/data/pending', [AttendanceController::class, 'attendancePending']);
    Route::get('attendance/attachment/download/{file}', [AttendanceController::class, 'download']);
    Route::delete('attendance/{id}/cancel', [AttendanceController::class, 'attendanceCancel']);

    Route::resource('overtimes', OvertimeController::class);

    // Route Manual Attendances
    Route::resource('manual-attendances', ManualAttendanceController::class)->except('create', 'show');

    // Overtime
    Route::resource('overtimes', OvertimeController::class);
    Route::post('overtimes-self-service', [OvertimeController::class, 'create']);
    Route::get('overtimes-pending', [OvertimeController::class, 'fetchPendingOvertimes']);
    Route::get('overtimes-date-filter/{date}', [OvertimeController::class, 'overtimesList']);
    Route::get('overtimes/{id}/details', [OvertimeController::class, 'getOvertimeDetails']);
    Route::delete('overtimes/{id}/cancel', [OvertimeController::class, 'overtimeCancel']);
    Route::get('overtimes/{id}/show', [OvertimeController::class, 'detailRedirect']);

    // Late
    Route::get('late', [LateMealController::class, 'index']);
    Route::post('late/store', [LateMealController::class, 'store']);
    Route::get('late/edit/{id}', [LateMealController::class, 'edit']);
    Route::post('late/update/{id}', [LateMealController::class, 'update']);
    Route::delete('late/delete/{id}', [LateMealController::class, 'destroy']);

    // Route Report Daily Attendance
    Route::get('/daily-attendance-list/{date}/{entity}/{sbu}/{position}', [AttendanceController::class, 'dailyAttendanceList']);
    Route::get('/daily-attendance', [AttendanceController::class, 'dailyAttendance']);
    Route::get('/daily-attendance/{id}', [AttendanceController::class, 'dailyAttendanceDetail']);

    // Route Report Summary Attendance
    Route::get('/attendance-summary-report', [AttendanceReportController::class, 'attendanceSummary']);
    Route::get('/download-attendance-summary-report/{date}/{department_id}/{branch_id}', [AttendanceReportController::class, 'downloadAttendanceSummaryReport']);

    // Route Report Monthly Attendance
    Route::get('/monthly-attendance-list', [AttendanceController::class, 'getMonthlyAttendance']);
    Route::get('/monthly-attendance-statistic', [AttendanceController::class, 'monthlyStatistic']);
    Route::get('/monthly-attendance', [AttendanceController::class, 'monthlyAttendance']);

    // Route Report Overall Attendance
    Route::get('/overall-attendance', [AttendanceController::class, 'overallAttendance']);
    Route::get('/download-overall-attendance', [AttendanceController::class, 'downloadoverallAttendance']);
    Route::get('/get-overall-attendance', [AttendanceController::class, 'getOverallAttandance']);

    // Route Bank
    Route::get('/bank', [BankController::class, 'index']);
    Route::get('/bank/{id}/edit', [BankController::class, 'edit']);
    Route::post('/bank/{id}', [BankController::class, 'update']);
    Route::get('/bank/get_list', [BankController::class, 'get_list']);
    Route::post('/bank', [BankController::class, 'store']);
    Route::delete('/bank/delete/{id}', [BankController::class, 'destroy']);
    Route::get('/bank/select', [BankController::class, 'select']);

    // Route Types of Leave
    Route::resource('leave-types', LeaveTypeController::class)->except('create', 'show');

    // Leave Leave Periods
    Route::resource('leave-periods', LeavePeriodController::class)->except('create', 'show', 'destroy');

    // Route Joint Holidays
    Route::resource('joint-holidays', JointHolidayController::class)->except('create', 'show');

    // Route Leave Reports
    Route::get('/leave-reports-list', [LeaveReportController::class, 'leaveReportsList']);
    Route::get('/leave-reports', [LeaveReportController::class, 'index']);
    Route::get('/leave-reports/{id}', [LeaveReportController::class, 'edit']);
    Route::get('/leave-reports/employee/{id}', [LeaveReportController::class, 'show']);

    // Route Leave Balance 
    Route::get('/leave-balance', [LeaveBalanceController::class, 'index']);
    Route::get('/leave-balance-list', [LeaveBalanceController::class, 'getLeave']);

    // Route Job Classes
    Route::resource('job-classes', JobClassController::class)->except('create', 'show');

    // Route Job Positions
    Route::resource('job-positions', JobPositionController::class)->except('create', 'show');

    // Route Employees
    Route::resource('employees', EmployeeController::class)->except('show');
    Route::get('employees/create/branch/{id}', [EmployeeController::class, 'getBranches']);
    Route::get('employees/create/department/{id}', [EmployeeController::class, 'getDepartments']);
    Route::post('employees/store-vaccine/', [EmployeeController::class, 'storeVaccine']);
    Route::get('/employee/select', [EmployeeController::class, 'select']);
    Route::get('profile', [EmployeeController::class, 'show']);
    Route::put('employee-informations/{id}', [EmployeeController::class, 'employeeInformation']);
    Route::resource('employee-educations', EmployeeEducationController::class)->except('create', 'show');
    Route::resource('employee-experiences', EmployeeExperienceController::class)->except('create', 'show');
    Route::resource('employee-emergency-contacts', EmployeeEmergencyContactController::class)->except('create', 'show');
    Route::get('profile-all-list', [EmployeeController::class, 'profilAllEmployeesList']);
    Route::put('profile/{id}', [EmployeeController::class, 'updateProfile']);
    Route::get('/profile/{id}/edit', [EmployeeController::class, 'editProfile']);
    Route::put('/profile-img-reset/{id}', [EmployeeController::class, 'updateImageProfile']);
    Route::get('/employee-additional-information/{id}/edit', [EmployeeController::class, 'showAdditional']);
    Route::put('/employee-additional-information/{id}', [EmployeeController::class, 'updateAdditional']);


    Route::resource('employee-skills', EmployeeSkillController::class)->except('create', 'show');

    // Route Workdays
    Route::resource('workdays', WorkDaysController::class)->except('show');
    Route::get('workdays/events', [WorkDaysController::class, 'events']);
    Route::get('workdays/shifts', [WorkDaysController::class, 'shifts']);

    // Route Work Shifts
    Route::resource('work-shifts', WorkShiftController::class)->except('create', 'show');

    // Route Approval Leave Application
    Route::get('approval/leave-application', [LeaveApplicationApprovalController::class, 'get_leave_application']);
    Route::get('approval/leave-application/details/{id}', [LeaveApplicationApprovalController::class, 'get_leave_application_details']);
    Route::get('approval/leave-application/export', [LeaveApplicationApprovalController::class, 'get_leave_application_excel']);
    Route::get('approval/leave-application/download/{file}', [LeaveApplicationApprovalController::class, 'download']);
    Route::get('approval/leave-application/reject/{id}/{reason}', [LeaveApplicationApprovalController::class, 'reject']);
    Route::get('approval/leave-application/approve/{id}', [LeaveApplicationApprovalController::class, 'approve']);

    // Route Approval Manual Attendance
    Route::get('approval/manual-attendance', [ManualAttendanceApprovalController::class, 'get_manual_attendance']);
    Route::get('approval/manual-attendance/details/{id}', [ManualAttendanceApprovalController::class, 'get_manual_attendance_details']);
    Route::get('approval/manual-attendance/export', [ManualAttendanceApprovalController::class, 'get_manual_attendance_excel']);
    Route::get('approval/manual-attendance/download/{file}', [ManualAttendanceApprovalController::class, 'download']);
    Route::put('approval/manual-attendance/approve/{id}', [ManualAttendanceApprovalController::class, 'ManualAttendanceApprove']);
    Route::put('approval/manual-attendance/reject/{id}', [ManualAttendanceApprovalController::class, 'ManualAttendanceReject']);
    Route::put('approval/manual-attendance/{id}/close', [ManualAttendanceApprovalController::class, 'ManualAttendanceClose']);


    // Route Approval Overtime
    Route::get('approval/overtime', [OvertimeApprovalController::class, 'get_overtime']);
    Route::get('approval/overtime/{id}/details', [OvertimeApprovalController::class, 'get_overtime_details']);
    Route::put('approval/overtime/{id}/approve', [OvertimeApprovalController::class, 'approveOvertime']);
    Route::put('approval/overtime/{id}/reject', [OvertimeApprovalController::class, 'rejectOvertime']);

    //Route Approval Update Data
    Route::get('approval/update-data', [UpdateDataApprovalController::class, 'getData']);
    Route::get('approval/detail/update-data/{id}', [UpdateDataApprovalController::class, 'detailUpdateData']);
    Route::delete('approval/update-data/{id}/reject', [UpdateDataApprovalController::class, 'editDataReject']);
    Route::post('approval/update-data/{id}/approve', [UpdateDataApprovalController::class, 'editDataApprove']);
    Route::get('approval/update-data/export', [UpdateDataApprovalController::class, 'editDataExcel']);

    //Route Approval Medical Reimbursement
    Route::get('approval/medical-reimbursement', [MedicalReimbursementApprovalController::class, 'index']);
    Route::get('approval/medical-reimbursement/{id}/details', [MedicalReimbursementApprovalController::class, 'detail']);
    Route::get('approval/medical-reimbursement/{id}/details-higher-up', [MedicalReimbursementApprovalController::class, 'detailHu']);
    Route::get('approval/medical-reimbursement/{id}/details-human-resource', [MedicalReimbursementApprovalController::class, 'detailHr']);
    Route::get('approval/medical-reimbursement/{id}/details-finance', [MedicalReimbursementApprovalController::class, 'detailFinance']);
    Route::put('approval/medical-reimbursement/{id}/approve-manager', [MedicalReimbursementApprovalController::class, 'managerApprove']);
    Route::put('approval/medical-reimbursement/{id}/approve-human-resource', [MedicalReimbursementApprovalController::class, 'humanResourceApprove']);
    Route::put('approval/medical-reimbursement/{id}/approve-finance', [MedicalReimbursementApprovalController::class, 'financeApprove']);
    Route::put('approval/medical-reimbursement/{id}/reject-manager', [MedicalReimbursementApprovalController::class, 'rejectManager']);
    Route::put('approval/medical-reimbursement/{id}/reject-human-resource', [MedicalReimbursementApprovalController::class, 'rejectHumanResource']);
    Route::put('approval/medical-reimbursement/{id}/reject-finance', [MedicalReimbursementApprovalController::class, 'rejectFinance']);

    // Approval Queue
    Route::resource('approval', ApprovalController::class)->except('create', 'show', 'edit', 'update', 'destroy');

    //Route Overtime History
    Route::get('overtime-history/get-overtime/{date}', [OvertimeHistoryController::class, 'getOvertimeAll']);
    Route::get('overtime-history/{overtimeId}/details', [OvertimeHistoryController::class, 'getOvertimeDetails']);
    Route::post('overtime-history/employee', [OvertimeHistoryController::class, 'getEmployeeShift']);
    Route::resource('overtime-history',  OvertimeHistoryController::class)->only('index', 'store');

    // Route Medical Reimbursement
    Route::get('medical-reimbursement/statistic/{year}', [MedicalReimbursementController::class, 'statistic']);
    Route::get('medical-reimbursement/data', [MedicalReimbursementController::class, 'data']);
    Route::get('medical-reimbursement/list/{type}/{status}', [MedicalReimbursementController::class, 'list']);
    Route::get('medical-reimbursement/export', [MedicalReimbursementController::class, 'export']);
    Route::get('medical-reimbursement/cancel/{id}', [MedicalReimbursementController::class, 'cancel']);
    Route::get('medical-reimbursement/details/{id}', [MedicalReimbursementController::class, 'details']);
    Route::get('medical-reimbursement/download/{file}', [MedicalReimbursementController::class, 'download']);
    Route::resource('medical-reimbursement', MedicalReimbursementController::class)->except('create', 'show', 'edit', 'update', 'destroy');

    // Route Leave Application (Self-service)
    Route::get('self-leave-application/statistic/{year}', [SelfLeaveApplicationController::class, 'statistic']);
    Route::get('self-leave-application/data', [SelfLeaveApplicationController::class, 'data']);
    Route::get('self-leave-application/list/{type}/{status}', [SelfLeaveApplicationController::class, 'list']);
    Route::get('self-leave-application/export', [SelfLeaveApplicationController::class, 'export']);
    Route::get('self-leave-application/cancel/{id}', [SelfLeaveApplicationController::class, 'cancel']);
    Route::get('self-leave-application/details/{id}', [SelfLeaveApplicationController::class, 'details']);
    Route::get('self-leave-application/download/{file}', [SelfLeaveApplicationController::class, 'download']);
    Route::resource('self-leave-application', SelfLeaveApplicationController::class)->except('create', 'show', 'edit', 'update', 'destroy');
    Route::get('self-leave-application/information/{year}', [SelfLeaveApplicationController::class, 'informationBalanceYear']);

    // Reimbursement Management
    Route::get('employee-balance-list', [EmployeeBalanceController::class, 'getEmployeeBalance']);
    Route::get('employee-balance', [EmployeeBalanceController::class, 'index']);
    Route::get('employee-balance/{id}/edit', [EmployeeBalanceController::class, 'editEmployeeBalance']);
    Route::put('employee-balance/{id}', [EmployeeBalanceController::class, 'updateEmployeeBalance']);
    Route::get('medical-reports', [MedicalReportController::class, 'index']);
    Route::get('medical-reports/{id}', [MedicalReportController::class, 'show']);
    Route::get('medical-reports/employee/{id}', [MedicalReportController::class, 'edit']);

    // Sorting Cerdas
    Route::get('/employee-attendance', [SortingController::class, 'index']);
    Route::get('/employee-attendance/countAttendance', [SortingController::class, 'indexCountAtt']);
    Route::get('/employee-attendance/data', [SortingController::class, 'db_get']);
    Route::get('/employee-attendance/statistics', [SortingController::class, 'statistics']);
    Route::get('/employee-attendance/countAttendance/data', [SortingController::class, 'informationCountAttendance']);
    Route::get('/employee-attendance/informationLeave/{year}', [SortingController::class, 'informationLeaveBalanceYear']);
    Route::get('/employee-attendance/informationAttendance/{year}', [SortingController::class, 'informationAttendanceBalanceYear']);

    // Find Employee Now
    Route::get('/find-employee-worker', [FindEmployeeController::class, 'index']);
    Route::get('/find-employee-worker/data', [FindEmployeeController::class, 'findWoker']);
    Route::get('/find-employee-worker/data2', [FindEmployeeController::class, 'workerNotPresent']);

    // Cooperative Saving and Loan
    Route::get('/saving-and-loan/data', [SavingandLoanController::class, 'data']);
    Route::get('/saving-and-loan/list/{status}', [SavingandLoanController::class, 'list']);
    Route::get('/saving-and-loan/list2/{status}', [SavingandLoanController::class, 'laporan']);
    Route::get('/saving-and-loan/statistic/{year}', [SavingandLoanController::class, 'statistic']);
    Route::get('saving-and-loan/laporan-pinjam', [SavingandLoanController::class, 'viewLap']);
    Route::resource('/saving-and-loan', SavingandLoanController::class)->except('create', 'show', 'edit', 'update', 'destroy');

    // Approval Saving and Loan
    Route::get('approval/saving-and-loan', [SavingandLoanApprovalController::class, 'get_savingand_loan']);
    Route::get('approval/saving-and-loan/{id}/details', [SavingandLoanApprovalController::class, 'details']);
    Route::put('approval/saving-and-loan/{id}/approve', [SavingandLoanApprovalController::class, 'Approve']);
    Route::put('approval/saving-and-loan/{id}/reject', [SavingandLoanApprovalController::class, 'reject']);
    
});
