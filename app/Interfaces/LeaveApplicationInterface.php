<?php

namespace App\Interfaces;

interface LeaveApplicationInterface
{
    public function getAllLeaveApplications();
    public function getAllLeave($branchId, $employeeId);
    public function getLeaveApplicationByid($id);
    public function getAllHolidays();
    public function getLeaveApprovalAnnualLeave($leaveApplicationId, $leavePeriodId);
    public function getLeaveApprovalBigLeave($leaveApplicationId, $leavePeriodId);
    public function getLeaveApprovalMaternityLeave($leaveApplicationId, $leavePeriodId);
    public function statistics($employeeId, $year);
    public function countLeaveBalance($employeeId, $year);
    public function countEachLeaveBalance($employeeId, $year);
    public function countCanceledLeave($employeeId, $year);
    public function countApprovedLeave($employeeId, $year);
    public function countRejectedLeave($employeeId, $year);
    public function createLeaveApplication($data);
    public function updateLeaveApplication($id, Array $data);
    public function cancelLeaveApplication($id);
    public function rejectLeaveApplication($id, $reason);
    public function approveLeaveApplication($id);
    public function calculateEmployeeLeaveBalance($leaveTypeId, $employeeId, $leavePeriodId);
    public function getUserLeaveHistory($id, $year);
    public function countLeaveDaysAnually($id, $year, $leaveTypeId);
    public function countRejectLeaveDaysAnually($id, $year, $leaveTypeId);
    public function getAllLeaveApplicationListsAppr($branchId, $employeeId);
    public function getLeaveApplicationsById($Id);
    public function cancelLeave($id);
    public function countAnnualApprAndPend($leaveTypeId, $employeeId, $leavePeriodId);
    public function getAllCountExceptAnnualLeave();
    public function getLeaveBalanceInformation($year);
}
