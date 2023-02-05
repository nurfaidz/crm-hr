<?php

namespace App\Http\Controllers;

use App\Interfaces\OvertimeInterface;
use App\Interfaces\ConfigInterface;
use App\Interfaces\AttendanceInterface;
use App\Interfaces\EmployeeInterface;
use App\Models\Attendance;
use App\Models\Employee;
use App\Models\Notification;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\Facades\DataTables;
use Carbon\Carbon;
use Exception;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Validator;

use function PHPUnit\Framework\isNull;

class OvertimeApprovalController extends Controller
{
    private $overtimeInterface;
    private $attendanceInterface;
    private $configInterface;
    private $employeeInterface;

    public function __construct(
        OvertimeInterface $overtimeInterface,
        AttendanceInterface $attendanceInterface,
        ConfigInterface $configInterface,
        EmployeeInterface $employeeInterface
    ) {
        $this->overtimeInterface = $overtimeInterface;
        $this->attendanceInterface = $attendanceInterface;
        $this->configInterface = $configInterface;
        $this->employeeInterface = $employeeInterface;
        $this->middleware('role_or_permission:Super Admin|overtime.approve', ['only' => ['approveOvertime']]);
        $this->middleware('role_or_permission:Super Admin|overtime.reject', ['only' => ['rejectOvertime']]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    public function get_overtime()
    {
        $user = Auth::user()->employee->employee_id;
        $employee = $this->employeeInterface->getEmployeeById($user)
            ->join('department', 'employees.department_id', '=', 'department.department_id')
            ->first();

        $overtimes = $this->overtimeInterface->getOvertimeAllPending($employee->branch_id, $employee->employee_id)
            ->where('code', '=', $employee->code);
        return DataTables::of($overtimes)
            ->addIndexColumn()
            ->addColumn('employee_image', function ($data) {
                return $this->employeeInterface->getEmployeePicture($data->user_id);
            })
            ->addColumn('application_date', function ($data) {
                return  date('d/m/Y', strtotime($data->created_at));
            })
            ->addColumn('overtime_date', function ($data) {
                return  date('d/m/Y', strtotime($data->date));
            })
            ->addColumn('status', function ($data) {

                $description = $data->status_desc;

                return '<div class="badge badge-pill badge-light-warning">' . $description . '</div>';
                // switch ($data->status_code) {
                // case 'can':
                //     return '<div class="badge badge-pill badge-light-secondary">' . $description . '</div>';
                //     break;
                // case 'cls':
                //     return '<div class="badge badge-pill badge-light-info">' . $description . '</div>';
                //     break;
                // case 'lhy':
                //     return '<div class="badge badge-pill badge-light-success">' . $description . '</div>';
                //     break;
                // case 'lhn':
                //     return '<div class="badge badge-pill badge-light-danger">' . $description . '</div>';
                //     break;
                // case 'lmn':
                //     return '<div class="badge badge-pill badge-light-danger">' . $description . '</div>';
                //     break;
                // case 'lmy':
                //     return '<div class="badge badge-pill badge-light-success">' . $description . '</div>';
                //     break;
                // default:
                //     return '<div class="badge badge-pill badge-light-warning">' . $description . '</div>';
                //     break;
                // }
            })
            ->addColumn('notes', function ($data) {
                return '
                <a class="btn btn-flat-primary" onclick=OvertimeNotesDetail($(this)) data-id="' . $data->overtime_id . '" href="javascript:void(0);">
                    Notes
                </a>';
            })
            ->addColumn('action', function ($data) {
                return '
                <a class="btn btn-outline-info round waves-effect button-rounded" href="' . url('approval/overtime') . '/' . $data->overtime_id . '/' . 'details' . '">
                    Details
                </a>';
            })
            ->rawColumns(['status', 'notes', 'action'])
            ->toJson(true);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    public function get_overtime_details($id)
    {
        $overtime = $this->overtimeInterface->getOvertimeEmployeeById($id);
        $quota = $this->configInterface->overtimeQoutas();

        $now = Carbon::now();
        $remainingOvertime = $this->overtimeInterface->calculateRemainingOvertimeOnWeeks(Auth::user()->id, $quota, $now);

        // if ($overtime) {
        //     return response()->json(["error" => false, "data" => $overtime->notes]);
        // } else {
        //     return response()->json(["error" => true, "message" => "Data Not Found"]);
        // }

        return view('approval.overtime-show', compact('overtime', 'remainingOvertime'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
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

    public function approveOvertime($id)
    {
        try {
            $overtime       = $this->overtimeInterface;
            $overtimebyid   = $overtime->getOvertimeById($id);
            $attendances    = Attendance::where('employee_id', '=', $overtimebyid->employee_id)
                ->orderBy('date', 'DESC')
                ->get();

            $today           = date('Y-m-d');
            $attendanceId    = null;
            $attendanceDate  = null;

            foreach ($attendances as $attendance) {
                if ($attendance->date === $today) {
                    $attendanceId = $attendance->id;
                }

                $attendanceDate[] = $attendance->date;
            }

            if (is_Null($attendanceDate)) {
                return response()->json([
                    "error"   => true,
                    "message" => "No Employee Attendance Found!"
                ]);
            }

            $attendanceToday = null;

            for ($i = 0; $i < count($attendanceDate); $i++) {
                if ($attendanceDate[$i] === $today) {
                    $attendanceToday = $attendanceDate[$i];
                    break;
                }
            }

            if ($attendanceToday === $overtimebyid->date) {
                $overtime->updateOvertime($id, [
                    'status'       => 'oap',
                    'update_time'  => Carbon::now(),
                    'update_by'    => Auth::user()->employee->employee_id,
                ]);

                $this->attendanceInterface->updateAttendance($attendanceId, ['overtime_id' => $id]);

                $employeeNotif = Employee::where('employee_id', $overtimebyid->employee_id)->first();
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
                    'data' => ["detail_id" => $overtimebyid->overtime_id],
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
                    "detail_id" => $overtimebyid->overtime_id,
                    "is_approval" => false,
                ];

                Notification::create($createNotifications);

                return response()->json([
                    "error"   => false,
                    "message" => "Approved Overtime Request"
                ]);
            } else {
                return response()->json([
                    "error"   => true,
                    "message" => "You can only approve in the same day of request!"
                ]);
            }
        } catch (Exception $e) {
            return response()->json(["error" => true, "message" => $e->getMessage()]);
        }
    }

    public function rejectOvertime(Request $request, $id)
    {
        try {
            $requestOnly       = $request->only('reason');
            $rules             = ['reason' => 'required'];

            $validatedData = Validator::make($requestOnly, $rules);
            if ($validatedData->fails()) {
                return response()->json([
                    'error' => $validatedData->errors()->toArray()
                ]);
            }

            $overtime       = $this->overtimeInterface;
            $overtimebyid   = $overtime->getOvertimeById($id);
            $attendances    = Attendance::where('employee_id', '=', $overtimebyid->employee_id)
                ->get();

            $today           = date('Y-m-d');
            $attendanceId    = null;
            $attendanceDate  = null;

            foreach ($attendances as $attendance) {
                if ($attendance->date === $today) {
                    $attendanceId = $attendance->id;
                }

                $attendanceDate[] = $attendance->date;
            }

            if (is_Null($attendanceDate)) {
                return response()->json([
                    "error"   => true,
                    "message" => "No Employee Attendance Found!"
                ]);
            }

            $attendanceToday = null;

            for ($i = 0; $i < count($attendanceDate); $i++) {
                if ($attendanceDate[$i] === $today) {
                    $attendanceToday = $attendanceDate[$i];
                    break;
                }
            }

            if ($attendanceToday === $overtimebyid->date) {
                $overtime->updateOvertime($id, [
                    'status'        => 'orj',
                    'update_time'   => Carbon::now(),
                    'update_by'     => Auth::user()->employee->employee_id,
                    'reject_reason' => $request->reason,
                ]);

                $employeeNotif = Employee::where('employee_id', $overtimebyid->employee_id)->first();
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
                    'data' => ["detail_id" => $overtimebyid->overtime_id],
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
                    "detail_id" => $overtimebyid->overtime_id,
                    "is_approval" => false,
                ];

                Notification::create($createNotifications);
                return response()->json(["error" => false, "message" => "Rejected Overtime Request"]);
            } else {
                return response()->json(["error" => true, "message" => "You can only reject in the same day of request!"]);
            }
        } catch (Exception $e) {
            return response()->json(["error" => true, "message" => $e->getMessage()]);
        }
    }
}
