<?php

namespace App\Http\Controllers\API;

use App\Helpers\DateHelpers;
use App\Helpers\ResponseFormatter;
use App\Http\Controllers\Controller;
use App\Http\Requests\AttendanceCheckInRequest;
use App\Models\Attendance;
use App\Models\Employee;
use App\Http\Requests\AttendanceCheckOutGetRequest;
use Carbon\Carbon;
use Exception;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use App\Http\Requests\ManualAttendanceGetRequest;
use App\Interfaces\AttendanceInterface;
use App\Interfaces\EmployeeInterface;
use App\Interfaces\LeaveApplicationInterface;
use App\Interfaces\ManualAttendanceInterface;
use App\Models\Department;
use App\Models\Notification;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Http;

class AttendanceController extends Controller
{
    private $attendanceInterface, $leaveApplicationInterface, $employeeInterface, $manualAttendanceInterface;

    public function __construct(
        AttendanceInterface $attendanceInterface,
        LeaveApplicationInterface $leaveApplicationInterface,
        EmployeeInterface $employeeInterface,
        ManualAttendanceInterface $manualAttendanceInterface
    ) {
        $this->attendanceInterface = $attendanceInterface;
        $this->leaveApplicationInterface = $leaveApplicationInterface;
        $this->employeeInterface = $employeeInterface;
        $this->manualAttendanceInterface = $manualAttendanceInterface;
        // $this->middleware('role_or_permission:Super Admin|manual_attendance.approve', ['only' => ['ManualAttendanceApprove']]);
        // $this->middleware('role_or_permission:Super Admin|manual_attendance.reject', ['only' => ['ManualAttendanceReject']]);
        // $this->middleware('role_or_permission:Super Admin|manual_attendance.cancel', ['only' => ['ManualAttendanceCancel']]);
    }

