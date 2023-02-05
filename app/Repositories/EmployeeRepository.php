<?php

namespace App\Repositories;

use App\Interfaces\EmployeeInterface;
use App\Models\Employee;
use App\Models\User;
use App\Models\Vaccine;
use Carbon\Carbon;

class EmployeeRepository implements EmployeeInterface
{
    public function getAllEmployees()
    {
        return Employee::join('branches', 'employees.branch_id', '=', 'branches.branch_id')
            ->join('department', 'employees.department_id', '=', 'department.department_id')
            ->join('job_positions', 'employees.job_position_id', '=', 'job_positions.job_position_id')
            ->join('job_classes', 'employees.job_class_id', '=', 'job_classes.job_class_id');
    }

    public function createUser($data)
    {
        return User::create($data);
    }

    public function createEmployee($data)
    {
        return Employee::create($data);
    }

    public function createVaccine($data)
    {
        return Vaccine::create($data);
    }

    public function updateEmployee($id, $data)
    {
        return Employee::where('employee_id', $id)->update($data);
    }

    public function updateVaccine($id, $data)
    {
        return Vaccine::where('id', $id)->update($data);
    }

    public function updateUser($id, $data)
    {
        return User::where('id', $id)->update($data);
    }

    public function deleteEmployee($id, $data)
    {
        Employee::where('employee_id', $id)->update($data);
    }

    public function getEmployeeId($userId)
    {
        return Employee::where('user_id', $userId)->get('employee_id')->first();
    }

    public function getEmployee($userId)
    {
        return Employee::where('user_id', $userId)
            ->get()->first();
    }

    public function getVaccine($id)
    {
        return Vaccine::where('id', $id)
            ->get()->first();
    }

    public function getEmployeeById($id)
    {
        return Employee::where('employee_id', $id);
    }

    public function getEmployeeNameById($id)
    {
        return Employee::where('user_id', $id)
            ->join('religions', 'employees.religion_id', '=', 'religions.religion_id')
            ->join('marital_statuses', 'employees.marital_status_id', '=', 'marital_statuses.marital_status_id')
            ->join('companies', 'employees.company_id', '=', 'companies.company_id')
            ->join('department', 'employees.department_id', '=', 'department.department_id')
            ->join('branches', 'employees.branch_id', '=', 'branches.branch_id')
            ->join('job_classes', 'employees.job_class_id', '=', 'job_classes.job_class_id')
            ->join('job_positions', 'employees.job_position_id', '=', 'job_positions.job_position_id')
            ->join('employment_statuses', 'employees.employment_status_id', '=', 'employment_statuses.employment_status_id')
            ->join('work_shifts', 'employees.work_shift_id', '=', 'work_shifts.work_shift_id')
            ->join('salary_types', 'employees.salary_type_id', '=', 'salary_types.salary_type_id')
            ->join('banks', 'employees.bank_id', '=', 'banks.bank_id')
            ->get()->first();
    }

    public function getEmployeePicture($id)
    {
        $data = Employee::where('user_id', $id)->get(['image'])->first();
        $imageSrc = $data->image == '' || $data->image == '' ? asset('img/profile.png') : asset('uploads/' . $data['image']);
        return $imageSrc;
    }
    public function getEmployeeAge($date_of_birth)
    {
        return Carbon::parse($date_of_birth)->age;
    }

    public function getProfilEmployee()
    {
        return Employee::join('users', 'employees.employee_id', '=', 'users.id')
            ->join('branches', 'employees.branch_id', '=', 'branches.branch_id')
            ->join('department', 'employees.department_id', '=', 'department.department_id')
            ->join('job_positions', 'employees.job_position_id', '=', 'job_positions.job_position_id');
    }
}
