<?php

namespace App\Repositories;

use DateTime;
use Exception;
use Carbon\Carbon;
use App\Models\Employee;
use App\Models\LeaveType;
use App\Models\Attendance;
use App\Models\LeavePeriod;
use App\Helpers\DateHelpers;
use Illuminate\Support\Facades\DB;
use App\Interfaces\AttendanceInterface;
use App\Repositories\OvertimeRepository;
use App\Repositories\LeaveApplicationRepository;


class AttendanceRepository implements AttendanceInterface
{
    private $leaveApplicationRepository;
    private $overtimeRepository;

    public function __construct(LeaveApplicationRepository $leaveApplicationRepository, OvertimeRepository $overtimeRepository)
    {
        $this->leaveApplicationRepository = $leaveApplicationRepository;
        $this->overtimeRepository = $overtimeRepository;
    }

    /**
     * 
     * Get all the statistic
     * 
     * @return array
     * 
     **/
    public function statistics($employeeId, $month, $year)
    {
        try {
            $leaveTypeID = LeaveType::where("leave_type_name", "Annual Leave")->get("leave_type_id")->first();
            if (!isset($leaveTypeID["leave_type_id"]))
                throw new Exception("Please Contact your administrator or developer, \"Annual Leave\" is not found.");

            $leaveTypeID = $leaveTypeID["leave_type_id"];

            $leavePeriodID = LeavePeriod::whereYear("from_date", "<=", $year)
                ->whereMonth("from_date", '<=', $month)
                ->whereYear("to_date", ">=", $year)
                ->whereMonth("to_date", ">=", $month)
                ->get()->first();

            if (!isset($leavePeriodID["leave_period_id"]))
                throw new Exception("Please Contact your administrator or developer, \"Leave Period on {$year}\" is not found.");

            $leavePeriodID = $leavePeriodID["leave_period_id"];

            // Work Days
            $workDays = [];

            // Attend
            $attend = $this->getAttend($employeeId, $month, $year);
            $attendDays = $this->getAttendDays($employeeId, $month, $year);

            // Insert Image Location on Attend Days
            $APP_URL = env('APP_URL');
            $path = $APP_URL . '/img/attendances/';

            foreach ($attendDays as $attendDay) {
                $day = explode("-", $attendDay->date);

                $folder = implode("", $day);
                $fileName = $attendDay->image;
                $attendDay->image = $path . $folder . "/" . $fileName;
            }

            // Late Hours
            $lateHours = DateHelpers::minutesToHours($this->getLateHours($employeeId, $month, $year));

            // Leave Balance
            $leaveBalance = $this->leaveApplicationRepository->calculateEmployeeLeaveBalance($leaveTypeID, $employeeId, $leavePeriodID);

            // Overtimes
            $overtime = DateHelpers::minutesToHours($this->getOvertime($employeeId, $month, $year));

            // Absence
            $absence = [];

            // Leave Period
            $start = $this->getLeavePeriod($month, $year)->from_date;
            $end = $this->getLeavePeriod($month, $year)->to_date;
            $limit = $this->getLeavePeriod($month, $year)->limit;

            // Statistics
            $days_presence = $this->getDaysPresence($employeeId, $month, $year);
            $days_absence = $this->getDaysAbsence($employeeId, $month, $year);
            $days_leave_by_month = $this->getDaysLeaveByMonth($employeeId, $month, $year)
                ->get(["leave_application_id as id","application_date", "application_from_date", "application_to_date", "number_of_day", 'status_codes.short as status']);

            $totals_leave_by_month = 0;
            $total_days_leave = $this->getTotalDaysLeave($employeeId, $year);
            $hours_overtime = floor($this->getHoursOvertime($employeeId, $month, $year) / 60);
            $total_late_in = $this->getTotalLateIn($employeeId, $month, $year);
            $attendances = $this->getAttendances($employeeId, $month, $year);


            if(!is_null($attendances)){
                foreach ($attendances as $attendance) {
                    if (!is_null($attendance->overtime)) {
                        $time = $attendance->overtime;
                        $attendance->overtime = DateHelpers::minutesToHours($time);
                    }
                    if (!is_null($attendance->late_duration)) {
                        $time = $attendance->late_duration;
                        $attendance->late_duration = DateHelpers::minutesToHours($time);
                    }
                }
            }

            if (!is_null($days_leave_by_month)) {
                foreach ($days_leave_by_month as $leave) {
                    if(!is_null($leave->number_of_day))
                    $totals_leave_by_month = $totals_leave_by_month + $leave->number_of_day;
                }
            }

            $response = [
                "workDays" => $workDays,
                "attend" => $attend,
                "attendDays" => $attendDays,
                "late_hours" => $lateHours,
                "leave_balance" => $leaveBalance,
                "overtime" => $overtime,
                "absence" => $absence,
                "start" => $start,
                "end" => $end,
                "limit" => $limit,
                "days_presence" => $days_presence,
                "days_absence" => $days_absence,
                "totals_leave_by_month" => $totals_leave_by_month,
                "total_days_leave" => $total_days_leave,
                "hours_overtime" => $hours_overtime,
                "total_late_in" => $total_late_in,
                "attendances" => $attendances,
                "leaves" => $days_leave_by_month
            ];

            return $response;
        } catch (Exception $error) {
            return $error->getMessage();
        }
    }