    /**
     * @OA\Get(
     *     path="/api/attendances/{year_month}",
     *     tags={"Attendances"},
     *     summary="Attendances",
     *     description="Get Statistics",
     *     operationId="getstatistics",
     *     security={{"bearerAuth":{}}}, 
     *     @OA\Parameter(
     *         name="year_month",
     *         in="path",
     *         description="got statistics in attendances from year and month",
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
    public function statistics($year_month)
    {
        try {
            $year = substr($year_month, 0, 4);
            $month = substr($year_month, 4, 2);
            $firstDate = (date('Y-m-d H:i:s', mktime(0, 0, 0, $month, 1, $year)));
            $lastDate = (date('Y-m-t H:i:s', mktime(23, 59, 59, $month, 1, $year)));

            $userId = Auth::user()->id;
            $employee = Employee::where('user_id', $userId)->first();

            $stats = $this->attendanceInterface->statistics($employee->employee_id, $month, $year);

            // Statistics
            $days_work = $stats['days_presence'] + $stats['days_absence'] + $stats['totals_leave_by_month'];
            if ($days_work <= 0) {
                return ResponseFormatter::success([], 'No data available at the moment', 204);
            }
            $days_presence_percentages = ($stats['days_presence'] / $days_work) * 100;
            $days_absent_percentages = ($stats['days_absence'] / $days_work) * 100;
            $days_leave_by_month_percentages = ($stats['totals_leave_by_month'] / $days_work) * 100;

            return ResponseFormatter::success([
                'year' => $year,
                'month' => $month,
                'period leave' => [
                    'start' => $stats['start'],
                    'end' => $stats['end'],
                    'limit' => $stats['limit']
                ],
                'statistics' => [
                    'days_presence' => $stats['days_presence'],
                    'days_absence' => $stats['days_absence'],
                    'days_leave_by_month' => $stats['totals_leave_by_month'],
                    'days_work' => $days_work,
                    'remain_days_leave' => $stats['limit'] - $stats['total_days_leave'],
                    'total_days_leave' => $stats['total_days_leave'],
                    'hours_overtime' => $stats['overtime'],
                    'total_late_in' => DateHelpers::minutesToHours($stats['total_late_in']),
                    'days_presence_percentages' => round($days_presence_percentages, 0),
                    'days_absent_percentages' => round($days_absent_percentages, 0),
                    'days_leave_by_month_percentages' => round($days_leave_by_month_percentages, 0)
                ],
                'attendances' => $stats['attendances'],
                'leaves' => $stats['leaves']
            ], 'Get Attendance Success');
        } catch (Exception $error) {
            return ResponseFormatter::error([
                'error' => $error->getMessage(),
                'line' => $error->getLine()
            ], 'Something went wrong', 500);
        }
    }

    /**
     * @OA\Post(
     *     path="/api/attendances/check-in",
     *     tags={"Attendances"},
     *     summary="Attendances",
     *     description="Check-in",
     *     operationId="check-in",
     *     security={{"bearerAuth":{}}}, 
     *      @OA\RequestBody(
     *       @OA\MediaType(
     *           mediaType="multipart/form-data",
     *           @OA\Schema(
     *               type="object", 
     *               @OA\Property(
     *                  property="photo",
     *                  type="file",
     *               ),
     *               @OA\Property(
     *                  property="latitude",
     *                  type="number"
     *               ),
     *               @OA\Property(
     *                   property="longitude",
     *                   type="number"
     *               ),
     *               @OA\Property(
     *                   property="address",
     *                   type="string"
     *               ),
     *               @OA\Property(
     *                   property="note_check_in",
     *                   type="string"
     *               ),
     *           ),
     *       )
     *   ),
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

    public function checkInEmployee(AttendanceCheckInRequest $request)
    {
        try {
            $timeSubmitted = Carbon::now();

            $user = Auth::user();
            $attendance = Attendance::where('id')->first();
            $employee = Employee::where('user_id', $user->id)->firstOrFail();

            $holidays = $this->attendanceInterface->isTodayAreHolidays($timeSubmitted);
            if (count($holidays) > 0) {
                $response = [
                    'modal-key' => 'holidays',
                    'holidays' => $holidays
                ];

                return ResponseFormatter::error($response, 'You can only request on work days.', 400);
            }

            $leave = $this->attendanceInterface->isTodayLeave($timeSubmitted, $employee->employee_id);
            if (count($leave) > 0) {
                $response = [
                    'modal-key' => 'leave',
                    'Leave' => $leave
                ];

                return ResponseFormatter::error($response, 'You can request after your leave day has finished,', 400);
            }

            $isWorkday = $this->attendanceInterface->isTodayIsWorkdays($timeSubmitted, $employee->branch_id, $employee->work_shift_id);
            if (count($isWorkday) <= 0) {
                $response = [
                    'modal-key' => 'days-off',
                ];

                return ResponseFormatter::error($response, "You can only request on work days.", 400);
            }

            $isCheckedIn = $this->attendanceInterface->checkEmployeeHasCheckedIn($employee->employee_id, $timeSubmitted);

            if ($isCheckedIn) {
                $response = [
                    'modal-key' => 'over-request',
                ];
                return ResponseFormatter::error($response, "You've already checked in today!", 400);
            }


            $late = $this->attendanceInterface->checkEmployeeIsLated($employee->employee_id, $timeSubmitted);

            $data['photo'] = null;

            if ($request->file('photo')) {
                $data['photo'] = $request->file('photo')->store('attendances');
            }

            $return = Attendance::create([
                "employee_id" => $employee->employee_id,
                "date" => $timeSubmitted,
                "check_in" => $timeSubmitted,
                "late_duration" => $late["lateTime"],
                "image" => env('APP_URL') . ('/uploads/') . $data['photo'],
                "location" => json_encode([
                    "latitude" => $request->input('latitude'),
                    "longitude" => $request->input('longitude'),
                    "address" => $request->input('address')
                ]),
                "note_check_in" => $request->input('note_check_in'),
                "status" => "acm"
            ]);

            $return["late_duration"] = DateHelpers::minutesToHours($late["lateTime"]);

            $return['location'] = json_decode($return['location']);

            $data = [
                "saved" => $return,
            ];

            return ResponseFormatter::success($data, 'Check');
        } catch (Exception $e) {
            $response = [
                'errors' => $e->getMessage(),
            ];
            return ResponseFormatter::error($response, 'Something went wrong', 500);
        }
    }

    /**
     * @OA\Post(
     *     path="/api/attendances/check-out",
     *     tags={"Attendances"},
     *     summary="Attendances",
     *     description="Check-out",
     *     operationId="check-out",
     *     security={{"bearerAuth":{}}}, 
     *     @OA\RequestBody(
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(
     *                 @OA\Property(
     *                      type="object",
     *                      @OA\Property(
     *                          property="_method",
     *                          type="string"
     *                      ),
     *                      @OA\Property(
     *                          property="note_check_out",
     *                          type="string"
     *                      ),
     *                 ),
     *                 example={
     *                     "_method":"PUT",
     *                     "note_check_out":"test note",
     *                }
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

    public function checkOutEmployee(AttendanceCheckOutGetRequest $request)
    {
        try {
            $timeSubmitted = Carbon::now();
            $note = $request->input('note_check_out', '');

            $user = Auth::user();
            $employee = Employee::where('user_id', $user->id)->firstOrFail();

            $employeeId = $employee->employee_id;

            $isCheckedIn = $this->attendanceInterface->checkEmployeeHasCheckedIn($employeeId, $timeSubmitted);

            if (!$isCheckedIn) {
                return ResponseFormatter::error([], 'You\'ve to checked in first!', 400);
            }

            $fieldChoosed = DB::table('attendances')
                ->where('employee_id', $employeeId)
                ->whereDate('date', $timeSubmitted);

            if (!is_null($fieldChoosed->first()->check_out)) {
                return ResponseFormatter::error([], 'You\'ve already checked out!', 400);
            };

            $data = [
                "check_out" => $timeSubmitted,
                "note_check_out" => $note,
            ];

            $fieldChoosed->update($data);

            return ResponseFormatter::success($data, "check");
        } catch (Exception $e) {
            $response = [
                'errors' => $e->getMessage(),
            ];
            return ResponseFormatter::error($response, 'Something went wrong', 500);
        }
    }


    /**
     * @OA\Post(
     *     path="/api/attendances/manual-attendance",
     *     tags={"Attendances"},
     *     summary="Attendances",
     *     description="Manual-Attendance",
     *     operationId="manual-attendance",
     *     security={{"bearerAuth":{}}}, 
     *     @OA\RequestBody(
     *         @OA\MediaType(
     *             mediaType="multipart/form-data",
     *             @OA\Schema(
     *                      type="object",
     *                      @OA\Property(
     *                  property="photo",
     *                  type="file",
     *               ),
     *                     @OA\Property(
     *                          property="date",
     *                          type="date"
     *                      ),
     *                      @OA\Property(
     *                          property="check_in",
     *                          type="date"
     *                      ),
     *                      @OA\Property(
     *                          property="check_out",
     *                          type="date"
     *                      ),
     *                      @OA\Property(
     *                          property="note",
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
    public function manualAttendance(ManualAttendanceGetRequest $request)
    {
        try {
            $timeSubmitted = Carbon::now();

            $date = $request->input('date');

            $user = Auth::user();
            $employee = Employee::where('user_id', $user->id)->firstOrFail();
            $manager_id = Department::where('department_id', $employee->department_id)->first()->manager;
            $employeeManager = Employee::where('employee_id', $manager_id)->first();
            $userManager = User::find($employeeManager->user_id);

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

            $isCheckedIn = $this->attendanceInterface->checkEmployeeHasCheckedIn($employee->employee_id, $date);

            if ($isCheckedIn) {
                $response = [
                    'modal-key' => 'over-request',
                ];
                return ResponseFormatter::success($response, "You've already checked in or request attendance on selected date!", 400);
            }

            $date_folder = date('Ymd');
            $data['photo'] = null;


            if ($request->file('photo')) {
                $data['photo'] = $request->file('photo')->store('manual-attendance');
            }

            $create = [
                "employee_id" => $employee->employee_id,
                "work_shift_id" => $employee->work_shift_id,
                "date" => $date,
                "check_in" => $date . ' ' . $request->input('check_in'),
                "check_out" => $date . ' ' . $request->input('check_out'),
                "image_manual_attendance" => env('APP_URL') . ('/uploads/') . $data['photo'],
                "note" => $request->input('note'),
                "status" => "apd"
            ];

            $manualAttenndace = Attendance::create($create);

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
                'data' => ["detail_id" => $manualAttenndace->id],
                'headings' => ["en" => "Manual attendance"],
                'contents' => ["en" => $employee->first_name . " " . $employee->last_name . " request approval for manual attendance"]
            ]);

            $createNotifications = [
                "employee_id" => $employeeManager->employee_id,
                "title" => "Manual attendance",
                "notif_type" => 'Manual',
                "notif_status" => 'Pending',
                "message" => $employee->first_name . " " . $employee->last_name . " request approval for manual attendance",
                "send_time" => Carbon::now(),
                "detail_id" => $manualAttenndace->id,
                "is_approval" => true,
            ];

            Notification::create($createNotifications);

            return ResponseFormatter::success($data, "check");
        } catch (Exception $e) {
            $response = [
                'errors' => $e->getMessage(),
            ];
            return ResponseFormatter::error($response, 'Something went wrong', 500);
        }
    }

    /**
     * @OA\Post(
     *      path="/api/attendances/cancel/{id}",
     *      operationId="ManualAttendanceCancel",
     *      tags={"Attendances"},
     *      summary="Cancel Manual Attendance",
     *      description="Cancel Manual Attendance",
     *      security={{"bearerAuth":{}}},
     *     @OA\Parameter(
     *          name="id",
     *          description="Attendance id",
     *          required=true,
     *          in="path",
     *          @OA\Schema(
     *              type="integer"
     *          )
     *      ),
     *      @OA\RequestBody(
     *          @OA\MediaType(
     *              mediaType="multipart/form-data",
     *              @OA\Schema(
     *                  required={"reason"},
     *                  type="object", 
     *                  @OA\Property(
     *                      property="reason",
     *                      type="string",
     *                  ),
     *              ),
     *          )
     *      ),
     *      @OA\Response(
     *          response=201,
     *          description="Successfully Cancelled Manual Attendance",
     *      ),
     *      @OA\Response(
     *          response=400,
     *          description="Bad Request"
     *      ),
     *      @OA\Response(
     *         response="500",
     *         description="Something went wrong"
     *      )
     * )
     */

