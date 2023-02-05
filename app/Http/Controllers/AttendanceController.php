<?php

namespace App\Http\Controllers;

use App\Models\Attendance;
use App\Models\Branches;
use App\Models\Employee;
use App\Models\Department;
use App\Models\JobPosition;
use App\Models\LeaveApplication;
use App\Models\LeaveType;
use App\Models\LeavePeriod;
use App\Models\Workdays;
use App\Models\WorkShift;
use App\Helpers\DateHelpers;
use App\Interfaces\WorkDaysInterface;
use App\Models\Days;
use App\Models\Notification;
use App\Models\User;
use App\Repositories\AttendanceRepository;
use App\Repositories\EmployeeRepository;
use App\Repositories\LeaveApplicationRepository;
use Carbon\Carbon;
use DateTime;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\Facades\DataTables;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use \PhpOffice\PhpSpreadsheet\Style\Border;
use App\Rules\SickRule;
use App\Rules\CheckInRule;
use App\Rules\PermissionRule;
use App\Rules\ManualRule;
use App\Rules\DayOfRule;
use Illuminate\Support\Facades\Http;

class AttendanceController extends Controller
{
    private $attendanceRepository, $employeeRepository;
    private $workDaysInterface;

    function __construct(AttendanceRepository $attendanceRepository, EmployeeRepository $employeeRepository, LeaveApplicationRepository $leaveApplicationRepository, WorkDaysInterface $workDaysInterface)
    {
        $this->attendanceRepository = $attendanceRepository;
        $this->employeeRepository = $employeeRepository;
        $this->leaveApplicationRepository = $leaveApplicationRepository;
        $this->WorkDaysRepository  = $workDaysInterface;
        $this->middleware('permission:daily_attendance.list', ['only' => ['dailyAttendance']]);
        $this->middleware('permission:monthly_attendance.list', ['only' => ['monthlyAttendance']]);
        $this->middleware('permission:my_attendance.list', ['only' => ['myAttendance']]);
        $this->middleware('permission:overall_attendance.list', ['only' => ['overallAttendance', 'getOverallAttandance', 'downloadoverallAttendance']]);
        $this->middleware('permission:self_attendance.list', ['only' => ['index', 'show']]);
        $this->middleware('permission:self_attendance.create', ['only' => ['create', 'store']]);
        $this->middleware('permission:self_attendance.edit', ['only' => ['edit', 'update']]);
        $this->middleware('permission:self_attendance.delete', ['only' => ['destroy']]);
    }



    public function index(Request $request)
    {
        $employeeIsLoggedInUser = Employee::where('user_id', Auth::user()->id);
        $employee = $employeeIsLoggedInUser->join('work_shifts', 'employees.work_shift_id', '=', 'work_shifts.work_shift_id')->first();
        $workshift = WorkShift::select(['work_shift_id', 'shift_name'])->get();
        $branch = $employeeIsLoggedInUser->first();
        $workday = Workdays::where('branch_id', $branch->branch_id)->where('work_shift_id', $branch->work_shift_id)->where('days_id', (date('w') + 1))->first();

        // $attendance = Attendance::all()
        //     ->whereNull('attendances.status');

        // die($attendance);

        // $time1 = new DateTime($employee['start_time']);
        // $time2 = new DateTime($employee['end_time']);
        // $interval = $time1->diff($time2);
        $employeeData = Employee::where('employee_id', Auth::user()->employee->employee_id);
        $employee = $employeeData->join('work_shifts', 'employees.work_shift_id', '=', 'work_shifts.work_shift_id')->first();

        $workdayDate  = $this->WorkDaysRepository->getWorkingDays(
            $employee->company_id,
            $employee->branch_id,
            $employee->work_shift_id
        );

        return view('attendances.list-attendance.index', compact('employee', 'workshift', 'workday', 'workdayDate'));
    }

    public function attendanceStatistics($date)
    {
        $employee = $this->employeeRepository->getEmployeeId(Auth::user()->id);
        $dateArr = explode('-', $date);
        $year = $dateArr[0];
        $month = $dateArr[1];
        $statistics = $this->attendanceRepository->statistics($employee->employee_id, $month, $year);
        return response()->json([
            "data" => $statistics,
            "error" => false,
            "message" => "Attendance found!"
        ]);
    }

    public function attendanceList($date)
    {
        $employeeIsLoggedInUser = Employee::where('user_id', Auth::user()->id);
        $dateArr = explode('-', $date);
        $attendance = Attendance::whereYear('date', '=', $dateArr[0])
            ->join('employees', 'attendances.employee_id', '=', 'employees.employee_id')
            ->join('work_shifts', 'employees.work_shift_id', '=', 'work_shifts.work_shift_id')
            ->whereMonth('date', '=', $dateArr[1])
            ->where('attendances.employee_id', '=', $employeeIsLoggedInUser->pluck('employee_id')->first())
            ->orderBy('date', 'desc')
            ->get(['attendances.*', 'work_shifts.shift_name']);

        $AttendanceData = [];

        foreach ($attendance as $attendance_list) {
            if ($attendance_list->status !== 'apd') {
                if ($attendance_list->status !== 'cls') {
                    $AttendanceData[] = (object) [
                        'id'                => $attendance_list->id,
                        'employee_id'       => $attendance_list->employee_id,
                        'date'              => date('d/m/Y', strtotime($attendance_list->date)),
                        'day'               => date('l', strtotime($attendance_list->date)),
                        'check_in'          => $attendance_list->check_in,
                        'check_out'         => $attendance_list->check_out,
                        'working_hour'      => $attendance_list->working_hour,
                        'lateness'          => $attendance_list->lateness,
                        'late_duration'     => $attendance_list->late_duration,
                        'overtime_duration' => $attendance_list->overtime_duration,
                        'shift'             => $attendance_list->shift_name,
                        'notes'             => $attendance_list->notes,
                        'status'            => $attendance_list->status,
                        'status_code'       => $attendance_list->status
                    ];
                }
            }
        }

        return DataTables::of($AttendanceData)
            ->addColumn('check_in', function ($row) {
                return date('H:i', strtotime($row->check_in));
            })
            ->addColumn('check_out', function ($row) {
                return date('H:i', strtotime($row->check_out));
            })
            ->addColumn('lateness', function ($row) {
                if ($row->late_duration >= 1) {
                    return '<div class="badge badge-pill badge-light-danger">Yes</div>';
                } else if ($row->late_duration == 0) {
                    return '<div class="badge badge-pill badge-light-success">No</div>';
                }
            })
            ->addColumn('late_duration', function ($row) {
                $minutes = $row->late_duration;
                $zero    = new DateTime('@0');
                $offset  = new DateTime('@' . $minutes * 60);
                $diff    = $zero->diff($offset);
                return $diff->format('%h H %i M');
            })
            ->addColumn('overtime_duration', function ($row) {
                $minutes = $row->overtime_duration;
                $zero    = new DateTime('@0');
                $offset  = new DateTime('@' . $minutes * 60);
                $diff    = $zero->diff($offset);
                return $diff->format('%h H %i M');
            })
            ->addColumn('notes', function ($row) {
                if ($row->status === 'acw' || $row->status === 'acm') {
                    $actionBtn  = '<a onclick=notes($(this)) data-id="' . $row->id . '"  class="edit btn p-0 mr-md-1"><img src="./img/icons/note.svg" alt="note"></a>';
                    return $actionBtn;
                } else {
                    $actionBtn  = '<a onclick=notes($(this)) data-id="' . $row->id . '"  class="edit btn p-0 mr-md-1"><img src="./img/icons/note-green.svg" alt="note"></a>';
                    return $actionBtn;
                }
            })
            ->addColumn('status', function ($row) {
                switch ($row->status) {
                    case 'acm':
                        return '<div class="badge badge-pill badge-light-success">Present</div>';
                        break;
                    case 'acw':
                        return '<div class="badge badge-pill badge-light-success">Present</div>';
                        break;
                    case 'amw':
                        return '<div class="badge badge-pill badge-light-success">Present</div>';
                        break;
                    case 'amm':
                        return '<div class="badge badge-pill badge-light-success">Present</div>';
                        break;
                    case 'aab':
                        return '<div class="badge badge-pill badge-light-danger">Absent</div>';
                        break;
                    case 'asc':
                        return '<div class="badge badge-pill badge-light-secondary">Sick</div>';
                        break;
                    case 'apm':
                        return '<div class="badge badge-pill badge-light-info">Permission</div>';
                        break;
                    case 'ado':
                        return '<div class="badge badge-pill badge-light-secondary">Day Off</div>';
                        break;
                    case 'arj':
                        return '<div class="badge badge-pill badge-light-danger">Rejected</div>';
                        break;
                    case 'can':
                        return '<div class="badge badge-pill badge-light-secondary">Cancelled</div>';
                        break;
                    default:
                        return '<div class="badge badge-pill badge-light-warning">Pending</div>';
                        break;
                }
            })
            ->rawColumns(['working_hour', 'lateness', 'late_time', 'notes', 'status'])
            ->toJson(true);
    }