    public function getLeavePeriod($month, $year)
    {
        return DB::table('leave_periods')
            ->whereMonth('from_date', '<=', $month)
            ->whereYear('from_date', '<=', $year)
            ->whereMonth('to_date', '>=', $month)
            ->whereYear('to_date', '>=', $year)
            ->get()->first();
    }

    public function getDaysPresence($employeeId, $month, $year)
    {
        return DB::table('attendances')
            ->whereMonth('date', '=', $month)
            ->whereYear('date', '=', $year)
            ->where('employee_id', '=', $employeeId)
            ->whereIn('status', ['acm', 'acw', 'amm', 'amw'])
            ->count();
    }

    public function getDaysAbsence($employeeId, $month, $year)
    {
        return DB::table('attendances')
            ->whereMonth('date', '=', $month)
            ->whereYear('date', '=', $year)
            ->where('employee_id', '=', $employeeId)
            ->where('status', '=', 'aab')
            ->count();
    }

    public function getDaysLeaveByMonth($employeeId, $month, $year)
    {
        return DB::table('leave_applications')
            ->join('status_codes', 'leave_applications.status', '=', 'status_codes.code')
            ->whereMonth('application_date', '=', $month)
            ->whereYear('application_date', '=', $year)
            ->whereIn('status', ['lmy', 'lhy'])
            ->where('employee_id', '=', $employeeId);
    }

    public function getTotalDaysLeave($employeeId, $year)
    {
        return DB::table('leave_applications')
            ->whereYear('application_date', '=', $year)
            ->where('employee_id', '=', $employeeId)
            ->count();
    }

    public function getHoursOvertime($employeeId, $month, $year)
    {
        return DB::table('attendances')
            ->whereMonth('date', '=', $month)
            ->whereYear('date', '=', $year)
            ->where('employee_id', '=', $employeeId)
            ->sum('overtime_duration');
    }

    public function getTotalLateIn($employeeId, $month, $year)
    {
        return DB::table('attendances')
            ->whereMonth('date', '=', $month)
            ->whereYear('date', '=', $year)
            ->where('employee_id', '=', $employeeId)
            ->sum('late_duration');
    }

    public function getAttendances($employeeId, $month, $year)
    {
        $attendances = DB::table('attendances')
            ->join('status_codes', 'attendances.status', '=', 'status_codes.code')
            ->whereMonth('date', '=', $month)
            ->whereYear('date', '=', $year)
            ->where('employee_id', '=', $employeeId)
            ->where('status', '!=', 'can')
            ->get(['id', 'date', 'status_codes.short as status', 'overtime_duration as overtime', 'check_in as time_in', 'check_out as time_out', 'late_duration']);

        $attendances->each(function ($attendance) {
            $time_in = new DateTime($attendance->time_in);
            $time_out = new DateTime($attendance->time_out);

            $attendance->time_in = $time_in->format('h:i A');
            $attendance->time_out = $time_out->format('h:i A');
        });

        return $attendances;
    }

    /**
     * 
     * Get number of employee late hours by month
     * 
     * 
     **/
    public function getLateHours($employeeId, $month, $year)
    {
        return DB::table('attendances')
            ->whereMonth("date", "=", $month)
            ->whereYear("date", "=", $year)
            ->where('employee_id', '=', $employeeId)
            ->sum("late_duration");
    }

    /**
     * 
     * Get hours of overtime by month
     * 
     * 
     **/
    public function getOvertime($employeeId, $month, $year)
    {
        return DB::table('attendances')
            ->whereMonth("date", "=", $month)
            ->whereYear("date", "=", $year)
            ->where('employee_id', '=', $employeeId)
            ->sum("overtime_duration");
    }

