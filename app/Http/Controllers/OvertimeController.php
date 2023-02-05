<?php

namespace App\Http\Controllers;

use App\Helpers\DateHelpers;
use App\Interfaces\ConfigInterface;
use App\Interfaces\OvertimeInterface;
use App\Interfaces\WorkDaysInterface;
use App\Interfaces\WorkShiftsInterface;
use App\Models\Employee;
use App\Models\Overtime;
use App\Models\Workdays;
use App\Models\WorkShift;
use App\Models\Branches;
use App\Models\Department;
use App\Models\JobPosition;
use App\Models\Notification;
use App\Models\User;
use Exception;
use App\Rules\OvertimeRule;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Validator;

class OvertimeController extends Controller
{
    private $overtimeInterface;
    private $configInterface;
    private $workDaysInterface;
    private $workShiftsInterface;

    public function __construct(OvertimeInterface $overtimeInterface, ConfigInterface $configInterface, WorkDaysInterface $workDaysInterface, WorkShiftsInterface $workShiftsInterface)
    {
        $this->overtimeInterface = $overtimeInterface;
        $this->configInterface = $configInterface;
        $this->workDaysInterface = $workDaysInterface;
        $this->workShiftsInterface = $workShiftsInterface;
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $employeeIsLoggedInUser = Employee::where('user_id', Auth::user()->id);
        $employee = $employeeIsLoggedInUser->join('work_shifts', 'employees.work_shift_id', '=', 'work_shifts.work_shift_id')->first();

        $branch = $employeeIsLoggedInUser->first();
        $workday = $this->workDaysInterface->getWorkingDays($employee->company_id, $employee->branch_id, $employee->work_shift_id);

        $quota = $this->configInterface->overtimeQoutas();
        // $minOvertime = DateHelpers::minutesToHours($this->configInterface->overtimeMinHours());
        $minOvertime = $this->configInterface->overtimeMinHours();
        $maxOvertime = $this->configInterface->overtimeMaxHours();
        // $quotas = DateHelpers::minutesToHours($this->configInterface->overtimeQoutas());


        $now = Carbon::now();
        $remainingOvertime = $this->overtimeInterface->calculateRemainingOvertimeOnWeeks(Auth::user()->id, $quota, $now);
        // dd($remainingOvertime);
        $overtime = $this->overtimeInterface->getOvertimeToday(Auth::user()->id);
        $overtimeHours = $this->overtimeInterface->getOvertimeHoursToday(Auth::user()->id);
        $overtimePending = $this->overtimeInterface->getOvertimePending($employee->employee_id);
        $todayOvertimeStats = $this->overtimeInterface->getOvertimeDay($employee->employee_id, date('Y-m-d'));

        return view('overtimes.index', compact('employee', 'workday', 'overtime', 'overtimePending', 'overtimeHours', 'remainingOvertime', 'minOvertime', 'maxOvertime', 'todayOvertimeStats'));
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
            $requestData = $request->all();
            // $overtimeRule = new OvertimeRule();
            $validate = Validator::make($requestData, [
                'overtime_date'      => [
                    'required'
                    // $overtimeRule
                ],
                'overtime_start_time'  => 'required',
                'overtime_end_time' => 'required|after:overtime_start_time',
                'overtime_note' => 'required',
            ]);

            if ($validate->fails()) {
                return response()->json([
                    'error' => $validate->errors()->toArray()
                ]);
            }

            $employeeIsLoggedInUser = Employee::where('user_id', Auth::user()->id);
            $employee = $employeeIsLoggedInUser->join('work_shifts', 'employees.work_shift_id', '=', 'work_shifts.work_shift_id')->first();
            $workshift = $this->workShiftsInterface->getWorkShiftByID($employee->work_shift_id);
            // $workshift = WorkShift::select('shift_name', 'start_time', 'end_time')->first();
            $manager_id = Department::where('department_id', $employee->department_id)->first()->manager;
            $employeeManager = Employee::where('employee_id', $manager_id)->first();
            $userManager = User::find($employeeManager->user_id);

            $overtime = new Overtime;
            $overtime->employee_id = $employee->employee_id;
            $overtime->work_shift_name = $workshift->shift_name;
            $overtime->work_shift_start = $workshift->start_time;
            $overtime->work_shift_end = $workshift->end_time;
            $overtime->date = $request->overtime_date;
            $overtime->start_time = $request->overtime_start_time;
            $overtime->end_time = $request->overtime_end_time;
            $overtime->notes = $request->overtime_note;

            $overtime->save();

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

    public function overtimesList($date)
    {
        $dateArr = explode('-', $date);
        $filterOvertimes = $this->overtimeInterface->getOvertimeAll(Auth::user()->id, $dateArr[0], $dateArr[1]);

        return datatables()->of($filterOvertimes)
            ->addIndexColumn()
            ->addColumn('date', function ($data) {
                return date('d/m/Y', strtotime($data->date));
            })
            ->addColumn('work_time', function ($data) {
                return "{$data->work_shift->h} H {$data->work_shift->m} M";
            })
            ->addColumn('notes', function ($data) {
                return '<a onclick=NotesDetail($(this)) data-id="' . $data->overtime_id . '"  class="edit btn p-0 mr-md-1"><img src="./img/icons/note.svg" alt="note"></a>';
            })
            ->addColumn('status', function ($data) {
                switch ($data->status_code) {
                    case 'can':
                        return '<div class="badge badge-pill badge-light-secondary">' . $data->status_desc . '</div>';
                        break;
                    case 'cls':
                        return '<div class="badge badge-pill badge-light-info">' . $data->status_desc . '</div>';
                        break;
                    case 'oap':
                        return '<div class="badge badge-pill badge-light-success">' . $data->status_desc . '</div>';
                        break;
                    case 'oca':
                        return '<div class="badge badge-pill badge-light-danger">' . $data->status_desc . '</div>';
                        break;
                    case 'orj':
                        return '<div class="badge badge-pill badge-light-danger">' . $data->status_desc . '</div>';
                        break;
                    default:
                        return '<div class="badge badge-pill badge-light-warning">' . $data->status_desc . '</div>';
                        break;
                }
            })
            ->addColumn('action', function ($data) {
                return '
                <a class="btn btn-outline-info round waves-effect button-rounded" href="' . url('overtimes') . '/' . $data->overtime_id . '/' . 'show' . '">
                    Details
                </a>';
            })
            ->rawColumns(['date', 'status', 'notes', 'work_time', 'action'])
            ->make(true);
    }

    /**
     * * Fetch Requested Overtimes
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function fetchPendingOvertimes()
    {
        $pendingOvertimes = $this->overtimeInterface->getOvertimePending(Auth::user()->id);
        return $pendingOvertimes;
    }

    public function getOvertimeDetails($overtimeId)
    {
        $overtime = $this->overtimeInterface->getOvertimeByIdForNotes($overtimeId);

        if ($overtime) {
            return response()->json(["error" => false, "data" => $overtime]);
        } else {
            return response()->json(["error" => true, "message" => "Data Not Found"]);
        }
    }


    public function overtimeCancel($overtimeId)
    {
        try {
            $this->overtimeInterface->cancelOvertime($overtimeId);
            return response()->json(["error" => false, "message" => "Sucessfully Cancelled Overtimes"]);
        } catch (Exception $e) {
            return response()->json(["error" => true, "message" => $e->getMessage()]);
        }
    }

    /**
     * * Fetch Requested Overtimes
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    // public function overtimeCheck(){
    //     $employeeIsLoggedInUser = Employee::where('user_id', Auth::user()->id);
    //     $employee = $employeeIsLoggedInUser->join('work_shifts', 'employees.work_shift_id', '=', 'work_shifts.work_shift_id')->first();

    //     $workday = $this->workDaysInterface->getWorkingDays($employee->company_id, $employee->branch_id, $employee->work_shift_id);
    //     dd($workday,$employee->branch_id, $employee->work_shift_id);
    // }

    public function detailRedirect($id)
    {
        $overtime = Overtime::where('overtime_id', $id)->first();
        if (!$overtime) {
            abort(404);
        }

        $employee = Employee::where('employee_id', $overtime->employee_id)->first();
        $branch = Branches::where('branch_id', $employee->branch_id)->first();
        $department = Department::where('department_id', $employee->department_id)->first();
        $jobPosition = JobPosition::where('job_position_id', $employee->job_position_id)->first();
        $approvedBy  = Employee::where('employee_id', $overtime->update_by)->first();
        $quota = $this->configInterface->overtimeQoutas();
        $now = Carbon::now();
        $remainingOvertime = $this->overtimeInterface->calculateRemainingOvertimeOnWeeks($overtime->employee_id, $quota, $now);

        $approve1 = 'active';

        if ($approvedBy && $overtime->status == "orj") {
            $approve1 = 'danger';
        } else if ($approvedBy && $overtime->status == "oap") {
            $approve1 = 'done';
        } else if ($overtime->status == "opd") {
            $approve1 = 'active';
        }

        return view('overtimes.detail', [
            'overtime' => $overtime,
            'employee' => $employee,
            'branch' => $branch,
            'department' => $department,
            'jobPosition' => $jobPosition,
            'approvedBy' => $approvedBy,
            'approve1' => $approve1,
            'remainingOvertime' => $remainingOvertime,
        ]);
    }
}
