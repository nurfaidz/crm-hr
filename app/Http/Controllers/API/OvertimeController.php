<?php

namespace App\Http\Controllers\API;

use App\Helpers\DateHelpers;
use App\Helpers\ResponseFormatter;
use App\Http\Controllers\Controller;
use App\Http\Requests\API\Overtimes\RejectOvertimeRequest;
use App\Http\Requests\OvertimeRequest;
use App\Interfaces\AttendanceInterface;
use App\Interfaces\ConfigInterface;
use App\Interfaces\EmployeeInterface;
use App\Interfaces\OvertimeInterface;
use App\Models\Attendance;
use App\Models\Department;
use App\Models\Employee;
use App\Models\Notification;
use App\Models\Overtime;
use App\Models\User;
use App\Models\WorkShift;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Http;
use PhpParser\Node\Expr\Throw_;

class OvertimeController extends Controller
{
    private $overtimeInterface;
    private $configInterface;
    private $employeeInterface;
    private $attendanceInterface;

    public function __construct(
        OvertimeInterface $overtimeInterface,
        ConfigInterface $configInterface,
        EmployeeInterface $employeeInterface,
        AttendanceInterface $attendanceInterface
    ) {
        $this->overtimeInterface = $overtimeInterface;
        $this->configInterface = $configInterface;
        $this->employeeInterface = $employeeInterface;
        $this->attendanceInterface = $attendanceInterface;
    }

    /**
     * @OA\Get(
     *     path="/api/overtimes/{overtimeId}",
     *     tags={"Overtimes"},
     *     summary="Overtimes",
     *     description="Get Overtime By Id",
     *     operationId="getOvertimesById",
     *     security={{"bearerAuth":{}}}, 
     *     @OA\Parameter(
     *         name="overtimeId",
     *         in="path",
     *         description="Id overtime",
     *         required=false,
     *         @OA\Schema(
     *             type="integer"
     *         ),
     *     ),
     *     @OA\Response(
     *         response="200",
     *         description="Authenticated Success"
     *     ),
     *     @OA\Response(
     *         response="400",
     *         description="Bad Request / Validation Errors"
     *     ),
     *     @OA\Response(
     *         response="500",
     *         description="Something went wrong"
     *     ),
     * )
     */

    public function getOvertimeId($overtimeId)
    {
        try {
            $user = Auth::user();
            $employee = Employee::where('user_id', $user->id)->firstOrFail();
            $overtime = $this->overtimeInterface->getOvertimeById($overtimeId, $employee->employee_id);
            $workshift = WorkShift::where('work_shift_id', $employee->work_shift_id)->firstOrFail();
            $env = env('APP_URL');

            $overtimeAppr = (object)[];

            if ($overtime === null) {
                $response = [
                    'start_time' => 0,
                    'end_time' => 0,
                    'status' => null,
                    'overtimes_notes' => null,
                    'workshift' => null,
                    'overtime_duration' => 0,
                    'overtime_date' => 0
                ];

                return ResponseFormatter::error($response, 'No data available at the moment', 204);
            }

            if (!is_null($overtime->update_by)) {
                $overtimeAppr = $this->employeeInterface->getEmployeeById($overtime->update_by)
                    ->join('job_positions', 'employees.job_position_id', '=', 'job_positions.job_position_id')
                    ->first([
                        'employees.user_id',
                        'employees.employee_id',
                        'employees.first_name',
                        'employees.last_name',
                        'employees.image',
                        'job_positions.job_position',
                    ]);


                $overtimeAppr->image = $this->employeeInterface->getEmployeePicture($overtimeAppr->user_id);
                $overtimeAppr->reason = $overtime->reject_reason;

                Carbon::setLocale('en');
                $overtimeAppr->update_time = $overtime->update_time;
                $overtimeAppr->dateDiff = Carbon::createFromFormat('Y-m-d H:i:s',  $overtime->update_time)->diffForHumans(Carbon::now());
            }

            $overtime->image = $this->employeeInterface->getEmployeePicture($employee->user_id);

            return ResponseFormatter::success([
                'requester' => [
                    'user_id' => $overtime->user_id,
                    'employee_id' => $overtime->employee_id,
                    'first_name' => $overtime->first_name,
                    'last_name' => $overtime->last_name,
                    'image' => $overtime->image,
                    'department' => $overtime->department_name,
                    'job_position' => $overtime->job_position
                ],
                'start_time' => $overtime->start_time,
                'end_time' => $overtime->end_time,
                'status' => $overtime->short,
                'overtime_notes' => $overtime->notes,
                'overtime_duration' => $overtime->duration,
                'workshift' => $workshift->shift_name,
                'overtime_date' => $overtime->date,
                'approver' => $overtimeAppr
            ], 'Get Overtime Detail Success');
        } catch (Exception $error) {
            return ResponseFormatter::error([
                'error' => $error->getMessage(),
                'line' => $error->getLine()
            ], 'Something went wrong', 500);
        }
    }

