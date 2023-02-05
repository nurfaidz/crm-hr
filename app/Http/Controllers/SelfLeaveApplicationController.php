<?php

namespace App\Http\Controllers;

use App\Interfaces\LeaveApplicationInterface;
use App\Models\Department;
use App\Models\Employee;
use App\Models\LeaveApplication;
use App\Models\LeavePeriod;
use App\Models\LeaveType;
use App\Models\Notification;
use App\Models\OptionLeave;
use App\Models\User;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class SelfLeaveApplicationController extends Controller
{
    private LeaveApplicationInterface $leaveApplicationInterface;

    public function __construct(LeaveApplicationInterface $leaveApplicationInterface)
    {
        $this->middleware('permission:self_leave_application.list', ['only' => ['index', 'data', 'list', 'details', 'download']]);
        $this->middleware('permission:self_leave_application.create', ['only' => 'store']);
        $this->middleware('permission:self_leave_application.cancel', ['only' => 'cancel']);

        $this->leaveApplicationInterface = $leaveApplicationInterface;
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $publicHolidays = $this->leaveApplicationInterface->getAllHolidays();

        $leaveTypes = LeaveType::all();
        $optionLeaves = OptionLeave::all();

        $employeeId = Auth::user()->employee->employee_id;
        $joinDate = Employee::where('employee_id', '=', $employeeId)->first();
        $joinDate = $joinDate['date_of_joining'];

        $parseJoinDate = Carbon::parse($joinDate);
        $joinDateDuration = $parseJoinDate->diffInYears(Carbon::now()->toDateString());

        return view('self-leave-application.index', compact('publicHolidays', 'leaveTypes', 'optionLeaves', 'joinDate', 'joinDateDuration'));
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
                'application_to_date.after' => 'The end date field must be after form date.',
                'notes.required' => 'The reason for leave field is required.'
            ]
        );
        if ($validate->fails()) {
            return response()->json([
                'error' => $validate->errors()->toArray()
            ]);
        } else {
            $employeeId = Auth::user()->employee->employee_id;
            $date = Carbon::now();
            $attachment = [];
            $employee   = Employee::where('user_id', Auth::user()->id)->first();
            $manager_id = Department::where('department_id', $employee->department_id)->first()->manager;
            $employeeManager = Employee::where('employee_id', $manager_id)->first();
            $userManager = User::find($employeeManager->user_id);

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

                    $counter = 0;
                    $usedAndRequests = $this->leaveApplicationInterface->getAllLeaveApplications()->where('leave_period_id', $data['leave_period_id'])->where('short', 'Approved')->where('state', 'real');
                    $getBalance = LeavePeriod::where('leave_period_id', '=', $data['leave_period_id'])->first();
                    $getBalance = $getBalance['limit'];

                    foreach ($usedAndRequests as $usedAndRequest) {
                        $counter += $usedAndRequest['number_of_day'];
                    }

                    $data['balance'] = $getBalance - $counter;
                    $data['used'] = $counter;
                }
            }

            if ($request->hasFile('attachment')) {
                if ($files = $request->file('attachment')) {
                    foreach ($files as $file) {
                        $name = Str::random('20') . '-' . $file->getClientOriginalName();
                        $file->move('uploads/leave-applications/', $name);
                        $attachment[] = $name;
                    }
                }
            }

            $data['application_from_date'] = $startDate;
            $data['application_to_date'] = $endDate;
            $data['number_of_day'] = $parseEndDate->diffInDays($parseStartDate) + 1;
            $data['status'] = 'lpd';
            $data['state'] = 'real';
            $data['attachment'] = 'leave-applications/' . implode('|', $attachment);

            $leaveNotif = $this->leaveApplicationInterface->createLeaveApplication($data);

            $notification = Http::withHeaders([
                'Content-Type' => 'application/json',
                'Authorization' => 'Basic ' . env('ONESIGNAL_REST_API_KEY')
            ])->post(env('ONESIGNAL_URL'), [
                'app_id' => env('ONESIGNAL_APP_ID'),
                'android_channel_id' => env('ONESIGNAL_ANDROID_CHANNEL_ID'),
                'small_icon' => "ic_stat_onesignal_default",
                'include_external_user_ids' => [$userManager->email],
                'channel_for_external_user_ids' => "push",
                'data' => ["detail_id" => $leaveNotif->id],
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
                "detail_id" => $leaveNotif->id,
                "is_approval" => true,
            ];

            Notification::create($createNotifications);

            return response()->json([
                'error' => false,
                'message' => 'Successfully Requested Leave Application'
            ]);
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

    public function statistic($year)
    {
        $employeeId = Auth::user()->employee->employee_id;
        $statistics = $this->leaveApplicationInterface->statistics($employeeId, $year);

        return response()->json([
            'data' => $statistics,
            'error' => false
        ]);
    }

    public function data()
    {
        $pendingStatusArray = [];
        $pendingStatuses = $this->leaveApplicationInterface->getAllLeaveApplications()->where('employee_id', Auth::user()->employee->employee_id)->where('state', 'real')->where('code', 'lpd')->sortByDesc('application_date');

        foreach ($pendingStatuses as $pendingStatus) {
            $startDate = explode('-', $pendingStatus->application_from_date);
            $endDate = explode('-', $pendingStatus->application_to_date);
            $total = Carbon::parse($pendingStatus->application_to_date)->diffInDays($pendingStatus->application_from_date) + 1;

            $pendingStatusArray[] = [
                'leave_application_id' => $pendingStatus->leave_application_id,
                'leave_type_name' => $pendingStatus->leave_type_name,
                'application_from_date' => $startDate[2] . '/' . $startDate[1] . '/' . $startDate[0],
                'application_to_date' => $endDate[2] . '/' . $endDate[1] . '/' . $endDate[0],
                'total' => $total . ' Hari',
                'status' => $pendingStatus->short
            ];
        }

        return response()->json([
            'data' => $pendingStatusArray,
            'error' => false
        ]);
    }

    public function list($type, $status)
    {
        $leaveHistory = $this->leaveApplicationInterface->getAllLeaveApplications()->where('employee_id', Auth::user()->employee->employee_id)->where('state', 'real')->where('code', '!=', 'lpd')->sortByDesc('application_date');

        if ($type != 0) {
            $leaveHistory->where('leave_type_id', $type);
        }

        if ($status != 0) {
            $leaveHistory->where('status', $status);
        }

        return datatables()->of($leaveHistory)
            ->addIndexColumn()
            ->addColumn('action', function ($data) {
                return '
                    <a href="' . url('self-leave-application/details') . '/' . $data->leave_application_id . '" id="pending-status-details" class="btn btn-outline-info round pending-status-details">Details</a>
                ';
            })
            ->rawColumns(['action'])
            ->make(true);
    }

    public function export()
    {
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $leaveApplications = $this->leaveApplicationInterface->getAllLeaveApplications()->where('employee_id', Auth::user()->employee->employee_id)->where('state', 'real')->where('code', '!=', 'lpd');

        $sheet->setCellValue('A1', 'Employee ID')
            ->setCellValue('B1', 'Employee Name')
            ->setCellValue('C1', 'Entity')
            ->setCellValue('D1', 'SBU')
            ->setCellValue('E1', 'Job Position')
            ->setCellValue('F1', 'Type of Leave')
            ->setCellValue('G1', 'Start Date')
            ->setCellValue('H1', 'End Date')
            ->setCellValue('I1', 'Total')
            ->setCellValue('J1', 'Status');

        $i = 2;
        foreach ($leaveApplications as $leaveApplication) {
            $startDate = explode('-', $leaveApplication->application_from_date);
            $startDate = $startDate[2] . '/' . $startDate[1] . '/' . $startDate[0];
            $endDate = explode('-', $leaveApplication->application_to_date);
            $endDate = $endDate[2] . '/' . $endDate[1] . '/' . $endDate[0];

            $sheet->setCellValue('A' . $i, $leaveApplication->nip)
                ->setCellValue('B' . $i, $leaveApplication->first_name . ' ' . $leaveApplication->last_name)
                ->setCellValue('C' . $i, $leaveApplication->branch_name)
                ->setCellValue('D' . $i, $leaveApplication->department_name)
                ->setCellValue('E' . $i, $leaveApplication->job_position)
                ->setCellValue('F' . $i, $leaveApplication->leave_type_name)
                ->setCellValue('G' . $i, $startDate)
                ->setCellValue('H' . $i, $endDate)
                ->setCellValue('I' . $i, $leaveApplication->number_of_day . ' Hari')
                ->setCellValue('J' . $i, $leaveApplication->long);

            $i++;
        }

        $writer = new Xlsx($spreadsheet);

        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment; filename="' . urlencode('LeaveApplication.xlsx') . '"');

        $writer->save('php://output');
    }

    public function cancel($id)
    {
        try {
            $this->leaveApplicationInterface->cancelLeaveApplication($id);

            return response()->json([
                'error' => false,
                'message' => 'Successfully Canceled Leave Application'
            ]);
        } catch (Exception $e) {
            return response()->json([
                'error' => true,
                'message' => $e->getMessage()
            ]);
        }
    }

    public function details($id)
    {
        $leaveApplication = $this->leaveApplicationInterface->getAllLeaveApplications()->where('leave_application_id', $id)->where('state', 'real')->first();

        $canceler = Employee::where('employee_id', '=', $leaveApplication['cancel_by'])->first();
        $approver = Employee::where('employee_id', '=', $leaveApplication['approve_by'])->first();
        $rejecter = Employee::where('employee_id', '=', $leaveApplication['reject_by'])->first();

        $leaveBalance = $this->leaveApplicationInterface->countEachLeaveBalance(Auth::user()->employee->employee_id, Carbon::now()->year);
        $firstArrayKey = array_key_first($leaveBalance);
        $firstLeaveBalancePeriod = LeavePeriod::where('leave_period_id', '=', $firstArrayKey)->first();
        $firstLeaveBalancePeriod = date_format(Carbon::parse($firstLeaveBalancePeriod['from_date']), 'j F Y') . ' - ' . date_format(Carbon::parse($firstLeaveBalancePeriod['to_date']), 'j F Y');
        $firstLeaveBalance = $leaveBalance[$firstArrayKey];
        $secondArrayKey = array_keys($leaveBalance)[0];
        $secondLeaveBalancePeriod = LeavePeriod::where('leave_period_id', '=', $secondArrayKey)->first();
        $secondLeaveBalancePeriod = date_format(Carbon::parse($secondLeaveBalancePeriod['from_date']), 'j F Y') . ' - ' . date_format(Carbon::parse($secondLeaveBalancePeriod['to_date']), 'j F Y');
        $secondLeaveBalance = $leaveBalance[$secondArrayKey];
        $usedBalancePeriod = date_format(Carbon::parse($leaveApplication['from_date']), 'j F Y') . ' - ' . date_format(Carbon::parse($leaveApplication['to_date']), 'j F Y');
        $usedBalance = $leaveApplication['used'];

        return view('self-leave-application.details', compact('leaveApplication', 'canceler', 'approver', 'rejecter', 'firstLeaveBalancePeriod', 'firstLeaveBalance', 'secondLeaveBalancePeriod', 'secondLeaveBalance', 'usedBalancePeriod', 'usedBalance'));
    }

    public function download($file_name)
    {
        $file_path = public_path('uploads/leave-applications/' . $file_name);

        return response()->download($file_path);
    }

    public function informationBalanceYear($year){
        return $this->leaveApplicationInterface->getLeaveBalanceInformation($year);
    }
}
