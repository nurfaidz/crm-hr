<?php

namespace App\Http\Controllers;

use App\Interfaces\EmployeeInterface;
use App\Interfaces\LeaveApplicationInterface;
use App\Models\Employee;
use App\Models\LeaveApplication;
use App\Models\Notification;
use App\Models\User;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class LeaveApplicationApprovalController extends Controller
{
    private EmployeeInterface $employeeInterface;
    private LeaveApplicationInterface $leaveApplicationInterface;

    public function __construct(EmployeeInterface $employeeInterface, LeaveApplicationInterface $leaveApplicationInterface)
    {
        $this->employeeInterface = $employeeInterface;
        $this->leaveApplicationInterface = $leaveApplicationInterface;

        $this->middleware('permission:leave_approval.approve', ['only' => ['approve']]);
        $this->middleware('permission:leave_tracker.reject', ['only' => ['reject']]);
    }

    public function get_leave_application()
    {
        $employee = $this->employeeInterface->getEmployeeById(Auth::user()->employee->employee_id)->join('department', 'employees.department_id', '=', 'department.department_id')->first();

        $leaveApplications = $this->leaveApplicationInterface->getAllLeave($employee->branch_id, $employee->employee_id)
            ->join('job_positions', 'employees.job_position_id', '=', 'job_positions.job_position_id')
            ->where('department.code', '=', $employee->code)
            ->where('state', '=', 'real')
            ->where('status_codes.code', '=', 'lpd')
            ->get([
                'leave_applications.leave_application_id',
                'employees.employee_id',
                'employees.user_id',
                'employees.first_name',
                'employees.last_name',
                'employees.nip',
                'branches.branch_name',
                'department.department_name',
                'job_positions.job_position',
                'leave_types.leave_type_name',
                'leave_applications.application_date',
                'leave_applications.application_from_date',
                'leave_applications.application_to_date',
                'leave_applications.number_of_day',
                'status_codes.code as status_code',
                'status_codes.short as status_desc',
            ]);

        return datatables()->of($leaveApplications)
            ->addIndexColumn()
            ->addColumn('employee_image', function ($data) {
                return $this->employeeInterface->getEmployeePicture($data['user_id']);
            })
            ->addColumn('status', function ($data) {
                switch ($data->status_code) {
                    case 'can':
                        return '<div class="badge badge-pill badge-light-secondary">' . $data->status_desc . '</div>';
                        break;
                    case 'cls':
                        return '<div class="badge badge-pill badge-light-info">' . $data->status_desc . '</div>';
                        break;
                    case 'lhy':
                        return '<div class="badge badge-pill badge-light-success">' . $data->status_desc . '</div>';
                        break;
                    case 'lhn':
                        return '<div class="badge badge-pill badge-light-danger">' . $data->status_desc . '</div>';
                        break;
                    case 'lmn':
                        return '<div class="badge badge-pill badge-light-danger">' . $data->status_desc . '</div>';
                        break;
                    case 'lmy':
                        return '<div class="badge badge-pill badge-light-success">' . $data->status_desc . '</div>';
                        break;
                    default:
                        return '<div class="badge badge-pill badge-light-warning">' . $data->status_desc . '</div>';
                        break;
                }
            })
            ->addColumn('action', function ($data) {
                return '<a href="approval/leave-application/details/' . $data->leave_application_id . '" id="pending-status-cancel" class="btn btn-outline-info round pending-status-cancel d-flex justify-content-center align-items-center">Details</a>';
            })
            ->rawColumns(['status', 'action'])
            ->toJson(true);
    }

    public function get_leave_application_details($id)
    {
        $where = array('leave_application_id' => $id);
        $data = LeaveApplication::join('status_codes', 'leave_applications.status', '=', 'status_codes.code')->join('employees', 'leave_applications.employee_id', '=', 'employees.employee_id')->join('branches', 'employees.branch_id', '=', 'branches.branch_id')->join('department', 'employees.department_id', '=', 'department.department_id')->join('job_positions', 'employees.job_position_id', '=', 'job_positions.job_position_id')->join('leave_types', 'leave_applications.leave_type_id', '=', 'leave_types.leave_type_id')->leftJoin('leave_periods', 'leave_applications.leave_period_id', '=', 'leave_periods.leave_period_id')->leftJoin('option_leaves', 'leave_applications.option_leave_id', '=', 'option_leaves.option_leave_id')->where($where)->first();

        $approver = Employee::where('employee_id', '=', $data['approve_by'])->first();
        $rejecter = Employee::where('employee_id', '=', $data['reject_by'])->first();

        $annualLeave = $this->leaveApplicationInterface->getLeaveApprovalAnnualLeave($id, $data['leave_period_id']);
        $annualLeaveBalance = $annualLeave[0];
        $annualLeaveUsedBalance = $annualLeave[1];

        $bigLeave = $this->leaveApplicationInterface->getLeaveApprovalBigLeave($id, $data['leave_period_id']);
        $bigLeaveBalance = $bigLeave[0];
        $bigLeaveUsedBalance = $bigLeave[1];

        $maternityLeave = $this->leaveApplicationInterface->getLeaveApprovalMaternityLeave($id, $data['leave_period_id']);
        $maternityLeaveBalance = $maternityLeave[0];
        $maternityLeaveUsedBalance = $maternityLeave[1];

        return view('approval.leave-details', compact('data', 'approver', 'rejecter', 'annualLeaveBalance', 'annualLeaveUsedBalance', 'bigLeaveBalance', 'bigLeaveUsedBalance', 'maternityLeaveBalance', 'maternityLeaveUsedBalance'));
    }

    public function get_leave_application_excel()
    {
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        $employee = $this->employeeInterface->getEmployee(Auth::user()->id);
        $leaveApplications = $this->leaveApplicationInterface->getAllLeave()
            ->join('job_positions', 'employees.job_position_id', '=', 'job_positions.job_position_id')
            ->leftjoin('employees as emp', 'leave_applications.approve_by', '=', 'emp.employee_id')
            ->where('department.department_id', '=', $employee->department_id)
            ->where('state', '=', 'real')
            ->where('status_codes.code', '=', 'lpd')
            ->get([
                'leave_applications.leave_application_id',
                'employees.employee_id',
                'employees.first_name',
                'employees.last_name',
                'branches.branch_name',
                'department.department_name',
                'job_positions.job_position',
                'leave_types.leave_type_name',
                'leave_applications.application_date',
                'leave_applications.application_from_date',
                'leave_applications.application_to_date',
                'leave_applications.number_of_day',
                'status_codes.short',
                'emp.first_name as f_name',
                'emp.last_name as l_name',
                'leave_applications.approve_date'
            ]);

        $sheet->setCellValue('A1', 'Employee ID')
            ->setCellValue('B1', 'Employee Name')
            ->setCellValue('C1', 'Entity')
            ->setCellValue('D1', 'SBU')
            ->setCellValue('E1', 'Position')
            ->setCellValue('F1', 'Leave Type')
            ->setCellValue('G1', 'Application Date')
            ->setCellValue('H1', 'Duration')
            ->setCellValue('I1', 'Total')
            ->setCellValue('J1', 'Status')
            ->setCellValue('K1', 'Approve By')
            ->setCellValue('L1', 'Approve Date');

        $i = 2;
        foreach ($leaveApplications as $result) {
            $sheet->setCellValue('A' . $i, $result->employee_id)
                ->setCellValue('B' . $i, $result->first_name . ' ' . $result->last_name)
                ->setCellValue('C' . $i, $result->branch_name)
                ->setCellValue('D' . $i, $result->department_name)
                ->setCellValue('E' . $i, $result->job_position)
                ->setCellValue('F' . $i, $result->leave_type_name)
                ->setCellValue('G' . $i, $result->application_date)
                ->setCellValue('H' . $i, $result->application_from_date . ' to ' . $result->application_to_date)
                ->setCellValue('I' . $i, $result->number_of_day . ' days')
                ->setCellValue('J' . $i, $result->short)
                ->setCellValue('K' . $i, $result->f_name . ' ' . $result->l_name)
                ->setCellValue('L' . $i, $result->approve_date);

            $i++;
        }

        $writer = new Xlsx($spreadsheet);

        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment; filename="' . urlencode('LeaveApplication.xlsx') . '"');

        $writer->save('php://output');
    }

    public function download($file_name)
    {
        $file_path = public_path('uploads/leave-applications/' . $file_name);

        return response()->download($file_path);
    }

    public function reject($id, $reason)
    {
        try {
            $this->leaveApplicationInterface->rejectLeaveApplication($id, $reason);
            $leaveApplication = $this->leaveApplicationInterface->getLeaveApplicationByid($id)->first();

            $employeeNotif = Employee::where('employee_id', $leaveApplication->employee_id)->first();
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
                'data' => ["detail_id" => $leaveApplication->leave_application_id],
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
                "detail_id" =>  $leaveApplication->leave_application_id,
                "is_approval" => false,
            ];

            Notification::create($createNotifications);

            return response()->json([
                'error' => false,
                'message' => 'Successfully Rejected Leave Application'
            ]);
        } catch (Exception $e) {
            return response()->json([
                'error' => true,
                'message' => $e->getMessage()
            ]);
        }
    }

    public function approve($id)
    {
        try {
            $this->leaveApplicationInterface->approveLeaveApplication($id);
            $leaveApplications = $this->leaveApplicationInterface->getLeaveApplicationByid($id)->first();

            $employeeNotif = Employee::where('employee_id', $leaveApplications->employee_id)->first();
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
                'data' => ["detail_id" => $leaveApplications->leave_application_id],
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
                "detail_id" =>  $leaveApplications->leave_application_id,
                "is_approval" => false,
            ];

            Notification::create($createNotifications);

            return response()->json([
                'error' => false,
                'message' => 'Successfully Approved Leave Application'
            ]);
        } catch (Exception $e) {
            return response()->json([
                'error' => true,
                'message' => $e->getMessage()
            ]);
        }
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
}