    /**
     * @OA\Get(
     *     path="/api/overtimes/today",
     *     tags={"Overtimes"},
     *     summary="Overtimes",
     *     description="Get Overtime Today",
     *     operationId="getOvertimesToday",
     *     security={{"bearerAuth":{}}}, 
     *     @OA\Response(
     *         response="200",
     *         description="Authenticated Success"
     *     ),
     *     @OA\Response(
     *         response="400",
     *         description="Bad Request / Validation Errors"
     *     ),
     *     @OA\Response(
     *         response="500",
     *         description="Something went wrong"
     *     ),
     * )
     */

    public function getOvertimeToday()
    {
        try {
            $user = Auth::user();
            $employee = Employee::where('user_id', $user->id)->firstOrFail();
            $overtime = $this->overtimeInterface->getOvertimeToday($employee->employee_id);

            $overtimeHours = $this->overtimeInterface->getOvertimeHoursTest();

            if ($overtime === null) {
                $response = [
                    'start_time' => 0,
                    'end_time' => 0,
                    'status' => null,
                    'overtime_duration' => 0,
                    'overtime_date' => 0
                ];

                return ResponseFormatter::error($response, 'No data available at the moment', 500);
            }


            return ResponseFormatter::success([
                'start_time' => $overtime->start_time,
                'end_time' => $overtime->end_time,
                'status' => $overtime->short,
                'overtime_duration' => $overtimeHours,
                'overtime_date' => $overtime->date
            ], 'Get Overtime Today Success');
        } catch (Exception $error) {
            return ResponseFormatter::error([
                'error' => $error->getMessage()
            ], 'Something went wrong', 500);
        }
    }

    /**
     * @OA\Get(
     *     path="/api/overtimes/",
     *     tags={"Overtimes"},
     *     summary="Overtimes",
     *     description="Get Overtime All",
     *     operationId="getOvertimesAll",
     *     security={{"bearerAuth":{}}}, 
     *     @OA\Response(
     *         response="200",
     *         description="Authenticated Success"
     *     ),
     *     @OA\Response(
     *         response="400",
     *         description="Bad Request / Validation Errors"
     *     ),
     *     @OA\Response(
     *         response="500",
     *         description="Something went wrong"
     *     ),
     * )
     */

    public function getOvertimeAllEmployee()
    {
        try {
            $user = Auth::user();
            $employee = Employee::where('user_id', $user->id)->firstOrFail();

            $overtime = $this->overtimeInterface->getOvertimeAll($employee->employee_id, null, null);

            $now = Carbon::now();
            $quota = $this->configInterface->overtimeQoutas();
            $remainingOvertime = $this->overtimeInterface->calculateRemainingOvertimeOnWeeks(Auth::user()->id, $quota, $now);
            $minOvertime = $this->configInterface->overtimeMinHours();
            $maxOvertime = $this->configInterface->overtimeMaxHours();

            if (count($overtime) <= 0) {
                $response = [
                    'minutes_weekly' => $quota,
                    'overtime_balance' => $remainingOvertime,
                    'overtime_history' => $overtime
                ];

                return ResponseFormatter::success($response, 'No data was available at the moment', 200);
            }

            return ResponseFormatter::success([
                'config' => [
                    "minutes_weekly" => $quota,
                    "minutes_minperday" => $minOvertime,
                    "minutes_maxperday" => $maxOvertime
                ],
                'overtime_balance' => $remainingOvertime,
                'overtime_history' => $overtime
            ], 'Get Overtime Success');
        } catch (Exception $error) {
            return ResponseFormatter::error([
                'error' => $error->getMessage()
            ], 'Something went wrong', 500);
        }
    }

