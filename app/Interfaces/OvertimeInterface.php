<?php

namespace App\Interfaces;

interface OvertimeInterface{
    public function getOvertimeHours($employeeId, $month, $year);
    public function getOvertimeById($overtimeId);
    public function getOvertimeEmployeeById($overtimeId);
    public function getOvertimeToday($employeeId);
    public function getOvertimeHoursTest();
    public function getOvertimeHoursToday($overtimeId);
    public function getOvertimeAll($employeeId, $month, $year);
    public function createOvertime(array $arr);
    public function getOvertimePending($employeeId);
    public function getOvertimeByIdForNotes($overtimeId);
    public function cancelOvertime($overtimeId);
    public function calculateRemainingOvertimeonWeeks($employeeId, $totalQouta, $date);
    public function getOvertimeAllPending($branchId, $employeeId);
    public function getOvertimeAllFilter($date);
    public function autoOvertimeCanceling();
    public function getOvertimeDay($employeeId, $date);
    public function getOvertimeListAppr($employeeId, $branchId);
    public function updateOvertime($overtimeId, Array $data);
    public function createOvertimeEloquent(Array $arr);
}