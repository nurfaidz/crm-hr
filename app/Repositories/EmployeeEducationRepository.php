<?php

namespace App\Repositories;

use App\Interfaces\EmployeeEducationInterface;
use App\Models\EmployeeEducation;

class EmployeeEducationRepository implements EmployeeEducationInterface
{
    public function getEmployeeEducation($employeeID){
        return EmployeeEducation::where('employee_id', $employeeID)->get();
    }
}
