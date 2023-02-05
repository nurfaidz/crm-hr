<?php

namespace App\Interfaces;

interface AttendanceInterface{
    public function statistics($employeeId, $month, $year);
    public function getLeavePeriod($month, $year);
    public function getDaysPresence($employeeId, $month, $year);
    public function getDaysAbsence($employeeId, $month, $year);
    public function getDaysLeaveByMonth($employeeId, $month, $year);
    public function getTotalDaysLeave($employeeId, $year);
    public function getHoursOvertime($employeeId, $month, $year);
    public function getTotalLateIn($employeeId, $month, $year);
    public function getAttendances($employeeId, $month, $year);
    public function getLateHours($employeeId, $month, $year);
    public function getOvertime($employeeId, $month, $year);
    public function getAttend($employeeId, $month, $year);
    public function getAttendDays($employeeId, $month, $year);
    public function checkEmployeeHasCheckedIn($employeeId, $date = null);
    public function getMaxArrivalTime($employeeId);
    public function checkEmployeeIsLated($employeeId, $time);
    public function isTodayAreHolidays($date);
    public function isTodayLeave($date, $employeeId);
    public function isTodayIsWorkdays($date, $branch_id, $workshift_id);
    public function getAttendanceToday($employeeId, $date);
    public function getAllAttendance();
    public function getAllManualAttendance($branchId, $employeeId);
    public function getAttendanceById($id);
    public function updateAttendance($id, array $data);
    public function getEmployeeAttendance();
    public function AttendanceCancel($id);
    public function getEmployeePicture($id);
}