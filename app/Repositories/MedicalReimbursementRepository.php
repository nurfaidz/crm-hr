<?php

namespace App\Repositories;

use App\Interfaces\MedicalReimbursementInterface;
use App\Models\Employee;
use App\Models\EmployeeBalance;
use App\Models\MedicalReimbursement;
use Carbon\Carbon;
use Exception;
use Illuminate\Support\Facades\Auth;

class MedicalReimbursementRepository implements MedicalReimbursementInterface
{
    public function getAllMedicalReimbursements()
    {
        return MedicalReimbursement::join('employees', 'medical_reimbursements.employee_id', '=', 'employees.employee_id')->join('branches', 'employees.branch_id', '=', 'branches.branch_id')->join('department', 'employees.department_id', '=', 'department.department_id')->join('job_positions', 'employees.job_position_id', '=', 'job_positions.job_position_id')->join('status_codes', 'medical_reimbursements.status', '=', 'status_codes.code')->get();
    }

    public function statistics($employeeId, $year)
    {
        try {
            $reimbursementBalance = $this->countReimbursementBalance($employeeId, $year);
            $totalExpenses = $this->countTotalExpenses($employeeId, $year);
            $numberOfApproved = $this->countApprovedReimbursement($employeeId, $year);
            $numberOfRejected = $this->countRejectedReimbursement($employeeId, $year);

            return [
                'reimbursementBalance' => $reimbursementBalance,
                'totalExpenses' => $totalExpenses,
                'numberOfApproved' => $numberOfApproved,
                'numberOfRejected' => $numberOfRejected
            ];
        } catch (Exception $error) {
            return $error->getMessage();
        }
    }
    
    public function countReimbursementBalance($employeeId, $year)
    {

        $balances = MedicalReimbursement::join('employees', 'medical_reimbursements.employee_id', '=', 'employees.employee_id')->join('branches', 'employees.branch_id', '=', 'branches.branch_id')->join('department', 'employees.department_id', '=', 'department.department_id')->join('job_positions', 'employees.job_position_id', '=', 'job_positions.job_position_id')->join('status_codes', 'medical_reimbursements.status', '=', 'status_codes.code')->where('medical_reimbursements.employee_id', '=', $employeeId)->whereYear('reimbursement_date', '=', $year)->where('short', '=', 'Approved')->get();
        $amount = 0;

        $employee_balance = $this->getEmployeeReimbursementBalance($employeeId);

        foreach ($balances as $balance) {
            $amount += $balance['amount'];
        }

        return $employee_balance['total_balance'] - $amount;
    }

    public function countTotalExpenses($employeeId, $year)
    {
        $balances = MedicalReimbursement::join('employees', 'medical_reimbursements.employee_id', '=', 'employees.employee_id')->join('branches', 'employees.branch_id', '=', 'branches.branch_id')->join('department', 'employees.department_id', '=', 'department.department_id')->join('job_positions', 'employees.job_position_id', '=', 'job_positions.job_position_id')->join('status_codes', 'medical_reimbursements.status', '=', 'status_codes.code')->where('medical_reimbursements.employee_id', '=', $employeeId)->whereYear('reimbursement_date', '=', $year)->where('short', '=', 'Approved')->get();
        $amount = 0;

        foreach ($balances as $balance) {
            $amount += $balance['amount'];
        }

        return $amount;
    }

    public function countGlassesReimbursement($employeeId, $year)
    {
        return MedicalReimbursement::join('status_codes', 'medical_reimbursements.status', '=', 'status_codes.code')
            ->where('employee_id', '=', $employeeId)
            ->whereYear('reimbursement_date', '=', $year)
            ->where('outpatient_type', '=', 2)
            ->where(function($query) {
                $query->where('short', '=', 'Pending')->orWhere('short', '=', 'Approved');
            })
            ->count();
    }

    public function countApprovedReimbursement($employeeId, $year)
    {
        return MedicalReimbursement::where('employee_id', '=', $employeeId)
            ->whereYear('reimbursement_date', '=', $year)
            ->where(function($query) {
                $query->where('status', '=', 'rmy')->orWhere('status', '=', 'rhy');
            })
            ->count();
    }

    public function countRejectedReimbursement($employeeId, $year)
    {
        return MedicalReimbursement::where('employee_id', '=', $employeeId)
            ->whereYear('reimbursement_date', '=', $year)
            ->where(function($query) {
                $query->where('status', '=', 'rmr')->orWhere('status', '=', 'rhr');
            })
            ->count();
    }

