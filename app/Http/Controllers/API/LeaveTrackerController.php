<?php

namespace App\Http\Controllers\API;

use App\Helpers\DataHelpers;
use Illuminate\Http\Request;
use App\Helpers\ResponseFormatter;
use App\Models\LeaveApplication;
use App\Http\Controllers\Controller;
use App\Models\Employee;
use App\Models\Department;
use App\Models\Notification;
use App\Models\user;
use Carbon\Carbon;
use Exception;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use App\Interfaces\EmployeeInterface;
use App\Interfaces\LeaveApplicationInterface;
use App\Models\LeavePeriod;
use App\Models\LeaveType;
use App\Models\StatusCode;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Http;

class LeaveTrackerController extends Controller
{
    protected $leaveApplicationInterface;
    private EmployeeInterface $employeeInterface;

    public function __construct(LeaveApplicationInterface $leaveApplicationInterface, EmployeeInterface $employeeInterface)
    {
        $this->leaveApplicationInterface = $leaveApplicationInterface;
        $this->employeeInterface = $employeeInterface;

        // $this->middleware('role_or_permission:Super Admin|self_leavetracker.list', ['only' => ['index', 'show']]);
        // $this->middleware('role_or_permission:Super Admin|self_leavetracker.create', ['only' => ['store']]);
        // $this->middleware('role_or_permission:Super Admin|leave_tracker.approve', ['only' => ['approve']]);
        // $this->middleware('role_or_permission:Super Admin|leave_tracker.reject', ['only' => ['reject']]);
        // $this->middleware('role_or_permission:Super Admin|leave_tracker.cancel', ['only' => ['cancel']]);
    }

    /**
     * @OA\Get(
     *     path="/api/users/leave-tracker/{yearmonth}",
     *     tags={"Leave Tracker"},
     *     summary="Getting Monthly Leave Tracker History",
     *     operationId="getLeaveTracker",
     *     security={{"bearerAuth":{}}}, 
     *     @OA\Parameter(
     *          name="yearmonth",
     *          description="Year and month",
     *          required=true,
     *          in="path",
     *          @OA\Schema(
     *              type="integer"
     *          )
     *      ),
     *     @OA\Response(
     *         response="200",
     *         description="Successful operation"
     *     ),
     *     @OA\Response(
     *         response="204",
     *         description="No Content"
     *     ),
     *     @OA\Response(
     *         response="500",
     *         description="Something went wrong"
     *     )
     * )
     */
    public function index($yearmonth)
    {
        $year = substr($yearmonth, 0, 4);
        $month = substr($yearmonth, 4, 6);
        $first_date = date("$year-$month-01");
        $last_date = date("Y-m-t", strtotime($first_date));

        $leaveApplications = LeaveApplication::leftJoin('leave_types', 'leave_applications.leave_type_id', '=', 'leave_types.leave_type_id')->leftJoin('leave_periods', 'leave_applications.leave_period_id', 'leave_periods.leave_period_id')->whereBetween('application_date', [$first_date, $last_date])->get();

        if (count($leaveApplications) == 0) {
            return ResponseFormatter::error($leaveApplications, 'No data available at the moment', 204);
        }

        $totalDaysSick = LeaveApplication::where('leave_type_id', 3)->whereBetween('application_date', [$first_date, $last_date])->get();
        $totalDaysAnnualLeave = LeaveApplication::where('leave_type_id', 1)->get();

        $totalApprovedLeave = [];
        $totalRejectedLeave = [];

        foreach ($leaveApplications as $leaveApplication) {
            $statusCode = StatusCode::where('code', $leaveApplication->status)->first();
            $leavePeriod = [
                'start' => $leaveApplication->from_date,
                'end' => $leaveApplication->to_date,
                'limit' => $leaveApplication->limit
            ];

            $leaveHistory[] = [
                'leave_application_id' => $leaveApplication->leave_application_id,
                'leave_type_id' => $leaveApplication->leave_type_id,
                'leave_type_name' => $leaveApplication->leave_type_name,
                'start_date' => $leaveApplication->application_from_date,
                'end_date' => $leaveApplication->application_to_date,
                'status' => $statusCode->short,
                'notes' => $leaveApplication->notes,
                'attachment_path' => ($leaveApplication->attachment !== null) ? env('APP_URL') . "/uploads/$leaveApplication->attachment" : $leaveApplication->attachment
            ];

            if ($leaveApplication->approve_by > 0) {
                $totalApprovedLeave[] = $leaveApplication->approve_by;
            }

            if ($leaveApplication->reject_by > 0) {
                $totalRejectedLeave[] = $leaveApplication->reject_by;
            }
        }

        $approvedLeave = (count($totalApprovedLeave) > 0) ? count($totalApprovedLeave) : 0;
        $rejectedLeave = (count($totalRejectedLeave) > 0) ? count($totalRejectedLeave) : 0;

        $data = [
            'leave' => $year,
            'month' => $month,
            'period_leave' => $leavePeriod,
            'statistic' => [
                'total_days_sick' => count($totalDaysSick),
                'remain_days_leave' => $leavePeriod['limit'] - count($totalDaysAnnualLeave),
                'total_leave_application' => count($leaveApplications),
                'total_approved_leave' => $approvedLeave,
                'total_rejected_leave' => $rejectedLeave,
                'total_approved_percentages' => round($approvedLeave / count($leaveApplications) * 100),
                'toral_rejected_percentages' => round($rejectedLeave / count($leaveApplications) * 100)
            ],
            'leave_history' => $leaveHistory
        ];

        return ResponseFormatter::success($data, 'Get Leave Success');
    }