    public function attendanceCancel($id)
    {
        try {
            $this->attendanceRepository->AttendanceCancel($id);
            return response()->json(["error" => false, "message" => "Sucessfully Cancelled Attendance"]);
        } catch (Exception $e) {
            return response()->json(["error" => true, "message" => $e->getMessage()]);
        }
    }

    public function attendancePending()
    {
        $attendancePending = [];
        $attendance =  $this->attendanceRepository->getEmployeeAttendance()
            ->join('work_shifts', 'employees.work_shift_id', '=', 'work_shifts.work_shift_id')
            ->where('employees.user_id', '=', Auth::user()->id)
            ->where('attendances.status', '=', 'apd')
            ->whereNotNull('attendances.status')
            ->get();

        foreach ($attendance as $attendance_list) {
            $attendancePending[] = [
                'id'            => $attendance_list->id,
                'date'          => date('d/m/Y', strtotime($attendance_list->date)),
                'check_in'      => date('H:i', strtotime($attendance_list->check_in)),
                'check_out'     => date('H:i', strtotime($attendance_list->check_out)),
                'shift_name'    => $attendance_list->shift_name,
                'image'         => $attendance_list->image_manual_attendance
            ];
        }

        return response()->json([
            'data' => $attendancePending,
            'error' => false,
        ]);
    }