    /**
     * @OA\Post(
     *     path="/api/overtimes/request",
     *     tags={"Overtimes"},
     *     summary="Overtimes",
     *     description="request-overtime",
     *     operationId="request-overtime",
     *     security={{"bearerAuth":{}}}, 
     *     @OA\RequestBody(
     *         @OA\MediaType(
     *             mediaType="multipart/form-data",
     *             @OA\Schema(
     *                      type="object",
     *                      @OA\Property(
     *                          property="date",
     *                          type="date"
     *                      ),
     *                      @OA\Property(
     *                          property="start_time",
     *                          type="date"
     *                      ),
     *                      @OA\Property(
     *                          property="end_time",
     *                          type="date"
     *                      ),
     *                      @OA\Property(
     *                          property="notes",
     *                          type="string"
     *                      ),
     *             )
     *         )
     *      ),
     *     @OA\Response(
     *         response="200",
     *         description="Authenticated Success"
     *     ),
     *     @OA\Response(
     *         response="400",
     *         description="Bad Request / Validation Errors"
     *     ),
     *     @OA\Response(
     *         response="500",
     *         description="Something went wrong"
     *     ),
     * )
     */

    public function requestOvertime(OvertimeRequest $request)
    {
        try {
            $user = Auth::user();
            $employee = Employee::where('user_id', $user->id)->firstOrFail();
            $manager_id = Department::where('department_id', $employee->department_id)->first()->manager;
            $employeeManager = Employee::where('employee_id', $manager_id)->first();
            $userManager = User::find($employeeManager->user_id);

            $date = $request->get('date');

            $holidays = $this->attendanceInterface->isTodayAreHolidays($date);
            if (count($holidays) > 0) {
                $response = [
                    'modal-key' => 'holidays',
                    'holidays' => $holidays
                ];

                return ResponseFormatter::error($response, 'You can only request on work days.', 400);
            }

            $leave = $this->attendanceInterface->isTodayLeave($date, $employee->employee_id);
            if (count($leave) > 0) {
                $response = [
                    'modal-key' => 'leave',
                    'leave' => $leave
                ];

                return ResponseFormatter::error($response, 'You can request after your leave day has finished,', 400);
            }

            $workshift = $this->attendanceInterface->isTodayIsWorkdays($date, $employee->branch_id, $employee->work_shift_id);
            if (count($workshift) <= 0) {
                $response = [
                    'modal-key' => 'days-off',
                ];

                return ResponseFormatter::error($response, "You can only request on work days.", 400);
            }

            $overtime = $this->overtimeInterface->getOvertimeDay($employee->employee_id, $date);
            if ($overtime !== null) {
                $response = [
                    'modal-key' => 'over-request',
                ];
                return ResponseFormatter::error($response, "You can only request overtime one time for one day.", 400);
            }

            $workshift = Workshift::where('work_shift_id', $employee->work_shift_id)->firstOrFail();

            $create = [
                "employee_id" => $employee->employee_id,
                "work_shift_name" => $workshift->shift_name,
                "date" => $request->input('date'),
                "work_shift_start" => $workshift->start_time,
                "work_shift_end" => $workshift->end_time,
                "start_time" => $request->input('start_time'),
                "end_time" => $request->input('end_time'),
                "notes" => $request->input('notes'),
                "status" => "opd"
            ];

            $overtime = Overtime::create($create);

            $data = [
                "saved" => $create,
            ];

            $notification = Http::withHeaders([
                'Content-Type' => 'application/json',
                'Authorization' => 'Basic ' . env('ONESIGNAL_REST_API_KEY')
            ])->post(env('ONESIGNAL_URL'), [
                'app_id' => env('ONESIGNAL_APP_ID'),
                'android_channel_id' => env('ONESIGNAL_ANDROID_CHANNEL_ID'),
                'small_icon' => "ic_stat_onesignal_default",
                'include_external_user_ids' => [$userManager->email],
                'channel_for_external_user_ids' => "push",
                'data' => ["detail_id" => $overtime->overtime_id],
                'headings' => ["en" => "Overtime"],
                'contents' => ["en" => $employee->first_name . " " . $employee->last_name . " request approval for overtime"]
            ]);

            $createNotifications = [
                "employee_id" => $employeeManager->employee_id,
                "title" => "Overtime",
                "notif_type" => 'Overtime',
                "notif_status" => 'Pending',
                "message" => $employee->first_name . " " . $employee->last_name . " request approval for overtime",
                "send_time" => Carbon::now(),
                "detail_id" => $overtime->overtime_id,
                "is_approval" => true,
            ];

            Notification::create($createNotifications);

            return ResponseFormatter::success($data, "Succeed to added overtime.");
        } catch (Exception $e) {
            $response = [
                'errors' => $e->getMessage(),
                'line', $e->getLine()
            ];
            return ResponseFormatter::error($response, 'Something went wrong', 500);
        }
    }