    public function leaveTracker($year)
    {
        $user = Auth::user();
        $employee = Employee::where('user_id', $user->id)->firstOrFail();
        $env = env('APP_URL');

        $leaveApplications = $this->leaveApplicationInterface->getUserLeaveHistory($employee->employee_id, $year)
            ->leftJoin('leave_periods', 'leave_applications.leave_period_id', 'leave_periods.leave_period_id')
            ->get([
                'leave_applications.*',
                'leave_types.leave_type_name',
                'leave_periods.*'
            ]);

        if (count($leaveApplications) == 0) {
            return ResponseFormatter::error($leaveApplications, 'No data available at the moment', 204);
        }

        $daysApproved = [];
        $daysRejected = [];
        $totalDayApproved = 0;
        $totalDayRejected = 0;

        $leaveTypes = LeaveType::get(['leave_type_id', 'leave_type_name']);

        foreach ($leaveTypes as $l) {
            $app = $this->leaveApplicationInterface->countLeaveDaysAnually($employee->employee_id, $year, $l->leave_type_id);
            $daysApproved[$l->leave_type_name] = $app;
            $totalDayApproved += $app;

            $rej = $this->leaveApplicationInterface->countRejectLeaveDaysAnually($employee->employee_id, $year, $l->leave_type_id);
            $daysRejected[$l->leave_type_name] = $rej;
            $totalDayRejected += $rej;
        }

        $leavePeriods = LeavePeriod::whereYear('from_date', $year)->whereYear('to_date', $year)->first('leave_period_id');

        $leavePeriod = $this->leaveApplicationInterface->countAnnualApprAndPend(1, $employee->employee_id, $leavePeriods['leave_period_id']);

        foreach ($leaveApplications as $leaveApplication) {
            $statusCode = StatusCode::where('code', $leaveApplication->status)->first();

            $file_path = [];
            if ($leaveApplication->attachment !== null) {
                $charPos = strpos($leaveApplication->attachment, '/') + 1;
                $temp = substr($leaveApplication->attachment, $charPos);
                $exp = explode('|', $temp);

                foreach ($exp as $e) {
                    $tempStr = "${env}/uploads/leave-applications/{$e}";

                    $obj = (object)[];
                    $obj->link = $tempStr;
                    $obj->filename = $e;

                    array_push($file_path, $obj);

                    unset($tempStr);
                }
            };

            $leaveHistory[] = [
                'leave_application_id' => $leaveApplication->leave_application_id,
                'leave_type_id' => $leaveApplication->leave_type_id,
                'leave_type_name' => $leaveApplication->leave_type_name,
                'application_date' => $leaveApplication->application_date,
                'status' => $statusCode->short,
                'notes' => $leaveApplication->notes,
                'file_path' => $file_path
            ];
        }

        $totalDaysLeave = $totalDayApproved + $totalDayRejected;
        if ($totalDaysLeave === 0) {
            $totalDaysLeave = 1;
        }

        $data = [
            'leave' => $year,
            'period_leave' => $leavePeriod,
            'statistic' => [
                'remain_days_leave' => $leavePeriod - $daysApproved['Annual Leave'],
                'total_days_sick' => $daysApproved['Sick Leave'],
                'total_approved_leave' => $totalDayApproved,
                'total_rejected_leave' => $totalDayRejected,
                'total_approved_percentages' => round($totalDayApproved / $totalDaysLeave * 100),
                'total_rejected_percentages' => round($totalDayRejected / $totalDaysLeave * 100)
            ],
            'leave_history' => $leaveHistory
        ];

        return ResponseFormatter::success($data, 'Get Leave Success');
    }