    public function ManualAttendanceCancel(Request $request, $id)
    {
        try {
            $hasData = $this->manualAttendanceInterface->checkDateHasManualAttendance($id);
            if ($hasData === false) {
                return ResponseFormatter::error([], "No Data", 204);
            }

            $employeeId = Auth::user()->employee->id;
            $this->attendanceInterface->updateAttendance($id, [
                'status'        => 'can',
                'status_date'   => date('Y-m-d'),
                'status_by'     => $employeeId,
            ]);

            $attendance = $this->attendanceInterface->getAttendanceById($id)
                ->first();

            $data = [
                'attendance_id' => $attendance->id,
                'employee_id'   => $attendance->employee_id,
                'date'          => $attendance->date,
                'check_in'      => $attendance->check_in,
                'check_out'     => $attendance->check_out,
                'note'          => $attendance->note,
                'status'        => $attendance->status,
                'status_date'   => $attendance->status_date,
                'status_by'     => $attendance->status_by,
            ];

            $cancelled = [
                "cancelled" => $data,
            ];

            return ResponseFormatter::success($cancelled, "Successfully Cancelled Manual Attendance", 200);
        } catch (Exception $e) {
            return ResponseFormatter::error([
                'error' => $e->getMessage()
            ], 'Something went wrong', 500);
        }
    }

