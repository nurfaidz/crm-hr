<?php

namespace App\Repositories;

use App\Helpers\DateHelpers;
use App\Interfaces\OvertimeInterface;
use App\Models\Overtime;
use Carbon\Carbon;
use DateTime;
use Error;
use Exception;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class OvertimeRepository implements OvertimeInterface
{
    /**
     * 
     * Get approved overtime hours per month
     * 
     * @return array
     * 
     **/
    public function getOvertimeHours($employeeId, $month, $year)
    {
        $times = DB::table('overtimes')
            ->whereMonth("date", "=", $month)
            ->whereYear("date", "=", $year)
            ->where('employee_id', '=', $employeeId)
            ->get(['start_time', 'end_time']);

        $totalSeconds = 0;
        foreach ($times as $time) {
            $totalSeconds += DateHelpers::secondsDifference($time->start_time, $time->end_time);
        }

        return (object)[
            "h" => gmdate("H", $totalSeconds),
            "m" => gmdate("i", $totalSeconds)
        ];
    }

    /**
     * 
     * Get Overtime By Id
     * 
     * @return array
     * 
     **/
    public function getOvertimeById($overtimeId)
    {
        $overtimes = DB::table('overtimes')
            ->join('status_codes', 'overtimes.status', '=', 'status_codes.code')
            ->join('employees', 'overtimes.employee_id', '=', 'employees.employee_id')
            ->join('department', 'employees.department_id', '=', 'department.department_id')
            ->join('job_positions', 'employees.job_position_id', '=', 'job_positions.job_position_id')
            ->where("overtime_id", "=", $overtimeId)
            ->first([
                'overtimes.*',
                'employees.user_id',
                'employees.employee_id',
                'employees.first_name',
                'employees.last_name',
                'employees.image',
                'department.department_name',
                'job_positions.job_position',
                'status_codes.short'
            ]);

        if ($overtimes !== null) {
            $minuteDiff = DateHelpers::minutesDifference($overtimes->start_time, $overtimes->end_time);
            $overtimes->duration = DateHelpers::minutesToHours($minuteDiff);
        }

        return $overtimes;
    }

    public function getOvertimeEmployeeById($overtimeId)
    {
        $overtime = DB::table('overtimes')
            ->join('employees', 'overtimes.employee_id', '=', 'employees.employee_id')
            ->join('companies', 'employees.company_id', '=', 'companies.company_id')
            ->join('branches', 'employees.branch_id', '=', 'branches.branch_id')
            ->join('department', 'employees.department_id', '=', 'department.department_id')
            ->join('job_positions', 'employees.job_position_id', '=', 'job_positions.job_position_id')
            ->leftJoin('employees as emp', 'overtimes.update_by', '=', 'emp.employee_id')
            ->where("overtime_id", "=", $overtimeId)
            ->first([
                'overtimes.*',
                'employees.first_name',
                'employees.last_name',
                'employees.nip',
                'companies.company_name',
                'branches.branch_name',
                'department.department_name',
                'job_positions.job_position',
                'emp.first_name as f_name',
                'emp.last_name as l_name'
            ]);

        return $overtime;
    }

    /**
     * 
     * Get Overtime By Id for notes
     * 
     * @return array
     * 
     **/
    public function getOvertimeByIdForNotes($overtimeId)
    {
        $overtime = DB::table('overtimes')
            ->where("overtime_id", "=", $overtimeId)
            ->first(['notes']);

        return $overtime;
    }

    /**
     * 
     * Get Overtime Today
     * 
     * @return array
     * 
     **/
    public function getOvertimeToday($employeeId)
    {
        $date = Carbon::now();
        $overtime = DB::table('overtimes')
            ->join('status_codes', 'overtimes.status', '=', 'status_codes.code')
            ->whereDate("date", $date)
            ->where("status", "=", "oap")
            ->where('employee_id', '=', $employeeId)
            ->first();

        return $overtime;
    }

    /**
     * 
     * Get approved overtime hours Today
     * 
     * @return array
     * 
     **/
    public function getOvertimeHoursTest()
    {
        $date = Carbon::now()->format('Y-m-d');
        $overtimes = DB::table('overtimes')
            ->where("status", "=", "oap")
            ->where("date", "=", $date)
            ->get(['start_time', 'end_time']);

        $totalSeconds = 0;
        foreach ($overtimes as $overtime) {
            $totalSeconds += DateHelpers::secondsDifference($overtime->start_time, $overtime->end_time);
        }

        return (object)[
            "h" => gmdate("H", $totalSeconds),
            "m" => gmdate("i", $totalSeconds)
        ];
    }

    /**
     * 
     * Get approved overtime hours Details
     * 
     * @return array
     * 
     **/
    public function getOvertimeHoursToday($employeeId)
    {
        $dateNow = Carbon::now();
        $overtimes = DB::table('overtimes')
            ->where("employee_id", "=", $employeeId)
            ->where("status", "=", "oap")
            ->whereDate('date', $dateNow)
            ->get(['start_time', 'end_time']);

        $totalSeconds = 0;
        foreach ($overtimes as $overtime) {
            $totalSeconds += DateHelpers::secondsDifference($overtime->start_time, $overtime->end_time);
        }

        return (object)[
            "h" => gmdate("H", $totalSeconds),
            "m" => gmdate("i", $totalSeconds)
        ];
    }

    /**
     * 
     * Get Overtime All
     * 
     * @return array
     * 
     **/
    public function getOvertimeAll($employeeId, $year = null, $month = null)
    {
        // print_r($month);
        $overtimes = DB::table('overtimes')
            ->join('employees', 'overtimes.employee_id', '=', 'employees.employee_id')
            ->join('work_shifts', 'employees.work_shift_id', '=', 'work_shifts.work_shift_id')
            ->join('status_codes', 'overtimes.status', '=', 'status_codes.code')
            ->where('overtimes.employee_id', '=', $employeeId)
            ->get([
                'overtimes.overtime_id',
                'overtimes.start_time',
                'overtimes.end_time',
                // 'overtimes.status',
                'overtimes.notes',
                'overtimes.date',
                // 'status_codes.code as status_code',
                'status_codes.short as status_desc',
                'work_shifts.shift_name',
                'work_shifts.work_shift_id'

            ]);

        if (!is_null($month) || !is_null($year)) {
            $overtimes = DB::table('overtimes')
                ->join('employees', 'overtimes.employee_id', '=', 'employees.employee_id')
                ->join('work_shifts', 'employees.work_shift_id', '=', 'work_shifts.work_shift_id')
                ->join('status_codes', 'overtimes.status', '=', 'status_codes.code')
                ->where('overtimes.employee_id', '=', $employeeId)
                ->where('overtimes.status', '!=', 'opd')
                ->whereMonth('date', $month)
                ->whereYear('date', $year)
                ->orderBy('date')
                ->get([
                    'overtimes.overtime_id',
                    'overtimes.start_time',
                    'overtimes.end_time',
                    'overtimes.status',
                    'overtimes.notes',
                    'overtimes.date',
                    'status_codes.code as status_code',
                    'status_codes.short as status_desc',
                    'work_shifts.shift_name',
                    'work_shifts.work_shift_id'
                ]);
        }

        foreach ($overtimes as $overtime) {
            $minuteDiff = DateHelpers::minutesDifference($overtime->start_time, $overtime->end_time);
            $overtime->work_shift = DateHelpers::minutesToHours($minuteDiff);
        }

        return $overtimes;
    }

    public function createOvertime(array $arr)
    {
        try {
            $overtime = new Overtime;
            return $overtime->save($arr);
        } catch (Exception $error) {
            return $error->getMessage();
        }
    }

    public function getOvertimePending($employeeId)
    {
        $overtimes = DB::table('overtimes')
            ->where("status", "=", "opd")
            ->where('employee_id', '=', $employeeId)
            ->orderBy('date')
            ->get();

        foreach ($overtimes as $overtime) {
            $minuteDiff = DateHelpers::minutesDifference($overtime->start_time, $overtime->end_time);
            $overtime->work_shift = DateHelpers::minutesToHours($minuteDiff);
        }

        return $overtimes;
    }

    public function cancelOvertime($overtimeId)
    {
        return DB::table('overtimes')->where('overtime_id', '=', $overtimeId)
            ->delete();
    }

    public function calculateRemainingOvertimeonWeeks($employeeId, $totalQouta, $date)
    {
        $startOfWeek = $date->startOfWeek()->format('Y-m-d H:i:s');
        $endOfWeek = $date->EndOfWeek()->format('Y-m-d H:i:s');

        $overtimes = Overtime::where('employee_id', '=', $employeeId)
            ->whereBetween('date', [$startOfWeek, $endOfWeek])
            ->get(['start_time', 'end_time']);

        $overtimeMinutes = 0;

        foreach ($overtimes as $overtime) {
            $minuteDiff = DateHelpers::minutesDifference($overtime->start_time, $overtime->end_time);
            $overtimeMinutes += $minuteDiff;
        }

        $total = $totalQouta - $overtimeMinutes;
        if ($total > 0) {
            return DateHelpers::minutesToHours($total);
        }

        return DateHelpers::minutesToHours($totalQouta);
    }

    public function getOvertimeAllPending($branchId, $employeeId)
    {
        $overtimes = DB::table('overtimes')
            ->join('employees', 'overtimes.employee_id', '=', 'employees.employee_id')
            ->join('branches', 'employees.branch_id', '=', 'branches.branch_id')
            ->join('department', 'employees.department_id', '=', 'department.department_id')
            ->join('job_positions', 'employees.job_position_id', '=', 'job_positions.job_position_id')
            ->join('work_shifts', 'employees.work_shift_id', '=', 'work_shifts.work_shift_id')
            ->join('status_codes', 'overtimes.status', '=', 'status_codes.code')
            ->where('employees.branch_id', '=', $branchId)
            ->where('employees.employee_id', '!=', $employeeId)
            ->where('overtimes.status', '=', 'opd')
            ->orderBy('date')
            ->get([
                'employees.user_id',
                'employees.first_name',
                'employees.last_name',
                'employees.nip',
                'branches.branch_name',
                'department.department_name',
                'department.code',
                'job_positions.job_position',
                'overtimes.overtime_id',
                'overtimes.start_time',
                'overtimes.end_time',
                'overtimes.status',
                'overtimes.notes',
                'overtimes.date',
                'overtimes.created_at',
                'status_codes.code as status_code',
                'status_codes.short as status_desc',
                'work_shifts.shift_name',
                'work_shifts.work_shift_id'
            ]);

        foreach ($overtimes as $overtime) {
            $minuteDiff = DateHelpers::minutesDifference($overtime->start_time, $overtime->end_time);
            $overtime->work_shift = DateHelpers::minutesToHours($minuteDiff);
        }

        return $overtimes;
    }

    public function getOvertimeAllFilter($date)
    {
        $employeeIsLoggedIn = DB::table('employees')->where('user_id', '=', Auth::user()->id)
                                    ->first();

        $overtimes = DB::table('overtimes')
            ->join('employees', 'overtimes.employee_id', '=', 'employees.employee_id')
            ->join('companies', 'employees.company_id', '=', 'companies.company_id')
            ->join('branches', 'employees.branch_id', '=', 'branches.branch_id')
            ->join('department', 'employees.department_id', '=', 'department.department_id')
            ->join('job_positions', 'employees.job_position_id', '=', 'job_positions.job_position_id')
            ->join('work_shifts', 'employees.work_shift_id', '=', 'work_shifts.work_shift_id')
            ->join('status_codes', 'overtimes.status', '=', 'status_codes.code')
            ->whereDate('date', '=', $date)
            ->where('department.department_id', '=', $employeeIsLoggedIn->department_id)
            ->where(function ($query) {
                $query->where('overtimes.status', '=', 'oap')
                    ->orWhere('overtimes.status', '=', 'orj');
            })
            ->orderBy('date')
            ->get([
                'employees.first_name',
                'employees.last_name',
                'employees.nip',
                'employees.user_id',
                'companies.company_name as holding',
                'branches.branch_name as entity',
                'department.department_name as department',
                'job_positions.job_position',
                'overtimes.overtime_id',
                'overtimes.start_time',
                'overtimes.end_time',
                'overtimes.status',
                'overtimes.notes',
                'overtimes.date',
                'overtimes.created_at',
                'status_codes.code as status_code',
                'status_codes.short as status_desc',
                'work_shifts.shift_name',
                'work_shifts.work_shift_id',
            ]);

        foreach ($overtimes as $overtime) {
            $minuteDiff = DateHelpers::minutesDifference($overtime->start_time, $overtime->end_time);
            $overtime->work_shift = DateHelpers::minutesToHours($minuteDiff);
        }

        return $overtimes;
    }

    public function autoOvertimeCanceling()
    {
        try {
            $data = [];
            $now = Carbon::now();
            $overtimes = Overtime::where('date', '<=', $now)
                ->where('status', '=', 'opd')
                ->get();

            foreach ($overtimes as $overtime) {
                if ($overtime->date < date('Y-m-d') || ($overtime->date == date('Y-m-d') && strtotime($overtime->end_time) <= strtotime($now))) {

                    DB::table('overtimes')
                        ->where('overtime_id', '=', $overtime->overtime_id)
                        ->update(
                            [
                                'status' => 'orj',
                                'reject_reason' => 'Passed request’s time limit.',
                                'update_time' => $now
                            ]
                        ); // set to rejecting

                    array_push($data, [
                        'message' => 'Overtime succed to canceled',
                        'overtime_id' => $overtime->overtime_id
                    ]);
                }
            }

            return $data;
        } catch (Exception $e) {
            return $e->getMessage();
        }
    }

    public function getOvertimeDay($employeeId, $date)
    {
        $overtime = DB::table('overtimes')
            ->join('status_codes', 'overtimes.status', '=', 'status_codes.code')
            ->whereDate("date", $date)
            ->where('employee_id', '=', $employeeId)
            ->first();

        return $overtime;
    }

    public function getOvertimeListAppr($employeeId, $branchId)
    {
        $overtime = DB::table('overtimes')
            ->join('status_codes', 'overtimes.status', '=', 'status_codes.code')
            ->join('employees', 'overtimes.employee_id', '=', 'employees.employee_id')
            ->join('department', 'employees.department_id', '=', 'department.department_id')
            ->where('employees.branch_id', '=', $branchId)
            ->where('employees.employee_id', '!=', $employeeId)
            ->orderBy('overtimes.date');
        return $overtime;
    }

    public function updateOvertime($overtimeId, array $data)
    {
        return DB::table('overtimes')->where('overtime_id', '=', $overtimeId)
            ->update($data);
    }

    // Eloquent
    public function createOvertimeEloquent(array $arr)
    {
        return Overtime::create($arr);
    }
}