    /**
     * @OA\Post(
     *      path="/api/users/leave-tracker",
     *      operationId="addLeaveTracker",
     *      tags={"Leave Tracker"},
     *      summary="Adding Leave Tracker",
     *      description="Return id data",
     *      security={{"bearerAuth":{}}},
     *      @OA\RequestBody(
     *          @OA\MediaType(
     *              mediaType="multipart/form-data",
     *              @OA\Schema(
     *                  required={"employee_id", "leave_type_id", "leave_period_id", "application_from_date", "application_to_date", "notes"},
     *                  type="object", 
     *                  @OA\Property(
     *                      property="employee_id",
     *                      type="number",
     *                  ),
     *                  @OA\Property(
     *                      property="leave_type_id",
     *                      type="number",
     *                  ),
     *                  @OA\Property(
     *                      property="leave_period_id",
     *                      type="number",
     *                  ),
     *                  @OA\Property(
     *                      property="application_from_date",
     *                      type="date",
     *                  ),
     *                  @OA\Property(
     *                      property="application_to_date",
     *                      type="date",
     *                  ),
     *                  @OA\Property(
     *                      property="notes",
     *                      type="string",
     *                  ),
     *                  @OA\Property(
     *                      property="attachment",
     *                      type="file",
     *                  ),
     *              ),
     *          )
     *      ),
     *      @OA\Response(
     *          response=201,
     *          description="Successful operation",
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
    public function store(Request $request)
    {
        try {
            $employeeId = Auth::user()->employee->employee_id;
            $employee = Employee::where('user_id', Auth::user()->id)->firstOrFail();
            $date = Carbon::now();
            $manager_id = Department::where('department_id', $employee->department_id)->first()->manager;
            $employeeManager = Employee::where('employee_id', $manager_id)->first();
            $userManager = User::find($employeeManager->user_id);

            $data = $request->all();

            $validate = Validator::make(
                $data,
                [
                    'leave_type_id' => 'required',
                    'option_request' => 'required_if:leave_type_id,==,1',
                    'option_leave_id' => 'required_if:leave_type_id,==,5',
                    'application_from_date' => 'required',
                    'application_to_date' => 'required',
                    'attachment' => 'required',
                    'notes' => 'required'
                ],
                [
                    'leave_type_id.required' => 'The type of leave field is required.',
                    'option_request.required_if' => 'The option request field is required.',
                    'option_leave_id.required_if' => 'The option leave field is required.',
                    'application_from_date.required' => 'The start date field is required.',
                    'application_to_date.required' => 'The end date field is required.',
                    'notes.required' => 'The reason for leave field is required.'
                ]
            );

            if ($validate->fails()) {
                $response = [
                    'errors' => $validate->errors()->toArray(),
                ];
                return ResponseFormatter::error($response, 'Something went wrong', 500);
            } else {
                $startDate = $data['application_from_date'];
                $parseStartDate = Carbon::parse($startDate);
                $startDateDummy = explode('-', $startDate);
                $startDateYearDummy = (int)$startDateDummy[0];
                $startDateMonthDummy = (int)$startDateDummy[1];
                $lastDateDummy = Carbon::create($startDateYearDummy, $startDateMonthDummy)->lastOfMonth()->format('Y-m-d');
                $parseLastDateDummy = Carbon::parse($lastDateDummy);

                $endDate = $data['application_to_date'];
                $parseEndDate = Carbon::parse($endDate);
                $endDateDummy = explode('-', $endDate);
                $endDateYearDummy = (int)$endDateDummy[0];
                $endDateMonthDummy = (int)$endDateDummy[1];
                $firstDateDummy = Carbon::create($endDateYearDummy, $endDateMonthDummy)->startOfMonth()->format('Y-m-d');
                $parseFirstDateDummy = Carbon::parse($firstDateDummy);

                $data['leave_application_id'] = LeaveApplication::max('leave_application_id') + 1;
                $data['employee_id'] = $employeeId;
                $data['application_date'] = $date;

                if ($data['leave_type_id'] == 1) {
                    $leavePeriod = LeavePeriod::whereDate('from_date', '<=', $startDate)->whereDate('to_date', '>=', $endDate)->first();

                    if ($leavePeriod == null) {
                        $count = 0;
                        $remainder = 0;
                        $leavePeriodCounters = LeavePeriod::whereYear('from_date', '<=', $startDateYearDummy)->whereYear('to_date', '>=', $endDateYearDummy)->get();

                        foreach ($leavePeriodCounters as $leavePeriodCounter) {
                            $leaveApplications = LeaveApplication::where('employee_id', '=', $employeeId)
                                ->where('leave_type_id', '=', 1)
                                ->where('leave_period_id', '=', $leavePeriodCounter['leave_period_id'])
                                ->where('state', '=', 'real')
                                ->where(function ($query) {
                                    $query->where('status', '=', 'lhy')->orWhere('status', '=', 'lmy');
                                })
                                ->get();

                            foreach ($leaveApplications as $leaveApplication) {
                                $count += $leaveApplication['number_of_day'];
                            }

                            $remainder = $leavePeriodCounter['limit'] - $count;

                            if ($remainder > 0) {
                                $data['leave_period_id'] = $leavePeriodCounter['leave_period_id'];

                                break;
                            }
                        }

                        if ($remainder <= 0) {
                            $data['leave_period_id'] = $leavePeriodCounters->max('leave_period_id');
                        }

                        $data['application_to_date'] = $lastDateDummy;
                        $data['number_of_day'] = $parseLastDateDummy->diffInDays($parseStartDate) + 1;
                        $data['status'] = '-';
                        $data['state'] = 'dummy';
                        $data['attachment'] = '-';

                        $this->leaveApplicationInterface->createLeaveApplication($data);

                        $data['application_from_date'] = $firstDateDummy;
                        $data['application_to_date'] = $endDate;
                        $data['number_of_day'] = $parseEndDate->diffInDays($parseFirstDateDummy) + 1;

                        $this->leaveApplicationInterface->createLeaveApplication($data);
                    } else {
                        $data['leave_period_id'] = $leavePeriod['leave_period_id'];
                    }
                }
                if (!$request->hasFile('attachment')) {
                    return ResponseFormatter::error([], 'File attachments doesn\'t uploaded', 400);
                }

                $leavePeriods = LeavePeriod::whereYear('from_date', Carbon::now())->whereYear('to_date', Carbon::now())->first('leave_period_id');
                $numberOfDay = Carbon::parse($data['application_from_date'])->diffInDays(Carbon::parse($data['application_to_date'])) + 1;

                $leaveBalance = $this->leaveApplicationInterface->countAnnualApprAndPend($data['leave_type_id'], $employeeId, $leavePeriods['leave_period_id']);

                if ($data['leave_type_id'] == 1) {
                    if ($leaveBalance <= $numberOfDay) {
                        return ResponseFormatter::error([
                            'modal-key' => 'out-of-qouta',
                            "remain_days_leave" => $leaveBalance,
                            "request_number_of_day" => $numberOfDay
                        ], 'Out Of Quota'); // TODO Message Error
                    }
                }

                $yearJoining = Carbon::createFromFormat('Y-m-d', $employee->date_of_joining);
                $yearDiff = Carbon::now()->diffInYears($yearJoining);

                if ($request->leave_type_id == 2 && $yearDiff <= 6) {
                    return ResponseFormatter::error([], 'You can\'t request Big Leave', 400);
                }

                if ($data['option_request'] == 1) {
                    // dd($numberOfDay > 1);
                    if ($numberOfDay > 1) {
                        return ResponseFormatter::error([
                            'modal-key' => 'half-day-only',
                            "request_number_of_day" => $numberOfDay
                        ], 'Half Day Only'); // TODO Message Error
                    }
                }

                $sickLetters = array();
                $file_path = [];
                $env = env('APP_URL');

                $files = $request->file('attachment');
                foreach ($files as $file) {
                    $extension = $file->getClientOriginalExtension();
                    $check = in_array($extension, ['pdf', 'jpg', 'png']);

                    if ($check) {
                        $name = Str::random("20") . "-" . $file->getClientOriginalName();
                        $file->move("uploads/leave-applications/", $name);
                        $sickLetters[] = $name;

                        $tempStr = "${env}/uploads/leave-applications/{$name}";

                        $obj = (object)[];
                        $obj->link = $tempStr;
                        $obj->filename = $name;

                        array_push($file_path, $obj);
                    } else {
                        return ResponseFormatter::error([], 'Invalid File Format', 422);
                    }
                }

                $sickLettersString = "leave-applications/" . implode("|", $sickLetters);


                $arr = [
                    'employee_id' => $employee->employee_id,
                    'leave_type_id' => $data['leave_type_id'],
                    'application_from_date' => $data['application_from_date'],
                    'application_to_date' => $data['application_to_date'],
                    'application_date' => Carbon::now()->toDateString(),
                    'option_request' => ($data['option_request'] === null) ? null : $data['option_request'],
                    'option_leave_id' => $data['option_leave_id'],
                    'notes' => $data['notes'],
                    'state' => 'real',
                    'leave_application_id' => LeaveApplication::max('leave_application_id') + 1,
                    'number_of_day' => $numberOfDay,
                    'attachment' => $sickLettersString
                ];

                $leaveApplication = LeaveApplication::create($arr);

                $arr['sick_letter'] = $file_path;

                $notification = Http::withHeaders([
                    'Content-Type' => 'application/json',
                    'Authorization' => 'Basic ' . env('ONESIGNAL_REST_API_KEY')
                ])->post(env('ONESIGNAL_URL'), [
                    'app_id' => env('ONESIGNAL_APP_ID'),
                    'android_channel_id' => env('ONESIGNAL_ANDROID_CHANNEL_ID'),
                    'small_icon' => "ic_stat_onesignal_default",
                    'include_external_user_ids' => [$userManager->email],
                    'channel_for_external_user_ids' => "push",
                    'data' => ["detail_id" => $leaveApplication->id],
                    'headings' => ["en" => "Leave Application"],
                    'contents' => ["en" => $employee->first_name . " " . $employee->last_name . " request approval for leave application"]
                ]);

                $createNotifications = [
                    "employee_id" => $employeeManager->employee_id,
                    "title" => "Leave Application",
                    "notif_type" => 'Leave',
                    "notif_status" => 'Pending',
                    "message" => $employee->first_name . " " . $employee->last_name . "request approval for leave application",
                    "send_time" => Carbon::now(),
                    "detail_id" => $leaveApplication->id,
                    "is_approval" => true,
                ];

                Notification::create($createNotifications);

                return ResponseFormatter::success($arr, 'Added Leave Success', 200);
            }
        } catch (Exception $e) {
            $response = [
                'errors' => $e->getMessage(),
                'line' => $e->getLine()
            ];
            return ResponseFormatter::error($response, 'Something went wrong', 500);
        }
    }

    /**
     * @OA\Get(
     *     path="/api/users/leave-tracker/{id}/edit",
     *     tags={"Leave Tracker"},
     *     summary="Getting Detail Leave Tracker by Id",
     *     operationId="getDetailLeaveTracker",
     *     security={{"bearerAuth":{}}}, 
     *     @OA\Parameter(
     *          name="id",
     *          description="Leave Tracker Id",
     *          required=true,
     *          in="path",
     *          @OA\Schema(
     *              type="integer"
     *          )
     *      ),
     *     @OA\Response(
     *         response="200",
     *         description="Successful operation"
     *     ),
     *     @OA\Response(
     *         response="204",
     *         description="No Content"
     *     ),
     *     @OA\Response(
     *         response="500",
     *         description="Something went wrong"
     *     )
     * )
     */
    public function show($id)
    {
        try {
            $leaveApplication = LeaveApplication::leftJoin('leave_types', 'leave_applications.leave_type_id', 'leave_types.leave_type_id')->where('leave_application_id', $id)->first();

            if ($leaveApplication === null) {
                return ResponseFormatter::error($leaveApplication, 'No data available at the moment', 204);
            }

            $statusCode = StatusCode::where('code', $leaveApplication->status)->first();

            $data = [
                "leave_application_id" => $id,
                "leave_type_id" => $leaveApplication->leave_type_id,
                "leave_type_name" => $leaveApplication->leave_type_name,
                "start_date" => $leaveApplication->application_from_date,
                "end_date" => $leaveApplication->application_to_date,
                "status" => $statusCode->long,
                "days_of_leave" => $leaveApplication->number_of_day,
                "notes" => $leaveApplication->notes,
                "attachment_path" => ($leaveApplication->attachment !== null) ? env('APP_URL') . "/uploads/$leaveApplication->attachment" : $leaveApplication->attachment,
                "date_from" => Carbon::parse($leaveApplication->application_date)->diffForHumans(),
                "approve_by" => ($leaveApplication->approve_by > 0) ? $this->employeeInterface->getEmployeeNameById($leaveApplication->approve_by) : $leaveApplication->approve_by,
                "approve_date" => $leaveApplication->approve_date,
                "cancel_by" => ($leaveApplication->cancel_by > 0) ? $this->employeeInterface->getEmployeeNameById($leaveApplication->cancel_by) : $leaveApplication->cancel_by,
                "cancel_reason" => $leaveApplication->cancel_reason,
                "cancel_date" => $leaveApplication->cancel_date,
                "close_by" => ($leaveApplication->close_by > 0) ? $this->employeeInterface->getEmployeeNameById($leaveApplication->close_by) : $leaveApplication->close_by,
                "close_date" => $leaveApplication->close_date,
                "reject_by" => ($leaveApplication->reject_by > 0) ? $this->employeeInterface->getEmployeeNameById($leaveApplication->reject_by) : $leaveApplication->reject_by,
                "reject_date" => $leaveApplication->reject_date,
                "reject_reason" => $leaveApplication->reject_reason,
            ];

            return ResponseFormatter::success($data, 'Added Leave Success', 200);
        } catch (Exception $e) {
            $response = [
                'errors' => $e->getMessage(),
            ];
            return ResponseFormatter::error($response, 'Something went wrong', 500);
        }
    }

