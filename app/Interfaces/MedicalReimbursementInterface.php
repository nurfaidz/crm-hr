<?php

namespace App\Interfaces;

interface MedicalReimbursementInterface
{
    public function getAllMedicalReimbursements();
    public function statistics($employeeId, $year);
    public function countReimbursementBalance($employeeId, $year);
    public function countTotalExpenses($employeeId, $year);
    public function countGlassesReimbursement($employeeId, $year);
    public function requestMedicalReimbursement($data);
    public function cancelMedicalReimbursement($id);
    public function managerApproverName($id);
    public function humanResourcesApproverName($id);
    public function financeApproverName($id);
    public function reimbursementUpdate($id, Array $data);
    public function getAllMedicalReimbursementAppr($branchId, $employeeId);
    public function getMedicalReimbursementByid($Id);
    public function getAllMedicalReimbursementsFinance($entity, $department);
    public function getAllMedicalReimbursementsManager($entity, $department);
    public function getAllMedicalReimbursementsHumanResource($entity, $department);
    public function getEmployeeReimbursementBalance($employeeId);
    public function getReimburseHistory($id, $year);
    public function calculateEmployeeReimburseBalance($id, $year);
    public function balanceApproved($id, $year);
    public function balanceRejected($id, $year);
    public function balancePending($id, $year);
    public function getReimburseBalance($id);
}