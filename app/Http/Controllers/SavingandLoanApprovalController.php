<?php

namespace App\Http\Controllers;

use App\Interfaces\SavingandLoanInterface;
use App\Interfaces\EmployeeInterface;
use Yajra\DataTables\Facades\DataTables;
use App\Models\SavingandLoan;
use App\Models\EmployeeBalance;
use App\Models\Employee;
use App\Models\User;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Http;

class SavingandLoanApprovalController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    private SavingandLoanInterface $savingandLoanInterface;
    private EmployeeInterface $employeeInterface;

    public function __construct(
        SavingandLoanInterface $savingandLoanInterface,
        EmployeeInterface $employeeInterface
    )
    {
        $this->savingandLoanInterface = $savingandLoanInterface;
        $this->employeeInterface = $employeeInterface;
    }

    public function get_savingand_loan()
    {
        $user = Auth::user()->employee->employee_id;
        $employee = $this->employeeInterface->getEmployeeById($user)
            ->join('department', 'employees.department_id', '=', 'department.department_id')
            ->first();

        $savingLoan = $this->savingandLoanInterface->gettingAllSavingandLoan($employee->branch_id, $employee->employee_id)
                    ->where('department.code', '=', $employee->code)
                    ->where('status_codes.code', '=', 'shp')
                    ->where('status_codes.code', '!=', 'shc')
                    ->get();

        return DataTables::of($savingLoan)
            ->addIndexColumn()
            ->addColumn('employee_image', function ($row) {
                return $this->employeeInterface->getEmployeePicture($row->user_id);
            })
            ->addColumn('transaction_date', function ($row) {
                return  date('d/m/Y', strtotime($row->date));
            })
            ->addColumn('action', function ($row) {
                    return '<a href="approval/saving-and-loan/' . $row->cooperative_id . '/details" id="pending-status-cancel" class="btn btn-outline-info round pending-status-cancel d-flex justify-content-center align-items-center">Details</a>';
 
            })
            ->addColumn('status', function ($row) {
                return '<div class="badge badge-pill badge-light-warning">Pending</div>';
            })
            ->rawColumns(['status', 'action'])
            ->toJson(true);
    }

    public function details($id)
    {
        $savingLoan = $this->savingandLoanInterface->getAllSavingLoan()
          ->where ('cooperative_id', $id)
          ->first();

        $employeeId = $savingLoan['employee_id'];
        $year = explode('-', $savingLoan['date']);
        $balance = $this->savingandLoanInterface->countSavingLoanBalance($employeeId, $year[0]);
        $expenses = $this->savingandLoanInterface->countTotalExpenses($employeeId, $year[0]);

        $approved       = $this->savingandLoanInterface->ApproverName($id);

        return view('savingandloan.details', compact(
            'savingLoan',
            'balance',
            'expenses',
            'approved',
        ));
    }

    public function Approve($id)
    {
        try {
            $this->savingandLoanInterface->savingLoanUpdate($id, [
                'status'      => 'shy',
                'status_by'   => Auth::user()->employee->employee_id,
                'status_date' => Carbon::now()
            ]);

            return response()->json([
                'error' => false,
                'message' => 'Successfully Approved'
            ]);
        } catch (Exception $e) {
            return response()->json([
                'error' => true,
                'message' => $e->getMessage()
            ]);
        }
    }

    public function reject(Request $request, $id)
    {
        $employeeId   = Auth::user()->employee->employee_id;
        $requestOnly  = $request->only('reject_reason');
        $rules        = ['reject_reason' => 'required'];

        $validatedData = Validator::make($requestOnly, $rules);
        if ($validatedData->fails()) {
            return response()->json([
                'error' => $validatedData->errors()->toArray()
            ]);
        }

        try {
            $this->savingandLoanInterface->savingLoanUpdate($id, [
                'status'        => 'shr',
                'status_by'     => $employeeId,
                'status_date'   => Carbon::now(),
                'reject_reason' => $requestOnly
            ]);

            return response()->json([
                'error'   => false,
                'message' => 'Successfully Rejected'
            ]);
        } catch (Exception $e) {
            return response()->json([
                'error'   => true,
                'message' => $e->getMessage()
            ]);
        }
    }

}
