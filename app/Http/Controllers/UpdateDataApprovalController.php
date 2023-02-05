<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\TempEmployee;
use Yajra\DataTables\Facades\DataTables;
use App\Repositories\EmployeeRepository;
use Illuminate\Support\Facades\Auth;
use App\Models\Employee;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use App\Interfaces\EmployeeInterface;

class UpdateDataApprovalController extends Controller
{
    private EmployeeInterface $employeeInterface;
    private EmployeeRepository $employeeRepository;

    public function __construct(EmployeeInterface $employeeInterface, EmployeeRepository $employeeRepository)
    {
        $this->employeeInterface = $employeeInterface;
        $this->employeeRepository = $employeeRepository;
        $this->middleware('role_or_permission:Super Admin|update_data.approve', ['only' => ['editDataApprove']]);
        $this->middleware('role_or_permission:Super Admin|update_data.reject', ['only' => ['editDataReject']]);
    }

    public function detailUpdateData($id)
    {
        try {
            $employee = TempEmployee::join('employees', 'temp_employee.employee_id', '=', 'employees.employee_id')
                ->leftJoin('religions', 'employees.religion_id', "=", 'religions.religion_id')
                ->leftJoin('marital_statuses', 'employees.marital_status_id', "=", 'marital_statuses.marital_status_id')
                ->leftJoin('branches', 'employees.branch_id', '=', 'branches.branch_id')
                ->leftJoin('department', 'employees.department_id', '=', 'department.department_id')
                ->leftJoin('job_positions', 'employees.job_position_id', '=', 'job_positions.job_position_id')
                ->where('id', $id)->first();

            $temp_data = json_decode($employee->data);

            $dataEmployee = json_encode([
                'nik' => [
                    'title' => 'NIK',
                    'employee' => $employee->nik,
                    'temp_employee' => $temp_data->nik
                ],
                'passport' => [
                    'title' => 'No Passport',
                    'employee' => $employee->passport,
                    'temp_employee' => $temp_data->passport
                ],
                'place_of_birth' => [
                    'title' => 'Birth Of Place',
                    'employee' => $employee->place_of_birth,
                    'temp_employee' => $temp_data->place_of_birth
                ],
                'date_of_birth' => [
                    'title' => 'Birth of Date',
                    'employee' => $employee->date_of_birth,
                    'temp_employee' => $temp_data->date_of_birth
                ],
                'blood_type' => [
                    'title' => 'Blood Type',
                    'employee' => $employee->blood_type,
                    'temp_employee' => $temp_data->blood_type
                ],
                'address' => [
                    'title' => 'Address',
                    'employee' => $employee->address,
                    'temp_employee' => $temp_data->address
                ],
                'religion_name' => [
                    'title' => 'Religion',
                    'employee' => $employee->religion,
                    'temp_employee' => $temp_data->religion_name
                ],
                'relationship' => [
                    'title' => 'Marital Status',
                    'employee' => $employee->marital_status,
                    'temp_employee' => $temp_data->relationship
                ],
                'nationality' => [
                    'title' => 'Address',
                    'employee' => $employee->nationality,
                    'temp_employee' => $temp_data->nationality
                ],

            ]);
            $dataEmployee = json_decode($dataEmployee);
            foreach ($dataEmployee as $key => $value) {
                if ($value->employee == $value->temp_employee) {
                    unset($dataEmployee->{"$key"});
                }
            }

            return view("approval.update-data-detail", compact('dataEmployee', 'employee'));
        } catch (Exception $e) {
            return response()->json(["error" => true, "message" => $e->getMessage()]);
        }
    }
    public function getData()
    {
        $user = Auth::user()->employee->employee_id;
        $employee = $this->employeeRepository->getEmployeeById($user)
            ->join('branches', 'employees.branch_id', '=', 'branches.branch_id')
            ->join('department', 'employees.department_id', '=', 'department.department_id')
            ->first();

        $datas = TempEmployee::join('employees', 'temp_employee.employee_id', '=', 'employees.employee_id')
            ->join('branches', 'employees.branch_id', '=', 'branches.branch_id')
            ->join('department', 'employees.department_id', '=', 'department.department_id')
            ->join('job_positions', 'employees.job_position_id', '=', 'job_positions.job_position_id')
            ->where('branches.branch_id', '=', $employee->branch_id)
            ->where('employees.employee_id', '!=', $employee->employee_id)
            ->where('department.code', '=', $employee->code)
            ->get();

        return datatables::of($datas)
            ->addIndexColumn()
            ->addColumn('employee_image', function ($data) {
                return $this->employeeInterface->getEmployeePicture($data['user_id']);
            })
            ->addColumn('status', function ($data) {
                return '<div class="badge badge-pill badge-light-warning">' . 'Pending' . '</div>';
            })
            ->addColumn('action', function ($data) {
                return '<a href="approval/detail/update-data/' . $data->id . '" id="pending-status-cancel" class="btn btn-outline-info round pending-status-cancel d-flex justify-content-center align-items-center">Details</a>';
            })
            ->rawColumns(['action', 'status'])
            ->toJson(true);
    }

