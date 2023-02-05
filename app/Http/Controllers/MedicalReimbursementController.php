<?php

namespace App\Http\Controllers;

use App\Interfaces\MedicalReimbursementInterface;
use App\Models\Department;
use App\Models\Employee;
use App\Models\MedicalReimbursement;
use App\Models\Notification;
use App\Models\User;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class MedicalReimbursementController extends Controller
{
    private MedicalReimbursementInterface $medicalReimbursementInterface;

    public function __construct(MedicalReimbursementInterface $medicalReimbursementInterface)
    {
        $this->middleware('permission:self_medical_reimbursement.list', ['only' => ['index', 'data', 'list', 'download']]);
        $this->middleware('permission:self_medical_reimbursement.create', ['only' => 'store']);
        $this->middleware('permission:self_medical_reimbursement.cancel', ['only' => 'cancel']);

        $this->medicalReimbursementInterface = $medicalReimbursementInterface;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $employeeId = Auth::user()->employee->employee_id;
        $year = Carbon::now()->year;
        $countGlassesReimbursement = $this->medicalReimbursementInterface->countGlassesReimbursement($employeeId, $year);

        return view('medical-reimbursement.index', compact('countGlassesReimbursement'));
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
                'category' => 'required',
                'outpatient_type' => 'required_if:category,==,1',
                'amount' => 'required',
                'attachment' => 'required',
                'notes' => 'required'
            ],
            [
                'outpatient_type.required_if' => 'The outpatient type field is required.'
            ]
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
                        $file->move('uploads/medical-reimbursement/', $name);
                        $attachment[] = $name;
                    }
                }
            }

            $data['attachment'] = 'medical-reimbursement/' . implode('|', $attachment);
            $data['employee_id'] = $employeeId;
            if ($request->has('outpatient-type')) {
                $data['outpatient_type'] = $data['outpatient-type'];
            }
            $data['balance'] = $this->medicalReimbursementInterface->countReimbursementBalance($employeeId, $date->year);
            $data['expenses'] = $this->medicalReimbursementInterface->countTotalExpenses($employeeId, $date->year);
            $data['amount'] = str_replace('Rp', '', $data['amount']);
            $data['amount'] = str_replace('.', '', $data['amount']);
            $data['status'] = 'rpd';

            $reimbursementNotif = $this->medicalReimbursementInterface->requestMedicalReimbursement(array_merge($data, ['reimbursement_date' => $date]));

            $employee = Employee::where('user_id', Auth::user()->id)->first();
            $manager_id = Department::where('department_id', $employee->department_id)->first()->manager;
            $employeeManager = Employee::where('employee_id', $manager_id)->first();
            $userManager = User::find($employeeManager->user_id);

            $notification = Http::withHeaders([
                'Content-Type' => 'application/json',
                'Authorization' => 'Basic ' . env('ONESIGNAL_REST_API_KEY')
            ])->post(env('ONESIGNAL_URL'), [
                'app_id' => env('ONESIGNAL_APP_ID'),
                'android_channel_id' => env('ONESIGNAL_ANDROID_CHANNEL_ID'),
                'small_icon' => "ic_stat_onesignal_default",
                'include_external_user_ids' => [$userManager->email],
                'channel_for_external_user_ids' => "push",
                'data' => ["detail_id" => $reimbursementNotif->id],
                'headings' => ["en" => "Reimbursement"],
                'contents' => ["en" => $employee->first_name . " " . $employee->last_name . " request approval for reimbursement"]
            ]);

            $createNotifications = [
                "employee_id" => $employeeManager->employee_id,
                "title" => "Medical Reimbursement",
                "notif_type" => 'Reimbursement',
                "notif_status" => 'Pending',
                "message" => $employee->first_name . " " . $employee->last_name . " request approval for reimbursement",
                "send_time" => Carbon::now(),
                "detail_id" => $reimbursementNotif->id,
                "is_approval" => true,
            ];

            Notification::create($createNotifications);

            return response()->json([
                'error' => false,
                'message' => 'Successfully Requested Medical Reimbursement'
            ]);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    public function statistic($year)
    {
        $employeeId = Auth::user()->employee->employee_id;
        $statistics = $this->medicalReimbursementInterface->statistics($employeeId, $year);

        return response()->json([
            'data' => $statistics,
            'error' => false
        ]);
    }

    public function data()
    {
        $pendingStatusArray = [];
        $pendingStatuses = $this->medicalReimbursementInterface->getAllMedicalReimbursements()->where('employee_id', Auth::user()->employee->employee_id)->where('code', 'rpd')->sortByDesc('reimbursement_date');

        foreach ($pendingStatuses as $pendingStatus) {
            if ($pendingStatus->category == 0) {
                $reimbursementType = 'Inpatient';
            } else {
                if ($pendingStatus->outpatient_type == 0) {
                    $reimbursementType = 'Outpatient - Laboratory Examinations';
                } else if ($pendingStatus->outpatient_type == 1) {
                    $reimbursementType = 'Outpatient - Doctor Consultation';
                } else {
                    $reimbursementType = 'Outpatient - Glasses Replacement';
                }
            }

            $reimbursementDate = explode('-', $pendingStatus->reimbursement_date);

            $pendingStatusArray[] = [
                'medical_reimbursement_id' => $pendingStatus->medical_reimbursement_id,
                'reimbursement_type' => $reimbursementType,
                'reimbursement_date' => $reimbursementDate[2] . '/' . $reimbursementDate[1] . '/' . $reimbursementDate[0],
                'total_expenses' => 'Rp' . number_format($pendingStatus->amount, 0, ',', '.'),
                'status' => $pendingStatus->short
            ];
        }

        return response()->json([
            'data' => $pendingStatusArray,
            'error' => false
        ]);
    }

    public function list($type, $status)
    {
        $medicalReimbursementHistory = $this->medicalReimbursementInterface->getAllMedicalReimbursements()->where('employee_id', Auth::user()->employee->employee_id)->where('code', '!=', 'rpd')->sortByDesc('reimbursement_date');

        if ($type != 0) {
            $medicalReimbursementHistory->where('category', $type);
        }

        if ($status != 0) {
            $medicalReimbursementHistory->where('short', $status);
        }

        return datatables()->of($medicalReimbursementHistory)
            ->addIndexColumn()
            ->addColumn('action', function ($data) {
                return '
                    <a href="' . url('medical-reimbursement/details') . '/' . $data->medical_reimbursement_id . '" id="pending-status-details" class="btn btn-outline-info round pending-status-details">Details</a>
                ';
            })
            ->rawColumns(['action'])
            ->make(true);
    }

    public function export()
    {
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        $employeeId = Auth::user()->employee->employee_id;
        $medicalReimbursements = MedicalReimbursement::join('employees', 'medical_reimbursements.employee_id', '=', 'employees.employee_id')->join('branches', 'employees.branch_id', '=', 'branches.branch_id')->join('department', 'employees.department_id', '=', 'department.department_id')->join('job_positions', 'employees.job_position_id', '=', 'job_positions.job_position_id')->join('status_codes', 'medical_reimbursements.status', '=', 'status_codes.code')->where('medical_reimbursements.employee_id', '=', $employeeId)->where('medical_reimbursements.status', '!=', 'rpd')->get();

        $sheet->setCellValue('A1', 'Employee ID')
            ->setCellValue('B1', 'Employee Name')
            ->setCellValue('C1', 'Entity')
            ->setCellValue('D1', 'SBU')
            ->setCellValue('E1', 'Job Position')
            ->setCellValue('F1', 'Reimbursement Type')
            ->setCellValue('G1', 'Outpatient Type')
            ->setCellValue('H1', 'Total Expenses')
            ->setCellValue('I1', 'Total Reimburse');

        $i = 2;
        foreach ($medicalReimbursements as $medicalReimbursement) {
            if ($medicalReimbursement->category == 0) {
                $reimbursementType = 'Inpatient';
            } else {
                $reimbursementType = 'Outpatient';
            }

            if ($medicalReimbursement->outpatient_type == 0) {
                $outpatientType = 'Laboratory Examinations';
            } else if ($medicalReimbursement->outpatient_type == 1) {
                $outpatientType = 'Doctor Consultation';
            } else {
                $outpatientType = 'Glasses Replacement';
            }

            if ($medicalReimbursement->total_reimburse == null) {
                $medicalReimbursement->total_reimburse = '-';
            }

            $sheet->setCellValue('A' . $i, $medicalReimbursement->nip)
                ->setCellValue('B' . $i, $medicalReimbursement->first_name . ' ' . $medicalReimbursement->last_name)
                ->setCellValue('C' . $i, $medicalReimbursement->branch_name)
                ->setCellValue('D' . $i, $medicalReimbursement->department_name)
                ->setCellValue('E' . $i, $medicalReimbursement->job_position)
                ->setCellValue('F' . $i, $reimbursementType)
                ->setCellValue('G' . $i, $outpatientType)
                ->setCellValue('H' . $i, $medicalReimbursement->amount)
                ->setCellValue('I' . $i, $medicalReimbursement->total_reimburse);

            $i++;
        }

        $writer = new Xlsx($spreadsheet);

        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment; filename="' . urlencode('MedicalReimbursement.xlsx') . '"');

        $writer->save('php://output');
    }

    public function cancel($id)
    {
        try {
            $this->medicalReimbursementInterface->cancelMedicalReimbursement($id);

            return response()->json([
                'error' => false,
                'message' => 'Successfully Canceled Medical Reimbursement'
            ]);
        } catch (Exception $e) {
            return response()->json([
                'error' => true,
                'message' => $e->getMessage()
            ]);
        }
    }

    public function details($id)
    {
        $medicalReimbursement = $this->medicalReimbursementInterface->getAllMedicalReimbursements()->where('medical_reimbursement_id', $id)->first();

        $approvedByManager = $this->medicalReimbursementInterface->managerApproverName($id);
        $approvedByHumanResources = $this->medicalReimbursementInterface->humanResourcesApproverName($id);
        $approvedByFinance = $this->medicalReimbursementInterface->financeApproverName($id);

        return view('medical-reimbursement.details', compact('medicalReimbursement', 'approvedByManager', 'approvedByHumanResources', 'approvedByFinance'));
    }

    public function download($file_name)
    {
        $file_path = public_path('uploads/medical-reimbursement/' . $file_name);

        return response()->download($file_path);
    }
}
