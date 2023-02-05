<?php

namespace App\Repositories;

use App\Interfaces\LeaveApplicationInterface;
use App\Models\LeaveType;
use App\Models\LeavePeriod;
use App\Models\LeaveApplication;
use App\Models\JointHoliday;
use App\Models\NationalHoliday;
use App\Helpers\DateHelpers;
use Carbon\Carbon;
use Exception;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class LeaveApplicationRepository implements LeaveApplicationInterface
{
    public function getAllLeaveApplications()
    {
        return LeaveApplication::join('employees', 'leave_applications.employee_id', '=', 'employees.employee_id')
            ->join('branches', 'employees.branch_id', '=', 'branches.branch_id')
            ->join('department', 'employees.department_id', '=', 'department.department_id')
            ->join('job_positions', 'employees.job_position_id', '=', 'job_positions.job_position_id')
            ->join('leave_types', 'leave_applications.leave_type_id', '=', 'leave_types.leave_type_id')
            ->join('status_codes', 'leave_applications.status', '=', 'status_codes.code')
            ->leftJoin('leave_periods', 'leave_applications.leave_period_id', '=', 'leave_periods.leave_period_id')
            ->leftJoin('option_leaves', 'leave_applications.option_leave_id', '=', 'option_leaves.option_leave_id')
            ->get();
    }

    public function getAllLeave($branchId, $employeeId)
    {
        return LeaveApplication::join('employees', 'leave_applications.employee_id', '=', 'employees.employee_id')
            ->join('branches', 'employees.branch_id', '=', 'branches.branch_id')
            ->join('department', 'employees.department_id', '=', 'department.department_id')
            ->join('leave_types', 'leave_applications.leave_type_id', '=', 'leave_types.leave_type_id')
            ->join('status_codes', 'leave_applications.status', '=', 'status_codes.code')
            ->where('employees.branch_id', '=', $branchId)
            ->where('employees.employee_id', '!=', $employeeId);
    }

    public function getLeaveApplicationByid($id)
    {
        return LeaveApplication::where('leave_application_id', $id);
    }

    public function getAllHolidays()
    {
        return NationalHoliday::join('manage_holidays', 'national_holidays.holiday_id', '=', 'manage_holidays.holiday_id')
            ->where('start_date', '>=', Carbon::now()->toDateString())->get();
    }

    public function getLeaveApprovalAnnualLeave($leaveApplicationId, $leavePeriodId)
    {
        $balances = LeaveApplication::join('leave_periods', 'leave_applications.leave_period_id', '=', 'leave_periods.leave_period_id')->join('status_codes', 'leave_applications.status', '=', 'status_codes.code')->where('leave_application_id', '!=', $leaveApplicationId)->where('leave_applications.leave_period_id', '=', $leavePeriodId)->where('short', '=', 'Approved');

        $annualBalances = $balances->where('leave_type_id', '=', 1)->get(['number_of_day', 'limit']);
        $annualNumOfDay = 0;

        foreach ($annualBalances as $annualBalance) {
            $annualNumOfDay += $annualBalance['number_of_day'];
        }

        $annualLeaveBalance = $annualBalances->first();

        if ($annualLeaveBalance != null) {
            $annualLeaveBalance = $annualLeaveBalance['limit'] - $annualNumOfDay;
        } else {
            $annualLeaveBalance = 12;
        }

        $annualLeaveUsedBalance = $annualNumOfDay;

        return [$annualLeaveBalance, $annualLeaveUsedBalance];
    }

    public function getLeaveApprovalBigLeave($leaveApplicationId, $leavePeriodId)
    {
        $balances = LeaveApplication::join('leave_periods', 'leave_applications.leave_period_id', '=', 'leave_periods.leave_period_id')->join('status_codes', 'leave_applications.status', '=', 'status_codes.code')->where('leave_application_id', '!=', $leaveApplicationId)->where('leave_applications.leave_period_id', '=', $leavePeriodId)->where('short', '=', 'Approved');

        $bigBalances = $balances->where('leave_type_id', '=', 2)->get('number_of_day');
        $bigNumOfDay = 0;

        foreach ($bigBalances as $bigBalance) {
            $bigNumOfDay += $bigBalance['number_of_day'];
        }

        $bigLeaveBalance = 30 - $bigNumOfDay;
        $bigLeaveUsedBalance = $bigNumOfDay;

        return [$bigLeaveBalance, $bigLeaveUsedBalance];
    }

    public function getLeaveApprovalMaternityLeave($leaveApplicationId, $leavePeriodId)
    {
        $balances = LeaveApplication::join('leave_periods', 'leave_applications.leave_period_id', '=', 'leave_periods.leave_period_id')->join('status_codes', 'leave_applications.status', '=', 'status_codes.code')->where('leave_application_id', '!=', $leaveApplicationId)->where('leave_applications.leave_period_id', '=', $leavePeriodId)->where('short', '=', 'Approved');

        $maternityBalances = $balances->where('leave_type_id', '=', 3)->get('number_of_day');
        $maternityNumOfDay = 0;

        foreach ($maternityBalances as $maternityBalance) {
            $maternityNumOfDay += $maternityBalance['number_of_day'];
        }

        $maternityLeaveBalance = 91 - $maternityNumOfDay;
        $maternityLeaveUsedBalance = $maternityNumOfDay;

        return [$maternityLeaveBalance, $maternityLeaveUsedBalance];
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

    public function countLeaveBalance($employeeId, $year)
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

    public function countCanceledLeave($employeeId, $year)
    {
        return LeaveApplication::where('employee_id', '=', $employeeId)
            ->whereYear('application_from_date', '<=', $year)
            ->whereYear('application_to_date', '>=', $year)
            ->where('state', '=', 'real')
            ->where('status', '=', 'can')
            ->count();
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

    public function countRejectedLeave($employeeId, $year)
    {
        return LeaveApplication::where('employee_id', '=', $employeeId)
            ->whereYear('application_from_date', '<=', $year)
            ->whereYear('application_to_date', '>=', $year)
            ->where('state', '=', 'real')
            ->where(function($query) {
                $query->where('status', '=', 'lhn')->orWhere('status', '=', 'lmn');
            })
            ->count();
    }

    public function createLeaveApplication($data)
    {
        return LeaveApplication::create($data);
    }

    public function updateLeaveApplication($id, array $data)
    {
        return LeaveApplication::where('leave_application_id', $id)->update($data);
    }

    public function cancelLeaveApplication($id)
    {
        return LeaveApplication::where('leave_application_id', '=', $id)->where('state', '=', 'real')->update(['status' => 'can', 'cancel_by' => Auth::user()->employee->employee_id, 'cancel_date' => Carbon::now()]);
    }

    public function rejectLeaveApplication($id, $reason)
    {
        $userRole = Auth::user()->roles[0]->name;

        if ($userRole == 'Manager') {
            return LeaveApplication::where('leave_application_id', '=', $id)->update(['status' => 'lmn', 'reject_by' => Auth::user()->employee->employee_id, 'reject_date' => Carbon::now(), 'reject_reason' => $reason]);
        } else {
            return LeaveApplication::where('leave_application_id', '=', $id)->update(['status' => 'lhn', 'reject_by' => Auth::user()->employee->employee_id, 'reject_date' => Carbon::now(), 'reject_reason' => $reason]);
        }
    }

    public function approveLeaveApplication($id)
    {
        $userRole = Auth::user()->roles[0]->name;

        if ($userRole == 'Manager') {
            return LeaveApplication::where('leave_application_id', '=', $id)->update(['status' => 'lmy', 'approve_by' => Auth::user()->employee->employee_id, 'approve_date' => Carbon::now()]);
        } else {
            return LeaveApplication::where('leave_application_id', '=', $id)->update(['status' => 'lhy', 'approve_by' => Auth::user()->employee->employee_id, 'approve_date' => Carbon::now()]);
        }
    }

    public function calculateEmployeeLeaveBalance($leaveTypeId, $employeeId, $leavePeriodId)
    {
        $leaveType = LeaveType::where('leave_type_id', $leaveTypeId)->first();
        $leavePeriod = LeavePeriod::where('leave_period_id', $leavePeriodId)->first();

        $leaveNewBalance = LeaveApplication::where('employee_id', $employeeId)
            ->whereIn('status', ['lhy','lmy'])
            ->where('leave_type_id', $leaveTypeId)
            ->whereBetween('approve_date', [$leavePeriod->from_date, $leavePeriod->to_date])
            ->sum('number_of_day');
        
        if ($leaveType->type == 0) {
            $joint_leave = JointHoliday::whereDate('from_date', '>=', $leavePeriod->from_date)
            ->whereDate('from_date', '<=', $leavePeriod->to_date)
            ->sum('count_day');

            $results = $leavePeriod->limit - $leaveNewBalance - $joint_leave;
        } else {
            $results = $leavePeriod->limit;
        }

        return $results;
    }

    /**
     * 
     * Check whether today is leaving (status is accepted)
     * 
     * @return array
     * 
     **/
    public function isTodayAreLeave($date)
    {
        $date = Carbon::parse($date)->format("Y-m-d");

        $leaveDays = DB::table('leave_applications')
            ->join('leave_types', 'leave_applications.leave_type_id', "=", "leave_types.leave_type_id")
            ->whereDate('application_from_date', "<=", $date)
            ->whereDate('application_to_date', ">=", $date)
            ->where('status', "=", "lhy")
            ->get();

        $purposes = [];

        foreach ($leaveDays as $leave) {
            array_push($purposes, $leave->leave_type_name);
        }

        return $purposes;
    }

       
    /**
     * 
     * Get all leave appication approved
     * 
     * @return array
     * 
     **/
    public function getAllLeaveApplicationListsAppr($branchId, $employeeId)
    {
        $leaveApplications =  LeaveApplication::join('employees', 'leave_applications.employee_id', '=', 'employees.employee_id')
            // ->join('branches', 'employees.branch_id', '=', 'branches.branch_id')
            ->join('users', 'employees.user_id', '=', 'users.id')
            ->join('leave_types', 'leave_applications.leave_type_id', '=', 'leave_types.leave_type_id')
            ->join('status_codes', 'leave_applications.status', '=', 'status_codes.code')
            ->where('employees.branch_id', '=', $branchId)
            ->where('employees.employee_id', '!=', $employeeId)
            ->orderBy('leave_applications.application_date');

        return $leaveApplications;
    }

    /**
     * 
     * Get leave attendance details
     * 
     * @return array
     * 
     **/
    public function getLeaveApplicationsById($Id)
    {
        $leaveApplications = LeaveApplication::join('status_codes', 'leave_applications.status', '=', 'status_codes.code')
            ->join('leave_types', 'leave_applications.leave_type_id', '=', 'leave_types.leave_type_id')
            ->join('employees', 'leave_applications.employee_id', '=', 'employees.employee_id')
            ->join('branches', 'employees.branch_id', '=', 'branches.branch_id')
            ->join('department', 'employees.department_id', '=', 'department.department_id')
            ->join('job_positions', 'employees.job_position_id', '=', 'job_positions.job_position_id')
            ->where("leave_application_id", "=", $Id)
            ->first([
                'leave_applications.*',
                'leave_types.*',
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

            if($leaveApplications !== null){
                $minuteDiff = DateHelpers::minutesDifference($leaveApplications->check_in, $leaveApplications->check_out);
                $leaveApplications->duration = DateHelpers::minutesToHours($minuteDiff);
            }

        return $leaveApplications;
    }

    public function getUserLeaveHistory($id, $year)
    {
        $leaveApps = LeaveApplication::leftJoin('leave_types', 'leave_applications.leave_type_id', '=', 'leave_types.leave_type_id')
        ->whereYear('application_date', $year)
            ->where('employee_id', '=', $id);

        return $leaveApps;
    }

    public function countLeaveDaysAnually($id, $year, $leaveTypeId)
    {
        $leaveApps = LeaveApplication::where('leave_type_id', $leaveTypeId)
            ->whereYear('application_date', $year)
            ->where('employee_id', '=', $id)
            ->whereIn('status', ['lhy', 'lmy'])
            ->get('number_of_day');

        if (count($leaveApps) <= 0) {
            return 0;
        }

        $total = 0;
        foreach ($leaveApps as $l) {
            $total += ($l['number_of_day']);
        }

        return $total;
    }

    public function countRejectLeaveDaysAnually($id, $year, $leaveTypeId)
    {
        $leaveApps = LeaveApplication::where('leave_type_id', $leaveTypeId)
            ->whereYear('application_date', $year)
            ->where('employee_id', '=', $id)
            ->whereIn('status', ['lhn', 'lmn'])
            ->get('number_of_day');

        if (count($leaveApps) <= 0) {
            return 0;
        }

        $total = 0;
        foreach ($leaveApps as $l) {
            $total += ($l['number_of_day']);
        }

        return $total;
    }

    public function cancelLeave($id){
        return LeaveApplication::where('leave_application_id', '=', $id)->delete();
    }

    public function countAnnualApprAndPend($leaveTypeId, $employeeId, $leavePeriodId)
    {
        // $leaveType = LeaveType::where('leave_type_id', $leaveTypeId)->first();
        $leavePeriod = LeavePeriod::where('leave_period_id', $leavePeriodId)->first();

        $leaveNewBalance = LeaveApplication::where('employee_id', $employeeId)
            ->whereIn('status', ['lhy','lmy','lpd'])
            ->where('leave_type_id', $leaveTypeId)
            ->sum('number_of_day');

        $results = $leavePeriod->limit - $leaveNewBalance;
        
        // if ($leaveType->type == 0) {
        //     $joint_leave = JointHoliday::whereDate('from_date', '>=', $leavePeriod->from_date)
        //     ->whereDate('from_date', '<=', $leavePeriod->to_date)
        //     ->sum('count_day');

        //     $results = $leavePeriod->limit - $leaveNewBalance - $joint_leave;
        // } else {
        //     $results = $leavePeriod->limit;
        // }

        return $results;
    }

    public function getAllCountExceptAnnualLeave(){
        $leave = LeaveApplication::join('leave_types', 'leave_applications.leave_type_id', '=', 'leave_types.leave_type_id')
            ->whereYear('application_date', Carbon::now()->year)
            ->where('state', '=', 'real')
            ->where('leave_type_name', '!=', "Annual Leave")
            ->whereIn('status', ['lhy', 'lmy'])
            ->where('employee_id', '=', Auth::user()->employee->employee_id)
            ->get();

        return $leave;
    }

    public function getLeaveBalanceInformation($year){
        try{
            $collection = collect($this->getAllCountExceptAnnualLeave());
            $leavePeriods = LeavePeriod::whereYear('from_date', $year)->first();

            $data = $this->getAllLeave()
                ->where('leave_applications.leave_period_id', '=', $leavePeriods['leave_period_id'])
                ->where('leave_applications.employee_id', '=', Auth::user()->employee->employee_id)
                ->where('leave_applications.state', '=', 'real')
                ->where('status_codes.short', '=', 'Approved')
                ->latest('leave_applications.id')->first();

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
}