    public function requestMedicalReimbursement($data)
    {
        return MedicalReimbursement::create($data);
    }

    public function cancelMedicalReimbursement($id)
    {
        return MedicalReimbursement::where('medical_reimbursement_id', '=', $id)->update(['status' => 'can', 'cancel_by' => Auth::user()->employee->employee_id, 'cancel_date' => Carbon::now()]);
    }

    public function managerApproverName($id)
    {
        $medicalReimbursement = MedicalReimbursement::where('medical_reimbursement_id', '=', $id)->first();

        if ($medicalReimbursement['approve_by_manager'] != null) {
            $employee = Employee::where('employee_id', '=', $medicalReimbursement['approve_by_manager'])->first();

            return $employee->first_name . ' ' . $employee->last_name;
        } else {
            return '-';
        }
    }

    public function humanResourcesApproverName($id)
    {
        $medicalReimbursement = MedicalReimbursement::where('medical_reimbursement_id', '=', $id)->first();
        
        if ($medicalReimbursement['approve_by_human_resources'] != null) {
            $employee = Employee::where('employee_id', '=', $medicalReimbursement['approve_by_human_resources'])->first();

            return $employee->first_name . ' ' . $employee->last_name;
        } else {
            return '-';
        }
    }

    public function financeApproverName($id)
    {
        $medicalReimbursement = MedicalReimbursement::where('medical_reimbursement_id', '=', $id)->first();
        
        if ($medicalReimbursement['approve_by_finance'] != null) {
            $employee = Employee::where('employee_id', '=', $medicalReimbursement['approve_by_finance'])->first();

            return $employee->first_name . ' ' . $employee->last_name;
        } else {
            return '-';
        }
    }

    public function reimbursementUpdate($id, Array $data) 
    {
        return MedicalReimbursement::where('medical_reimbursement_id', '=', $id)
                    ->update($data);
    }

    /**
     * 
     * Get all medical reimbursement approved
     * 
     * @return array
     * 
     **/
    public function getAllMedicalReimbursementAppr($branchId, $employeeId)
    {
        $medicalReimbursement = MedicalReimbursement::join('employees', 'medical_reimbursements.employee_id', '=', 'employees.employee_id')
            ->join('branches', 'employees.branch_id', '=', 'branches.branch_id')
            ->join('department', 'employees.department_id', '=', 'department.department_id')
            ->join('job_positions', 'employees.job_position_id', '=', 'job_positions.job_position_id')
            ->join('users', 'employees.user_id', '=', 'users.id')
            ->join('status_codes', 'medical_reimbursements.status', '=', 'status_codes.code')
            ->where('employees.branch_id', '=', $branchId)
            ->where('employees.employee_id', '!=', $employeeId)
            ->orderBy('medical_reimbursements.reimbursement_date','desc');

        return $medicalReimbursement;
    }

    /**
     * 
     * Get medical reimbursement details
     * 
     * @return array
     * 
     **/
    public function getMedicalReimbursementById($Id)
    {
        $medicalReimbursement = MedicalReimbursement::join('employees', 'medical_reimbursements.employee_id', '=', 'employees.employee_id')
            ->join('branches', 'employees.branch_id', '=', 'branches.branch_id')
            ->join('department', 'employees.department_id', '=', 'department.department_id')
            ->join('job_positions', 'employees.job_position_id', '=', 'job_positions.job_position_id')
            ->join('status_codes', 'medical_reimbursements.status', '=', 'status_codes.code')
            ->where("medical_reimbursement_id", "=", $Id)
            ->first([
                'medical_reimbursements.*',
                'employees.user_id',
                'employees.employee_id',
                'employees.first_name',
                'employees.last_name',
                'employees.image',
                'branches.branch_name',
                'department.department_name',
                'job_positions.job_position',
                'status_codes.short'
            ]);

        return $medicalReimbursement;
    }

    public function getAllMedicalReimbursementsFinance($entity, $department)
    {
        return MedicalReimbursement::join('employees', 'medical_reimbursements.employee_id', '=', 'employees.employee_id')
            ->join('branches', 'employees.branch_id', '=', 'branches.branch_id')
            ->join('department', 'employees.department_id', '=', 'department.department_id')
            ->join('job_positions', 'employees.job_position_id', '=', 'job_positions.job_position_id')
            ->join('status_codes', 'medical_reimbursements.status', '=', 'status_codes.code')
            ->where('employees.branch_id', '=', $entity)
            ->where('employees.department_id', '=', $department)
            ->where('medical_reimbursements.status', '=', 'rhy')
            ->get();
    }

