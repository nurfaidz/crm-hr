<?php

namespace App\Repositories;

use App\Helpers\DateHelpers;
use App\Interfaces\ManualAttendanceInterface;
use App\Models\Attendance;
use Carbon\Carbon;
use Exception;
use DateTime;
use Error;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ManualAttendanceRepository implements ManualAttendanceInterface
{
   
    /**
     * 
     * Get all manual attendance approved
     * 
     * @return array
     * 
     **/
    public function getAllManualAttendanceAppr($branchId, $employeeId)
    {
        $manualAttendance =  Attendance::join('employees', 'attendances.employee_id', '=', 'employees.employee_id')
            ->join('branches', 'employees.branch_id', '=', 'branches.branch_id')
            ->join('department', 'employees.department_id', '=', 'department.department_id')
            ->join('users', 'employees.user_id', '=', 'users.id')
            ->join('status_codes', 'attendances.status', '=', 'status_codes.code')
            ->where('employees.branch_id', '=', $branchId)
            ->where('employees.employee_id', '!=', $employeeId)
            ->orderBy('attendances.date');

        return $manualAttendance;
    }

    /**
     * 
     * Get manual attendance details
     * 
     * @return array
     * 
     **/
    public function getManualAttendanceById($Id)
    {
        $manualAttendance = Attendance::join('status_codes', 'attendances.status', '=', 'status_codes.code')
            ->join('employees', 'attendances.employee_id', '=', 'employees.employee_id')
            ->join('branches', 'employees.branch_id', '=', 'branches.branch_id')
            ->join('department', 'employees.department_id', '=', 'department.department_id')
            ->join('job_positions', 'employees.job_position_id', '=', 'job_positions.job_position_id')
            ->where("id", "=", $Id)
            ->first([
                'attendances.*',
                'employees.user_id',
                'employees.employee_id',
                'employees.first_name',
                'employees.last_name',
                'employees.image',
                'branches.branch_name',
                'department.department_name',
                'job_positions.job_position',
                'status_codes.short'
            ]);

            if(!$manualAttendance === null){
                $minuteDiff = DateHelpers::minutesDifference($manualAttendance->check_in, $manualAttendance->check_out);
                $manualAttendance->duration = DateHelpers::minutesToHours($minuteDiff);
            }

        return $manualAttendance;
    }

    public function checkDateHasManualAttendance($id)
    {
        $man = Attendance::where('id', $id)
            ->first();

        return $man !== null;
    } 
}