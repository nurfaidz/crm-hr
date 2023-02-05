<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\EmployeeBalance;
use Yajra\DataTables\Facades\DataTables;
use App\Interfaces\EmployeeInterface;
use App\Models\Branches;
use App\Models\Department;
use Exception;
use Illuminate\Support\Facades\Validator;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use \PhpOffice\PhpSpreadsheet\Style\Border;

class EmployeeBalanceController extends Controller
{
    private EmployeeInterface $employeeInterface;

    public function __construct(EmployeeInterface $employeeInterface)
    {
        $this->employeeInterface = $employeeInterface;
        $this->middleware('permission:employee_balance.list', ['only' => ['index', 'getEmployeeBalance']]);
    }

    public function index(Request $request)
    {
        $branches = Branches::all();

        if (request('branch')) {
            $departments = Department::where('department_branch_id', $request->branch)->get();
            return ['departments' => $departments];
        }

        return view('employee-balance.index', [
            'branches' => $branches,
        ]);
    }

    public function getEmployeeBalance(Request $request)
    {
        $datas = EmployeeBalance::join('employees', 'employee_balances.employee_id', '=', 'employees.employee_id')
            ->join('branches', 'employees.branch_id', '=', 'branches.branch_id')
            ->join('department', 'employees.department_id', '=', 'department.department_id')
            ->join('job_positions', 'employees.job_position_id', '=', 'job_positions.job_position_id');


        if (request('entity') > 0) {
            $datas->where('employees.branch_id',  $request->entity);
        }

        if (request('sbu') > 0) {
            $datas->where('employees.department_id', $request->sbu);
        }

        if (request('export')) {
            $spreadsheet = new Spreadsheet();
            $sheet = $spreadsheet->getActiveSheet();
            $sheet->setCellValue('A1', 'NO');
            $sheet->setCellValue('B1', 'EMPLOYEE NAME');
            $sheet->setCellValue('C1', 'ENTITY');
            $sheet->setCellValue('D1', 'SBU');
            $sheet->setCellValue('E1', 'JOB POSITION');
            $sheet->setCellValue('F1', 'TOTAL BALANCE');
            $sheet->setCellValue('G1', 'REMAINING BALANCE');
            $sheet->setCellValue('H1', 'USED BALANCE');

            $cell = 2;
            $i = 1;
            $data = $datas->get();
            foreach ($data as $attend) {
                $sheet->setCellValue('A' . $cell, $i++);
                $sheet->setCellValue('B' . $cell, $attend->first_name . ' ' . $attend->last_name);
                $sheet->setCellValue('C' . $cell, $attend->branch_name);
                $sheet->setCellValue('D' . $cell, $attend->department_name);
                $sheet->setCellValue('E' . $cell, $attend->job_position);
                $sheet->setCellValue('F' . $cell, $attend->total_balance);
                $sheet->setCellValue('G' . $cell, $attend->remaining_balance);
                $sheet->setCellValue('H' . $cell, $attend->used_balance);
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
            $filename = 'Employee_Balance.xlsx';

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
            // ->addColumn('action', function($data) {
            //     return '<button type="submit" data-toggle="modal" title="Edit" data-target="#modal-form" data-id="' . $data->id . '" class="edit btn btn-icon btn-success my-1 " id="edit"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-edit"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path></svg></button> <form id="form_delete_data" style="display:inline" class="" action="/companies/delete/' . $data->id . '" method="post" title="Delete"><button type="submit" style="border:none; background:transparent" class="btn btn-icon btn-danger " onclick="sweetConfirm(' . $data->id . ')"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-trash-2"><polyline points="3 6 5 6 21 6"></polyline><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path><line x1="10" y1="11" x2="10" y2="17"></line><line x1="14" y1="11" x2="14" y2="17"></line></svg></button><input type="hidden" name="_method" value="delete" /><input type="hidden" name="_token" value="' . csrf_token() . '"></form>';
            // })
            // ->rawColumns(['action'])
            ->toJson(true);
    }

    public function editEmployeeBalance()
    {
        $datas = EmployeeBalance::join('employees', 'employee_balances.employee_id', '=', 'employees.employee_id')
            ->join('branches', 'employees.branch_id', '=', 'branches.branch_id')
            ->join('department', 'employees.department_id', '=', 'department.department_id')
            ->join('job_positions', 'employees.job_position_id', '=', 'job_positions.job_position_id')
            ->first();

        $employee = [
            'name' => $datas->first_name . " " . $datas->last_name,
            'entity' => $datas->branch_name,
            'nip' => $datas->nip,
            'job_position' => $datas->job_position,
            'department' => $datas->department_name,
            'remaining_balance' => $datas->remaining_balance,
        ];
        return $employee;
    }

    public function updateEmployeeBalance(Request $request, $id)
    {
        $data = $request->except(['_token']);

        $validate = Validator::make(
            $data,
            [
                'remaining_balance' => 'required|numeric'
            ]
        );

        if ($validate->fails()) {
            return response()->json([
                'error' => $validate->errors()->toArray()
            ]);
        }

        $balance = EmployeeBalance::where('id', $id);
        $balance->update($data);

        if ($balance) {
            return response()->json(["error" => false, "message" => "Successfully Updated Remaining Balance Data!"]);
        } else {
            return response()->json(["error" => true, "message" => "Data Is Not Found!"]);
        }
    }
}
