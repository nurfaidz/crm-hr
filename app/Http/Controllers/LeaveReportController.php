<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\LeaveType;
use App\Models\Branches;
use App\Models\Department;
use App\Models\Employee;
use App\Models\JobPosition;
use App\Models\LeaveApplication;
use App\Models\LeavePeriod;
use App\Models\StatusCode;
use App\Repositories\EmployeeRepository;
use Carbon\Carbon;
use Exception;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use \PhpOffice\PhpSpreadsheet\Style\Border;

class LeaveReportController extends Controller
{
    function __construct(EmployeeRepository $employeeRepository)
    {
        $this->employeeRepository = $employeeRepository;
        $this->middleware('permission:leave_report.list', ['only' => ['index', 'show', 'leaveReportsList']]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function index(Request $request)
    {
        $branches = Branches::all();
        $departments = Department::all();
        $leaveTypes = LeaveType::all();

        $leaveApplications = LeaveApplication::join('employees', 'leave_applications.employee_id', '=', 'employees.employee_id')
            ->join('department', 'employees.department_id', '=', 'department.department_id')
            ->join('branches', 'employees.branch_id', '=', 'branches.branch_id')
            ->join('leave_types', 'leave_applications.leave_type_id', '=', 'leave_types.leave_type_id')
            ->join('status_codes', 'leave_applications.status', '=', 'status_codes.code');

        $leaveApplications->where('leave_applications.status', '!=', 'lpd');

        if (request('branches')) {
            $departments = Department::where('department_branch_id', $request->branches)->get();
            return response()->json(["error" => false, "departments" => $departments], 200);
        }

        if (request('date')) {
            $leaveApplications->where('application_date', $request->date);
        }

        if (request('branch')) {
            $leaveApplications->where('employees.branch_id', $request->branch);
        }

        if (request('department')) {
            $leaveApplications->where('employees.department_id', $request->department);
        }

        if (request('leave_types')) {
            $leaveApplications->where('leaveapplications.leave_type_id', $request->leave_types);
        }

        if (!request('date')) {
            $leaveApplications->where('application_date', Carbon::today());
        }

        if (request('export')) {
            $spreadsheet = new Spreadsheet();
            $sheet = $spreadsheet->getActiveSheet();
            $sheet->setCellValue('A1', 'NO');
            $sheet->setCellValue('B1', 'EMPLOYEE NAME');
            $sheet->setCellValue('C1', 'ENTITY');
            $sheet->setCellValue('D1', 'DEPARMENT');
            $sheet->setCellValue('E1', 'LEAVE TYPE');
            $sheet->setCellValue('F1', 'DATE');
            $sheet->setCellValue('G1', 'START-DATE');
            $sheet->setCellValue('H1', 'END-DATE');
            $sheet->setCellValue('I1', 'TOTAL DAYS');
            $sheet->setCellValue('J1', 'STATUS');

            $cell = 2;
            $i = 1;
            $data = $leaveApplications->get();
            foreach ($data as $d) {
                $sheet->setCellValue('A' . $cell, $i++);
                $sheet->setCellValue('B' . $cell, $d->first_name . ' ' . $d->last_name);
                $sheet->setCellValue('C' . $cell, $d->branch_name);
                $sheet->setCellValue('D' . $cell, $d->department_name);
                $sheet->setCellValue('E' . $cell, $d->leave_type_name);
                $sheet->setCellValue('F' . $cell, $d->application_date);
                $sheet->setCellValue('G' . $cell, $d->application_from_date);
                $sheet->setCellValue('H' . $cell, $d->application_to_date);
                $sheet->setCellValue('I' . $cell, $d->number_of_day);
                $sheet->setCellValue('J' . $cell, $d->short);
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
            $filename = 'leave_report.xlsx';

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

        return view('leave-reports.index', [
            'branches' => $branches,
            'departments' => $departments,
            'leaveTypes' => $leaveTypes,
        ]);
    }

    public function leaveReportsList(Request $request)
    {
        $leaveApplications = LeaveApplication::join('employees', 'leave_applications.employee_id', '=', 'employees.employee_id')
            ->join('department', 'employees.department_id', '=', 'department.department_id')
            ->join('branches', 'employees.branch_id', '=', 'branches.branch_id')
            ->join('leave_types', 'leave_applications.leave_type_id', '=', 'leave_types.leave_type_id')
            ->join('status_codes', 'leave_applications.status', '=', 'status_codes.code');

        $leaveApplications->where('leave_applications.status', '!=', 'lpd');
        $leaveApplications->where('application_date', $request->date);

        if (request('branch') != 0) {
            $leaveApplications->where('employees.branch_id', $request->branch);
        }

        if (request('department') != 0) {
            $leaveApplications->where('employees.department_id', $request->department);
        }

        if (request('leavetype') != 0) {
            $leaveApplications->where('leave_applications.leave_type_id', $request->leavetype);
        }

        return datatables()->of($leaveApplications->get())
            ->addColumn('employee_image', function ($data) {
                return $this->employeeRepository->getEmployeePicture($data->user_id);
            })
            ->addIndexColumn()
            ->make(true);
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
    public function show(Request $request, $id)
    {
        $employee = Employee::where('employee_id', $id)->first();
        if (!$employee) {
            abort(404);
        }
        $leavePeriod = LeavePeriod::where('from_date', '<', date('Y-m-d'))->where('expired_date', '>', date('Y-m-d'))->first();
        $annualLeave = LeaveApplication::where('employee_id', $id)->where('leave_type_id', 1)->whereBetween('application_date', [$leavePeriod->from_date, $leavePeriod->expired_date])->get();
        $sickLeave = LeaveApplication::where('employee_id', $id)->where('leave_type_id', 3)->whereBetween('application_date', [$leavePeriod->from_date, $leavePeriod->expired_date])->get();
        $approveLeave = LeaveApplication::where('employee_id', $id)->where('approve_by', '>', '0')->whereBetween('application_date', [$leavePeriod->from_date, $leavePeriod->expired_date])->get();
        $rejectLeave = LeaveApplication::where('employee_id', $id)->where('reject_by', '>', '0')->whereBetween('application_date', [$leavePeriod->from_date, $leavePeriod->expired_date])->get();
        $leaveApplications = LeaveApplication::where('employee_id', $id)->join('leave_types', 'leave_applications.leave_type_id', '=', 'leave_types.leave_type_id');
        $branch = Branches::where('branch_id', $employee->branch_id)->first();
        $department = Department::where('department_id', $employee->department_id)->first();

        $leaveApplications->where('status', '!=', 'lpd');

        if (request('status')) {
            $leaveApplications->where('status', $request->status);
        }

        if (request('date') != 0) {
            if (strlen(request('date')) < 11) {
                $leaveApplications->where('application_date', $request->date);
            }

            if (strlen(request('date')) > 11) {
                $fromDate = substr($request->date, 0, 10);
                $toDate = substr($request->date, 14);
                $leaveApplications->whereBetween('application_date', [$fromDate, $toDate]);
            }
        }

        if (request('export')) {
            $spreadsheet = new Spreadsheet();
            $sheet = $spreadsheet->getActiveSheet();
            $sheet->setCellValue('A1', 'NO');
            $sheet->setCellValue('B1', 'EMPLOYEE NAME');
            $sheet->setCellValue('C1', 'ENTITY');
            $sheet->setCellValue('D1', 'DEPARMENT');
            $sheet->setCellValue('E1', 'LEAVE TYPE');
            $sheet->setCellValue('F1', 'DATE');
            $sheet->setCellValue('G1', 'START-DATE');
            $sheet->setCellValue('H1', 'END-DATE');
            $sheet->setCellValue('I1', 'TOTAL DAYS');
            $sheet->setCellValue('J1', 'STATUS');

            $cell = 2;
            $i = 1;
            $data = $leaveApplications->get();
            foreach ($data as $d) {
                $status = StatusCode::where('code', $d->status)->first();
                $sheet->setCellValue('A' . $cell, $i++);
                $sheet->setCellValue('B' . $cell, $employee->first_name . ' ' . $employee->last_name);
                $sheet->setCellValue('C' . $cell, $branch->branch_name);
                $sheet->setCellValue('D' . $cell, $department->department_name);
                $sheet->setCellValue('E' . $cell, $d->leave_type_name);
                $sheet->setCellValue('F' . $cell, $d->application_date);
                $sheet->setCellValue('G' . $cell, $d->application_from_date);
                $sheet->setCellValue('H' . $cell, $d->application_to_date);
                $sheet->setCellValue('I' . $cell, $d->number_of_day);
                $sheet->setCellValue('J' . $cell, $status->short);
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
            $filename = 'leave_report.xlsx';

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

        if ($request->ajax()) {
            return datatables()->of($leaveApplications->get())
                ->addIndexColumn()
                ->addColumn('detail', function ($data) {
                    return '<div class="dropdown">
                                <button type="button"
                                    class="btn btn-outline-primary dropdown-toggle budget-dropdown waves-effect"
                                    style="border: none !important" data-toggle="dropdown" aria-haspopup="true"
                                    aria-expanded="false">Show</button>
                                <div id="drop-detail" class="dropdown-menu rounded-lg p-0">
                                    <a href="#" data-toggle="modal" data-purpose="' . $data->notes . '" data-target="#modal-notes"
                                        class="dropdown-item" id="notes">Notes</a>
                                    <a href="#" data-toggle="modal" data-sick-letter="' . $data->attachment . '" data-target="#modal-files"
                                        class="dropdown-item" id="files">Files</a>
                                </div></div>';
                })
                ->rawColumns(['detail'])
                ->make(true);
        }

        return view('leave-reports.show', [
            'annualLeave' => $annualLeave,
            'sickLeave' => $sickLeave,
            'approveLeave' => $approveLeave,
            'rejectLeave' => $rejectLeave,
            'leaveApplications' => $leaveApplications,
            'employee' => $employee
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $leaveApplication = LeaveApplication::where('id', $id)->first();
        if (!$leaveApplication) {
            abort(404);
        }
        $employee = Employee::where('employee_id', $leaveApplication->employee_id)->first();
        $branch = Branches::where('branch_id', $employee->branch_id)->first();
        $department = Department::where('department_id', $employee->department_id)->first();
        $jobPosition = JobPosition::where('job_position_id', $employee->job_position_id)->first();
        $leaveType = LeaveType::where('leave_type_id', $leaveApplication->leave_type_id)->first();
        $leavePeriod = LeavePeriod::where('from_date', '<', $leaveApplication->application_from_date)->where('expired_date', '>', $leaveApplication->application_to_date)->first();

        $approve1 = 'active';
        $approval = null;
        if ($leaveApplication->status === 'lhy' || $leaveApplication->status === 'lmy') {
            $approve1 = 'done';
            $approval = Employee::where('employee_id', $leaveApplication->approve_by)->first();
        }

        if ($leaveApplication->status === 'lhn' || $leaveApplication->status === 'lmn') {
            $approve1 = 'danger';
            $approval = Employee::where('employee_id', $leaveApplication->reject_by)->first();
        }

        if ($leaveApplication->status === 'can') {
            $approve1 = 'disable';
            $approval = Employee::where('employee_id', $leaveApplication->cancel_by)->first();
        }

        return view('leave-reports.edit', [
            'leaveApplication' => $leaveApplication,
            'employee' => $employee,
            'branch' => $branch,
            'department' => $department,
            'jobPosition' => $jobPosition,
            'leaveType' => $leaveType,
            'leavePeriod' => $leavePeriod,
            'approve1' => $approve1,
            'approval' => $approval
        ]);
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