    public function getOvertimeListAppr()
    {
        try {
            $user = Auth::user();
            $employee = Employee::where('user_id', $user->id)->join('department', 'employees.department_id', '=', 'department.department_id')->firstOrFail();

            if (!$user->hasAnyPermission(["approval_queue.overtime"]))
                return ResponseFormatter::error([
                    'modal-key' => 'authorization-error'
                ], "You account doesn't gain access to access overtime list.", 401);

            $overtimeList = $this->overtimeInterface->getOvertimeListAppr($employee->employee_id, $employee->branch_id)
                ->where('department.code', '=', $employee->code)
                ->where('overtimes.status', '=', 'opd')
                ->get([
                    'overtimes.overtime_id',
                    'employees.first_name',
                    'employees.last_name',
                    'overtimes.date',
                    'overtimes.notes',
                    'overtimes.status',
                    'overtimes.reject_reason',
                    'status_codes.short',
                ]);

            if (count($overtimeList) <= 0) return ResponseFormatter::success([], 'No data available at the moment', 204);

            return ResponseFormatter::success($overtimeList, 'Get Overtime Success');
        } catch (Exception $error) {
            return ResponseFormatter::error([
                'error' => $error->getMessage()
            ], 'Something went wrong', 500);
        }
    }

    public function cancelOvertime($id)
    {
        try {
            $user = Auth::user();
            $employee = Employee::where('user_id', $user->id)->firstOrFail();

            $overtime = $this->overtimeInterface->getOvertimeById($id);
            if ($overtime->status !== 'opd') return ResponseFormatter::error(null, 'Overtime cannot canceled', 400);

            $canceled = $this->overtimeInterface->cancelOvertime($id);

            if ($canceled === 0) {
                return ResponseFormatter::error(null, 'Overtime not found', 204);
            }

            return ResponseFormatter::success([
                "id" => $id
            ], 'Overtime has been cancel');
        } catch (Exception $error) {
            return ResponseFormatter::error(['error' => $error->getMessage()], 'Something went wrong', 500);
        }
    }

    public function approveOvertime($id)
    {
        try {
            $user = Auth::user();
            $employee = Employee::where('user_id', $user->id)->firstOrFail();

            if (!$user->hasAnyPermission(["overtime.approve"]))
                return ResponseFormatter::error([
                    'modal-key' => 'authorization-error'
                ], "You account doesn't gain access to approve overtime.", 401);

            $overtime = $this->overtimeInterface->getOvertimeById($id);
            if ($overtime === null) return ResponseFormatter::error(null, 'Overtime was not found', 204);

            $attendance = Attendance::where('employee_id', '=', $overtime->employee_id)
                ->where('date', '=', $overtime->date)->first(['id']);

            if ($attendance === null) return ResponseFormatter::error(null, 'Overtime requests can only be approved after employees checked in', 400);

            $timeSubmitted = Carbon::now();
            $data = [
                "status" => "oap",
                "update_time" => $timeSubmitted,
                "update_by" => $employee->employee_id,
                "reject_reason" => null
            ];

            $overtimeApproved = $this->overtimeInterface->updateOvertime($id, $data);

            if ($overtimeApproved !== 1) {
                throw new Exception('Error while approving overtime');
            }

            $attendanceData = [
                "overtime_id" => $id
            ];

            $attendanceUpdated = $this->attendanceInterface->updateAttendance($attendance->id, $attendanceData);

            if ($attendanceUpdated !== 1) {
                throw new Exception('Error while updating attendances');
            }

            $employeeNotif = Employee::where('employee_id', $overtime->employee_id)->first();
            $userNotif = User::where('id', $employeeNotif->user_id)->first();

            Http::withHeaders([
                'Content-Type' => 'application/json',
                'Authorization' => 'Basic ' . env('ONESIGNAL_REST_API_KEY')
            ])->post(env('ONESIGNAL_URL'), [
                'app_id' => env('ONESIGNAL_APP_ID'),
                'android_channel_id' => env('ONESIGNAL_ANDROID_CHANNEL_ID'),
                'small_icon' => "ic_stat_onesignal_default",
                'include_external_user_ids' => [$userNotif->email],
                'channel_for_external_user_ids' => "push",
                'data' => ["detail_id" => $overtime->overtime_id],
                'headings' => ["en" => "Overtime"],
                'contents' => ["en" => "Your request for overtime has been approved"]
            ]);

            $createNotifications = [
                "employee_id" => $employeeNotif->employee_id,
                "title" => "Overtime",
                "notif_type" => 'Overtime',
                "notif_status" => 'Approve',
                "message" => 'Your request for overtime has been approved',
                "send_time" => Carbon::now(),
                "detail_id" => $overtime->overtime_id,
                "is_approval" => false,
            ];

            Notification::create($createNotifications);

            return ResponseFormatter::success([
                'overtime_id' => (int)$id,
                'attendance_id' => $attendance->id
            ], 'Overtime has been approved');
        } catch (Exception $error) {
            return ResponseFormatter::error(['error' => $error->getMessage()], 'Something went wrong', 500);
        }
    }

