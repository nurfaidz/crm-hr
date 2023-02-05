<?php

namespace App\Http\Controllers;

use Exception;
use Carbon\Carbon;
use App\Models\Branches;
use App\Models\Employee;
use App\Models\Companies;
use App\Models\WorkShift;
use App\Models\Attendance;
use App\Models\LeaveApplication;
use App\Models\Department;
use Illuminate\Http\Request;
use App\Interfaces\SortingInterface;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use App\Interfaces\EmployeeInterface;
use App\Interfaces\AttendanceInterface;
use Yajra\DataTables\Facades\DataTables;
use Phpml\Association\Apriori;
use Illuminate\Support\Facades\DB;

class SortingController extends Controller
{

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    private EmployeeInterface $employeeInterface;
    private AttendanceInterface $attendanceInterface;
    private SortingInterface $sortingInterface;

    public function __construct(
        EmployeeInterface $employeeInterface, 
        AttendanceInterface $attendanceInterface,
        SortingInterface $sortingInterface
        )
        {
        $this->employeeInterface = $employeeInterface;
        $this->attendanceInterface = $attendanceInterface;
        $this->sortingInterface = $sortingInterface;
    }

    public function index()
    {
        return view('chart.index');

    }

    public function db_get()
    {
        $user            = Auth::user()->employee->employee_id;
        $employee        = $this->employeeInterface->getEmployeeById($user)
            ->join('department', 'employees.department_id', '=', 'department.department_id')
            ->first();

        $employees = Employee::join('attendances', 'attendances.employee_id', '=', 'employees.employee_id')
                    // ->join('leave_applications', 'leave_applications.employee_id', '=', 'employees.employee_id')
                    ->join('branches', 'employees.branch_id', '=', 'branches.branch_id')
                    ->join('department', 'employees.department_id', '=', 'department.department_id')
                    ->join('job_positions', 'employees.job_position_id', '=', 'job_positions.job_position_id')
                    ->join('job_classes', 'employees.job_class_id', '=', 'job_classes.job_class_id')
                    ->join('status_codes', 'attendances.status', '=', 'status_codes.code')
                    // ->whereIn('status_codes.code', ['lmy', 'lhy', 'amw', 'acw'])
                    ->whereIn('status_codes.code', ['amw', 'acw'])
                    // ->withCount('attendances')
                    // ->select('attendances.employee_id', Employee::raw('SUM as id'))
                    // ->groupBy('employee_id')
                    ->orderBy('employees.employee_id', 'desc')
                    ->orderBy('attendances.id', 'desc')
                    // ->orderBy('leave_applications.leave_application_id', 'desc')
                    // ->take(5)
                    // ->first();
                    // ->get();
                    ->get([
                        'employees.employee_id',
                        'employees.user_id',
                        'employees.first_name',
                        'employees.last_name',
                        'employees.nip',
                        'branches.branch_name',
                        'department.department_name',
                        'job_positions.job_position',
                        'job_classes.job_class',
                        'status_codes.code as status_code',
                        'status_codes.short as status_desc',
                    ]);

        // $data = $employees->sortBy('employee_id')->groupBy('employee_id');

        // dd($data->toArray(true));

        // $attenByEmp = $this->sortingInterface->groupAttendance();
        // $leaveByEmp = $this->sortingInterface->groupLeave();
        // dd($attenByEmp);

        return datatables::of($employees)
            ->addIndexColumn()
            ->addColumn('employee_image', function ($data) {
                return $this->employeeInterface->getEmployeePicture($data['user_id']);
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
            ->rawColumns(['status', 'action'])
            ->toJson(true);
    }

    public function informationLeaveBalanceYear($year){
        return $this->sortingInterface->getLeaveBalanceInformation($year);
    }

    public function informationAttendanceBalanceYear($year){
        // return $this->sortingInterface->getAttendanceBalanceInformation($year);
        $b = $this->sortingInterface->countWorkTime($year);
        dd($b);
    }

    public function informationCountAttendance()
    {
        // $data = array();
        $a = $this->sortingInterface->countAttendanceEmployee()                   
            // ->join('status_codes', 'attendances.status', '=', 'status_codes.code')
            ->where('status_codes.short', '=', 'Check In')
            // ->orderBy('employees.employee_id', 'desc')
            // ->get([
                //     'employees.employee_id',
                //     'employees.first_name',
                //     'employees.last_name',
                //     'branches.branch_name',
                //     'department.department_name',
                // ]);
                // ->select(DB::raw('attendances.employee_id','count(*) as id'))
                ->where('status_codes.short', '=', 'Check In')
                // ->groupBy('employees.employee_id')
                ->orderBy('employees.employee_id', 'desc')->get();
        // ->get([
        //                 'employees.employee_id',
        //                 'employees.first_name',
        //                 'employees.last_name',
        //                 'branches.branch_name',
        //                 'department.department_name',
        // ]);

        // $data[] = (array)$a;

        return datatables::of($a)
            ->addIndexColumn()
            ->toJson(true);

            // dd($a);
        }
        
    public function indexCountAtt()
    {
        
        $a = $this->sortingInterface->countAttendanceEmployee()                   
        ->where('status_codes.short', '=', 'Check In')
        ->select(DB::raw('attendances.employee_id','count(*) as id'))
        ->groupBy('attendances.employee_id')
        ->orderBy('id', 'desc')->get();

        $b = $a;
        
        return view ('chart.details', compact(
                'b',
        ));
    }

    public function statistics()
    {
        $sample = [
            [ 'Human Resources', 'Manager', 'Worker'],
            ['Worker', 'Manager', 'Human Resources',], 
            ['Human Resources', 'Human Resources', 'Worker'], 
        ];


        $labels = [];

        $associator = new Apriori($support = 0.5, $confidence = 0.5);
        $associator->train($sample, $labels);
        $as = $associator->apriori();
        // dd($as[3][0]);

        return view('chart.statistic', compact(
            'as',
        ));

        
            // return datatables::of($as)
            // ->addIndexColumn()
            // ->toJson(true);
        // dd($as);
        



        // $employeeId = Auth::user()->employee->employee_id;
        // $statistics = $this->informationCountAttendance();
        // sort($statistics);

        // if($statistics['employee_id'] =  )
        // $a = $statistics->toArray(true);
        // $statistics->apriori();
//     $associator = new Apriori();
//     $associator->apriori($a);

// dd($associator);

        // return response()->json([
        //     'data' => $statistics,
        //     'error' => false
        // ]);
    }

    public function data()
    {
        // $pendingStatusArray = [];
        // $pendingStatuses = $this->leaveApplicationInterface->getAllLeaveApplications()->where('employee_id', Auth::user()->employee->employee_id)->where('state', 'real')->where('code', 'lpd')->sortByDesc('application_date');

        // foreach ($pendingStatuses as $pendingStatus) {
        //     $startDate = explode('-', $pendingStatus->application_from_date);
        //     $endDate = explode('-', $pendingStatus->application_to_date);
        //     $total = Carbon::parse($pendingStatus->application_to_date)->diffInDays($pendingStatus->application_from_date) + 1;

        //     $pendingStatusArray[] = [
        //         'leave_application_id' => $pendingStatus->leave_application_id,
        //         'leave_type_name' => $pendingStatus->leave_type_name,
        //         'application_from_date' => $startDate[2] . '/' . $startDate[1] . '/' . $startDate[0],
        //         'application_to_date' => $endDate[2] . '/' . $endDate[1] . '/' . $endDate[0],
        //         'total' => $total . ' Hari',
        //         'status' => $pendingStatus->short
        //     ];
        // }

        // return response()->json([
        //     'data' => $pendingStatusArray,
        //     'error' => false
        // ]);
    }

    public function list($type, $status)
    {
        // $leaveHistory = $this->leaveApplicationInterface->getAllLeaveApplications()->where('employee_id', Auth::user()->employee->employee_id)->where('state', 'real')->where('code', '!=', 'lpd')->sortByDesc('application_date');

        // if ($type != 0) {
        //     $leaveHistory->where('leave_type_id', $type);
        // }

        // if ($status != 0) {
        //     $leaveHistory->where('status', $status);
        // }

        // return datatables()->of($leaveHistory)
        //     ->addIndexColumn()
        //     ->addColumn('action', function ($data) {
        //         return '
        //             <a href="' . url('self-leave-application/details') . '/' . $data->leave_application_id . '" id="pending-status-details" class="btn btn-outline-info round pending-status-details">Details</a>
        //         ';
        //     })
        //     ->rawColumns(['action'])
        //     ->make(true);
    }

    public function cancel($id)
    {
        // try {
        //     $this->leaveApplicationInterface->cancelLeaveApplication($id);

        //     return response()->json([
        //         'error' => false,
        //         'message' => 'Successfully Canceled Leave Application'
        //     ]);
        // } catch (Exception $e) {
        //     return response()->json([
        //         'error' => true,
        //         'message' => $e->getMessage()
        //     ]);
        // }
    }

    // public function details($id)
    // {
    //     $leaveApplication = $this->leaveApplicationInterface->getAllLeaveApplications()->where('leave_application_id', $id)->where('state', 'real')->first();

    //     $canceler = Employee::where('employee_id', '=', $leaveApplication['cancel_by'])->first();
    //     $approver = Employee::where('employee_id', '=', $leaveApplication['approve_by'])->first();
    //     $rejecter = Employee::where('employee_id', '=', $leaveApplication['reject_by'])->first();

    //     $leaveBalance = $this->leaveApplicationInterface->countEachLeaveBalance(Auth::user()->employee->employee_id, Carbon::now()->year);
    //     $firstArrayKey = array_key_first($leaveBalance);
    //     $firstLeaveBalancePeriod = LeavePeriod::where('leave_period_id', '=', $firstArrayKey)->first();
    //     $firstLeaveBalancePeriod = date_format(Carbon::parse($firstLeaveBalancePeriod['from_date']), 'j F Y') . ' - ' . date_format(Carbon::parse($firstLeaveBalancePeriod['to_date']), 'j F Y');
    //     $firstLeaveBalance = $leaveBalance[$firstArrayKey];
    //     $secondArrayKey = array_keys($leaveBalance)[0];
    //     $secondLeaveBalancePeriod = LeavePeriod::where('leave_period_id', '=', $secondArrayKey)->first();
    //     $secondLeaveBalancePeriod = date_format(Carbon::parse($secondLeaveBalancePeriod['from_date']), 'j F Y') . ' - ' . date_format(Carbon::parse($secondLeaveBalancePeriod['to_date']), 'j F Y');
    //     $secondLeaveBalance = $leaveBalance[$secondArrayKey];
    //     $usedBalancePeriod = date_format(Carbon::parse($leaveApplication['from_date']), 'j F Y') . ' - ' . date_format(Carbon::parse($leaveApplication['to_date']), 'j F Y');
    //     $usedBalance = $leaveApplication['used'];

    //     return view('self-leave-application.details', compact('leaveApplication', 'canceler', 'approver', 'rejecter', 'firstLeaveBalancePeriod', 'firstLeaveBalance', 'secondLeaveBalancePeriod', 'secondLeaveBalance', 'usedBalancePeriod', 'usedBalance'));
    // }

    // public function download($file_name)
    // {
    //     $file_path = public_path('uploads/leave-applications/' . $file_name);

    //     return response()->download($file_path);
    // }

    // public function informationBalanceYear($year){
    //     return $this->leaveApplicationInterface->getLeaveBalanceInformation($year);
    // }
}