    /**
     * @OA\Post(
     *      path="/api/attendances/approve/{id}",
     *      operationId="ManualAttendanceApprove",
     *      tags={"Attendances"},
     *      summary="Approve Manual Attendance",
     *      description="Approve Manual Attendance",
     *      security={{"bearerAuth":{}}},
     *      @OA\Parameter(
     *          name="id",
     *          description="Attendance id",
     *          required=true,
     *          in="path",
     *          @OA\Schema(
     *              type="integer"
     *          )
     *      ),
     *      @OA\Response(
     *          response=201,
     *          description="Successfully Approved Manual Attendance",
     *      ),
     *      @OA\Response(
     *          response=400,
     *          description="Bad Request"
     *      ),
     *      @OA\Response(
     *         response="500",
     *         description="Something went wrong"
     *      )
     * )
     */

    public function ManualAttendanceApprove($id)
    {
        try {

            $hasData = $this->manualAttendanceInterface->checkDateHasManualAttendance($id);
            if ($hasData === false) {
                return ResponseFormatter::error([], "No Data", 204);
            }

            $employeeId = Auth::user()->employee->id;
            $this->attendanceInterface->updateAttendance($id, [
                'status'       => 'amm',
                'status_by'   => $employeeId,
                'status_date' => date('Y-m-d')
            ]);

            $attendance = $this->attendanceInterface->getAttendanceById($id)
                ->first();
            $employee = Employee::where('employee_id', $attendance->employee_id)->first();
            $user = User::where('id', $employee->user_id)->first();

            Http::withHeaders([
                'Content-Type' => 'application/json',
                'Authorization' => 'Basic ' . env('ONESIGNAL_REST_API_KEY')
            ])->post(env('ONESIGNAL_URL'), [
                'app_id' => env('ONESIGNAL_APP_ID'),
                'android_channel_id' => env('ONESIGNAL_ANDROID_CHANNEL_ID'),
                'small_icon' => "ic_stat_onesignal_default",
                'include_external_user_ids' => [$user->email],
                'channel_for_external_user_ids' => "push",
                'data' => ["detail_id" => $attendance->id],
                'headings' => ["en" => "Manual attendance"],
                'contents' => ["en" => "Your request for manual attendance has been approved"]
            ]);

            $createNotifications = [
                "employee_id" => $employee->employee_id,
                "title" => "Manual attendance",
                "notif_type" => 'Manual',
                "notif_status" => 'Approve',
                "message" => 'Your request for manual attendance has been approved',
                "send_time" => Carbon::now(),
                "detail_id" => $attendance->id,
                "is_approval" => false,
            ];

            Notification::create($createNotifications);

            $data = [
                'attendance_id' => $attendance->id,
                'employee_id'   => $attendance->employee_id,
                'date'          => $attendance->date,
                'check_in'      => $attendance->check_in,
                'check_out'     => $attendance->check_out,
                'note'          => $attendance->note,
                'status'        => $attendance->status,
                'status_date'   => $attendance->status_date,
                'status_by'     => $attendance->status_by,
            ];

            $approved = [
                "approved" => $data,
            ];

            return ResponseFormatter::success($approved, "Successfully Approved Manual Attendance", 200);
        } catch (Exception $e) {
            return ResponseFormatter::error([
                'error' => $e->getMessage()
            ], 'Something went wrong', 500);
        }
    }