    public function rejectOvertime(RejectOvertimeRequest $request, $id)
    {
        try {
            $user = Auth::user();
            $employee = Employee::where('user_id', $user->id)->firstOrFail();

            if (!$user->hasAnyPermission(["overtime.reject"]))
                return ResponseFormatter::error(['modal-key' => 'authorization-error'], "You account doesn't gain access to reject overtime.", 401);

            $overtime = $this->overtimeInterface->getOvertimeById($id);
            if ($overtime === null) return ResponseFormatter::error(null, 'Overtime was not found', 204);

            $attendance = Attendance::where('employee_id', '=', $overtime->employee_id)
                ->where('date', '=', $overtime->date)->first(['id']);

            if ($attendance === null) return ResponseFormatter::error(null, 'Overtime requests can only be rejected after employees checked in', 400);

            $timeSubmitted = Carbon::now();
            $data = [
                "status" => "orj",
                "update_time" => $timeSubmitted,
                "update_by" => $employee->employee_id,
                "reject_reason" => $request->reject_reason

            ];
            $approved = $this->overtimeInterface->updateOvertime($id, $data);

            if ($approved !== 1) {
                throw new Exception('Error while rejecting overtime');
            }

            $attendanceData = [
                "overtime_id" => $id
            ];

            $attendanceUpdated = $this->attendanceInterface->updateAttendance($attendance->id, $attendanceData);

            if ($attendanceUpdated !== 1) {
                throw new Exception('Error while updating attendances');
            }

            $employeeNotif = Employee::where('employee_id', $overtime->employee_id)->first();
            $userNotif = User::where('id', $employeeNotif->user_id)->first();

            Http::withHeaders([
                'Content-Type' => 'application/json',
                'Authorization' => 'Basic ' . env('ONESIGNAL_REST_API_KEY')
            ])->post(env('ONESIGNAL_URL'), [
                'app_id' => env('ONESIGNAL_APP_ID'),
                'android_channel_id' => env('ONESIGNAL_ANDROID_CHANNEL_ID'),
                'small_icon' => "ic_stat_onesignal_default",
                'include_external_user_ids' => [$userNotif->email],
                'channel_for_external_user_ids' => "push",
                'data' => ["detail_id" => $overtime->overtime_id],
                'headings' => ["en" => "Overtime"],
                'contents' => ["en" => "Your request for overtime has been rejected"]
            ]);

            $createNotifications = [
                "employee_id" => $employeeNotif->employee_id,
                "title" => "Overtime",
                "notif_type" => 'Overtime',
                "notif_status" => 'Reject',
                "message" => 'Your request for overtime has been rejected',
                "send_time" => Carbon::now(),
                "detail_id" => $overtime->overtime_id,
                "is_approval" => false,
            ];

            Notification::create($createNotifications);

            return ResponseFormatter::success([
                'overtime_id' => (int)$id,
                'attendance_id' => $attendance->id
            ], 'Overtime has been rejected');
        } catch (Exception $error) {
            return ResponseFormatter::error(['error' => $error->getMessage()], 'Something went wrong', 500);
        }
    }
}
