<?php

namespace App\Repositories;

use App\Helpers\DateHelpers;
use App\Interfaces\WorkShiftsInterface;
use App\Models\WorkShift;

class WorkShiftsRepository implements WorkShiftsInterface
{
    /**
     * Get working hours of employee
     * 
     * @return array
     */
    public function getWorkingHours($workshiftId){
        $workShift = WorkShift::where('work_shift_id', $workshiftId)->first();

        $workShiftStart = $workShift->start_time;
        $workShiftEnd = $workShift->end_time;

        $minutesDiff = DateHelpers::minutesDifference($workShiftStart, $workShiftEnd);

        return $minutesDiff;
    }

    public function getWorkShiftByID($workshiftId)
    {
        return WorkShift::where('work_shift_id', $workshiftId)->first();
    }
}