    // public function ManualAttendanceClose($id)
    // {
    //     try {
    //         $response = $this->attendanceInterface->updateAttendance($id, [
    //             'status'       => 'cls',
    //             'status_date'   => date('Y-m-d'),
    //             'status_by'     => Auth::user()->employee->employee_id,
    //         ]);

    //         return ResponseFormatter::success($response, "Successfully Approved Manual Attendance", 201);
    //     } catch (Exception $e) {
    //         return ResponseFormatter::error([
    //             'error' => $e->getMessage()
    //         ], 'Something went wrong', 500);
    //     }
    // }

    /**
     * @OA\Post(
     *      path="/api/attendances/reject/{id}",
     *      operationId="ManualAttendanceReject",
     *      tags={"Attendances"},
     *      summary="Reject Manual Attendance",
     *      description="Reject Manual Attendance",
     *      security={{"bearerAuth":{}}},
     *     @OA\Parameter(
     *          name="id",
     *          description="Attendance id",
     *          required=true,
     *          in="path",
     *          @OA\Schema(
     *              type="integer"
     *          )
     *      ),
     *      @OA\RequestBody(
     *          @OA\MediaType(
     *              mediaType="multipart/form-data",
     *              @OA\Schema(
     *                  required={"reason"},
     *                  type="object", 
     *                  @OA\Property(
     *                      property="reason",
     *                      type="string",
     *                  ),
     *              ),
     *          )
     *      ),
     *      @OA\Response(
     *          response=201,
     *          description="Successfully Rejected Manual Attendance",
     *      ),
     *      @OA\Response(
     *          response=400,
     *          description="Bad Request"
     *      ),
     *      @OA\Response(
     *         response="500",
     *         description="Something went wrong"
     *      )
     * )
     */

