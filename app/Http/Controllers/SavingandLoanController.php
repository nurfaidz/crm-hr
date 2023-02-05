<?php

namespace App\Http\Controllers;

use App\Interfaces\SavingandLoanInterface;
use App\Models\Department;
use App\Models\Employee;
use App\Models\User;
use App\Models\Branch;
use App\Models\SavingandLoan;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class SavingandLoanController extends Controller
{
    private SavingandLoanInterface $savingandLoanInterface;

    public function __construct(SavingandLoanInterface $savingandLoanInterface)
    {
        $this->savingandLoanInterface = $savingandLoanInterface;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $employeeId = Auth::user()->employee->employee_id;

        return view('savingandloan.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $data = $request->all();

        $validate = Validator::make(
            $data,
            [
                // 'type' => 'required',
                'amount' => 'required',
                'attachment' => 'required',
                'note' => 'required'
            ],
        );

        if ($validate->fails()) {
            return response()->json([
                'error' => $validate->errors()->toArray()
            ]);
        } else {
            $attachment = [];
            $employeeId = Auth::user()->employee->employee_id;
            $date = Carbon::now();

            if ($request->hasFile('attachment')) {
                if ($files = $request->file('attachment')) {
                    foreach ($files as $file) {
                        $name = Str::random('20') . '-' . $file->getClientOriginalName();
                        $file->move('uploads/saving-and-loan/', $name);
                        $attachment[] = $name;
                    }
                }
            }

            $data['attachment'] = 'saving-and-loan/' . implode('|', $attachment);
            $data['employee_id'] = $employeeId;
            $data['balance'] = $this->savingandLoanInterface->countSavingLoanBalance($employeeId,$date->year);
            $data['expenses'] = $this->savingandLoanInterface->countTotalExpenses($employeeId, $date->year);
            $data['amount'] = str_replace('Rp', '', $data['amount']);
            $data['amount'] = str_replace('.', '', $data['amount']);
            $bungabaru = $data['amount'] * 0.003;
            $total = $bungabaru + $data['amount'];
            $data['amount'] = $total;
            // dd($data['amount']);
            $data['status'] = 'shp';


            return $this->savingandLoanInterface->requestSavingandLoan(array_merge($data, ['date' => $date]));

            return response()->json([
                'error' => false,
                'message' => 'Successfully Requested Saving Loan'
            ]);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\SavingandLoan  $savingandLoan
     * @return \Illuminate\Http\Response
     */
    public function show(SavingandLoan $savingandLoan)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\SavingandLoan  $savingandLoan
     * @return \Illuminate\Http\Response
     */
    public function edit(SavingandLoan $savingandLoan)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\SavingandLoan  $savingandLoan
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, SavingandLoan $savingandLoan)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\SavingandLoan  $savingandLoan
     * @return \Illuminate\Http\Response
     */
    public function destroy(SavingandLoan $savingandLoan)
    {
        //
    }

    public function data()
    {
        $pendingStatusArray = [];
        $pendingStatus = $this->savingandLoanInterface->allSavingLoan()
            ->where('employee_id', Auth::user()->employee->employee_id)
            ->where('code', 'shp')
            ->sortByDesc('date');

        // dd($this->savingandLoanInterface->allSavingLoan());
        // dd($pendingStatus);

        foreach ($pendingStatus as $pending){

            $savingLoanDate = explode('-', $pending->date);
    
            $pendingStatusArray[] = [
                'cooperative_id' => $pending->cooperative_id,
                'type' => $pending->type,
                'date' => $savingLoanDate[2] . '/' . $savingLoanDate[1] . '/' . $savingLoanDate[0],
                'total_expenses' => 'Rp' . number_format($pending->amount, 0, ',', '.'),
                'status' => $pending->short
            ];
        }

        // dd($pendingStatusArray);

        return response()->json([
            'data' => $pendingStatusArray,
            'error' => false
        ]); 
    }

    public function list($status)
    {
        $savingLoan = $this->savingandLoanInterface->allSavingLoan()
            ->where('employees.employee_id', Auth::user()->employee->employee_id)
            // ->where('status_codes.code', ['shr', 'shy', 'shc'] )
            ->where('code', '!=', 'shp')
            ->sortByDesc('date');

            if($status != 0)
            {
                $savingLoan->where('status', $status);
            }
            // dd($savingLoan);

        return datatables()->of($savingLoan)
            ->addIndexColumn()
            ->addColumn('action', function ($data) {
                return '
                    <a href="' . url('saving-loan/details') . '/' . $data->cooperative_id . '" id="pending-status-details" class="btn btn-outline-info round pending-status-details">Details</a>
                ';
            })
            ->rawColumns(['action'])
            ->make(true);
    }

    public function cancel($id)
    {
        try {
            $this->savingandLoanInterface->cancelSavingLoan($id);
        } catch (\Throwable $th) {
            //throw $th;
        }
    }

    public function statistic($year)
    {
        $employeeId = Auth::user()->employee->employee_id;
        $statistics = $this->savingandLoanInterface->statistics($employeeId, $year);

        return response()->json([
            'data' => $statistics,
            'error' => false
        ]);
    }

    public function viewLap()
    {
        return view('savingandloan.data');
    }

    public function laporan($status)
    {
        $savingLoan = $this->savingandLoanInterface->allSavingLoan()
            ->where('employees.employee_id', Auth::user()->employee->employee_id)
            ->where('code', 'shy')
            ->sortByDesc('date');

            if($status != 0)
            {
                $savingLoan->where('status', $status);
            }

        return datatables()->of($savingLoan)
            ->addIndexColumn()
            ->make(true);
    }

}
