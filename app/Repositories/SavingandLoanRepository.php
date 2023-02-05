<?php

namespace App\Repositories;

use App\Interfaces\SavingandLoanInterface;
use App\Models\Employee;
use App\Models\EmployeeBalance;
use App\Models\SavingandLoan;
use Carbon\Carbon;
use Exception;
use Illuminate\Support\Facades\Auth;

class SavingandLoanRepository implements SavingandLoanInterface
{
    public function getAllSavingLoan()
    {
        $savingLoan = SavingandLoan::join('employees', 'savingand_loans.employee_id', '=', 'employees.employee_id')
        ->join('branches', 'employees.branch_id', '=', 'branches.branch_id')
        ->join('department', 'employees.department_id', '=', 'department.department_id')
        ->join('job_positions', 'employees.job_position_id', '=', 'job_positions.job_position_id')
        ->join('status_codes', 'savingand_loans.status', '=', 'status_codes.code');

        return $savingLoan;
    }

    public function allSavingLoan()
    {
        $savingLoan = SavingandLoan::join('employees', 'savingand_loans.employee_id', '=', 'employees.employee_id')
        ->join('branches', 'employees.branch_id', '=', 'branches.branch_id')
        ->join('department', 'employees.department_id', '=', 'department.department_id')
        ->join('job_positions', 'employees.job_position_id', '=', 'job_positions.job_position_id')
        ->join('status_codes', 'savingand_loans.status', '=', 'status_codes.code')
        ->get();

        return $savingLoan;
    }

    public function gettingAllSavingandLoan($branchId, $employeeId)
    {
        $savingLoan = SavingandLoan::join('employees', 'savingand_loans.employee_id', '=', 'employees.employee_id')
            ->join('branches', 'employees.branch_id', '=', 'branches.branch_id')
            ->join('department', 'employees.department_id', '=', 'department.department_id')
            ->join('job_positions', 'employees.job_position_id', '=', 'job_positions.job_position_id')
            ->join('status_codes', 'savingand_loans.status', '=', 'status_codes.code')
            ->where('employees.branch_id', '=', $branchId)
            ->where('employees.employee_id', '!=', $employeeId);

        return $savingLoan;
    }

    public function getEmployeeSavingLoanBalance($employeeId) {
        return EmployeeBalance::where('employee_id', '=', $employeeId)
                            ->first('total_balance');
    }

    public function countSavingLoanBalance($employeeId, $year)
    {
       
        $balances = SavingandLoan::join('employees', 'savingand_loans.employee_id', '=', 'employees.employee_id')
        ->join('branches', 'employees.branch_id', '=', 'branches.branch_id')
        ->join('department', 'employees.department_id', '=', 'department.department_id')
        ->join('job_positions', 'employees.job_position_id', '=', 'job_positions.job_position_id')
        ->join('status_codes', 'savingand_loans.status', '=', 'status_codes.code')
        ->where('savingand_loans.employee_id', '=', $employeeId)
        ->whereYear('date', '=', $year)->where('short', '=', 'Approved')->get();
        $amount = 0;

        $employee_balance = $this->getEmployeeSavingLoanBalance($employeeId);

        foreach ($balances as $balance) {
            $amount += $balance['amount'];
        }

        return $employee_balance['total_balance'] - $amount;
    }

    public function countTotalExpenses($employeeId, $year)
    {

        $balances = SavingandLoan::join('employees', 'savingand_loans.employee_id', '=', 'employees.employee_id')
        ->join('branches', 'employees.branch_id', '=', 'branches.branch_id')
        ->join('department', 'employees.department_id', '=', 'department.department_id')
        ->join('job_positions', 'employees.job_position_id', '=', 'job_positions.job_position_id')
        ->join('status_codes', 'savingand_loans.status', '=', 'status_codes.code')
        ->where('savingand_loans.employee_id', '=', $employeeId)->whereYear('date', '=', $year)->where('status_codes.short', '=', 'Approved')->get();
        $amount = 0;

        foreach ($balances as $balance) {
            $amount += $balance['amount'];
        }

        return $amount;
    }

    public function cancelSavingLoan($id)
    {
        return SavingandLoan::where('cooperative_id', '=', $id)->update(['status' => 'can', 'cancel_by' => Auth::user()->employee->employee_id, 'cancel_date' => Carbon::now()]);
    }

    public function requestSavingandLoan($data)
    {
        return SavingandLoan::create($data);
    }

    public function ApproverName($id)
    {
        $savingLoan = SavingandLoan::where('cooperative_id', '=', $id)->first();

        if ($savingLoan['status_by'] != null) {
            $employee = Employee::where('employee_id', '=', $savingLoan['status_by'])->first();

            return $employee->first_name . ' ' . $employee->last_name;
        } else {
            return '-';
        }
    }

    public function savingLoanUpdate($id, Array $data) 
    {
        return SavingandLoan::where('cooperative_id', '=', $id)
                    ->update($data);
    }

    public function statistics($employeeId, $year)
    {
        try {
            $savingLoanBalance = $this->countSavingLoanBalance($employeeId, $year);
            $totalExpenses = $this->countTotalExpenses($employeeId, $year);

            return [
                'savingLoanBalance' => $savingLoanBalance,
                'totalExpenses' => $totalExpenses,
            ];
        } catch (Exception $error) {
            return $error->getMessage();
        }
    }
}