<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Repositories\EmployeeRepository;
use App\Models\LeaveApplication;
use App\Interfaces\EmployeeInterface;
use Yajra\DataTables\Facades\DataTables;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use \PhpOffice\PhpSpreadsheet\Style\Border;

class LeaveBalanceController extends Controller
{
    private EmployeeRepository $employeeRepository;
    private EmployeeInterface $employeeInterface;

    public function __construct(EmployeeInterface $employeeInterface, EmployeeRepository $employeeRepository)
    {
        $this->employeeRepository = $employeeRepository;
        $this->employeeInterface = $employeeInterface;
        $this->middleware('permission:leave_balance.list', ['only' => ['index', 'getLeave']]);
    }
    public function index()
    {
        return view('employee-balance.leave-balance');
    }
    public function getLeave()
    {
        $user = Auth::user()->employee->employee_id;
        $employee = $this->employeeRepository->getEmployeeById($user)->join('department', 'employees.department_id', '=', 'department.department_id')
        ->first();

        $datas = LeaveApplication::join('employees', 'leave_applications.employee_id', '=', 'employees.employee_id')
            ->join('branches', 'employees.branch_id', '=', 'branches.branch_id')
            ->join('department', 'employees.department_id', '=', 'department.department_id')
            ->join('job_positions', 'employees.job_position_id', '=', 'job_positions.job_position_id')
            ->join('leave_types', 'leave_applications.leave_type_id', '=', 'leave_types.leave_type_id')
            ->where('department.code', '=', $employee->code)
            ->where('leave_applications.status', '=', 'lhy')
            ->orWhere('leave_applications.status', '=', 'lmy');

        if (request('export')) {
            $spreadsheet = new Spreadsheet();
            $sheet = $spreadsheet->getActiveSheet();
            $sheet->setCellValue('A1', 'NO');
            $sheet->setCellValue('B1', 'EMPLOYEE NAME');
            $sheet->setCellValue('C1', 'ENTITY');
            $sheet->setCellValue('D1', 'SBU');
            $sheet->setCellValue('E1', 'JOB POSITION');
            $sheet->setCellValue('F1', 'TYPE OF LEAVE');
            $sheet->setCellValue('G1', 'USED BALANCE');
            $sheet->setCellValue('H1', 'REMAINING LEAVE');

            $cell = 2;
            $i = 1;
            $data = $datas->get();
            foreach ($data as $attend) {
                $sheet->setCellValue('A' . $cell, $i++);
                $sheet->setCellValue('B' . $cell, $attend->first_name . ' ' . $attend->last_name);
                $sheet->setCellValue('C' . $cell, $attend->branch_name);
                $sheet->setCellValue('D' . $cell, $attend->department_name);
                $sheet->setCellValue('E' . $cell, $attend->job_position);
                $sheet->setCellValue('F' . $cell, $attend->leave_type_name);
                $sheet->setCellValue('G' . $cell, $attend->used);
                $sheet->setCellValue('H' . $cell, $attend->balance > $attend->used ? $attend->balance - $attend->used : '0 Hari');
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
            $filename = 'Leave_Balance.xlsx';

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

        return datatables::of($datas)
            ->addIndexColumn()
            ->addColumn('employee_image', function ($data) {
                return $this->employeeInterface->getEmployeePicture($data['user_id']);
            })
            ->toJson(true);
    }
}