    public function ManualAttendanceReject(Request $request, $id)
    {
        try {
            $requestOnly       = $request->only('reason');
            $rules             = ['reason' => 'required'];

            $validatedData = Validator::make($requestOnly, $rules);
            if ($validatedData->fails()) {
                $response = [
                    'errors' => $validatedData->errors()
                ];
                return ResponseFormatter::error($response, 'Bad Request', 400);
            }

            $hasData = $this->manualAttendanceInterface->checkDateHasManualAttendance($id);

            if ($hasData === false) {
                return ResponseFormatter::error([], "No Data", 204);
            }

            $this->attendanceInterface->updateAttendance($id, [
                'status'        => 'arj',
                'status_date'   => date('Y-m-d'),
                'reject_reason' => $request->reason,
                'status_by'     => Auth::user()->employee->employee_id
            ]);

            $attendance = $this->attendanceInterface->getAttendanceById($id)
                ->first();

            $employee = Employee::where('employee_id', $attendance->employee_id)->first();
            $user = User::where('id', $employee->user_id)->first();

            Http::withHeaders([
                'Content-Type' => 'application/json',
                'Authorization' => 'Basic ' . env('ONESIGNAL_REST_API_KEY')
            ])->post(env('ONESIGNAL_URL'), [
                'app_id' => env('ONESIGNAL_APP_ID'),
                'android_channel_id' => env('ONESIGNAL_ANDROID_CHANNEL_ID'),
                'small_icon' => "ic_stat_onesignal_default",
                'include_external_user_ids' => [$user->email],
                'channel_for_external_user_ids' => "push",
                'data' => ["detail_id" => $attendance->id],
                'headings' => ["en" => "Manual attendance"],
                'contents' => ["en" => "Your request for manual attendance has been rejected"]
            ]);

            $createNotifications = [
                "employee_id" => $employee->employee_id,
                "title" => "Manual attendance",
                "notif_type" => 'Manual',
                "notif_status" => 'Reject',
                "message" => 'Your request for manual attendance has been rejected',
                "send_time" => Carbon::now(),
                "detail_id" => $attendance->id,
                "is_approval" => false,
            ];

            Notification::create($createNotifications);

            $data = [
                'attendance_id' => $attendance->id,
                'employee_id'   => $attendance->employee_id,
                'date'          => $attendance->date,
                'check_in'      => $attendance->check_in,
                'check_out'     => $attendance->check_out,
                'note'          => $attendance->note,
                'status'        => $attendance->status,
                'status_date'   => $attendance->status_date,
                'status_by'     => $attendance->status_by,
                'reject_reason' => $attendance->reject_reason
            ];

            $rejected = [
                "rejected" => $data,
            ];

            return ResponseFormatter::success($rejected, "Successfully Rejected Manual Attendance", 200);
        } catch (Exception $e) {
            return ResponseFormatter::error([
                'error' => $e->getMessage()
            ], 'Something went wrong', 500);
        }
    }