    public function download($file_name)
    {
        $file_path = public_path('uploads/manual-attendance/' . $file_name);

        return response()->download($file_path);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function store(Request $request)
    {
        try {
            $user_id = Auth::user()->id;
            $employee = Employee::where('user_id', $user_id)->first();
            $findWorkShift = WorkShift::where('work_shift_id', $employee->work_shift_id)->first();
            $endWorkshift = new Carbon($findWorkShift->end_time);
            $attendance = Attendance::where('date', date("Y-m-d"))->where('employee_id', $employee->employee_id)->first();
            $timeNow = Carbon::now();
            $workday = Workdays::where('branch_id', $employee->branch_id)->where('work_shift_id', $employee->work_shift_id)->where('days_id', (date('w') + 1))->first();
            $holidays = $this->attendanceRepository->isTodayAreHolidays($timeNow);
            $leave = $this->attendanceRepository->isTodayLeave($timeNow, $employee->employee_id);

            if (count($holidays) > 0) {
                return response()->json(["error" => true, "message" => "Today is holiday"]);
            }

            if (count($leave) > 0) {
                return response()->json(["error" => true, "message" => "Today is Leave"]);
            }

            if ($workday === null) {
                return response()->json(["error" => true, "message" => "Today is not a working day"]);
            }

            if ($attendance) {
                return response()->json(["error" => true, "message" => "You have been check-in"]);
            }

            if (Carbon::now() > $endWorkshift) {
                return response()->json(["error" => true, "message" => "Working time has been passed"]);
            }

            $maxArrival = new Carbon($findWorkShift->max_arrival);

            if ($timeNow > $maxArrival) {
                $time = $timeNow->diff($findWorkShift->max_arrival);
                $time = $time->format('%h:%i:%s');

                $time = explode(':', $time);
                if ($time[0] == "0" && (int)$time[1] <= 4) {
                    $late_duration = 0;
                } else {
                    $late_duration = ($time[0] * 60) + ($time[1]) + ($time[2] / 60);
                }
            } else {
                $late_duration = 0;
            }

            $return = Attendance::create([
                'employee_id' => $employee->employee_id,
                'overtime_id' => 1,
                'date' => date('Y-m-d'),
                'check_in' => Carbon::now(),
                'late_duration' => $late_duration,
                'note_check_in' => $request->note,
                'status' => 'acw',
            ]);

            $employee = Employee::where('employee_id', $return->employee_id)->first();
            $workshift = WorkShift::where('work_shift_id', $employee->work_shift_id)->get();

            return response()->json([
                "error" => false,
                "data" => $return->id,
                "workshift" => $workshift,
                "attendance" => $return->check_in->format('Y-m-d H:i:s'),
                "message" => "Successfuly Added attendance!"
            ]);
        } catch (Exception $e) {
            return response()->json(["error" => true, "message" => $e->getMessage()]);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     *
     * @return \Illuminate\View\View
     */
    public function show($user_id)
    {
        $employee = Employee::where('user_id', $user_id)->first();

        $result = Attendance::whereDate('created_at', Carbon::today())->where('employee_id', $employee->employee_id)->get();
        if ($result->isEmpty()) {
            return response()->json([
                "error" => true,
                "message" => "Attendance not found!"
            ]);
        }
        return response()->json([
            "data" => $result,
            "error" => false,
            "message" => "Attendance found!"
        ]);
    }



    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     *
     * @return \Illuminate\View\View
     */
    public function edit($id)
    {
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param  int  $id
     *
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function update(Request $request, $id)
    {
        try {
            $attendance = Attendance::find($id);

            if ($attendance->check_out !== null) {
                return response()->json(["error" => true, "message" => "You have been check-out"]);
            }

            $timeDiff = Carbon::now()->diff($attendance->check_in);
            $workingHour = $timeDiff->format('%h:%i:%s');

            $attendance->update([
                'check_out' => Carbon::now(),
                'note_check_out' => $request->note,
                'working_hour' => $workingHour,
            ]);

            return response()->json([
                "error" => false,
                "message" => "Successfuly Updated attendance!"
            ]);
        } catch (Exception $e) {
            return response()->json(["error" => true, "message" => $e->getMessage()]);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     *
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function destroy($id)
    {
    }

    public function employee_attendance(Request $request)
    {
        try {
            $requestData = $request->all();
            // $sickRule = new SickRule();
            $checkInRule = new CheckInRule();
            $manualRule = new ManualRule();
            // $dayOffRule = new DayOfRule();
            $validate = Validator::make(
                $requestData,
                [
                    'att_date'      => [
                        'required',
                        // $sickRule,
                        $checkInRule,
                        $manualRule,
                        // $dayOffRule
                    ],
                    'att_check_in'  => 'required',
                    'att_check_out' => 'required|after:att_check_in',
                    'att_note' => 'required',
                    'attachment' => 'required | mimes:pdf,jpg,png|max:5000',

                ],
                [
                    'att_date.required' => 'The Date field is required.',
                    'att_check_in.required' => 'The Check In field is required.',
                    'att_check_out.required' => 'The Check Out field is required.',
                    'att_check_out.after' => 'The Check Out must be later than check-in time.',
                    'attachment.required' => 'The attachment field is required.',
                    'att_note.required' => 'The note field is required.',
                ]
            );
            if ($validate->fails()) {
                return response()->json([
                    'error' => $validate->errors()->toArray()
                ]);
            }

            $attendance = new Attendance;
            $user_id    = Auth::user()->id;
            $employee   = Employee::where('user_id', $user_id)->first();
            $manager_id = Department::where('department_id', $employee->department_id)->first()->manager;
            $employeeManager = Employee::where('employee_id', $manager_id)->first();
            $userManager = User::find($employeeManager->user_id);

            $attendance->employee_id   = $employee->employee_id;
            $attendance->status        = 'apd';
            $attendance->status_date   = Carbon::now();
            $attendance->date          = $request->att_date;
            $attendance->check_in      = $request->att_date . " " . $request->att_check_in;
            $attendance->check_out     = $request->att_date . " " . $request->att_check_out;
            $attendance->note = $request->att_note;
            $work_time                 = Carbon::parse(date('Y-m-d H:i:s', strtotime($request->att_check_in)))->diff(date('Y-m-d H:i:s', strtotime($request->att_check_out)));
            $work_time                 = $work_time->format('%h:%i:%s');
            $attendance->working_hour  = $work_time;

            $user_workshift = $employee->join('work_shifts', 'employees.work_shift_id', '=', 'work_shifts.work_shift_id')
                ->first();

            if ($request->hasFile('attachment')) {
                $attendance->image_manual_attendance = env('APP_URL') . ('/uploads/') . $request->file('attachment')->store('manual-attendance');
            }

            $attendance->save();

            $notification = Http::withHeaders([
                'Content-Type' => 'application/json',
                'Authorization' => 'Basic ' . env('ONESIGNAL_REST_API_KEY')
            ])->post(env('ONESIGNAL_URL'), [
                'app_id' => env('ONESIGNAL_APP_ID'),
                'android_channel_id' => env('ONESIGNAL_ANDROID_CHANNEL_ID'),
                'small_icon' => "ic_stat_onesignal_default",
                'include_external_user_ids' => [$userManager->email],
                'channel_for_external_user_ids' => "push",
                'data' => ["detail_id" => $attendance->id],
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
                "detail_id" => $attendance->id,
                "is_approval" => true,
            ];

            Notification::create($createNotifications);
        } catch (Exception $e) {
            return response()->json(["error" => true, "message" => $e->getMessage()]);
        }

        return response()->json([
            "error" => false,
            "message" => "Successfuly Added attendance!"
        ]);
    }

    public function attendanceDetail($id)
    {
        $attendances = Attendance::find($id);
        // foreach ($attendances as $attendance) {
        //     $attendanceDetail[] = [
        //         'note_check_in' => $attendance->note_check_in,
        //         'note_check_out' => $attendance->note_check_out,
        //         'image' => $attendance->image_manual_attendance,
        //         'status' => $attendance->status,
        //     ];
        // }

        if ($attendances->image_manual_attendance !== NULL) {
            $AttachmentUrl = explode('/', $attendances->image_manual_attendance);
            $attendanceDetail = [
                'date'           => $attendances->date,
                'note_check_in'  => $attendances->note_check_in,
                'note_check_out' => $attendances->note_check_out,
                'note'           => $attendances->note,
                'image'          => $AttachmentUrl[5],
                'status'         => $attendances->status,
            ];
        } else {
            $attendanceDetail = [
                'date'           => $attendances->date,
                'note_check_in'  => $attendances->note_check_in,
                'note_check_out' => $attendances->note_check_out,
                'image'          => $attendances->image_manual_attendance,
                'note'           => $attendances->note,
                'status'         => $attendances->status,
            ];
        }


        return response()->json([
            "data" => $attendanceDetail,
            "error" => false,
            "message" => "Attendance found!"
        ]);
    }

    public function singleAttendance($id)
    {
        return response()->json([
            "data" => Attendance::find($id),
            "error" => false,
            "message" => "Attendance found!"
        ]);
    }

    public function getTodayAttendance($id)
    {
        $employee = Employee::where('user_id', $id)->first();
        $attendance = Attendance::where('employee_id', $employee->employee_id)->where('date', date("Y-m-d"))->first();
        $lateFormat = null;
        if ($attendance) {
            $late = $attendance->late_duration;
            if ($late != null) {
                function hoursMinutesSeconds($late, $format = '%02d:%02d:%02d')
                {
                    if ($late < 1) {
                        return;
                    }

                    $hours = floor($late / 3600);
                    $minutes = floor(($late / 60) % 60);
                    $seconds = $late % 60;

                    return sprintf($format, $hours, $minutes, $seconds);
                }
                $lateFormat = hoursMinutesSeconds($late, '%02d:%02d:%02d');
            }
        }


        return response()->json([
            "data" => $attendance,
            "late" => $lateFormat,
            "error" => false,
            "message" => "Attendance found!"
        ]);
    }

    public function get_attendance()
    {
        try {
            $today = date('Y-m-d');

            $attendance      = Attendance::whereDate('date', $today)->orderBy('check_in', 'desc')->get();
            $list_attendance = [];

            foreach ($attendance as $item) {

                $dt     = new Carbon($item->check_in);
                $time   = strtotime($dt->toTimeString());
                $result = date('G:i', $time);

                $list_attendance[] = [
                    'id'        => $item->id,
                    'user'      => $item->employees->first_name . " " . $item->employees->last_name,
                    'check_in'  => $result,
                ];
            }
        } catch (Exception $e) {
            return response()->json(["error" => true, "message" => $e->getMessage()]);
        }

        return response()->json($list_attendance);
    }

    public function dailyAttendance(Request $request)
    {
        // Entity
        $branches = Branches::all();
        // SBU
        $departments = Department::all();
        // Job Position
        $positions = JobPosition::all();
        $attendances = Attendance::leftJoin('employees', 'attendances.employee_id', '=', 'employees.employee_id')->leftJoin('branches', 'employees.branch_id', '=', 'branches.branch_id')->leftJoin('department', 'employees.department_id', '=', 'department.department_id')->leftJoin('job_positions', 'employees.job_position_id', '=', 'job_positions.job_position_id');

        if (request('branches')) {
            $departments = Department::where('department_branch_id', $request->branches)->get();
            return response()->json(["error" => false, "departments" => $departments], 200);
        }

        if (request('departments')) {
            $departments = JobPosition::where('department_id', $request->departments)->get();
            return response()->json(["error" => false, "positions" => $departments], 200);
        }

        if (request('date')) {
            $attendances->where('date', $request->date);
        }

        if (request('branch')) {
            $attendances->where('employees.branch_id', $request->branch);
        }

        if (request('department')) {
            $attendances->where('employees.department_id', $request->department);
        }

        if (request('position')) {
            $attendances->where('employees.job_position_id', $request->position);
        }

        if (!request('date')) {
            $attendances->where('date', Carbon::today());
        }

        if (request('export')) {
            $spreadsheet = new Spreadsheet();
            $sheet = $spreadsheet->getActiveSheet();
            $sheet->setCellValue('A1', 'NO');
            $sheet->setCellValue('B1', 'EMPLOYEE NAME');
            $sheet->setCellValue('C1', 'ENTITY');
            $sheet->setCellValue('D1', 'SBU');
            $sheet->setCellValue('E1', 'JOB POSITION');
            $sheet->setCellValue('F1', 'DATE');
            $sheet->setCellValue('G1', 'CHECK-IN');
            $sheet->setCellValue('H1', 'CHECK-OUT');
            $sheet->setCellValue('I1', 'WORK TIME');
            $sheet->setCellValue('J1', 'LATENESS');
            $sheet->setCellValue('K1', 'LATE TIME');
            $sheet->setCellValue('L1', 'OVERTIME');
            $sheet->setCellValue('M1', 'CHECK-IN NOTE');
            $sheet->setCellValue('N1', 'CHECK-OUT NOTE');
            $sheet->setCellValue('O1', 'NOTE MANUAL ATTENDANCE');
            $sheet->setCellValue('P1', 'STATUS');

            $cell = 2;
            $i = 1;
            $data = $attendances->get();
            foreach ($data as $attendance) {
                if ($attendance->check_in) {
                    $checkIn = explode(' ', $attendance->check_in);
                }
                if ($attendance->check_out) {
                    $checkOut = explode(' ', $attendance->check_out);
                }
                $sheet->setCellValue('A' . $cell, $i++);
                $sheet->setCellValue('B' . $cell, $attendance->first_name . ' ' . $attendance->last_name);
                $sheet->setCellValue('C' . $cell, $attendance->branch_name);
                $sheet->setCellValue('D' . $cell, $attendance->department_name);
                $sheet->setCellValue('E' . $cell, $attendance->job_position);
                $sheet->setCellValue('F' . $cell, $attendance->date);
                $sheet->setCellValue('G' . $cell, $attendance->check_in ? substr($checkIn[1], 0, 5) : '-');
                $sheet->setCellValue('H' . $cell, $attendance->check_out ? substr($checkOut[1], 0, 5) : '-');
                $sheet->setCellValue('I' . $cell, $attendance->working_hour !== null ? (int)date('h', floor(strtotime($attendance->working_hour))) . ' H ' . (int)date('i', strtotime($attendance->working_hour)) . ' M' : '-');
                $sheet->setCellValue('J' . $cell, ($attendance->late_duration > 0) ? 'Ya' : 'Tidak');
                $sheet->setCellValue('K' . $cell, $attendance->late_duration > 0 ? floor($attendance->late_duration / 60) . ' H ' . $attendance->late_duration % 60 . ' M' : '-');
                $sheet->setCellValue('L' . $cell, $attendance->overtime_duration > 0 ? floor($attendance->overtime_duration / 60) . ' H ' . $attendance->overtime_duration % 60 . ' M' : '-');
                $sheet->setCellValue('M' . $cell, $attendance->note_check_in);
                $sheet->setCellValue('N' . $cell, $attendance->note_check_out);
                $sheet->setCellValue('O' . $cell, $attendance->note);
                $sheet->setCellValue('P' . $cell, ($attendance->check_in === null) ? 'Tidak Hadir' : 'Hadir');
                $cell++;
            }

            $styleArray = [
                'borders' => [
                    'allBorders' => [
                        'borderStyle' => Border::BORDER_THIN,
                    ],
                ],
            ];

            $sheet->getStyle('A1:O' . ($cell - 1))->applyFromArray($styleArray);
            $filename = 'daily_attendance.xlsx';

            try {
                $writer = new Xlsx($spreadsheet);
                $writer->save($filename);
                $content = file_get_contents($filename);
            } catch (Exception $e) {
                exit($e->getMessage());
            }

            header("Content-Disposition: attachment; filename=" . $filename);
            unlink($filename);
            exit($content);
        }

        return view('attendances.dailyAttendance.index', [
            'branches' => $branches,
            'departments' => $departments,
            'positions' => $positions,
        ]);
    }

    public function dailyAttendanceList($date, $entity, $sbu, $position)
    {
        $attendances = Attendance::leftJoin('employees', 'attendances.employee_id', '=', 'employees.employee_id')->leftJoin('branches', 'employees.branch_id', '=', 'branches.branch_id')->leftJoin('department', 'employees.department_id', '=', 'department.department_id')->leftJoin('job_positions', 'employees.job_position_id', '=', 'job_positions.job_position_id')->select(['attendances.*', 'employees.user_id', 'employees.nip', 'employees.first_name', 'employees.last_name', 'branches.*', 'department.*', 'job_positions.*'])->where('attendances.status', '!==', 'apd' );

        $attendances->where('date', $date);

        if ($entity != 0) {
            $attendances->where('employees.branch_id', $entity);
        }

        if ($sbu != 0) {
            $attendances->where('employees.department_id', $sbu);
        }

        if ($position != 0) {
            $attendances->where('employees.job_position_id', $position);
        }

        return datatables()->of($attendances->get())
            ->addIndexColumn()
            ->addColumn('employee_image', function ($data) {
                return $this->employeeRepository->getEmployeePicture($data->user_id);
            })
            ->addColumn('action', function ($data) {
                return '<button type="button" onclick="show($(this))" data-toggle="modal" data-target="#modal-notes" date="' . $data->date . '" in="' . $data->note_check_in . '" out="' . $data->note_check_out . '" note="' . $data->note . '" class="btn p-0"><img src="./img/icons/note.svg" alt="note"></button>';
            })
            ->rawColumns(['action'])
            ->make(true);
    }

    public function dailyAttendanceDetail($id)
    {
        $attendance = Attendance::where('id', $id)->first();
        if (!$attendance) {
            abort(404);
        }

        $employee = Employee::where('employee_id', $attendance->employee_id)->first();
        $branch = Branches::where('branch_id', $employee->branch_id)->first();
        $department = Department::where('department_id', $employee->department_id)->first();
        $jobPosition = JobPosition::where('job_position_id', $employee->job_position_id)->first();

        $approve1 = 'active';
        if ($attendance->status === 'amw' || $attendance->status === 'amm') {
            $approve1 = 'done';
        }

        if ($attendance->status === 'arj') {
            $approve1 = 'danger';
        }

        if ($attendance->status === 'can') {
            $approve1 = 'disable';
        }

        return view('attendances.dailyAttendance.show', [
            'attendance' => $attendance,
            'employee' => $employee,
            'branch' => $branch,
            'department' => $department,
            'jobPosition' => $jobPosition,
            'approve1' => $approve1,
        ]);
    }

    public function monthlyAttendance(Request $request)
    {

        $branches = Branches::all();

        if (request('branch')) {
            $sbu = Department::where('department_branch_id', $request->branch)->get();
            return ['sbu' => $sbu];
        }

        if (request('sbu')) {
            $job = JobPosition::where('department_id', $request->sbu)->get();
            return ['job_position' => $job];
        }

        if (request('job_position')) {
            $employees = Employee::where('job_position_id', $request->job_position)->get();
            return ['employees' => $employees];
        }
        return view('attendances.monthlyAttendance.index', [
            'branches' => $branches,
        ]);
    }

    public function monthlyStatistic(Request $request)
    {
        $yearMonthDate = explode('-', $request->date);
        $attendances = Attendance::leftJoin('employees', 'attendances.employee_id', '=', 'employees.employee_id')->select(['attendances.*', 'employees.branch_id', 'employees.department_id', 'employees.job_position_id'])->whereYear('date', '=', $yearMonthDate[0])->whereMonth('date', '=', $yearMonthDate[1]);
        $leave = 0;
        $limit = 0;
        $leaveApplications = LeaveApplication::all();

        if (request('entity') > 0) {
            $attendances->where('branch_id',  $request->entity);
        }

        if (request('employee') > 0) {
            $attendances->where('attendances.employee_id', $request->employee);
            $employee = Employee::join('job_classes', 'employees.job_class_id', '=', 'job_classes.job_class_id')->where('employees.employee_id', '=', $request->employee)->first(['first_name', 'last_name', 'nip', 'job_class']);
            $pict = Employee::where('employees.employee_id', '=', $request->employee)->first('image');
            $image = $pict->image === null || $pict->image == '' ? asset('img/profile.png') : asset('uploads/' . $pict['image']);
            $leaveApplication = LeaveApplication::where('employee_id', $request->employee)->latest('created_at')->first();
            if ($leaveApplication !== null) {
                $leavePeriod = LeavePeriod::where('leave_period_id', $leaveApplication->leave_period_id)->first();
                $limit = $leavePeriod->limit;
            }
            $employeeLeaveAplications = LeaveApplication::where('employee_id', $request->employee)->where('status', 'lhy')->get();
            foreach ($leaveApplications as $item) {
                foreach ($employeeLeaveAplications as $data) {
                    if ($item->leave_application_id == $data->leave_application_id) {
                        $leave = $leave + 1;
                    }
                }
            }
        }

        $lateAmount = $attendances->sum('late_duration');
        $late = DateHelpers::minutesToHours($lateAmount);


        $overtimeAmount = $attendances->sum('overtime_duration');
        $overtimes = DateHelpers::minutesToHours($overtimeAmount);

        $attend = $attendances->where('attendances.status', '!=', 'can')
            ->where('attendances.status', '!=', 'aab')
            ->where('attendances.status', '!=', 'ado')
            ->where('attendances.status', '!=', 'asc')
            ->where('attendances.status', '!=', 'apm')
            ->where('attendances.status', '!=', 'arj')->get();
        $absence = $attendances->where('attendances.status', '=', 'aab')->get();

        $workday = count($absence) + count($attend);

        $data = [
            'overtime' => $overtimes->h . " " . "H" . " " . $overtimes->m . " " . "M",
            'late' => $late->h . " " . "H" . " " . $late->m . " " . "M",
            'absence' => count($absence),
            'attend' => count($attend),
            'leave' => $limit - $leave,
            'workday' => $workday,
            'employee' => $employee->first_name . " " . $employee->last_name,
            'nip' => $employee->nip,
            'jobclass' => $employee->job_class,
            'image' => $image
        ];

        return $data;
    }

    public function getMonthlyAttendance(Request $request)
    {
        $attendance = Attendance::join('employees', 'attendances.employee_id', '=', 'employees.employee_id')
            ->join('work_shifts', 'employees.work_shift_id', '=', 'work_shifts.work_shift_id')
            ->orderBy('date', 'desc');

        if (request('date') != 0) {
            $yearMonthDate = explode('-', $request->date);
            $attendance->whereYear('date', '=', $yearMonthDate[0])->whereMonth('date', '=', $yearMonthDate[1]);
        }

        if (request('entity') > 0) {
            $attendance->where('branch_id',  $request->entity);
        }

        if (request('sbu') > 0) {
            $attendance->where('department_id',  $request->sbu);
        }

        if (request('job_position') > 0) {
            $attendance->where('job_position_id',  $request->job_position);
        }

        if (request('employee') > 0) {
            $attendance->where('employees.employee_id', $request->employee);
        }

        if (request('export')) {
            $spreadsheet = new Spreadsheet();
            $sheet = $spreadsheet->getActiveSheet();
            $sheet->setCellValue('A1', 'NO');
            $sheet->setCellValue('B1', 'EMPLOYEE NAME');
            // $sheet->setCellValue('C1', 'ENTITY');
            // $sheet->setCellValue('D1', 'SBU');
            // $sheet->setCellValue('E1', 'JOB POSITION');
            $sheet->setCellValue('C1', 'DATE');
            $sheet->setCellValue('D1', 'CHECK-IN');
            $sheet->setCellValue('E1', 'CHECK-OUT');
            $sheet->setCellValue('F1', 'WORK TIME');
            $sheet->setCellValue('G1', 'LATENESS');
            $sheet->setCellValue('H1', 'LATE TIME');
            $sheet->setCellValue('I1', 'OVERTIME');
            $sheet->setCellValue('J1', 'SHIFT');
            $sheet->setCellValue('K1', 'CHECK-IN NOTE');
            $sheet->setCellValue('L1', 'CHECK-OUT NOTE');
            $sheet->setCellValue('M1', 'NOTE MANUAL ATTENDANCE');
            $sheet->setCellValue('N1', 'STATUS');

            $cell = 2;
            $i = 1;
            $data = $attendance->get();
            foreach ($data as $attend) {
                if ($attend->check_in) {
                    $checkIn = explode(' ', $attend->check_in);
                }

                if ($attend->check_out) {
                    $checkOut = explode(' ', $attend->check_out);
                }
                $sheet->setCellValue('A' . $cell, $i++);
                $sheet->setCellValue('B' . $cell, $attend->first_name . ' ' . $attend->last_name);
                $sheet->setCellValue('C' . $cell, $attend->date);
                $sheet->setCellValue('D' . $cell, $attend->check_in ? substr($checkIn[1], 0, 5) : '-');
                $sheet->setCellValue('E' . $cell, $attend->check_out ? substr($checkOut[1], 0, 5) : '-');
                $sheet->setCellValue('F' . $cell, $attend->working_hour !== null ? (int)date('h', floor(strtotime($attend->working_hour))) . ' H ' . (int)date('i', strtotime($attend->working_hour)) . ' M' : '-');
                $sheet->setCellValue('G' . $cell, ($attend->late_duration > 0) ? 'Ya' : 'Tidak');
                $sheet->setCellValue('H' . $cell, $attend->late_duration > 0 ? floor($attend->late_duration / 60) . ' H ' . $attend->late_duration % 60 . ' M' : '-');
                $sheet->setCellValue('I' . $cell, $attend->overtime_duration > 0 ? floor($attend->overtime_duration / 60) . ' H ' . $attend->overtime_duration % 60 . ' M' : '-');
                $sheet->setCellValue('J' . $cell, $attend->shift_name);
                $sheet->setCellValue('K' . $cell, $attend->note_check_in);
                $sheet->setCellValue('L' . $cell, $attend->note_check_out);
                $sheet->setCellValue('M' . $cell, $attend->note);
                $sheet->setCellValue('N' . $cell, ($attend->check_in === null) ? 'Tidak Hadir' : 'Hadir');
                $cell++;
            }

            $styleArray = [
                'borders' => [
                    'allBorders' => [
                        'borderStyle' => Border::BORDER_THIN,
                    ],
                ],
            ];

            $sheet->getStyle('A1:O' . ($cell - 1))->applyFromArray($styleArray);
            $filename = 'monthly_attendance.xlsx';

            try {
                $writer = new Xlsx($spreadsheet);
                $writer->save($filename);
                $content = file_get_contents($filename);
            } catch (Exception $e) {
                exit($e->getMessage());
            }

            header("Content-Disposition: attachment; filename=" . $filename);
            unlink($filename);
            exit($content);
        }

        $AttendanceData = [];

        foreach ($attendance->get(['attendances.*', 'work_shifts.shift_name']) as $attendance_list) {
            if ($attendance_list->status !== 'apd') {
            $AttendanceData[] = (object) [
                'id'                => $attendance_list->id,
                'employee_id'       => $attendance_list->employee_id,
                'date'              => date('d/m/Y', strtotime($attendance_list->date)),
                'day'               => date('l', strtotime($attendance_list->date)),
                'check_in'          => $attendance_list->check_in,
                'check_out'         => $attendance_list->check_out,
                'working_hour'      => $attendance_list->working_hour,
                'lateness'          => $attendance_list->lateness,
                'late_duration'     => $attendance_list->late_duration,
                'overtime_duration' => $attendance_list->overtime_duration,
                'shift'             => $attendance_list->shift_name,
                'notes'             => $attendance_list->notes,
                'status'            => $attendance_list->status,
                'status_code'       => $attendance_list->status
            ];
            }
        }

        return DataTables::of($AttendanceData)
            ->addColumn('notes', function ($row) {
                if ($row->status === 'acw' || $row->status === 'acm') {
                    $actionBtn  = '<a onclick=notes($(this)) data-id="' . $row->id . '"  class="edit btn p-0 mr-md-1"><img src="./img/icons/note.svg" alt="note"></a>';
                    return $actionBtn;
                } else {
                    $actionBtn  = '<a onclick=notes($(this)) data-id="' . $row->id . '"  class="edit btn p-0 mr-md-1"><img src="./img/icons/note-green.svg" alt="note"></a>';
                    return $actionBtn;
                }
            })
            ->rawColumns(['notes'])
            ->toJson(true);
    }

    // private $month;
    public function overallAttendance(Request $request)
    {
        $getOverallAttendance = $this->getOverallAttandance($request);

        // dd($getOverallAttendance);
        return view('attendances.overallAttendance/index', [
            // 'branches' => $branches,
            'results' => $getOverallAttendance['dataFormat'],
            'monthToDate'   => $getOverallAttendance['monthToDate'],
            'month'         => $getOverallAttendance['month'],
            'leaveTypes'    =>  $getOverallAttendance['leaveTypes'],
            'monthName'     => $getOverallAttendance['monthName'],
            'startName'     =>  $getOverallAttendance['startName'],
            'endName'       => $getOverallAttendance['endName'],
            'branch_id' => $getOverallAttendance['branch_id'],
            'workday' => $getOverallAttendance['workday']
        ]);
    }

    public function getOverallAttandance(Request $request)
    {

        if (request('branch_id')) {
            $branch_id = $request->branch_id;
        } else {
            $branch_id = 1;
        }

        if (request('date')) {
            $yearMonthDate = explode('-', $request->date);
            $month = $yearMonthDate[0] . '-' . $yearMonthDate[1];
        } else {
            $month = date("Y-m");
        }

        $start  =  $month . '-01';
        $end    = date("Y-m-t", strtotime($start));
        $monthAndYear   = explode('-',  $month);
        $month_data     = $monthAndYear[1];
        $dateObj        = DateTime::createFromFormat('!m', $month_data);
        $monthName      = $dateObj->format('F');
        $startName      = DateTime::createFromFormat('Y-m-d', $start)->format('d F');
        $endName        = DateTime::createFromFormat('Y-m-d', $end)->format('d F');

        $monthToDate   = $this->rangeDateToAllDate($start, $end);
        $leaveType     = LeaveType::get();

        // dd()

        $start = Carbon::createFromFormat('Y-m-d H:i:s', $start . ' 00:00:00');
        $end = Carbon::createFromFormat('Y-m-d H:i:s', $end . ' 23:59:59');

        $employees = Employee::leftjoin('department', 'employees.department_id', '=', 'department.department_id')
            ->leftjoin('branches', 'employees.branch_id', '=', 'branches.branch_id')
            ->leftjoin('job_positions', 'employees.job_position_id', '=', 'job_positions.job_position_id')
            ->with('attendances')
            ->where('employees.branch_id',  $branch_id)->get();

        if (request('department_id')) {
            if (!$request->department_id == 0) {
                $employees = Employee::leftjoin('department', 'employees.department_id', '=', 'department.department_id')
                    ->leftjoin('branches', 'employees.branch_id', '=', 'branches.branch_id')
                    ->leftjoin('job_positions', 'employees.job_position_id', '=', 'job_positions.job_position_id')
                    ->with('attendances')
                    ->where('employees.department_id',  $request->department_id)
                    ->where('employees.branch_id',  $branch_id)->get();
            }
        }
        // dd($employees);
        $attendance = Attendance::whereBetween('check_in', [$start, $end])->get();

        $getWorkday = Workdays::leftJoin('branches', 'workdays.branch_id', '=', 'branches.branch_id')->leftJoin('work_shifts', 'workdays.work_shift_id', '=', 'work_shifts.work_shift_id')->leftJoin('days', 'workdays.days_id', '=', 'days.days_id');


        $getWorkday->where('workdays.branch_id', $branch_id);


        $getWorkday = $getWorkday->get();

        foreach ($getWorkday as $day) {
            $workdays[] = substr(Days::where('days_id', $day->days_id)->first()->day_name, 0, 3);
        }


        // dd($monthToDate);

        $dataFormat = [];
        $tempArray  = [];
        $workday = 0;
        foreach ($employees as $employee) {
            $tempArray['late_duration'] = 0;
            $tempArray['overtime_duration'] = 0;

            foreach ($monthToDate as $key => $value) {
                $workday++;
                $tempArray['employee_id']      = $employee->employee_id;
                $tempArray['designation_name'] = $employee->department_name;
                $tempArray['entity'] = $employee->branch_name;
                $tempArray['position'] = $employee->job_position;
                $tempArray['nip'] = $employee->nip;
                $tempArray['image'] = $this->employeeRepository->getEmployeePicture($employee->user_id);
                $leaveTypeId = LeaveType::where("leave_type_name", "Annual Leave")->get("leave_type_id")->first()["leave_type_id"];
                $leave_period_id =  LeavePeriod::whereYear("from_date", "<=", date('Y'))
                    ->whereMonth("from_date", '<=', date('m'))
                    ->whereYear("to_date", ">=", date('Y'))
                    ->whereMonth("to_date", ">=", date('m'))
                    ->get()->first()["leave_period_id"];
                $tempArray['leave_balance'] = $this->leaveApplicationRepository->calculateEmployeeLeaveBalance($leaveTypeId, $employee->employee_id, $leave_period_id);

                foreach ($employee->attendances as $val) {
                    if ($val->check_in > $start && $val->check_in < $end) {
                        $tempArray['late_duration'] += $val->late_duration;
                        $tempArray['overtime_duration'] += $val->overtime_duration;
                    }
                }

                $tempArray['date']             = $value['date'];
                $tempArray['day']              = $value['day'];
                $tempArray['day_name']         = $value['day_name'];

                $hasAttandance = $this->hasEmployeeAttendance($attendance, $employee->employee_id,  $value['date']);

                $branchWorkday = $this->branchWorkday($tempArray['day_name'], $workdays);
                // dd($workdays);

                if ($branchWorkday) {
                    if ($hasAttandance) {
                        $tempArray['attendance_status']  = 'present';
                        $tempArray['leave_type']     = 'no';
                    } else {
                        $hasLeave = $this->ifEmployeeWasLeave($employee->employee_id, $value['date']);
                        if ($hasLeave) {
                            $tempArray['attendance_status']  = 'leave';
                        } else {
                            if ($value['date'] > date("Y-m-d")) {
                                $tempArray['attendance_status']  = '';
                            } else {
                                $hasAbsence = $this->ifEmployeeAbsence($attendance, $employee->employee_id, $value['date']);
                                if ($hasAbsence) {
                                    $tempArray['attendance_status']  = 'absence';
                                } else {
                                    $tempArray['attendance_status']  = 'absence';
                                }
                            }
                        }
                    }
                } else {
                    $tempArray['attendance_status']  = 'notWorkday';
                }
                $dataFormat[$employee->first_name . " " . $employee->last_name][] = $tempArray;
            }
        }
        // dd($dataFormat);
        if (!sizeof($employees) == 0)  $workday = $workday / sizeof($employees);

        $data = [];
        $data['dataFormat'] = $dataFormat;
        $data['monthToDate'] = $monthToDate;
        $data['month'] = $month;
        $data['leaveTypes'] = $leaveType;
        $data['monthName'] = $monthName;
        $data['startName'] = $startName;
        $data['endName'] = $endName;
        $data['branch_id'] = $branch_id;
        $data['workday'] = $workday;

        // dd($data);
        return $data;
    }

    public function branchWorkday($day, $workdays)
    {
        // dd($workdays);
        if (in_array($day, $workdays)) {
            return true;
        }

        return false;
    }

    function downloadoverallAttendance(Request $request)
    {

        if ($request->branch_id != 0) {
            $branch_id = $request->branch_id;
        } else {
            $branch_id = 1;
        }

        if ($request->date != date("Y-m")) {
            $yearMonthDate = explode('-', $request->date);
            $month = $yearMonthDate[0] . '-' . $yearMonthDate[1];
        } else {
            $month = date("Y-m");
        }

        $start  =  $month . '-01';
        $end    = date("Y-m-t", strtotime($start));
        $monthAndYear   = explode('-',  $month);
        $month_data     = $monthAndYear[1];
        $dateObj        = DateTime::createFromFormat('!m', $month_data);
        $monthName      = $dateObj->format('F');
        $startName      = DateTime::createFromFormat('Y-m-d', $start)->format('d F');
        $endName        = DateTime::createFromFormat('Y-m-d', $end)->format('d F');

        $monthToDate   = $this->rangeDateToAllDate($start, $end);
        $leaveType     = LeaveType::get();

        // dd()

        $start = Carbon::createFromFormat('Y-m-d H:i:s', $start . ' 00:00:00');
        $end = Carbon::createFromFormat('Y-m-d H:i:s', $end . ' 23:59:59');

        $employees = Employee::leftjoin('department', 'employees.department_id', '=', 'department.department_id')
            ->leftjoin('branches', 'employees.branch_id', '=', 'branches.branch_id')
            ->leftjoin('job_positions', 'employees.job_position_id', '=', 'job_positions.job_position_id')
            ->with('attendances')
            ->where('employees.branch_id',  $branch_id)->get();

        if (request('department_id')) {
            if (!$request->department_id == 0) {
                $employees = Employee::leftjoin('department', 'employees.department_id', '=', 'department.department_id')
                    ->leftjoin('branches', 'employees.branch_id', '=', 'branches.branch_id')
                    ->leftjoin('job_positions', 'employees.job_position_id', '=', 'job_positions.job_position_id')
                    ->with('attendances')
                    ->where('employees.department_id',  $request->department_id)
                    ->where('employees.branch_id',  $branch_id)->get();
            }
        }
        // dd($employees);
        $attendance = Attendance::whereBetween('check_in', [$start, $end])->get();

        $getWorkday = Workdays::leftJoin('branches', 'workdays.branch_id', '=', 'branches.branch_id')->leftJoin('work_shifts', 'workdays.work_shift_id', '=', 'work_shifts.work_shift_id')->leftJoin('days', 'workdays.days_id', '=', 'days.days_id');


        $getWorkday->where('workdays.branch_id', $branch_id);


        $getWorkday = $getWorkday->get();

        foreach ($getWorkday as $day) {
            $workdays[] = substr(Days::where('days_id', $day->days_id)->first()->day_name, 0, 3);
        }


        // dd($monthToDate);

        $dataFormat = [];
        $tempArray  = [];
        $workday = 0;
        foreach ($employees as $employee) {
            $tempArray['late_duration'] = 0;
            $tempArray['overtime_duration'] = 0;

            foreach ($monthToDate as $key => $value) {
                $workday++;
                $tempArray['employee_id']      = $employee->employee_id;
                $tempArray['designation_name'] = $employee->department_name;
                $tempArray['entity'] = $employee->branch_name;
                $tempArray['position'] = $employee->job_position;
                $leaveTypeId = LeaveType::where("leave_type_name", "Annual Leave")->get("leave_type_id")->first()["leave_type_id"];
                $leave_period_id =  LeavePeriod::whereYear("from_date", "<=", date('Y'))
                    ->whereMonth("from_date", '<=', date('m'))
                    ->whereYear("to_date", ">=", date('Y'))
                    ->whereMonth("to_date", ">=", date('m'))
                    ->get()->first()["leave_period_id"];
                $tempArray['leave_balance'] = $this->leaveApplicationRepository->calculateEmployeeLeaveBalance($leaveTypeId, $employee->employee_id, $leave_period_id);

                foreach ($employee->attendances as $val) {
                    if ($val->check_in > $start && $val->check_in < $end) {
                        $tempArray['late_duration'] += $val->late_duration;
                        $tempArray['overtime_duration'] += $val->overtime_duration;
                    }
                }

                $tempArray['date']             = $value['date'];
                $tempArray['day']              = $value['day'];
                $tempArray['day_name']         = $value['day_name'];

                $hasAttandance = $this->hasEmployeeAttendance($attendance, $employee->employee_id,  $value['date']);

                $branchWorkday = $this->branchWorkday($tempArray['day_name'], $workdays);

                if ($branchWorkday) {
                    if ($hasAttandance) {
                        $tempArray['attendance_status']  = 'present';
                        $tempArray['leave_type']     = 'no';
                    } else {
                        $hasLeave = $this->ifEmployeeWasLeave($employee->employee_id, $value['date']);
                        if ($hasLeave) {
                            $tempArray['attendance_status']  = 'leave';
                        } else {
                            if ($value['date'] > date("Y-m-d")) {
                                $tempArray['attendance_status']  = '';
                            } else {
                                $hasAbsence = $this->ifEmployeeAbsence($attendance, $employee->employee_id, $value['date']);
                                if ($hasAbsence) {
                                    $tempArray['attendance_status']  = 'absence';
                                } else {
                                    $tempArray['attendance_status']  = 'absence';
                                }
                            }
                        }
                    }
                } else {
                    $tempArray['attendance_status']  = 'notWorkday';
                }
                $dataFormat[$employee->first_name . " " . $employee->last_name][] = $tempArray;
            }
        }
        if (!sizeof($employees) == 0)  $workday = $workday / sizeof($employees);


        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        $total_col = 0;
        foreach ($monthToDate as $key => $val) {
            $total_col++;
        }

        // $total_col += 7;

        $sheet->setCellValue('A1', 'NO');
        $sheet->setCellValue('B1', 'TAHUN');
        $sheet->setCellValue('C1', 'BULAN');
        $sheet->setCellValue('D1', '');
        $sheet->setCellValue('E1', '');
        $sheet->setCellValue('F1', '');

        $sheet->setCellValue('A2', '#');
        $sheet->setCellValue('B2', date("Y"));
        $sheet->setCellValue('C2', $monthName);
        $sheet->setCellValue('D2', '');
        $sheet->setCellValue('E2', '');
        $day = 'F';
        foreach ($monthToDate as $head) {
            $sheet->setCellValue($day . '2', $head['day_name']);
            $day++;
        }
        $sheet->setCellValue($day++ . '2', 'Workday');
        $sheet->setCellValue($day++ . '2', 'Attend');
        $sheet->setCellValue($day++ . '2', 'Absence');
        $sheet->setCellValue($day++ . '2', 'Late Time');
        $sheet->setCellValue($day++ . '2', 'Total Leave');
        $sheet->setCellValue($day++ . '2', 'Leave Balance');
        $sheet->setCellValue($day++ . '2', 'Overtime');





        $sheet->setCellValue('A3', '#');
        $sheet->setCellValue('B3', 'Employee Name');
        $sheet->setCellValue('C3', 'Entity');
        $sheet->setCellValue('D3', 'SBU');
        $sheet->setCellValue('E3', 'Job Position');

        $date = 'F';
        foreach ($monthToDate as $head) {
            $sheet->setCellValue($date . '3', $head['day']);
            $date++;
        }
        // $sheet->setCellValue($date++ . '3', '#');
        for ($i = 0; $i < 6; $i++) {
            $sheet->setCellValue($date . '3', '#');
            $date++;
        }
        // dd( $value[0]['late_duration']);
        $i = 1;
        $cell = 4;
        $sl = null;
        $totalPresent = 0;
        $totalAbsence = 0;
        $totalLeave = 0;
        $leaveData = [];
        $totalCol = 0;
        $total_day = $workday;

        // myAttendance

        foreach ($dataFormat as $key => $value) {
            $sheet->setCellValue('A' . $cell, $i++);
            $sheet->setCellValue('B' . $cell, $key);
            $sheet->setCellValue('C' . $cell, $value[0]['entity']);
            $sheet->setCellValue('D' . $cell, $value[0]['designation_name']);
            $sheet->setCellValue('E' . $cell, $value[0]['position']);
            $p = 'F';
            $leaveData = [];
            $totalPresent = 0;
            // dd($leaveData);
            $hours = floor($value[0]['late_duration'] / 60);
            $min = $value[0]['late_duration'] - ($hours * 60);
            $value[0]['late_duration'] = $hours . ' H ' . $min . ' M';

            foreach ($value as $v) {
                if ($v['attendance_status'] == 'notWorkday') {
                    $sheet->setCellValue($p . $cell, '-');
                    $total_day--;
                } elseif ($v['attendance_status'] == 'present') {
                    $sheet->setCellValue($p . $cell, 'P');
                    $totalPresent++;
                } elseif ($v['attendance_status'] == 'leave') {
                    $sheet->setCellValue($p . $cell, 'L');
                    $totalLeave++;
                } elseif ($v['attendance_status'] == 'absence') {
                    $sheet->setCellValue($p . $cell, 'A');
                    $totalAbsence++;
                } else {
                    $sheet->setCellValue($p . $cell, '');
                }
                $p++;
            }



            $sheet->setCellValue($p++ . $cell, $total_day . ' Days');
            $sheet->setCellValue($p++ . $cell, $totalPresent);
            $sheet->setCellValue($p++ . $cell, $totalAbsence);
            $sheet->setCellValue($p++ . $cell, $value[0]['late_duration']);
            $sheet->setCellValue($p++ . $cell, $totalLeave);
            $sheet->setCellValue($p++ . $cell, $value[0]['leave_balance']);
            $sheet->setCellValue($p++ . $cell, $value[0]['overtime_duration'] / 60 . " H");

            $totalPresent = 0;
            $totalAbsence = 0;
            $totalLeave = 0;
            $cell++;
            $total_day = $workday;
        }

        $styleArray = [
            'borders' => [
                'allBorders' => [
                    'borderStyle' => Border::BORDER_THIN,
                ],
            ],
        ];

        $sheet->getStyle('A1:' . $p . ($cell - 1))->applyFromArray($styleArray);

        $sheet->getStyle('A1:' . $p . '1')->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB('EBEAEF');
        $sheet->getStyle('A3:' . $p . '3')->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB('EBEAEF');
        $filename = 'overall_attendance.xlsx';

        try {
            $writer = new Xlsx($spreadsheet);
            $writer->save($filename);
            $content = file_get_contents($filename);
        } catch (Exception $e) {
            exit($e->getMessage());
        }

        header("Content-Disposition: attachment; filename=" . $filename);
        unlink($filename);
        exit($content);
    }


    function rangeDateToAllDate($start_date, $end_date)
    {

        $target      = strtotime($start_date);
        $workingDate = [];
        while ($target <= strtotime(date("Y-m-d", strtotime($end_date)))) {
            $temp = [];
            $temp['date'] = date('Y-m-d', $target);
            $temp['day']  = date('d', $target);
            $temp['day_name']  = date('D', $target);
            $workingDate[] = $temp;
            $target += (60 * 60 * 24);
        }
        return $workingDate;
    }

    public function hasEmployeeAttendance($attendance, $employee_id, $date)
    {

        // dd($attendance);
        foreach ($attendance as $key => $val) {
            $val->check_in = Carbon::parse($val->check_in)->format('Y-m-d');
            if ($val->employee_id == $employee_id && $val->check_in == $date && ($val->status == "acw" || $val->status == "acm" || $val->status == "amm" || $val->status == "amw")) {
                return true;
            }
        }
        return false;
    }

    public function ifEmployeeWasLeave($employee_id, $date)
    {
        $leave = LeaveApplication::leftJoin('leave_types', 'leave_applications.leave_type_id', '=', 'leave_types.leave_type_id')->get();
        $leaveRecord = [];
        $temp        = [];
        foreach ($leave as $value) {
            if ($employee_id == $value->employee_id && $value->status == 'lhy') {
                $start_date = $value->application_from_date;
                $end_date   = $value->application_to_date;
                while (strtotime($start_date) <= strtotime($end_date)) {
                    $temp['employee_id']        = $employee_id;
                    $temp['date']               = $start_date;
                    $temp['leave_type_name']    = $value->leave_type_name;
                    $leaveRecord[]              = $temp;
                    $start_date                 = date("Y-m-d", strtotime("+1 day", strtotime($start_date)));
                }
            }
        }

        for ($i = 0; $i < sizeof($leaveRecord); $i++) {
            if (($leaveRecord[$i]['employee_id'] == $employee_id && $leaveRecord[$i]['date'] == $date . ' 00:00:00')) {
                // dd($leaveRecord[$i]['date'], $date . ' 00:00:00');
                return $leaveRecord[$i]['leave_type_name'];
            }

            if (($leaveRecord[$i]['employee_id'] == $employee_id && $leaveRecord[$i]['date'] == $date)) {
                return $leaveRecord[$i]['leave_type_name'];
            }
        }

        return false;
    }

    public function ifEmployeeAbsence($attendances, $employee_id, $date)
    {

        foreach ($attendances as $key => $val) {
            $val->check_in = Carbon::parse($val->check_in)->format('Y-m-d');
            if ($val->employee_id == $employee_id &&  $val->check_in == $date && ($val->status == "aab" || $val->status == "arj")) {
                return true;
            }
        }
        return false;
    }
}
