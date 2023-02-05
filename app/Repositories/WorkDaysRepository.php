<?php

namespace App\Repositories;

use App\Interfaces\WorkDaysInterface;
use App\Models\Workdays;

class WorkDaysRepository implements WorkDaysInterface
{
    /**
     * Get list days on him/her workdays
     * 
     * @return array
     */
    public function getWorkingDays($branchID,$workshiftID)
    {
        $workDays = Workdays
            ::join('days','workdays.days_id','=', 'days.days_id')
            ->join('work_shifts', 'workdays.work_shift_id', '=' , 'work_shifts.work_shift_id')
            ->where('branch_id', $branchID)
            ->where('work_shifts.work_shift_id', $workshiftID)
            ->orderBy('workdays.days_id')
            ->get();

        return $workDays;
    }

    public function getWorkDays($companyID, $branchID, $workshiftID)
    {
        $workDays = Workdays
            ::join('days', 'workdays.days_id', '=', 'days.days_id')
            ->join('work_shifts', 'workdays.work_shift_id', '=', 'work_shifts.work_shift_id')
            ->where('work_days.branch_id', $branchID)
            ->where('work_shifts.work_shift_id', $workshiftID)
            ->where('work_shifts.company_id', $companyID)
            ->orderBy('workdays.days_id')
            ->get();

        return $workDays;
    }
}
