<?php

namespace App\Http\Controllers;

use App\Helpers\DateHelpers;
use App\Models\Employee;
use App\Repositories\WorkDaysRepository;
use App\Repositories\WorkShiftsRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    private $workShiftsRepository, $workDaysRepository;

    public function __construct(
        WorkShiftsRepository $workShiftsRepository,
        WorkDaysRepository $workDaysRepository
    ) 
    {
        $this->workShiftsRepository = $workShiftsRepository;
        $this->workDaysRepository = $workDaysRepository;
    }

    public function workshifts(){
        $user = Auth::user();
        $employee = Employee::where('user_id', $user->id)->firstOrFail();

        $workDays = DateHelpers::workDaysShortener($this->workDaysRepository->getWorkingDays($employee->branch_id, $employee->work_shift_id));
        // dd($workDays);

        $result = [
            "workshift" => $this->workShiftsRepository->getWorkShiftByID($employee->work_shift_id),
            "workhours" => DateHelpers::minutesToHours($this->workShiftsRepository->getWorkingHours($employee->work_shift_id)),
            "workdays" => $workDays
         ];

        unset($user, $employee);

        return $result;
    }
}
