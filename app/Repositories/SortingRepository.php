<?php

namespace App\Repositories;

use App\Interfaces\SortingInterface;
use App\Models\LeaveType;
use App\Models\LeavePeriod;
use App\Models\LeaveApplication;
use App\Models\JointHoliday;
use App\Models\NationalHoliday;
use App\Models\Attendance;
use App\Models\Employee;
use App\Helpers\DateHelpers;
use Carbon\Carbon;
use Exception;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class SortingRepository implements SortingInterface
{
    public function statisticsLeave($year)
    {
        $count = [];
        $remainder = 0;
        $year = (int)$year;
        $leavePeriods = LeavePeriod::whereYear('from_date', '<=', $year)->whereYear('to_date', '>=', $year)->get();
        
        foreach ($leavePeriods as $leavePeriod) {
            $leaveApplications = LeaveApplication::where('employee_id', '=', $employeeId)
                ->where('leave_type_id', '=', 1)
                ->where('leave_period_id', '=', $leavePeriod['leave_period_id'])
                ->where('state', '=', 'real')
                ->where(function($query) {
                    $query->where('status', '=', 'lhy')->orWhere('status', '=', 'lmy');
                })
                ->get();
            
            if (!($leaveApplications->isEmpty())) {
                foreach ($leaveApplications as $leaveApplication) {
                    if ($leaveApplication['option_request'] == 0) {
                        $count[] = $leaveApplication['number_of_day'] - 0.5;
                    } else {
                        $count[] = $leaveApplication['number_of_day'];
                    }
                }
            }

            $remainder += $leavePeriod['limit'];
        }

        return $remainder - array_sum($count);
    }

    public function groupAttendance()
    {
        $groupAttendance = Attendance::join('employees', 'attendances.employee_id', '=', 'employees.employee_id')
                            ->join('status_codes', 'attendances.status', '=', 'status_codes.code')
                            ->whereIn('status_codes.code', ['amw', 'acw'])
                            ->get([
                                'employees.employee_id',
                                'employees.first_name',
                                'employees.last_name',
                                'status_codes.code as status_code',
                            ])
                            ->groupBy('employees.employee_id');

        return $groupAttendance;
    }

    public function groupLeave()
    {
        $groupLeave = LeaveApplication::join('employees', 'leave_applications.employee_id', '=', 'employees.employee_id')
                        ->join('status_codes', 'leave_applications.status', '=', 'status_codes.code')
                        ->whereIn('status_codes.code', ['lmy', 'lhy'])
                        ->get([
                            'employees.employee_id',
                            'employees.first_name',
                            'employees.last_name',
                            'status_codes.code as status_code',
                        ])
                        ->groupBy('employees.employee_id');

        return $groupLeave;
    }

    public function calculateAttendance()
    {
        $count = [];
        $attendances = Attendance::join('employees', 'attendances.employee_id', '=', 'employees.employee_id')
                                    ->groupBy('employees.employee_id')
                                    ->count(); 
    }

    public function getAllLeave()
    {
        return LeaveApplication::join('employees', 'leave_applications.employee_id', '=', 'employees.employee_id')
            ->join('branches', 'employees.branch_id', '=', 'branches.branch_id')
            ->join('department', 'employees.department_id', '=', 'department.department_id')
            ->join('leave_types', 'leave_applications.leave_type_id', '=', 'leave_types.leave_type_id')
            ->join('status_codes', 'leave_applications.status', '=', 'status_codes.code');
            // ->where('employees.branch_id', '=', $branchId)
            // ->where('employees.employee_id', '!=', $employeeId);
    }

    public function getAllAttendance()
    {
        return Attendance::join('employees', 'attendances.employee_id', '=', 'employees.employee_id')
            ->join('branches', 'employees.branch_id', '=', 'branches.branch_id')
            ->join('department', 'employees.department_id', '=', 'department.department_id')
            ->join('status_codes', 'attendances.status', '=', 'status_codes.code');
    }

    public function getAllCountExceptAnnualLeave(){
        $leave = LeaveApplication::join('leave_types', 'leave_applications.leave_type_id', '=', 'leave_types.leave_type_id')
            ->whereYear('application_date', Carbon::now()->year)
            ->where('state', '=', 'real')
            ->where('leave_type_name', '!=', "Annual Leave")
            ->whereIn('status', ['lhy', 'lmy'])
            ->where('employee_id', '=', 'employees.employee_id')
            ->get();

        return $leave;
    }

    public function getLeaveBalanceInformation($year){
        try{
            $collection = collect($this->getAllCountExceptAnnualLeave());

            $leavePeriods = LeavePeriod::whereYear('from_date', $year)->first();

            $data = $this->getAllLeave()
                ->where('leave_applications.leave_period_id', '=', $leavePeriods['leave_period_id'])
                ->where('leave_applications.employee_id', '=', 'employees.employee_id')
                ->where('leave_applications.state', '=', 'real')
                ->where('status_codes.short', '=', 'Approved')
                ->latest('leave_applications.id')->get()->groupBy('employees.employee_id');

            $groupBy = $collection->groupBy('leave_type_name')->map(function($item) {
                return $item->sum('number_of_day');
            });

            return [
                'leaveBalance' => $groupBy,
                'leavePeriods' => $leavePeriods,
                'data' => $data,
            ];
        }
        catch(Exception $error){
            return $error->getMessage();
        }
    }

    public function getAllCountAttendance()
    {
        $attendance = Attendance::join('employees', 'attendances.employee_id', '=', 'employees.employee_id')
                        ->whereYear('date', Carbon::now()->year)
                        ->whereIn('attendances.status', ['acw', 'amw'])
                        ->where('employees.employee_id', '=', 'employees.employee_id')
                        ->get()->groupBy('employees.employee_id');

        return $attendance;
    }

    public function getAttendanceBalanceInformation($year)
    {
        try {
            $collection = collect($this->getAllCountAttendance());

            // dd($collection);

            $data = $this->getAllAttendance()
                    ->where('attendances.employee_id', '=', 'employees.employee_id')
                    ->where('status_codes.short', '=', 'Check In')
                    ->latest('attendances.id')->get()->groupBy('employees.employee_id');

            $groupBy = $collection->groupBy('employee_id')->map(function($item){
                return $item->sum('working_hour');
            });

            return [
                'attendanceBalance' => $groupBy,
                'data' => $data,
            ];

        } catch (Exception $error) {
            return $error->getMessage();
        }
    }

    public function countAttendanceEmployee()
    {
        $attendances = DB::table('attendances')
            ->leftJoin('employees', 'attendances.employee_id', '=', 'employees.employee_id')
            ->leftJoin('branches', 'employees.branch_id', '=', 'branches.branch_id')
            ->leftJoin('department', 'employees.department_id', '=', 'department.department_id')
            ->join('status_codes', 'attendances.status', '=', 'status_codes.code');

        return $attendances;
    }

    public function statistics($employeeId, $year)
    {
        try {
            $leaveBalance = $this->countLeaveBalance($employeeId, $year);
            $numberOfCanceled = $this->countCanceledLeave($employeeId, $year);
            $numberOfApproved = $this->countApprovedLeave($employeeId, $year);
            $numberOfRejected = $this->countRejectedLeave($employeeId, $year);

            return [
                'leaveBalance' => $leaveBalance,
                'numberOfCanceled' => $numberOfCanceled,
                'numberOfApproved' => $numberOfApproved,
                'numberOfRejected' => $numberOfRejected
            ];
        } catch (Exception $e) {
            return $e->getMessage();
        }
    }

    public function countWorkTime($year)
    {
        $count = [];
        $remainder = 0;
        $year = (int)$year;
        $Periods = Attendance::whereYear('date', $year)->get();

        foreach ($Periods as $Period) {
            $attendances = Attendance::where('employee_id', '=', 'employee_id')
            ->where(function($query){
                $query->whereIn('status', ['acw', 'amw']);
            })
            ->get();

            if(!($attendances->isEmpty())){
                foreach ($attendances as $attendance) {
                    if ($attendance['late_duration'] == 0) {
                        $count[] = $attenndance['working_hour'];
                    } else {
                        $count[] = $attendance['working_hour'] - $attendance['late_duration'];
                    };
                }
            }

        }

        return array_sum($count);
    }


    public function countEachLeaveBalance($employeeId, $year)
    {
        $count = [];
        $remainder = [];
        $year = (int)$year;
        $leavePeriods = LeavePeriod::whereYear('from_date', '<=', $year)->whereYear('to_date', '>=', $year)->get();
        
        foreach ($leavePeriods as $leavePeriod) {
            $leaveApplications = LeaveApplication::where('employee_id', '=', $employeeId)
                ->where('leave_type_id', '=', 1)
                ->where('leave_period_id', '=', $leavePeriod['leave_period_id'])
                ->where('state', '=', 'real')
                ->where(function($query) {
                    $query->where('status', '=', 'lhy')->orWhere('status', '=', 'lmy');
                })
                ->get();
            
            if (!($leaveApplications->isEmpty())) {
                foreach ($leaveApplications as $leaveApplication) {
                    if ($leaveApplication['option_request'] == 0) {
                        $count[] = $leaveApplication['number_of_day'] - 0.5;
                    } else {
                        $count[] = $leaveApplication['number_of_day'];
                    }
                }
            }

            $remainder[] = $leavePeriod['limit'] - array_sum($count);
        }

        return array_combine(range(1, count($remainder)), array_values($remainder));
    }

    public function countApprovedLeave($employeeId, $year)
    {
        return LeaveApplication::where('employee_id', '=', $employeeId)
            ->whereYear('application_from_date', '<=', $year)
            ->whereYear('application_to_date', '>=', $year)
            ->where('state', '=', 'real')
            ->where(function($query) {
                $query->where('status', '=', 'lhy')->orWhere('status', '=', 'lmy');
            })
            ->count();
    }

    public function getEmployeeWorker()
    {
        $employeeWorker = Attendance::join('employees', 'attendances.employee_id', '=', 'employees.employee_id')
            ->join('branches', 'employees.branch_id', '=', 'branches.branch_id')
            ->join('department', 'employees.department_id', '=', 'department.department_id')
            ->join('job_positions', 'employees.job_position_id', '=', 'job_positions.job_position_id')
            ->join('job_classes', 'employees.job_class_id', '=', 'job_classes.job_class_id')
            ->join('status_codes', 'attendances.status', '=', 'status_codes.code');

        return $employeeWorker;
    }

}
