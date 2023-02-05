<?php

use App\Http\Controllers\API\AnnouncementController;
use App\Http\Controllers\API\ApprovalController;
use App\Http\Controllers\API\AttendanceController;
use App\Http\Controllers\API\Auth\AuthController;
use App\Http\Controllers\API\UserController;
use App\Http\Controllers\API\ReimbursementController;
use App\Http\Controllers\API\OvertimeController;
use App\Http\Controllers\API\ManualAttendanceController;
use App\Http\Controllers\API\UpdateDataController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\LeaveTrackerController;
use App\Http\Controllers\NotificationController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:sanctum')->get('/user', [UserController::class, 'getProfile']);

Route::prefix('users')->group(function () {
    Route::post('/login', [AuthController::class, 'login']);
    Route::middleware('auth:sanctum')->post('/logout', [AuthController::class, 'logout']);
    Route::middleware('auth:sanctum')->group(function () {
        // Route::get('/leave-tracker/{yearmonth}', [LeaveTrackerController::class, 'index']);
        Route::get('/leave-tracker/{year}', [LeaveTrackerController::class, 'leaveTracker']);
        Route::post('/leave-tracker', [LeaveTrackerController::class, 'store']);
        Route::get('/leave-tracker/{id}/edit', [LeaveTrackerController::class, 'show']);
        Route::get('/reimbursements/{year}', [ReimbursementController::class, 'getAllReimburse']);
    });
    Route::get('/leave_approvals/{user_id}', [ApprovalController::class, 'leaveApprovalStatistics']);
    Route::get('/attendance_approvals/{user_id}', [ApprovalController::class, 'attendanceApprovalStatistics']);
});

Route::middleware('auth:sanctum')->group(function () {
    Route::prefix('announcements')->group(function () {
        Route::get('/', [AnnouncementController::class, 'index']);
    });

    Route::prefix('leave-tracker')->group(function () {
        Route::get('/{id}', [LeaveTrackerController::class, 'getLeaveApplicationsId']);
        Route::post('/approve/{id}', [LeaveTrackerController::class, 'approve']);
        Route::post('/cancel/{id}', [LeaveTrackerController::class, 'cancel']);
        Route::post('/reject/{id}', [LeaveTrackerController::class, 'reject']);
    });

    Route::prefix('attendances')->group(function () {
        Route::post('/check-in', [AttendanceController::class, 'checkInEmployee']);
        Route::put('/check-out', [AttendanceController::class, 'checkOutEmployee']);
        Route::post('/manual-attendance', [AttendanceController::class, 'manualAttendance']);
        Route::get('/get-attendance', [AttendanceController::class, 'getAttendanceToday']);
        Route::get('/manual-attendance/{id}', [ManualAttendanceController::class, 'getManualAttendanceId']);
        Route::get('/{year_month}', [AttendanceController::class, 'statistics']);
        Route::post('/cancel/{id}', [AttendanceController::class, 'ManualAttendanceCancel']);
        Route::post('/approve/{id}', [AttendanceController::class, 'ManualAttendanceApprove']);
        Route::post('/reject/{id}', [AttendanceController::class, 'ManualAttendanceReject']);
        Route::get('/detail/{id}', [AttendanceController::class, 'show']);;
    });

    Route::prefix('overtimes')->group(function () {
        Route::get('/today', [OvertimeController::class, 'getOvertimeToday']);
        Route::get('/', [OvertimeController::class, 'getOvertimeAllEmployee']);
        Route::get('/{overtimeId}', [OvertimeController::class, 'getOvertimeId']);
        Route::post('/request', [OvertimeController::class, 'requestOvertime']);
        Route::post('/cancel/{id}', [OvertimeController::class, 'cancelOvertime']);
        Route::post('/approve/{id}', [OvertimeController::class, 'approveOvertime']);
        Route::post('/reject/{id}', [OvertimeController::class, 'rejectOvertime']);
    });

    Route::resource('reimbursements', ReimbursementController::class)->only('index', 'store');
    Route::prefix('reimbursements')->group(function () {
        Route::post('/request', [ReimbursementController::class, 'requestReimburse']);
        Route::get('/stat', [ReimbursementController::class, 'index']);
        Route::get('/{id}', [ReimbursementController::class, 'getMedicalReimbursementId']);
        Route::post('/cancel/{id}', [ReimbursementController::class, 'cancelReimbursement']);
        Route::post('/approve/{id}', [ReimbursementController::class, 'approveReimbursement']);
        Route::post('/reject/{id}', [ReimbursementController::class, 'rejectReimbursement']);
    });

    Route::prefix('approval')->group(function () {
        Route::get('/overtimes', [OvertimeController::class, 'getOvertimeListAppr']);
        Route::get('/manual-attendance', [ManualAttendanceController::class, 'getAllManualAttendanceAppr']);
        Route::get('/leave-applications', [LeaveTrackerController::class, 'getAllLeaveApplicationListsAppr']);
        Route::get('/medical-reimbursement', [ReimbursementController::class, 'getAllMedicalReimbursementAppr']);
        Route::get('/update-data', [UpdateDataController::class, 'getUpdateDataListAppr']);
    });
    Route::prefix('notifications')->group(function () {
        Route::get('/get-notifications', [NotificationController::class, 'get_list']);
        Route::put('/has-read-all', [NotificationController::class, 'has_read_all']);
        Route::put('/has-read', [NotificationController::class, 'has_read']);
        Route::delete('/delete/employee', [NotificationController::class, 'destroy_by_employee']);
        Route::delete('/delete/{notification_id}', [NotificationController::class, 'destroy']);
    });
});
