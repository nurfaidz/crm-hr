<?php

namespace App\Interfaces;

interface ManualAttendanceInterface{
    public function getAllManualAttendanceAppr($branchId, $employeeId);
    public function getManualAttendanceById($Id);
    public function checkDateHasManualAttendance($id);
}