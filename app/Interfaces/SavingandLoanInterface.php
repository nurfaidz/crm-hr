<?php

namespace App\Interfaces;

interface SavingandLoanInterface
{
    public function getAllSavingLoan();
    public function getEmployeeSavingLoanBalance($employeeId);
    public function countSavingLoanBalance($employeeId, $year);
    public function countTotalExpenses($employeeId, $year);
    public function cancelSavingLoan($id);
    public function requestSavingandLoan($data);
    public function gettingAllSavingandLoan($branchId, $employeeId);
    public function ApproverName($id);
    public function savingLoanUpdate($id, Array $data);
    public function allSavingLoan();
    public function statistics($employeeId, $year);
}