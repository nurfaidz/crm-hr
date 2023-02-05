<?php

namespace App\Http\Controllers;

use App\Helpers\Helpers;
use App\Models\Branches;
use App\Models\Department;
use App\Models\Employee;
use App\Models\EmployeeBalance;
use App\Models\JobClass;
use App\Models\JobPosition;
use App\Models\MedicalReimbursement;
use App\Repositories\EmployeeRepository;
use Exception;
use Illuminate\Http\Request;
use NumberFormatter;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class MedicalReportController extends Controller
{
    function __construct(EmployeeRepository $employeeRepository)
    {
        $this->employeeRepository = $employeeRepository;
        $this->middleware('permission:medical_report.list', ['only' => ['index', 'show', 'edit']]);
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $branches = Branches::all();
        $medicalReimbursements = MedicalReimbursement::leftJoin('employees', 'medical_reimbursements.employee_id', '=', 'employees.employee_id')->leftJoin('branches', 'employees.branch_id', '=', 'branches.branch_id')->leftJoin('department', 'employees.department_id', '=', 'department.department_id')->select(['medical_reimbursements.*', 'employees.user_id', 'employees.nip', 'employees.first_name', 'employees.last_name', 'branches.*', 'department.*']);

        $medicalReimbursements->where('medical_reimbursements.status', '!=', 'rpd')->where('medical_reimbursements.status', '!=', 'rmy')->where('medical_reimbursements.status', '!=', 'rhy');
        $medicalReimbursements->where('reimbursement_date', request('date'));

        if (request('category') !== "null") {
            $medicalReimbursements->where('category', request('category'));
        }

        if (request('branch') !== "null") {
            $medicalReimbursements->where('branches.branch_id', request('branch'));
        }

        if (request('department') !== "null") {
            $medicalReimbursements->where('department.department_id', request('department'));
        }

        if (request('export')) {
            $spreadsheet = new Spreadsheet();
            $sheet = $spreadsheet->getActiveSheet();
            $sheet->setCellValue('A1', 'NO');
            $sheet->setCellValue('B1', 'EMPLOYEE NAME');
            $sheet->setCellValue('C1', 'ENTITY');
            $sheet->setCellValue('D1', 'SBU');
            $sheet->setCellValue('E1', 'REIMBURSEMENT TYPE');
            $sheet->setCellValue('F1', 'TRANSACTION DATE');
            $sheet->setCellValue('G1', 'TOTAL EXPENSE');
            $sheet->setCellValue('H1', 'TOTAL REIMBURSEMENT');
            $sheet->setCellValue('I1', 'STATUS');
            $sheet->setCellValue('J1', 'HIGHER-UP APPROVAL');
            $sheet->setCellValue('K1', 'HR APPROVAL');
            $sheet->setCellValue('L1', 'FINANCE APPROVAL');
            $sheet->setCellValue('M1', 'REJECT BY');
            $sheet->setCellValue('N1', 'REJECT REASON');

            $cell = 2;
            $i = 1;
            $data = $medicalReimbursements->get();
            foreach ($data as $d) {
                $approvedByManager  = Employee::where('employee_id', $d->approve_by_manager)->first();
                $approvedByHumanResources  = Employee::where('employee_id', $d->approve_by_human_resources)->first();
                $approvedByFinance  = Employee::where('employee_id', $d->approve_by_finance)->first();
                $rejectedBy = Employee::where('employee_id', $d->reject_by)->first();

                if ($d->approve_by_finance > 0) {
                    $d->status = 'Approved';
                } else if ($d->reject_by > 0) {
                    $d->status = 'Rejected';
                } else if ($d->cancel_by > 0) {
                    $d->status = 'Cancel';
                } else {
                    $d->status = 'Pending';
                }

                $sheet->setCellValue('A' . $cell, $i++);
                $sheet->setCellValue('B' . $cell, $d->first_name . ' ' . $d->last_name);
                $sheet->setCellValue('C' . $cell, $d->branch_name);
                $sheet->setCellValue('D' . $cell, $d->department_name);
                $sheet->setCellValue('E' . $cell, ($d->category == 0) ? 'Inpatient' : 'Outpatient');
                $sheet->setCellValue('F' . $cell, $d->reimbursement_date);
                $sheet->setCellValue('G' . $cell, $d->expenses);
                $sheet->setCellValue('H' . $cell, $d->total_reimburse);
                $sheet->setCellValue('I' . $cell, $d->status);
                $sheet->setCellValue('J' . $cell, ($approvedByManager) ? $approvedByManager->first_name . ' ' . $approvedByManager->last_name : '');
                $sheet->setCellValue('K' . $cell, ($approvedByHumanResources) ? $approvedByHumanResources->first_name . ' ' . $approvedByHumanResources->last_name : '');
                $sheet->setCellValue('L' . $cell, ($approvedByFinance) ? $approvedByFinance->first_name . ' ' . $approvedByFinance->last_name : '');
                $sheet->setCellValue('M' . $cell, ($rejectedBy) ? $rejectedBy->first_name . ' ' . $rejectedBy->last_name : '');
                $sheet->setCellValue('N' . $cell, $d->reject_reason);
                $cell++;
            }

            $styleArray = [
                'borders' => [
                    'allBorders' => [
                        'borderStyle' => Border::BORDER_THIN,
                    ],
                ],
            ];

            $sheet->getStyle('A1:N' . ($cell - 1))->applyFromArray($styleArray);
            $filename = 'medical_report.xlsx';

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
            return datatables()->of($medicalReimbursements)
                ->addColumn('employee_image', function ($data) {
                    return $this->employeeRepository->getEmployeePicture($data->user_id);
                })
                ->addIndexColumn()
                ->make(true);
        }

        return view('medical-report.index', [
            'branches' => $branches,
        ]);
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
     * @param  \App\Models\MedicalReimbursement  $medicalReimbursement
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $medicalReimbursement = MedicalReimbursement::where('medical_reimbursement_id', $id)->first();
        if (!$medicalReimbursement) {
            abort(404);
        }

        $employee = Employee::where('employee_id', $medicalReimbursement->employee_id)->first();
        $branch = Branches::where('branch_id', $employee->branch_id)->first();
        $department = Department::where('department_id', $employee->department_id)->first();
        $jobPosition = JobPosition::where('job_position_id', $employee->job_position_id)->first();
        $approvedByManager  = Employee::where('employee_id', $medicalReimbursement->approve_by_manager)->first();
        $approvedByHumanResources  = Employee::where('employee_id', $medicalReimbursement->approve_by_human_resources)->first();
        $approvedByFinance  = Employee::where('employee_id', $medicalReimbursement->approve_by_finance)->first();
        $rejectedBy = Employee::where('employee_id', $medicalReimbursement->reject_by)->first();

        $payments = null;
        if ($medicalReimbursement->payment_evidence) {
            $payments = explode('|', $medicalReimbursement->payment_evidence);
        }

        $approve1 = 'active';
        $approve2 = 'active';
        $approve3 = 'active';

        if (!$approvedByManager && $rejectedBy) {
            $approve1 = 'danger';
            $approve2 = 'disable';
            $approve3 = 'disable';
        }

        if ($approvedByManager && !$approvedByHumanResources && !$rejectedBy) {
            $approve1 = 'done';
            $approve2 = 'active';
            $approve3 = 'active';
        }

        if ($approvedByManager && !$approvedByHumanResources && $rejectedBy) {
            $approve1 = 'done';
            $approve2 = 'danger';
            $approve3 = 'disable';
        }

        if ($approvedByManager && $approvedByHumanResources && !$approvedByFinance && !$rejectedBy) {
            $approve1 = 'done';
            $approve2 = 'done';
            $approve3 = 'active';
        }

        if ($approvedByManager && $approvedByHumanResources && !$approvedByFinance && $rejectedBy) {
            $approve1 = 'done';
            $approve2 = 'done';
            $approve3 = 'danger';
        }

        if ($approvedByManager && $approvedByHumanResources && $approvedByFinance && !$rejectedBy) {
            $approve1 = 'done';
            $approve2 = 'done';
            $approve3 = 'done';
        }

        return view('medical-report.show', [
            'medicalReimbursement' => $medicalReimbursement,
            'employee' => $employee,
            'branch' => $branch,
            'department' => $department,
            'jobPosition' => $jobPosition,
            'approvedByManager' => $approvedByManager,
            'approvedByHumanResources' => $approvedByHumanResources,
            'approvedByFinance' => $approvedByFinance,
            'rejectedBy' => $rejectedBy,
            'payments' => $payments,
            'approve1' => $approve1,
            'approve2' => $approve2,
            'approve3' => $approve3,
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\MedicalReimbursement  $medicalReimbursement
     * @return \Illuminate\Http\Response
     */
    public function edit(Request $request, $id)
    {
        $employee = Employee::where('employee_id', $id)->first();
        if (!$employee) {
            abort(404);
        }

        $branch = Branches::where('branch_id', $employee->branch_id)->first();
        $department = Department::where('department_id', $employee->department_id)->first();
        $jobClass = JobClass::where('job_class_id', $employee->job_class_id)->first();
        $employeeBalance = EmployeeBalance::where('employee_id', $id)->latest()->first();
        $employeeBalance->total_balance = Helpers::getCurrency($employeeBalance->total_balance);
        $employeeBalance->used_balance = Helpers::getCurrency($employeeBalance->used_balance);
        $approved = MedicalReimbursement::where('employee_id', $id)->where('approve_by_finance', '>', 0)->latest()->get();
        $rejected = MedicalReimbursement::where('employee_id', $id)->where('reject_by', '>', 0)->latest()->get();
        $medicalReimbursements = MedicalReimbursement::where('employee_id', $id);
        $medicalReimbursements->where('medical_reimbursements.status', '!=', 'rpd')->where('medical_reimbursements.status', '!=', 'rmy')->where('medical_reimbursements.status', '!=', 'rhy');

        if (request('date') != 0) {
            if (strlen(request('date')) < 11) {
                $medicalReimbursements->where('reimbursement_date', request('date'));
            }

            if (strlen(request('date')) > 11) {
                $fromDate = substr($request->date, 0, 10);
                $toDate = substr($request->date, 14);
                $medicalReimbursements->whereBetween('reimbursement_date', [$fromDate, $toDate]);
            }
        }

        if (request('status') === "approved") {
            $medicalReimbursements->where('approve_by_finance', '>', 0);
        }

        if (request('status') === "rejected") {
            $medicalReimbursements->where('reject_by', '>', 0);
        }

        if (request('status') === "pending") {
            $medicalReimbursements->where('approve_by_finance', null)->where('reject_by', null)->where('cancel_by', null);
        }

        if (request('status') === "canceled") {
            $medicalReimbursements->where('cancel_by', '>', 0);
        }

        if (request('export')) {
            $spreadsheet = new Spreadsheet();
            $sheet = $spreadsheet->getActiveSheet();
            $sheet->setCellValue('A1', 'NO');
            $sheet->setCellValue('B1', 'EMPLOYEE NAME');
            $sheet->setCellValue('C1', 'ENTITY');
            $sheet->setCellValue('D1', 'SBU');
            $sheet->setCellValue('E1', 'REIMBURSEMENT TYPE');
            $sheet->setCellValue('F1', 'TRANSACTION DATE');
            $sheet->setCellValue('G1', 'TOTAL EXPENSE');
            $sheet->setCellValue('H1', 'TOTAL REIMBURSEMENT');
            $sheet->setCellValue('I1', 'STATUS');
            $sheet->setCellValue('J1', 'HIGHER-UP APPROVAL');
            $sheet->setCellValue('K1', 'HR APPROVAL');
            $sheet->setCellValue('L1', 'FINANCE APPROVAL');
            $sheet->setCellValue('M1', 'REJECT BY');
            $sheet->setCellValue('N1', 'REJECT REASON');

            $cell = 2;
            $i = 1;
            $data = $medicalReimbursements->get();
            foreach ($data as $d) {
                $approvedByManager = Employee::where('employee_id', $d->approve_by_manager)->first();
                $approvedByHumanResources = Employee::where('employee_id', $d->approve_by_human_resources)->first();
                $approvedByFinance = Employee::where('employee_id', $d->approve_by_finance)->first();
                $rejectedBy = Employee::where('employee_id', $d->reject_by)->first();

                if ($d->approve_by_finance > 0) {
                    $d->status = 'Approved';
                } else if ($d->reject_by > 0) {
                    $d->status = 'Rejected';
                } else if ($d->cancel_by > 0) {
                    $d->status = 'Cancel';
                } else {
                    $d->status = 'Pending';
                }

                $sheet->setCellValue('A' . $cell, $i++);
                $sheet->setCellValue('B' . $cell, $employee->first_name . ' ' . $employee->last_name);
                $sheet->setCellValue('C' . $cell, $branch->branch_name);
                $sheet->setCellValue('D' . $cell, $department->department_name);
                $sheet->setCellValue('E' . $cell, ($d->category == 0) ? 'Inpatient' : 'Outpatient');
                $sheet->setCellValue('F' . $cell, $d->reimbursement_date);
                $sheet->setCellValue('G' . $cell, $d->expenses);
                $sheet->setCellValue('H' . $cell, $d->total_reimburse);
                $sheet->setCellValue('I' . $cell, $d->status);
                $sheet->setCellValue('J' . $cell, ($approvedByManager) ? $approvedByManager->first_name . ' ' . $approvedByManager->last_name : '');
                $sheet->setCellValue('K' . $cell, ($approvedByHumanResources) ? $approvedByHumanResources->first_name . ' ' . $approvedByHumanResources->last_name : '');
                $sheet->setCellValue('L' . $cell, ($approvedByFinance) ? $approvedByFinance->first_name . ' ' . $approvedByFinance->last_name : '');
                $sheet->setCellValue('M' . $cell, ($rejectedBy) ? $rejectedBy->first_name . ' ' . $rejectedBy->last_name : '');
                $sheet->setCellValue('N' . $cell, $d->reject_reason);
                $cell++;
            }

            $styleArray = [
                'borders' => [
                    'allBorders' => [
                        'borderStyle' => Border::BORDER_THIN,
                    ],
                ],
            ];

            $sheet->getStyle('A1:N' . ($cell - 1))->applyFromArray($styleArray);
            $filename = 'medical_report.xlsx';

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
            return datatables()->of($medicalReimbursements->get())
                ->addIndexColumn()
                ->make(true);
        }

        return view('medical-report.edit', [
            'employee' => $employee,
            'jobClass' => $jobClass,
            'employeeBalance' => $employeeBalance,
            'approved' => $approved,
            'rejected' => $rejected,
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\MedicalReimbursement  $medicalReimbursement
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, MedicalReimbursement $medicalReimbursement)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\MedicalReimbursement  $medicalReimbursement
     * @return \Illuminate\Http\Response
     */
    public function destroy(MedicalReimbursement $medicalReimbursement)
    {
        //
    }
}
