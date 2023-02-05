<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;
use App\Interfaces\OvertimeInterface;
use App\Interfaces\WorkDaysInterface;
use App\Interfaces\WorkShiftsInterface;
use App\Interfaces\ConfigInterface;
use App\Interfaces\EmployeeInterface;
use Carbon\Carbon;
use Exception;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

use function PHPUnit\Framework\isNull;

class OvertimeHistoryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    private $OvertimeRepository;
    private $EmployeeRepository;
    private $WorkDaysRepository;
    private $WorkShiftRepository;
    private $ConfigRepository;
    private $ChosenEmployee;

    public function __construct(
        OvertimeInterface   $overtimeInterface,
        EmployeeInterface   $employeeInterface,
        WorkDaysInterface   $workDaysInterface,
        WorkShiftsInterface $workShiftInterface,
        ConfigInterface     $configInterface
    ) {
        $this->OvertimeRepository  = $overtimeInterface;
        $this->EmployeeRepository  = $employeeInterface;
        $this->WorkDaysRepository  = $workDaysInterface;
        $this->WorkShiftRepository = $workShiftInterface;
        $this->ConfigRepository    = $configInterface;

        $this->middleware('permission:overtime.history', [
            'only' => [
                'index', 'store', 'getOvertimeAll', 'getOvertimeDetails', 'getEmployeeShift',
            ]
        ]);
    }

    public function index()
    {
        $UserId = Auth::user()->id;
        $EmployeeIsLoggedIn = $this->EmployeeRepository->getEmployee($UserId);

        $Employees   = $this->EmployeeRepository->getAllEmployees()
            ->where('employees.department_id', '=', $EmployeeIsLoggedIn->department_id)
            ->get();

        $MinOvertime = $this->ConfigRepository->overtimeMinHours();
        $MaxOvertime = $this->ConfigRepository->overtimeMaxHours();

        return view('overtimes.overtimes-history', [
            'employee'    => $Employees,
            'minOvertime' => $MinOvertime,
            'maxOvertime' => $MaxOvertime
        ]);
    }

    public function getOvertimeAll($date)
    {
        $EmployeeOvertime = $this->OvertimeRepository->getOvertimeAllFilter($date);
        return DataTables::of($EmployeeOvertime)
            ->addColumn('date', function ($row) {
                return date('d/m/Y', strtotime($row->date));
            })
            ->addColumn('employee_image', function ($row) {
                return $this->EmployeeRepository->getEmployeePicture($row->user_id);
            })
            ->addColumn('start_time', function ($row) {
                return date('H:i', strtotime($row->start_time));
            })
            ->addColumn('end_time', function ($row) {
                return date('H:i', strtotime($row->end_time));
            })
            ->addColumn('notes', function ($row) {
                $actionBtn  = '<a onclick=NotesDetail($(this)) data-id="' . $row->overtime_id . '"  class="edit btn p-0 mr-md-1"><img src="./img/icons/note.svg" alt="note"></a>';

                return $actionBtn;
            })
            ->addColumn('status', function ($row) {
                switch ($row->status) {
                    case 'oap':
                        return '<div class="badge badge-pill badge-light-success">Approved</div>';
                        break;
                    case 'orj':
                        return '<div class="badge badge-pill badge-light-danger">Rejected</div>';
                        break;
                    default:
                        return '<div class="badge badge-pill badge-light-warning">Pending</div>';
                        break;
                }
            })
            ->addColumn('action', function ($data) {
                return '
                <a class="btn btn-outline-info round waves-effect button-rounded" href="' . url('overtimes') . '/' . $data->overtime_id . '/' . 'show' . '">
                    Details
                </a>';
            })
            ->rawColumns(['notes', 'status', 'employee_image', 'action'])
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
        try {
            $requestAll = $request->all();
            $validate   = Validator::make(
                $requestAll,
                [
                    'employee_id' => 'required',
                    'date'        => 'required',
                    'start_time'  => 'required',
                    'end_time'    => 'required',
                    'notes'       => 'required'
                ],
                [
                    'date.required'  => 'The overtime date field is required.',
                    'notes.required' => 'The overtime note field is required.'
                ]
            );

            if ($validate->fails()) {
                return response()->json([
                    'error' => $validate->errors()->toArray()
                ]);
            }

            $UserId = Auth::user()->id;

            $EmployeeIsLoggedIn = $this->EmployeeRepository->getEmployeeId($UserId);
            $employeeShift      = $this->WorkShiftRepository->getWorkShiftByID($requestAll['shift_id']);

            $reqOvertime = [
                'work_shift_name'  => $employeeShift['shift_name'],
                'work_shift_start' => $employeeShift['start_time'],
                'work_shift_end'   => $employeeShift['end_time'],

                'employee_id' => $requestAll['employee_id'],
                'date'        => $requestAll['date'],
                'start_time'  => $requestAll['start_time'],
                'end_time'    => $requestAll['end_time'],
                'notes'       => $requestAll['notes'],

                'status'      => 'oap',
                'update_time' => Carbon::now(),
                'update_by'   => $EmployeeIsLoggedIn->employee_id
            ];

            $this->OvertimeRepository->createOvertimeEloquent($reqOvertime);
            return response()->json(["error" => false, "message" => "Successfully Set Overtime"]);
        } catch (Exception $error) {
            return [
                'error' => $error->getMessage()
            ];
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */

    public function getOvertimeDetails($overtimeId)
    {
        $overtime = $this->OvertimeRepository->getOvertimeByIdForNotes($overtimeId);

        if ($overtime) {
            return response()->json(["error" => false, "data" => $overtime]);
        } else {
            return response()->json(["error" => true, "message" => "Data Not Found"]);
        }
    }

    public function getEmployeeShift(Request $request)
    {
        try {
            $this->setEmployee($request->employee_id);
            $employee = $this->ChosenEmployee;

            $Workday  = $this->WorkDaysRepository->getWorkingDays(
                $employee->company_id,
                $employee->branch_id,
                $employee->work_shift_id
            );

            $Quota = $this->ConfigRepository->overtimeQoutas();

            $Now                = Carbon::now();
            $RemainingOvertime  = $this->OvertimeRepository->calculateRemainingOvertimeOnWeeks($this->ChosenEmployee->user_id, $Quota, $Now);
            $TodayOvertimeStats = $this->OvertimeRepository->getOvertimeDay($this->ChosenEmployee->employee_id, date('Y-m-d'));
            $OvertimePending    = $this->OvertimeRepository->getOvertimePending($this->ChosenEmployee->employee_id);

            return response()->json([
                "error"    => false,
                "employee" => $employee,
                "workday"  => $Workday,

                "remainingOvertime"  => $RemainingOvertime,
                "todayOvertimeStats" => $TodayOvertimeStats,
                "overtimePending"    => $OvertimePending,
            ]);
            
        } catch (Exception $error) {
            return response()->json([
                'error'        => true,
                'status_code'  => 500,
                'message'      => 'No data received!',
            ]);
        }
    }

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

    private function setEmployee($employee_id)
    {
        return $this->ChosenEmployee = $this->EmployeeRepository->getEmployeeNameById($employee_id);
    }
}