    /**
     * @OA\Post(
     *     path="/api/leave-tracker/approve/{id}",
     *     tags={"Leave Tracker"},
     *     summary="Approving leave",
     *     operationId="approveLeave",
     *     security={{"bearerAuth":{}}}, 
     *     @OA\Parameter(
     *          name="id",
     *          description="Leave Id",
     *          required=true,
     *          in="path",
     *          @OA\Schema(
     *              type="integer"
     *          )
     *      ),
     *     @OA\Response(
     *         response="200",
     *         description="Leave Approved"
     *     ),
     *     @OA\Response(
     *         response="500",
     *         description="Something went wrong"
     *     )
     * )
     */
    public function approve($id)
    {
        try {
            $user = Auth::user();

            $validated = $this->leaveApplicationInterface->getLeaveApplicationByid($id)->first();
            if ($validated === null) return ResponseFormatter::error([], 'Data was not found', 204);

            if ($validated->employee_id === $user->employee->employee_id) return ResponseFormatter::error([], 'You cannot approve your leave.', 400);

            $approved = $this->leaveApplicationInterface->approveLeaveApplication($id);

            $data = [
                'cancel_by' => null,
                'cancel_date' => null,
                'reject_by' => null,
                'reject_date' => null,
                'reject_reason' => null
            ];

            $updated = $this->leaveApplicationInterface->updateLeaveApplication($id, $data);

            $employeeNotif = Employee::where('employee_id', $validated->employee_id)->first();
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
                'data' => ["detail_id" => $validated->leave_application_id],
                'headings' => ["en" => "Leave Application"],
                'contents' => ["en" => "Your request for leave application has been approved"]
            ]);

