<?php

namespace App\Interfaces;

interface SortingInterface
{
    public function calculateAttendance();
    public function groupAttendance();
    public function groupLeave();
    public function getAllCountAttendance();
    public function getAttendanceBalanceInformation($year);
    public function countAttendanceEmployee();
    public function countWorkTime($year);
    public function getEmployeeWorker();
}