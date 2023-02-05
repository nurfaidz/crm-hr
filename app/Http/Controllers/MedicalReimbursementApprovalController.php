<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Interfaces\MedicalReimbursementInterface;
use App\Interfaces\EmployeeInterface;
use App\Models\Employee;
use Exception;
use App\Models\MedicalReimbursement;
use App\Models\EmployeeBalance;
use App\Models\Notification;
use App\Models\User;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Yajra\DataTables\Facades\DataTables;

class MedicalReimbursementApprovalController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    private $medicalReimbursementRepository;
    private $employeeRepository;

    public function __construct(
        MedicalReimbursementInterface $medicalReimbursementInterface,
        EmployeeInterface $employeeInterface
    ) {
        $this->medicalReimbursementRepository = $medicalReimbursementInterface;
        $this->employeeRepository             = $employeeInterface;


        // $this->middleware('role_or_permission:Super Admin|medical_reimbursement_approval.manage', [
        //     'only' => [
        //         'managerApproval', 
        //         'detailHu',
        //         'humanResourceApprove', 
        //         'detailHr',
        //         'financeApprove', 
        //         'detailFinance',
        //         'rejectManager', 
        //         'rejectHumanResource',
        //         'rejectFinance'
        //     ]
        // ]);


        $this->middleware('role_or_permission:Super Admin|medical_reimbursement_approval.approve_manager', ['only' => ['managerApproval', 'detailHu']]);
        $this->middleware('role_or_permission:Super Admin|medical_reimbursement_approval.approve_hr', ['only' => ['humanResourceApprove', 'detailHr']]);
        $this->middleware('role_or_permission:Super Admin|medical_reimbursement_approval.approve_finance', ['only' => ['financeApprove', 'detailFinance']]);

        $this->middleware('role_or_permission:Super Admin|medical_reimbursement_approval.reject_manager', ['only' => ['rejectManager']]);
        $this->middleware('role_or_permission:Super Admin|medical_reimbursement_approval.reject_hr', ['only' => ['rejectHumanResource']]);
        $this->middleware('role_or_permission:Super Admin|medical_reimbursement_approval.reject_finance', ['only' => ['rejectFinance']]);
    }

    public function index()
    {
        $user            = Auth::user();
        $user_branch     = $user->employee->branch_id;
        $user_department = $user->employee->department_id;
        $user_employee   = $user->employee->employee_id;

        if ($user->hasPermissionTo('medical_reimbursement_approval.approve_hr')) {
            $medicalReimbursement = $this->medicalReimbursementRepository->getAllMedicalReimbursementsHumanResource($user_branch, $user_department, $user_employee);
        } else if ($user->hasPermissionTo('medical_reimbursement_approval.approve_finance')) {
            $medicalReimbursement = $this->medicalReimbursementRepository->getAllMedicalReimbursementsFinance($user_branch, $user_department, $user_employee);
        } else if ($user->hasPermissionTo('medical_reimbursement_approval.approve_manager')) {
            $medicalReimbursement = $this->medicalReimbursementRepository->getAllMedicalReimbursementsManager($user_branch, $user_department, $user_employee);
        } else {
            $medicalReimbursement = $this->medicalReimbursementRepository->getAllMedicalReimbursementsManager($user_branch, $user_department, $user_employee);
        }

        return DataTables::of($medicalReimbursement)
            ->addIndexColumn()
            ->addColumn('employee_image', function ($row) {
                return $this->employeeRepository->getEmployeePicture($row->user_id);
            })
            ->addColumn('transaction_date', function ($row) {
                return  date('d/m/Y', strtotime($row->reimbursement_date));
            })
            ->addColumn('action', function ($row) {
                $user = Auth::user();
                if ($user->hasPermissionTo('medical_reimbursement_approval.approve_hr')) {
                    return '<a href="approval/medical-reimbursement/' . $row->medical_reimbursement_id . '/details-human-resource" id="pending-status-cancel" class="btn btn-outline-info round pending-status-cancel d-flex justify-content-center align-items-center">Details</a>';
                } else if ($user->hasPermissionTo('medical_reimbursement_approval.approve_finance')) {
                    return '<a href="approval/medical-reimbursement/' . $row->medical_reimbursement_id . '/details-finance" id="pending-status-cancel" class="btn btn-outline-info round pending-status-cancel d-flex justify-content-center align-items-center">Details</a>';
                } else {
                    return '<a href="approval/medical-reimbursement/' . $row->medical_reimbursement_id . '/details-higher-up" id="pending-status-cancel" class="btn btn-outline-info round pending-status-cancel d-flex justify-content-center align-items-center">Details</a>';
                }
            })
            ->addColumn('status', function ($row) {
                return '<div class="badge badge-pill badge-light-warning">Pending</div>';
            })
            ->addColumn('category', function ($row) {
                switch ($row->category) {
                    case 0:
                        return 'Inpatient';
                        break;
                    case 1:
                        return 'Outpatient';
                        break;
                    default:
                        return 'Other';
                        break;
                }
            })
            ->rawColumns(['status', 'action'])
            ->toJson(true);
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
        //
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

    public function detailHu($id)
    {
        $medicalReimbursement = $this->medicalReimbursementRepository->getAllMedicalReimbursements()
            ->where('medical_reimbursement_id', $id)
            ->first();

        $employeeId = $medicalReimbursement['employee_id'];
        $year       = explode('-', $medicalReimbursement['reimbursement_date']);
        $balance    = $this->medicalReimbursementRepository->countReimbursementBalance($employeeId, $year[0]);
        $expenses   = $this->medicalReimbursementRepository->countTotalExpenses($employeeId, $year[0]);

        $approvedByManager        = $this->medicalReimbursementRepository->managerApproverName($id);
        $approvedByHumanResources = $this->medicalReimbursementRepository->humanResourcesApproverName($id);
        $approvedByFinance        = $this->medicalReimbursementRepository->financeApproverName($id);

        return view('approval.medical-reimbursement-details-hu', compact(
            'medicalReimbursement',
            'balance',
            'expenses',
            'approvedByManager',
            'approvedByHumanResources',
            'approvedByFinance'
        ));
    }

    public function detailHr($id)
    {
        $medicalReimbursement = $this->medicalReimbursementRepository->getAllMedicalReimbursements()
            ->where('medical_reimbursement_id', $id)
            ->first();

        $employeeId = $medicalReimbursement['employee_id'];
        $year       = explode('-', $medicalReimbursement['reimbursement_date']);
        $balance    = $this->medicalReimbursementRepository->countReimbursementBalance($employeeId, $year[0]);
        $expenses   = $this->medicalReimbursementRepository->countTotalExpenses($employeeId, $year[0]);

        $approvedByManager        = $this->medicalReimbursementRepository->managerApproverName($id);
        $approvedByHumanResources = $this->medicalReimbursementRepository->humanResourcesApproverName($id);
        $approvedByFinance        = $this->medicalReimbursementRepository->financeApproverName($id);

        return view('approval.medical-reimbursement-details-hr', compact(
            'medicalReimbursement',
            'balance',
            'expenses',
            'approvedByManager',
            'approvedByHumanResources',
            'approvedByFinance'
        ));
    }

    public function detailFinance($id)
    {
        $medicalReimbursement = $this->medicalReimbursementRepository->getAllMedicalReimbursements()
            ->where('medical_reimbursement_id', $id)
            ->first();

        $employeeId = $medicalReimbursement['employee_id'];
        $year       = explode('-', $medicalReimbursement['reimbursement_date']);
        $balance    = $this->medicalReimbursementRepository->countReimbursementBalance($employeeId, $year[0]);
        $expenses   = $this->medicalReimbursementRepository->countTotalExpenses($employeeId, $year[0]);

        $approvedByManager        = $this->medicalReimbursementRepository->managerApproverName($id);
        $approvedByHumanResources = $this->medicalReimbursementRepository->humanResourcesApproverName($id);
        $approvedByFinance        = $this->medicalReimbursementRepository->financeApproverName($id);

        return view('approval.medical-reimbursement-details-finance', compact(
            'medicalReimbursement',
            'balance',
            'expenses',
            'approvedByManager',
            'approvedByHumanResources',
            'approvedByFinance'
        ));
    }

    public function detail($id)
    {
        $medicalReimbursement = $this->medicalReimbursementRepository->getAllMedicalReimbursements()
            ->where('medical_reimbursement_id', $id)
            ->first();

        $employeeId = $medicalReimbursement['employee_id'];
        $year       = explode('-', $medicalReimbursement['reimbursement_date']);
        $balance    = $this->medicalReimbursementRepository->countReimbursementBalance($employeeId, $year[0]);
        $expenses   = $this->medicalReimbursementRepository->countTotalExpenses($employeeId, $year[0]);

        $approvedByManager        = $this->medicalReimbursementRepository->managerApproverName($id);
        $approvedByHumanResources = $this->medicalReimbursementRepository->humanResourcesApproverName($id);
        $approvedByFinance        = $this->medicalReimbursementRepository->financeApproverName($id);

        return view('approval.medical-reimbursement-details', compact(
            'medicalReimbursement',
            'balance',
            'expenses',
            'approvedByManager',
            'approvedByHumanResources',
            'approvedByFinance'
        ));
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


    public function rejectManager(Request $request, $id)
    {
        $employeeId   = Auth::user()->employee->employee_id;
        $requestOnly  = $request->only('reason');
        $rules        = ['reason' => 'required'];

        $validatedData = Validator::make($requestOnly, $rules);
        if ($validatedData->fails()) {
            return response()->json([
                'error' => $validatedData->errors()->toArray()
            ]);
        }

        try {
            $this->medicalReimbursementRepository->reimbursementUpdate($id, [
                'status'        => 'rmr',
                'reject_by'     => $employeeId,
                'reject_date'   => Carbon::now(),
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

    public function rejectHumanResource(Request $request, $id)
    {
        $employeeId   = Auth::user()->employee->employee_id;
        $requestOnly  = $request->only('reason');
        $rules        = ['reason' => 'required'];

        $validatedData = Validator::make($requestOnly, $rules);
        if ($validatedData->fails()) {
            return response()->json([
                'error' => $validatedData->errors()->toArray()
            ]);
        }

        try {
            $this->medicalReimbursementRepository->reimbursementUpdate($id, [
                'status'        => 'rhr',
                'reject_by'     => $employeeId,
                'reject_date'   => Carbon::now(),
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

    public function rejectFinance(Request $request, $id)
    {
        $employeeId   = Auth::user()->employee->employee_id;
        $requestOnly  = $request->only('reason');
        $rules        = ['reason' => 'required'];

        $validatedData = Validator::make($requestOnly, $rules);
        if ($validatedData->fails()) {
            return response()->json([
                'error' => $validatedData->errors()->toArray()
            ]);
        }

        try {
            $this->medicalReimbursementRepository->reimbursementUpdate($id, [
                'status'        => 'rfr',
                'reject_by'     => $employeeId,
                'reject_date'   => Carbon::now(),
                'reject_reason' => $requestOnly
            ]);
            $medicalReimbursement = $this->reimbursementInterface->getMedicalReimbursementByid($id);
            $employeeNotif = Employee::where('employee_id', $medicalReimbursement->employee_id)->first();
            $useNotif = User::where('id', $employeeNotif->user_id)->first();

            Http::withHeaders([
                'Content-Type' => 'application/json',
                'Authorization' => 'Basic ' . env('ONESIGNAL_REST_API_KEY')
            ])->post(env('ONESIGNAL_URL'), [
                'app_id' => env('ONESIGNAL_APP_ID'),
                'android_channel_id' => env('ONESIGNAL_ANDROID_CHANNEL_ID'),
                'small_icon' => "ic_stat_onesignal_default",
                'include_external_user_ids' => [$useNotif->email],
                'channel_for_external_user_ids' => "push",
                'data' => ["detail_id" => $medicalReimbursement->medical_reimbursement_id],
                'headings' => ["en" => "Medical Reimbursement"],
                'contents' => ["en" => "Your request for reimbursement has been rejected"]
            ]);

            $createNotifications = [
                "employee_id" => $employeeNotif->employee_id,
                "title" => "Medical Reimbursement",
                "notif_type" => 'Reimbursement',
                "notif_status" => 'Reject',
                "message" => "Your request for reimbursement has been rejected",
                "send_time" => Carbon::now(),
                "detail_id" => $medicalReimbursement->medical_reimbursement_id,
                "is_approval" => false,
            ];

            Notification::create($createNotifications);

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

    public function managerApprove($id)
    {
        try {
            $this->medicalReimbursementRepository->reimbursementUpdate($id, [
                'status'               => 'rmy',
                'approve_by_manager'   => Auth::user()->employee->employee_id,
                'approve_manager_date' => Carbon::now()
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

    public function humanResourceApprove($id)
    {
        try {
            $this->medicalReimbursementRepository->reimbursementUpdate($id, [
                'status'                       => 'rhy',
                'approve_by_human_resources'   => Auth::user()->employee->employee_id,
                'approve_human_resources_date' => Carbon::now()
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

    public function financeApprove(Request $request, $id)
    {
        $requestOnly  = $request->only('payment_evidence');
        $rules        = ['payment_evidence' => 'required'];

        $validatedData = Validator::make($requestOnly, $rules);
        if ($validatedData->fails()) {
            return response()->json([
                'error' => $validatedData->errors()->toArray()
            ]);
        }

        try {
            $attachment = [];
            $medical = MedicalReimbursement::where('medical_reimbursement_id', $id)->first();
            $balance = EmployeeBalance::where('employee_id', $medical->employee_id)->first();

            if ($request->hasFile('payment_evidence')) {
                if ($files = $request->file('payment_evidence')) {
                    foreach ($files as $file) {
                        $name = Str::random('20') . '-' . $file->getClientOriginalName();
                        $file->move('uploads/medical-reimbursement-approval/', $name);
                        $attachment[] = $name;
                    }
                }
            }

            $this->medicalReimbursementRepository->reimbursementUpdate($id, [
                'status'               => 'rfy',
                'approve_by_finance'   => Auth::user()->employee->employee_id,
                'approve_finance_date' => Carbon::now(),
                'payment_evidence'     => 'medical-reimbursement-approval/' . implode('|', $attachment),
            ]);

            $balance->update([
                'remaining_balance' => $balance->total_balance - $medical->amount,
                'used_balance' => $medical->amount,
            ]);
            $medicalReimbursement = $this->reimbursementInterface->getMedicalReimbursementByid($id);
            $employeeNotif = Employee::where('employee_id', $medicalReimbursement->employee_id)->first();
            $useNotif = User::where('id', $employeeNotif->user_id)->first();

            Http::withHeaders([
                'Content-Type' => 'application/json',
                'Authorization' => 'Basic ' . env('ONESIGNAL_REST_API_KEY')
            ])->post(env('ONESIGNAL_URL'), [
                'app_id' => env('ONESIGNAL_APP_ID'),
                'android_channel_id' => env('ONESIGNAL_ANDROID_CHANNEL_ID'),
                'small_icon' => "ic_stat_onesignal_default",
                'include_external_user_ids' => [$useNotif->email],
                'channel_for_external_user_ids' => "push",
                'data' => ["detail_id" => $medicalReimbursement->medical_reimbursement_id],
                'headings' => ["en" => "Medical Reimbursement"],
                'contents' => ["en" => "Your request for reimbursement has been approved"]
            ]);

            $createNotifications = [
                "employee_id" => $employeeNotif->employee_id,
                "title" => "Medical Reimbursement",
                "notif_type" => 'Reimbursement',
                "notif_status" => 'Approve',
                "message" => "Your request for reimbursement has been approved",
                "send_time" => Carbon::now(),
                "detail_id" => $medicalReimbursement->medical_reimbursement_id,
                "is_approval" => false,
            ];

            Notification::create($createNotifications);


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

    public function reimbursementCancel($id)
    {
        try {
            $user = Auth::user();
            $this->medicalReimbursementRepository->reimbursementUpdate($id, [
                'status'      => 'can',
                'cancel_by'   => $user->employee->employee_id,
                'cancel_date' => Carbon::now()
            ]);

            return response()->json([
                'error'   => false,
                'message' => 'Successfully Cancelled'
            ]);
        } catch (Exception $e) {
            return response()->json([
                'error'   => true,
                'message' => $e->getMessage()
            ]);
        }
    }
}