            $createNotifications = [
                "employee_id" => $employeeNotif->employee_id,
                "title" => "Leave Application",
                "notif_type" => 'Leave',
                "notif_status" => 'Approve',
                "message" => "Your request for leave application has been approved",
                "send_time" => Carbon::now(),
                "detail_id" =>  $validated->leave_application_id,
                "is_approval" => false,
            ];

            Notification::create($createNotifications);

            return ResponseFormatter::success([
                "leave_id" => $id,
                "approver" => [
                    "id" => $user->id,
                    "email" => $user->email,
                    "role" => $user->roles[0]->name
                ]

            ], 'Leave was approved');

            // $data = $this->leaveApplicationInterface
            //     ->getAllLeaveApplications()
            //     ->where('leave_application_id', $id)
            //     ->first();
            // $employee = Auth::user();
            // $role = $employee->getRoleNames();
            // $authorized_role = array("Manager", "Human Resource", "Super Admin");

            // if (in_array($role[0], $authorized_role)) {
            //     $user_id = $employee->id;
            //     if ($role[0] == 'Manager') {
            //         $status = 'lmy';
            //     } elseif ($role[0] == 'Human Resource') {
            //         $status = 'lhy';
            //     } elseif ($role[0] == 'Super Admin') {
            //         $status = 'lhy';
            //     }

