<?php

namespace App\Interfaces;

interface ApprovalInterface
{
    public function leaveApprovalStatistics($user_id);
    public function attendanceApprovalStatistics($user_id);
    public function getAllManualAttendanceEmployee();
}