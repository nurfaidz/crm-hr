<?php

namespace App\Http\Controllers;

use App\Repositories\AttendanceRepository;
use App\Repositories\EmployeeRepository;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\Auth;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use Illuminate\Support\Facades\Validator;
use App\Models\Employee;
use App\Models\Notification;
use App\Models\User;
use Carbon\Carbon;
use Exception;
use Illuminate\Support\Facades\Http;

class ManualAttendanceApprovalController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    private AttendanceRepository $attendanceRepository;
    private EmployeeRepository $employeeRepository;

    public function __construct(
        AttendanceRepository $attendanceRepository,
        EmployeeRepository $employeeRepository
    ) {
        $this->attendanceRepository = $attendanceRepository;
        $this->employeeRepository = $employeeRepository;
        $this->middleware('role_or_permission:Super Admin|manual_attendance.approve', ['only' => ['ManualAttendanceApprove']]);
        $this->middleware('role_or_permission:Super Admin|manual_attendance.reject', ['only' => ['ManualAttendanceReject']]);
        $this->middleware('role_or_permission:Super Admin|manual_attendance.cancel', ['only' => ['ManualAttendanceCancel']]);
        $this->middleware('role_or_permission:Super Admin|manual_attendance.finish', ['only' => ['ManualAttendanceFinish']]);
    }

    public function index()
    {
        //
    }

    public function get_manual_attendance()
    {
        $user            = Auth::user()->employee->employee_id;
        $employee        = $this->employeeRepository->getEmployeeById($user)
            ->join('department', 'employees.department_id', '=', 'department.department_id')
            ->first();


        $attendances     = $this->attendanceRepository->getAllManualAttendance($employee->branch_id, $employee->employee_id)
            ->join('job_positions', 'employees.job_position_id', '=', 'job_positions.job_position_id')
            ->where('department.code', '=', $employee->code)
            ->where('status_codes.code', '=', 'apd')
            // ->orWhere('status_codes.code', '=', 'amw')
            // ->orWhere('status_codes.code', '=', 'amm')
            // ->orWhere('status_codes.code', '=', 'arj')
            ->where('status_codes.code', '!=', 'cls')
            ->get([
                'attendances.id',
                'employees.employee_id',
                'employees.user_id',
                'employees.first_name',
                'employees.last_name',
                'employees.nip',
                'branches.branch_name',
                'department.department_name',
                'job_positions.job_position',
                'attendances.date',
                'attendances.status_date',
                'attendances.check_in',
                'attendances.check_out',
                'status_codes.code as status_code',
                'status_codes.short as status_desc',
            ]);

        return datatables::of($attendances)
            ->addIndexColumn()
            ->addColumn('employee_image', function ($data) {
                return $this->employeeRepository->getEmployeePicture($data['user_id']);
            })
            ->addColumn('status', function ($data) {
                switch ($data->status_code) {
                    case 'can':
                        return '<div class="badge badge-pill badge-light-secondary">' . $data->status_desc . '</div>';
                        break;
                    case 'amw':
                        return '<div class="badge badge-pill badge-light-success">Approved</div>';
                        break;
                    case 'amw':
                        return '<div class="badge badge-pill badge-light-success">' . $data->status_desc . '</div>';
                        break;
                    case 'amm':
                        return '<div class="badge badge-pill badge-light-success">' . $data->status_desc . '</div>';
                        break;
                    case 'arj':
                        return '<div class="badge badge-pill badge-light-danger">' . $data->status_desc . '</div>';
                        break;
                    default:
                        return '<div class="badge badge-pill badge-light-warning">' . $data->status_desc . '</div>';
                        break;
                }
            })
            ->addColumn('action', function ($data) {
                return '<a href="approval/manual-attendance/details/' . $data->id . '" id="pending-status-cancel" class="btn btn-outline-info round pending-status-cancel d-flex justify-content-center align-items-center">Details</a>';
            })
            ->rawColumns(['status', 'action'])
            ->toJson(true);
    }

    public function get_manual_attendance_details($id)
    {
        $where = array('id' => $id);
        $user            = Auth::user();
        $employeeIsLoggedInUser = Employee::where('user_id', $user->id);
        $shift = $employeeIsLoggedInUser->join('work_shifts', 'employees.work_shift_id', '=', 'work_shifts.work_shift_id')->first();
        $employee        = $this->employeeRepository->getEmployeeById($user->id)
            ->join('department', 'employees.department_id', '=', 'department.department_id')
            ->first();
        $data = $this->attendanceRepository->getAllAttendance()
            ->join('job_positions', 'employees.job_position_id', '=', 'job_positions.job_position_id')
            ->where('department.code', '=', $employee->code)
            ->where('status_codes.code', '=', 'apd')
            ->where($where)->first();
        if ($data == null) {
            $data = $this->attendanceRepository->getAllAttendance()
                ->join('job_positions', 'employees.job_position_id', '=', 'job_positions.job_position_id')
                ->where('department.code', '=', $employee->code)
                ->where('status_codes.code', '=', 'amw')
                ->where($where)->first();
        }
        if ($data == null) {
            $data = $this->attendanceRepository->getAllAttendance()
                ->join('job_positions', 'employees.job_position_id', '=', 'job_positions.job_position_id')
                ->where('department.code', '=', $employee->code)
                ->where('status_codes.code', '=', 'arj')
                ->where($where)->first();
        }

        $approver = Employee::where('user_id', '=', $data['status_by'])->first();
        $rejecter = Employee::where('user_id', '=', $data['status_by'])->first();

        //    dd($data);
        return view('approval.attendance-details', compact('data', 'approver', 'rejecter', 'shift'));
    }

    public function get_manual_attendance_excel()
    {
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        $user = Auth::user();
        $employee = $this->employeeRepository->getEmployeeById($user->id)
            ->join('department', 'employees.department_id', '=', 'department.department_id')
            ->first();

        $attendance = $this->attendanceRepository->getAllAttendance()
            ->join('job_positions', 'employees.job_position_id', '=', 'job_positions.job_position_id')
            ->leftjoin('employees as emp', 'attendances.status_by', '=', 'emp.employee_id')
            ->where('department.code', '=', $employee->code)
            ->where('status_codes.code', '=', 'apd')
            ->orWhere('status_codes.code', '=', 'amw')
            ->orWhere('status_codes.code', '=', 'amm')
            ->orWhere('status_codes.code', '=', 'arj')
            ->where('status_codes.code', '!=', 'cls')
            ->get([
                'attendances.id',
                'employees.employee_id',
                'employees.first_name',
                'employees.last_name',
                'branches.branch_name',
                'department.department_name',
                'job_positions.job_position',
                'attendances.date',
                'attendances.check_in',
                'attendances.check_out',
                'status_codes.code as status_code',
                'status_codes.short as status_desc',
                'emp.first_name as f_name',
                'emp.last_name as l_name',
                'attendances.status_date as approve_date'
            ]);

        $sheet->setCellValue('A1', 'Employee ID')
            ->setCellValue('B1', 'Employee Name')
            ->setCellValue('C1', 'Entity')
            ->setCellValue('D1', 'SBU')
            ->setCellValue('E1', 'Position')
            ->setCellValue('F1', 'Date')
            ->setCellValue('G1', 'Check In')
            ->setCellValue('H1', 'Check Out')
            ->setCellValue('I1', 'Status')
            ->setCellValue('J1', 'Approve By')
            ->setCellValue('K1', 'Approve Date');

        $i = 2;
        foreach ($attendance as $result) {
            $sheet->setCellValue('A' . $i, $result->employee_id)
                ->setCellValue('B' . $i, $result->first_name . ' ' . $result->last_name)
                ->setCellValue('C' . $i, $result->branch_name)
                ->setCellValue('D' . $i, $result->department_name)
                ->setCellValue('E' . $i, $result->job_position)
                ->setCellValue('F' . $i, $result->date)
                ->setCellValue('G' . $i, $result->check_in)
                ->setCellValue('H' . $i, $result->check_out)
                ->setCellValue('I' . $i, $result->status_desc)
                ->setCellValue('J' . $i, $result->f_name . ' ' . $result->l_name)
                ->setCellValue('K' . $i, $result->approve_date);

            $i++;
        }

        $writer = new Xlsx($spreadsheet);

        // $pdf = PDF::loadView('admin.attendance.report.pdf.attendanceSummaryReportPdf', $data);
        // $pdf->setPaper('A4', 'landscape');
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment; filename="' . urlencode('ManualAttendance.xlsx') . '"');
        $writer->save('php://output');
    }

    public function download($file_name)
    {
        $file_path = public_path('uploads/manual-attendance/' . $file_name);

        return response()->download($file_path);
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

    public function ManualAttendanceCancel(Request $request, $id)
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

            $this->attendanceRepository->updateAttendance($id, [
                'status'        => 'can',
                'status_date'   => date('Y-m-d'),
                'cancel_reason' => $request->reason,
                'status_by'     => Auth::user()->employee->employee_id,
            ]);

            return response()->json(["error" => false, "message" => "Cancelled Manual Attendance"]);
        } catch (Exception $e) {
            return response()->json(["error" => true, "message" => $e->getMessage()]);
        }
    }

    public function ManualAttendanceApprove($id)
    {
        try {
            $this->attendanceRepository->updateAttendance($id, [
                'status'       => 'amw',
                'status_by'   => Auth::user()->employee->employee_id,
                'status_date' => date('Y-m-d')
            ]);

            $attendance = $this->attendanceRepository->getAttendanceById($id)
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

            return response()->json(["error" => false, "message" => "Approved Manual Attendance"]);
        } catch (Exception $e) {
            return response()->json(["error" => true, "message" => $e->getMessage()]);
        }
    }

    public function ManualAttendanceClose($id)
    {
        try {
            $this->attendanceRepository->updateAttendance($id, [
                'status'       => 'cls',
                'status_date'   => date('Y-m-d'),
                'status_by'     => Auth::user()->employee->employee_id,
            ]);

            return response()->json(["error" => false, "message" => "Finished Manual Attendance"]);
        } catch (Exception $e) {
            return response()->json(["error" => true, "message" => $e->getMessage()]);
        }
    }

    public function ManualAttendanceReject(Request $request, $id)
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

            $this->attendanceRepository->updateAttendance($id, [
                'status'        => 'arj',
                'status_date'   => date('Y-m-d'),
                'reject_reason' => $request->reason,
                'status_by'     => Auth::user()->employee->employee_id
            ]);

            $attendance = $this->attendanceRepository->getAttendanceById($id)
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

            return response()->json(["error" => false, "message" => "Rejected Manual Attendance"]);
        } catch (Exception $e) {
            return response()->json(["error" => true, "message" => $e->getMessage()]);
        }
    }
}