    /**
     * 
     * Get total attend by month
     * 
     * 
     **/
    public function getAttend($employeeId, $month, $year)
    {
        // return DB::table('attendances')
        //     ->whereMonth("date", "=", $month)
        //     ->whereYear("date", "=", $year)
        //     ->where('employee_id', '=', $employeeId)
        //     ->where('working_hour', '!=', '00:00:00')
        //     ->where('working_hour', '!=', null)
        //     ->where('status', '=', null)
        //     ->count("id");

        $Attendance = DB::table('attendances')
            ->whereMonth("date", "=", $month)
            ->whereYear("date", "=", $year)
            ->where('employee_id', '=', $employeeId)
            // ->where('working_hour', '!=', '00:00:00')
            // ->where('working_hour', '!=', null)
            ->where('status', '!=', 'cls')
            ->where('status', '!=', 'can')
            ->where('status', '!=', 'aab')
            ->where('status', '!=', 'ado')
            ->where('status', '!=', 'asc')
            ->where('status', '!=', 'apm')
            ->where('status', '!=', 'arj')
            ->get();
            // ->count("id");
        $AttendanceData = [];
        
        foreach($Attendance as $attendance) {
            if ($attendance->status !== 'apd') {
                $AttendanceData[] = [
                    'id' => $attendance->id
                ];
            }
        }

        return count($AttendanceData);
    }

    /**
     * 
     * Get attend days by month
     * 
     * 
     **/
    public function getAttendDays($employeeId, $month, $year)
    {
        return DB::table('attendances')
            ->whereMonth("date", "=", $month)
            ->whereYear("date", "=", $year)
            ->where('employee_id', '=', $employeeId)
            ->where('working_hour', '!=', '00:00:00')
            ->where('working_hour', '!=', null)
            ->where('status', '=', null)
            ->get();
    }


    /**
     * 
     * Check if it's Employee is checked in ?
     * 
     * @return boolean
     * 
     **/
    public function checkEmployeeHasCheckedIn($employeeId, $date = null)
    {
        if (is_null($date)) {
            $date = Carbon::now();
        }

        return DB::table('attendances')
            ->whereDate('date', $date)
            ->where('status', "!=", "can")
            ->where('employee_id', $employeeId)
            ->exists();
    }

    /**
     * 
     * Getting Maximum time to check in before it's late.
     * 
     * @return string (time)
     * 
     **/
    public function getMaxArrivalTime($employeeId)
    {
        $workshiftID = DB::table('employees')->where('employee_id', $employeeId)->first('work_shift_id');
        $maxArrival = DB::table('work_shifts')->where('work_shift_id', $workshiftID->work_shift_id)->first('max_arrival');

        return $maxArrival->max_arrival;
    }

    /**
     * 
     * Check Employee If it's lated to check in
     * 
     * @return array
     * 
     **/
    public function checkEmployeeIsLated($employeeId, $time)
    {
        $maxArrivalTime = $this->getMaxArrivalTime($employeeId);
        $isLated = $time->gte($maxArrivalTime);
        if ($isLated) {
            $diff = $time->diffInMinutes($maxArrivalTime);
            return [
                "isLated" => $isLated,
                "lateTime" => ($diff)
            ];
        }
        return ["isLated" => $isLated];
    }

    /**
     * 
     * Check whether today is Holiday
     * 
     * @return array
     * 
     **/
    public function isTodayAreHolidays($date)
    {
        $date = Carbon::parse($date)->format("Y-m-d");
        $holidays = DB::table('national_holidays')
            ->join('manage_holidays', 'national_holidays.holiday_id', "=", "manage_holidays.holiday_id")
            ->whereDate('start_date', "<=", $date)
            ->whereDate('end_date', ">=", $date)
            ->get();

        $days = [];

        foreach ($holidays as $holiday) {
            array_push($days, $holiday->holiday_occasion);
        }

        return $days;
    }

