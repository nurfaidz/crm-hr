<?php

namespace App\Repositories;

use App\Interfaces\UpdateDataInterface;
use App\Models\TempEmployee;

class UpdateDataRepository implements UpdateDataInterface
{

    public function getUpdateDataListAppr($branchId, $employeeId)
    {
        $updateData = TempEmployee::join('employees', 'temp_employee.employee_id', '=', 'employees.employee_id')
        ->join('department', 'employees.department_id', '=', 'department.department_id')
        ->join('branches', 'employees.branch_id', '=', 'branches.branch_id')
        ->where('employees.branch_id', '=', $branchId)
        ->where('employees.employee_id', '!=', $employeeId);
        return $updateData;
    }
}