    public function editDataApprove(Request $req, $id)
    {
        try {
            $temp_employee = TempEmployee::where('id', $id)->first();
            $temp_data = json_decode($temp_employee->data);
            $employee = Employee::where('employee_id', $temp_employee->employee_id)->first();
            $dataEmployee = [
                'nik' => [
                    'title' => 'NIK',
                    'employee' => $employee->nik,
                    'temp_employee' => $temp_data->nik
                ],
                'passport' => [
                    'title' => 'No Passport',
                    'employee' => $employee->passport,
                    'temp_employee' => $temp_data->passport
                ],
                'place_of_birth' => [
                    'title' => 'Birth Of Place',
                    'employee' => $employee->place_of_birth,
                    'temp_employee' => $temp_data->place_of_birth
                ],
                'date_of_birth' => [
                    'title' => 'Birth of Date',
                    'employee' => $employee->date_of_birth,
                    'temp_employee' => $temp_data->date_of_birth
                ],
                'blood_type' => [
                    'title' => 'Blood Type',
                    'employee' => $employee->blood_type,
                    'temp_employee' => $temp_data->blood_type
                ],
                'address' => [
                    'title' => 'Address',
                    'employee' => $employee->address,
                    'temp_employee' => $temp_data->address
                ],
                'religion_name' => [
                    'title' => 'Religion',
                    'employee' => $employee->religion,
                    'temp_employee' => $temp_data->religion_name
                ],
                'relationship' => [
                    'title' => 'Marital Status',
                    'employee' => $employee->marital_status,
                    'temp_employee' => $temp_data->relationship
                ],
                'nationality' => [
                    'title' => 'Address',
                    'employee' => $employee->nationality,
                    'temp_employee' => $temp_data->nationality
                ],
            ];
            if($req->updateData == null){
                return response()->json(["error" => true, "message" => "Please Choose"]);
            }
            foreach ($req->updateData as $key => $value) {
                $employee->update([
                    $value => $dataEmployee[$value]['temp_employee'],
                ]);
            }

            $temp_employee->delete();
            return response()->json(["error" => false, "message" => "Approve Editing Data"]);
        } catch (Exception $e) {
            return response()->json(["error" => true, "message" => $e->getMessage()]);
        }
    }

    public function editDataReject($id)
    {
        try {
            TempEmployee::where('id', $id)->delete();
            return response()->json(["error" => false, "message" => "Rejected Editing Data"]);
        } catch (Exception $e) {
            return response()->json(["error" => true, "message" => $e->getMessage()]);
        }
    }

    public function editDataExcel()
    {
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();;

        $attendance = TempEmployee::join('employees', 'temp_employee.employee_id', '=', 'employees.employee_id')
            ->join('branches', 'employees.branch_id', '=', 'branches.branch_id')
            ->join('department', 'employees.department_id', '=', 'department.department_id')
            ->join('job_positions', 'employees.job_position_id', '=', 'job_positions.job_position_id')
            ->get();

        $sheet->setCellValue('A1', 'Employee ID')
            ->setCellValue('B1', 'Employee Name')
            ->setCellValue('C1', 'Entity')
            ->setCellValue('D1', 'SBU')
            ->setCellValue('E1', 'Position')
            ->setCellValue('F1', 'Data');

        $i = 2;
        foreach ($attendance as $result) {
            $sheet->setCellValue('A' . $i, $result->employee_id)
                ->setCellValue('B' . $i, $result->first_name . ' ' . $result->last_name)
                ->setCellValue('C' . $i, $result->branch_name)
                ->setCellValue('D' . $i, $result->department_name)
                ->setCellValue('E' . $i, $result->job_position)
                ->setCellValue('F' . $i, $result->data);

            $i++;
        }

        $writer = new Xlsx($spreadsheet);

        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment; filename="' . urlencode('UpdateData.xlsx') . '"');
        $writer->save('php://output');
    }
}