            //     if ($data) {
            //         if ($data->remarks != null) {
            //             throw new Exception("Leave have already Rejected");
            //         }
            //         if ($data->approve_date != null) {
            //             throw new Exception("Leave have already approved");
            //         }
            //         $data->where('leave_application_id', $id)->update([
            //             'status' => $status,
            //             'approve_date' => Carbon::now(),
            //             'approve_by' => $user_id,
            //         ]);
            //         $data = [
            //             'leave_application_id' => $data->leave_application_id,
            //             'employee_id' => $data->employee_id,
            //             'leave_type_name' => $data->leave_type_name,
            //             'application_from_date' => $data->application_from_date,
            //             'application_to_date' => $data->application_to_date,
            //             'application_date' => $data->application_date,
            //             'purpose' => $data->purpose,
            //             'status' => $status,
            //             'approve_date' => Carbon::now(),
            //             'approve_by' => $user_id,
            //         ];
            //         $approved = [
            //             "approved" => $data,
            //         ];
            //         return ResponseFormatter::success($approved, 'Leave Approved');
            //     } else {
            //         throw new Exception("Data not found");
            //     }
            // } else {
            //     throw new Exception("Role not authorized");
            // }
        } catch (Exception $error) {
            return ResponseFormatter::error([
                'error' => $error->getMessage()
            ], 'Something went wrong', 500);
        }
    }


    /**
     * @OA\Post(
     *     path="/api/leave-tracker/cancel/{id}",
     *     tags={"Leave Tracker"},
     *     summary="Canceling leave",
     *     operationId="cancel",
     *     security={{"bearerAuth":{}}}, 
     *     @OA\Parameter(
     *          name="id",
     *          description="Leave id",
     *          required=true,
     *          in="path",
     *          @OA\Schema(
     *              type="integer"
     *          )
     *      ),
     *      @OA\RequestBody(
     *       @OA\MediaType(
     *           mediaType="multipart/form-data",
     *           @OA\Schema(
     *               type="object", 
     *               @OA\Property(
     *                  property="reason",
     *                  type="string",
     *               ),
     *           ),
     *       )
     *   ),
     *     @OA\Response(
     *         response="200",
     *         description="Leave Canceled"
     *     ),
     *     @OA\Response(
     *         response="500",
     *         description="Something went wrong"
     *     )
     * )
     */
    public function cancel($id)
    {
        try {
            $user = Auth::user();

            $validated = $this->leaveApplicationInterface->getLeaveApplicationByid($id)->first();
            if ($validated === null) return ResponseFormatter::error([], 'Data was not found', 204);

            if ($validated->employee_id !== $user->employee->employee_id) return ResponseFormatter::error([], 'You are only can cancel your leave.', 400);

            // if status not pending
            if ($validated->status !== 'lpd') return ResponseFormatter::error([], 'You cannot canceled your approved or rejected leaves.', 400);

            $deleted = $this->leaveApplicationInterface->cancelLeave($id);
            if ($deleted === 0) throw new Exception("Data cannot deleted.");

            return ResponseFormatter::success([
                "id" => $id
            ], 'Leave was canceled');

            // $role = $employee->getRoleNames();
            // $authorized_role = array("Manager", "Human Resource", "Super Admin");


            // if (in_array($role[0], $authorized_role)) {


            //     if ($data != null) {
            //         if ($data->remarks != null) {
            //             throw new Exception("Leave have already canceled");
            //         }
            //         $validate = Validator::make(
            //             $request->all(),
            //             [
            //                 'reason' => 'required',
            //             ],
            //             [
            //                 'reason.required' => 'The reason field is required.',
            //             ]
            //         );
            //         $employee = Auth::user();
            //         // $role = $employee->getRoleNames();
            //         $user_id = $employee->id;
            //         $status = 'can';
            //         $reject_date = 'reject_date';
            //         if ($validate->fails()) {
            //             throw new Exception("The reason field is required");
            //         }

            //         $data->where('leave_application_id', $id)->update([
            //             'status' => $status,
            //             'reject_date' => Carbon::now(),
            //             'reject_by' => $user_id,
            //             $reject_date => Carbon::now(),
            //             'cancel_reason' => $request->reason
            //         ]);

            //         $data = [
            //             'leave_application_id' => $data->leave_application_id,
            //             'employee_id' => $data->employee_id,
            //             'leave_type_name' => $data->leave_type_name,
            //             'application_from_date' => $data->application_from_date,
            //             'application_to_date' => $data->application_to_date,
            //             'application_date' => $data->application_date,
            //             'reason_canceled' => $request->reason,
            //         ];
            //         $canceled = [
            //             "canceled" => $data,
            //         ];

            //         return ResponseFormatter::success($canceled, 'Leave canceled');
            //     } else {
            //         throw new Exception("Data not found");
            //     }
            // } else {
            //     throw new Exception("Role not authorized");
            // }
        } catch (Exception $error) {
            return ResponseFormatter::error([
                'error' => $error->getMessage()
            ], 'Something went wrong', 500);
        }
    }

    /**
     * @OA\Post(
     *     path="/api/leave-tracker/reject/{id}",
     *     tags={"Leave Tracker"},
     *     summary="Rejecting leave",
     *     operationId="rejectLeave",
     *     security={{"bearerAuth":{}}}, 
     *     @OA\Parameter(
     *          name="id",
     *          description="Leave id",
     *          required=true,
     *          in="path",
     *          @OA\Schema(
     *              type="integer"
     *          )
     *      ),
     *      @OA\RequestBody(
     *       @OA\MediaType(
     *           mediaType="multipart/form-data",
     *           @OA\Schema(
     *               type="object", 
     *               @OA\Property(
     *                  property="reason",
     *                  type="string",
     *               ),
     *           ),
     *       )
     *   ),
     *     @OA\Response(
     *         response="200",
     *         description="Leave Rejected"
     *     ),
     *     @OA\Response(
     *         response="500",
     *         description="Something went wrong"
     *     )
     * )
     */
    public function reject(Request $request, $id)
    {
        try {
            $user = Auth::user();

            $validate = Validator::make(
                $request->all(),
                [
                    'reason' => 'required',
                ],
                [
                    'reason.required' => 'The reason field is required.',
                ]
            );

            if ($validate->fails()) {
                throw new Exception("The reason field is required");
            }

            $validated = $this->leaveApplicationInterface->getLeaveApplicationByid($id)->first();
            if ($validated === null) return ResponseFormatter::error([], 'Data was not found', 204);

            if ($validated->employee_id === $user->employee->employee_id) return ResponseFormatter::error([], 'You cannot approve your leave.', 400);

            $approved = $this->leaveApplicationInterface->rejectLeaveApplication($id, $request->reason);

            $data = [
                'cancel_by' => null,
                'cancel_date' => null,
                'approve_by' => null,
                'approve_date' => null,
            ];

            $updated = $this->leaveApplicationInterface->updateLeaveApplication($id, $data);

            $employeeNotif = Employee::where('employee_id', $validated->employee_id)->first();
            $userNotif = User::where('id', Auth::user()->employee->employee_id)->first();

            Http::withHeaders([
                'Content-Type' => 'application/json',
                'Authorization' => 'Basic ' . env('ONESIGNAL_REST_API_KEY')
            ])->post(env('ONESIGNAL_URL'), [
                'app_id' => env('ONESIGNAL_APP_ID'),
                'android_channel_id' => env('ONESIGNAL_ANDROID_CHANNEL_ID'),
                'small_icon' => "ic_stat_onesignal_default",
                'include_external_user_ids' => [$userNotif->email],
                'channel_for_external_user_ids' => "push",
                'data' => ["detail_id" => $validated->leave_application_id],
                'headings' => ["en" => "Leave Application"],
                'contents' => ["en" => "Your request for leave application has been rejected"]
            ]);

            $createNotifications = [
                "employee_id" => $employeeNotif->employee_id,
                "title" => "Leave Application",
                "notif_type" => 'Leave',
                "notif_status" => 'Reject',
                "message" => "Your request for leave application has been rejected",
                "send_time" => Carbon::now(),
                "detail_id" =>  $validated->leave_application_id,
                "is_approval" => false,
            ];

            Notification::create($createNotifications);

            return ResponseFormatter::success([
                "leave_id" => $id,
                "reason" => $request->reason,
                "approver" => [
                    "id" => $user->id,
                    "email" => $user->email,
                    "role" => $user->roles[0]->name
                ]

            ], 'Leave was rejected');

            //     $data = $this->leaveApplicationInterface
            //         ->getAllLeaveApplications()
            //         ->where('leave_application_id', $id)
            //         ->first();
            //     $employee = Auth::user();
            //     $role = $employee->getRoleNames();
            //     $authorized_role = array("Manager", "Human Resource", "Super Admin");

            //     if (in_array($role[0], $authorized_role)) {
            //         if ($data != null) {
            //             if ($data->remarks != null) {
            //                 throw new Exception("Leave have already rejected");
            //             }

            //             $validate = Validator::make(
            //                 $request->all(),
            //                 [
            //                     'reason' => 'required',
            //                 ],
            //                 [
            //                     'reason.required' => 'The reason field is required.',
            //                 ]
            //             );

            //             if ($validate->fails()) {
            //                 throw new Exception("The reason field is required");
            //             }

            //             $user_id = $employee->id;
            //             if ($role[0] == 'Manager') {
            //                 $status = 'lmn';
            //             } elseif ($role[0] == 'Human Resource') {
            //                 $status = 'lhn';
            //             } elseif ($role[0] == 'Super Admin') {
            //                 $status = 'lhn';
            //             }
            //             $data->where('leave_application_id', $id)->update([
            //                 'status' => $status,
            //                 'reject_date' => Carbon::now(),
            //                 'reject_by' => $user_id,
            //                 'reject_reason' => $request->reason
            //             ]);

            //             $data = [
            //                 'leave_application_id' => $data->leave_application_id,
            //                 'employee_id' => $data->employee_id,
            //                 'leave_type_name' => $data->leave_type_name,
            //                 'application_from_date' => $data->application_from_date,
            //                 'application_to_date' => $data->application_to_date,
            //                 'application_date' => $data->application_date,
            //                 'reason_rejected' => $request->reason,
            //             ];
            //             $rejected = [
            //                 "rejected" => $data,
            //             ];

            //             return ResponseFormatter::success($rejected, 'Leave rejected');
            //         } else {
            //             throw new Exception("Data not found");
            //         }
            //     } else {
            //         throw new Exception("Role not authorized");
            //     }
        } catch (Exception $error) {
            return ResponseFormatter::error([
                'error' => $error->getMessage()
            ], 'Something went wrong', 500);
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    public function getAllLeaveApplicationListsAppr()
    {
        try {
            $user = Auth::user();
            $employee = Employee::where('user_id', $user->id)->join('department', 'employees.department_id', '=', 'department.department_id')->firstOrFail();
            $env = env('APP_URL');
            $leaveApplicationsList = $this->leaveApplicationInterface->getAllLeaveApplicationListsAppr($employee->branch_id, $employee->employee_id)
                ->join('department', 'employees.department_id', '=', 'department.department_id')
                ->where('department.code', '=', $employee->code)
                ->where('status_codes.code', '=', 'lpd')

                ->get([
                    'leave_applications.leave_application_id',
                    'leave_types.leave_type_name',
                    'employees.first_name',
                    'employees.last_name',
                    'leave_applications.application_date',
                    'leave_applications.notes',
                    'leave_applications.attachment as file_path',
                    'status_codes.short as status',
                ]);

            if (count($leaveApplicationsList) <= 0) return ResponseFormatter::success([], 'No data available at the moment', 204);

            foreach ($leaveApplicationsList as $list) {

                $file_path = [];

                if ($list->file_path !== null) {
                    $charPos = strpos($list->file_path, '/') + 1;
                    $temp = substr($list->file_path, $charPos);
                    $exp = explode('|', $temp);

                    foreach ($exp as $e) {
                        $tempStr = "${env}/uploads/leave-applications/{$e}";

                        $obj = (object)[];
                        $obj->link = $tempStr;
                        $obj->filename = $e;

                        array_push($file_path, $obj);

                        unset($tempStr);
                    }
                }

                $list->file_path = $file_path;
            }

            return ResponseFormatter::success($leaveApplicationsList, 'Get Leave Application Success');
        } catch (Exception $error) {
            return ResponseFormatter::error([
                'error' => $error->getMessage()
            ], 'Something went wrong', 500);
        }
    }

    public function getLeaveApplicationsId($Id)
    {
        try {
            $user = Auth::user();
            $employee = Employee::where('user_id', $user->id)->firstOrFail();
            $leaveApplications = $this->leaveApplicationInterface->getLeaveApplicationsById($Id);
            $env = env('APP_URL');

            $leaveApplicationsAppr = (object)[];

            if ($leaveApplications === null) {
                $response = [
                    'application_from_date' => 0,
                    'application_to_date' => 0,
                    'status' => null,
                    'purpose' => null,
                    'application_date' => 0
                ];

                return ResponseFormatter::error($response, 'No data available at the moment', 204);
            }

            // * Catatan : seharusnya logicnya tidak seperti itu, karena module leave fix maka saya buat seadanya.
            if ($leaveApplications->status === 'lmy' || $leaveApplications->status === 'lhy') {
                $leaveApplicationsAppr = $this->employeeInterface->getEmployeeById($leaveApplications->approve_by)
                    ->join('job_positions', 'employees.job_position_id', '=', 'job_positions.job_position_id')
                    ->first([
                        'employees.user_id',
                        'employees.employee_id',
                        'employees.first_name',
                        'employees.last_name',
                        'employees.image',
                        'job_positions.job_position',
                    ]);

                $leaveApplicationsAppr->image = $this->employeeInterface->getEmployeePicture($leaveApplicationsAppr->user_id);
                Carbon::setLocale('en');
                $leaveApplicationsAppr->action_date = $leaveApplications->approve_date;
                if(Carbon::parse($leaveApplications->approve_date)->isToday()){
                    $leaveApplicationsAppr->dateDiff = "Today";
                }
                else{
                    $date = Carbon::createFromFormat('Y-m-d',  $leaveApplications->approve_date)->diffForHumans();
                    $leaveApplicationsAppr->dateDiff = $date;
                    if (str_contains($date, 'before')) {
                        $leaveApplicationsAppr->dateDiff = str_replace("before","ago",$date);
                    }
                }
                $leaveApplicationsAppr->reason = null;
            }

            if ($leaveApplications->status === 'lhn' || $leaveApplications->status === 'lmn') {
                $leaveApplicationsAppr = $this->employeeInterface->getEmployeeById($leaveApplications->reject_by)
                    ->join('job_positions', 'employees.job_position_id', '=', 'job_positions.job_position_id')
                    ->first([
                        'employees.user_id',
                        'employees.employee_id',
                        'employees.first_name',
                        'employees.last_name',
                        'employees.image',
                        'job_positions.job_position',
                    ]);

                $leaveApplicationsAppr->image = $this->employeeInterface->getEmployeePicture($leaveApplicationsAppr->user_id);
                Carbon::setLocale('en');
                $leaveApplicationsAppr->action_date = $leaveApplications->reject_date;
                if (Carbon::parse($leaveApplications->reject_date)->isToday()) {
                    $leaveApplicationsAppr->dateDiff = "Today";
                } else {
                    $date = Carbon::createFromFormat('Y-m-d',  $leaveApplications->reject_date)->diffForHumans();
                    $leaveApplicationsAppr->dateDiff = $date;
                    if (str_contains($date, 'before')) {
                        $leaveApplicationsAppr->dateDiff = str_replace("before", "ago", $date);
                    }
                }
            }

            $employee->image = $this->employeeInterface->getEmployeePicture($employee->user_id);

            if ($leaveApplications->attachment !== null) {
                if ($leaveApplications->attachment !== null) {
                    $ext = explode('/', $leaveApplications->attachment);
                    $exp = explode('|', $ext[1]);

                    $obj = [];
                    foreach ($exp as $oh) {
                        $obj[] = [
                            'link' => asset('uploads/' . $ext[0] . '/' . $oh),
                            'filename' => $oh
                        ];
                    }
                }
                $leaveApplications->attachment = $obj;
            }

            return ResponseFormatter::success([
                'requester' => [
                    'user_id' => $leaveApplications->user_id,
                    'employee_id' => $leaveApplications->employee_id,
                    'first_name' => $leaveApplications->first_name,
                    'last_name' => $leaveApplications->last_name,
                    'image' => $employee->image,
                    'department' => $leaveApplications->department_name,
                    'job_position' => $leaveApplications->job_position
                ],
                'type_of_leave' => $leaveApplications->leave_type_name,
                'total_leave_days' => $leaveApplications->number_of_day,
                'application_from_date' => $leaveApplications->application_from_date,
                'application_to_date' => $leaveApplications->application_to_date,
                'application_date' => $leaveApplications->application_date,
                'status' => $leaveApplications->short,
                'notes' => $leaveApplications->notes,
                'files' => 
                    $leaveApplications->attachment
                ,
                'approver' => $leaveApplicationsAppr,
            ], 'Get Leave Application Detail Success');
        } catch (Exception $error) {
            return ResponseFormatter::error([
                'error' => $error->getMessage(),
                'line' => $error->getLine()
            ], 'Something went wrong', 500);
        }
    }
}
