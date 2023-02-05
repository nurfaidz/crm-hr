<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Helpers\ResponseFormatter;
use App\Models\Employee;
use App\Repositories\EmployeeRepository;
use Illuminate\Support\Facades\Auth;
use App\Interfaces\EmployeeInterface;
use App\Interfaces\UpdateDataInterface;

class UpdateDataController extends Controller
{
    private UpdateDataInterface $updateDataInterface;
    private EmployeeInterface $employeeInterface;

    public function __construct(UpdateDataInterface $updateDataInterface, EmployeeInterface $employeeInterface)
    {
        $this->updateDataInterface = $updateDataInterface;
        $this->employeeInterface = $employeeInterface;
    }

    public function getUpdateDataListAppr()
    {
        try {
            $user = Auth::user();
            $employee = Employee::where('user_id', $user->id)->join('department', 'employees.department_id', '=', 'department.department_id')
            ->firstOrFail();

            $updateDataList = $this->updateDataInterface->getUpdateDataListAppr($employee->branch_id, $employee->employee_id)
            ->where('code', '=', $employee->code)
            ->get([
                'temp_employee.id',
                'employees.first_name',
                'employees.last_name',
                'temp_employee.created_at',
            ]);

            if(count($updateDataList) <= 0)
            return ResponseFormatter::success([], 'No data available at the moment', 204);
            // dd($updateDataList);

            foreach ($updateDataList as $up) {
                $data[] = [
                    'id' => $up->id,
                    'first_name' => $up->first_name,
                    'last_name' => $up->last_name,
                    'date' => $up->created_at->format('Y-m-d'),
                    'status' => 'Pending' 
                ];
            }

            return ResponseFormatter::success($data, 'Get Updating Data Success');
        } catch (Exception $e) {
            return Responseformatter::error([
                'error' => $error->getMessage(),
                'link' => $error->getLine()
            ], 'Something wnet wrong');
        }
    }
}