    /**
     * 
     * Check whether today is Leave
     * 
     * @return array
     * 
     **/
    public function isTodayLeave($date, $employeeId)
    {
        $date = Carbon::parse($date)->format("Y-m-d");
        // dd($date, $employeeId);
        $leaves = DB::table('leave_applications')
            // ->join('employees', 'leave_applications.employee_id', '=', 'employees.employee_id')
            ->join('leave_types', 'leave_applications.leave_type_id', '=', 'leave_types.leave_type_id')
            // ->join('leave_periods', 'leave_applications.leave_period_id', '=', 'leave_periods.leave_period_id')
            // ->join('status_codes', 'leave_applications.status', '=', 'status_codes.code')
            ->whereNotIn('leave_applications.status', ['lhn', 'lmn', 'lpd', 'can'])
            // ->orWhere('status_codes.code', '=', 'lmy')
            ->where('leave_applications.employee_id', $employeeId)
            ->whereDate('application_from_date', "<=", $date)
            ->whereDate('application_to_date', ">=", $date)
            ->get();

        $days = [];

        if((count($leaves)) > 0){
            foreach ($leaves as $leave) {
                array_push($days, $leave->leave_type_name);
            }
        }

        return $days;
    }

    /**
     * 
     * Check whether today is Workday
     * 
     * @return array
     * 
     **/
    public function isTodayIsWorkdays($date, $branch_id, $workshift_id)
    {
        $date = Carbon::parse($date)->format('l');
        $workdays = DB::table('workdays')
            ->join('days', 'workdays.days_id', "=", "days.days_id")
            ->where('days.day_name', "=", $date)
            ->where('workdays.branch_id',  "=", $branch_id)
            ->where('workdays.work_shift_id', '=', $workshift_id)
            ->get();

        $days = [];

        foreach ($workdays as $workday) {
            array_push($days, $workday->day_name);
        }

        return $days;
    }

    /**
     * 
     * Check get attendance today
     * 
     * @return array
     * 
     **/
    public function getAttendanceToday($employeeId, $date)
    {
        // $date = Carbon::now();
        $todays = DB::table('attendances')
            ->join('status_codes', 'attendances.status', '=', 'status_codes.code')
            ->whereDate("date", $date)
            ->where('employee_id', $employeeId)
            ->first();

        unset($todays->code, $todays->long);

        return $todays;
    }

    public function getAllAttendance()
    {
        return Attendance::join('employees', 'attendances.employee_id', '=', 'employees.employee_id')
            ->join('branches', 'employees.branch_id', '=', 'branches.branch_id')
            ->join('department', 'employees.department_id', '=', 'department.department_id')
            ->join('status_codes', 'attendances.status', '=', 'status_codes.code');
    }

    public function getAllManualAttendance($branchId, $employeeId)
    {
        return Attendance::join('employees', 'attendances.employee_id', '=', 'employees.employee_id')
            ->join('branches', 'employees.branch_id', '=', 'branches.branch_id')
            ->join('department', 'employees.department_id', '=', 'department.department_id')
            ->join('status_codes', 'attendances.status', '=', 'status_codes.code')
            ->where('employees.branch_id', '=', $branchId)
            ->where('employees.employee_id', '!=', $employeeId);
    }


    public function getAttendanceById($id)
    {
        return Attendance::where('id', $id);
    }

    public function updateAttendance($id, array $data)
    {
        return Attendance::where('id', $id)->update($data);
    }

    public function getEmployeeAttendance()
    {
        return Attendance::join('employees', 'attendances.employee_id', '=', 'employees.employee_id')
            ->join('branches', 'employees.branch_id', '=', 'branches.branch_id')
            ->join('department', 'employees.department_id', '=', 'department.department_id')
            ->leftjoin('status_codes', 'attendances.status', '=', 'status_codes.code');
    }

    public function AttendanceCancel($id)
    {
        return Attendance::where('id', '=', $id)
            ->delete();
    }

    public function getEmployeePicture($id){
        $data = Employee::where('user_id', $id)->get(['gender', 'image'])->first();

        if ($data['image'] == null) {
            if ($data['gender'] == 'Male') {
                $randomNumbers = [];

                for ($i = 1; $i <= 25; $i++) {
                    if ($i % 2 != 0) {
                        $randomNumbers[] .= $i;
                    }
                }

                shuffle($randomNumbers);

                $randomNumber = array_slice($randomNumbers, 0, 1);

                return "../../../app-assets/images/portrait/small/avatar-s-$randomNumber[0].jpg";
            } else {
                $randomNumbers = [];

                for ($i = 2; $i <= 26; $i++) {
                    if ($i % 2 == 0) {
                        $randomNumbers[] .= $i;
                    }
                }

                shuffle($randomNumbers);

                $randomNumber = array_slice($randomNumbers, 0, 1);

                return "../../../app-assets/images/portrait/small/avatar-s-$randomNumber[0].jpg";
            }
        } else {
            return 'uploads/' . $data['image'];
        }
    }
}