    /**
     * @OA\Get(
     *      path="/api/attendances/get-attendances/",
     *     tags={"Attendances"},
     *     summary="Attendances",
     *     description="Get Attendance Today",
     *     operationId="getattendancetoday",
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
    public function getAttendanceToday()
    {
        try {
            $timeSubmitted = Carbon::now();

            $user = Auth::user();
            $employee = Employee::where('user_id', $user->id)->firstOrFail();

            $getToday = $this->attendanceInterface->getAttendanceToday($employee["employee_id"], $timeSubmitted);

            if (!$getToday) {
                return ResponseFormatter::success([], 'You\'ve haven`t check in today!', 204);
            }

            $getToday->late_duration = DateHelpers::minutesToHours($getToday->late_duration);
            $getToday->overtime_duration = DateHelpers::minutesToHours($getToday->overtime_duration);

            unset($getToday->working_hour);

            return ResponseFormatter::success(
                $getToday,
                "You've already checked in today!"
            );
        } catch (Exception $e) {
            $response = [
                'errors' => $e->getMessage(),
            ];
            return ResponseFormatter::error($response, 'Something went wrong', 500);
        }
    }

    public function show($id)
    {
        try {
            $user = Auth::user();
            $emp = Employee::where('user_id', $user->id)->firstOrFail();

            $employee = $this->employeeInterface->getEmployeeById($emp->employee_id)
                ->join('department', 'employees.department_id', '=', 'department.department_id')
                ->join('job_positions', 'employees.job_position_id', '=', 'job_positions.job_position_id')
                ->first([
                    'employees.user_id',
                    'employees.employee_id',
                    'employees.first_name',
                    'employees.last_name',
                    'employees.image',
                    'department.department_name',
                    'job_positions.job_position'
                ]);


            $employee->image = $this->employeeInterface->getEmployeePicture($employee->user_id);

            $attendance = $this->attendanceInterface->getAttendanceById($id)
                ->join('status_codes', 'attendances.status', '=', 'status_codes.code')
                ->first([
                    'check_in',
                    'check_out',
                    'image',
                    'image_manual_attendance',
                    'date',
                    'location',
                    'note_check_in',
                    'note_check_out',
                    'status_codes.short as status',
                    'late_duration'
                ]);

            if ($attendance === null) {
                return ResponseFormatter::success([], 'Attendance Id was not found', 204);
            }

            $attendance->location = json_decode($attendance->location);

            if (!is_null($attendance->late_duration)) {
                $time = $attendance->late_duration;
                $attendance->late_duration = DateHelpers::minutesToHours($time);
                if ($attendance->late_duration->h !== 0 || $attendance->late_duration->m !== 0) {
                    $attendance->status = "Late";
                }
                unset($time);
            }

            if (!is_null($attendance->image_manual_attendance)) { {
                    $attendance->file_path = $attendance->image_manual_attendance;
                    unset($attendance->image, $attendance->image_manual_attendance);
                }
            }


            $response = [
                "employee" => $employee,
                "attendance" => $attendance
            ];

            return ResponseFormatter::success(
                $response,
                "Get Attendance Detail is Success"
            );
        } catch (Exception $e) {
            $response = [
                'errors' => $e->getMessage(),
                'line' => $e->getLine()
            ];
            return ResponseFormatter::error($response, 'Something went wrong', 500);
        }
    }
}