    public function getAllMedicalReimbursementsManager($entity, $department)
    {
        return MedicalReimbursement::join('employees', 'medical_reimbursements.employee_id', '=', 'employees.employee_id')
            ->join('branches', 'employees.branch_id', '=', 'branches.branch_id')
            ->join('department', 'employees.department_id', '=', 'department.department_id')
            ->join('job_positions', 'employees.job_position_id', '=', 'job_positions.job_position_id')
            ->join('status_codes', 'medical_reimbursements.status', '=', 'status_codes.code')
            ->where('branches.branch_id', '=', $entity)
            ->where('department.department_id', '=', $department)
            ->where('medical_reimbursements.status', '=', 'rpd')
            ->get();
    }

    public function getAllMedicalReimbursementsHumanResource($entity, $department)
    {
        return MedicalReimbursement::join('employees', 'medical_reimbursements.employee_id', '=', 'employees.employee_id')
            ->join('branches', 'employees.branch_id', '=', 'branches.branch_id')
            ->join('department', 'employees.department_id', '=', 'department.department_id')
            ->join('job_positions', 'employees.job_position_id', '=', 'job_positions.job_position_id')
            ->join('status_codes', 'medical_reimbursements.status', '=', 'status_codes.code')
            ->where('employees.branch_id', '=', $entity)
            ->where('employees.department_id', '=', $department)
            ->where('medical_reimbursements.status', '=', 'rmy')
            ->get();
    }

    public function getEmployeeReimbursementBalance($employeeId) {
        return EmployeeBalance::where('employee_id', '=', $employeeId)
                            ->first('total_balance');
    }

    public function getReimburseHistory($id, $year)
    {
        $reimburse = MedicalReimbursement::join('employees', 'medical_reimbursements.employee_id', '=', 'employees.employee_id')
            ->join('status_codes', 'medical_reimbursements.status', '=', 'status_codes.code')
            ->whereYear('reimbursement_date', $year)
            ->where('medical_reimbursements.employee_id', '=', $id);

        return $reimburse;
    }

    public function calculateEmployeeReimburseBalance($id, $year)
    {
        $app = $this->balanceApproved($id, $year);
        $pen = $this->balancePending($id, $year);

        $employee_balance = $this->getEmployeeReimbursementBalance($id);

        return $employee_balance['total_balance'] - $app - $pen;
    }

    public function balanceApproved($id, $year)
    {
        $balanceApproved = MedicalReimbursement::join('employees', 'medical_reimbursements.employee_id', '=', 'employees.employee_id')
        ->join('status_codes', 'medical_reimbursements.status', '=', 'status_codes.code')
        ->where('medical_reimbursements.employee_id', '=', $id)
        ->whereYear('reimbursement_date', '=', $year)
        ->where('short', '=', 'Approved')
        ->get();
        $amount = 0;

        foreach ($balanceApproved as $balance) {
            $amount += $balance['amount'];
        }

        return $amount;
    }

        public function balanceRejected($id, $year)
    {
        $balanceRejected = MedicalReimbursement::join('employees', 'medical_reimbursements.employee_id', '=', 'employees.employee_id')
        ->join('status_codes', 'medical_reimbursements.status', '=', 'status_codes.code')
        ->where('medical_reimbursements.employee_id', '=', $id)
        ->whereYear('reimbursement_date', '=', $year)
        ->where('short', '=', 'Rejected')
        ->get();
        $amount = 0;

        foreach ($balanceRejected as $balance) {
            $amount += $balance['amount'];
        }
        return $amount;
    }

    public function balancePending($id, $year)
    {
        $baPending = MedicalReimbursement::join('employees', 'medical_reimbursements.employee_id', '=', 'employees.employee_id')
        ->join('status_codes', 'medical_reimbursements.status', '=', 'status_codes.code')
        ->where('medical_reimbursements.employee_id', '=', $id)
        ->whereYear('reimbursement_date', '=', $year)
        ->where('short', '=', 'Pending')
        ->get();
        $amount = 0;

        foreach ($baPending as $balance) {
            $amount += $balance['amount'];
        }
        return $amount;
    }

    public function getReimburseBalance($id)
    {
        $reimbalance = MedicalReimbursement::join('employees', 'medical_reimbursements.employee_id', '=', 'employees.employee_id')
        ->join('employee_balances', 'employees.employee_id', '=', 'employee_balances.employee_id')
        ->where('medical_reimbursements.employee_id', '=', $id);
        
        return $reimbalance;
    }
}