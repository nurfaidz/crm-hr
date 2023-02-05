<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Reimbursement;
use App\Models\MedicalReimbursement;
use App\Models\Employee;
use App\Models\StatusCode;
use App\Http\Requests\API\MedicalReimbursement\StoreMedicalReimbursement;
use App\Interfaces\MedicalReimbursementInterface;
use App\Interfaces\EmployeeInterface;
use Carbon\Carbon;
use Exception;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Helpers\ResponseFormatter;
use App\Http\Requests\API\MedicalReimbursement\ApproveMedicalReimbursement;
use App\Http\Requests\API\MedicalReimbursement\RejectMedicalReimbursement;
use App\Models\Department;
use App\Models\Notification;
use App\Models\Role;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class ReimbursementController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    private MedicalReimbursementInterface $reimbursementInterface;
    private EmployeeInterface $employeeInterface;

    public function __construct(MedicalReimbursementInterface $reimbursementInterface, EmployeeInterface $employeeInterface)
    {
        $this->reimbursementInterface = $reimbursementInterface;
        $this->employeeInterface = $employeeInterface;
    }

    /**
     * @OA\Get(
     *     path="/api/reimbursements",
     *     tags={"Reimbursement"},
     *     summary="Getting Reimbursement",
     *     description="Reimbursement GET Request",
     *     operationId="getReimbursement",
     *     security={{"bearerAuth":{}}}, 
     *     @OA\Response(
     *         response="200",
     *         description="Success"
     *     ),
     *     @OA\Response(
     *         response="500",
     *         description="Something went wrong"
     *     )
     * )
     */

    public function index()
    {
        try {
            $ReimbursementData = $this->reimbursementInterface->getAllReimbursementsStat();
            $ReimbursementResponse = [];

            foreach ($ReimbursementData as $reimbursement_data) {
                $explode = explode('|', $reimbursement_data->images);

                $images = [];
                foreach ($explode as $item) {
                    $images[] = url("uploads/$item");
                }

                $ReimbursementResponse[] =  [
                    'reimbursement_id' => $reimbursement_data->reimbursement_id,
                    'employee_name'    => $reimbursement_data->first_name . ' ' . $reimbursement_data->last_name,
                    'start'            => $reimbursement_data->start,
                    'end'              => $reimbursement_data->end,
                    'images'           => $images,
                    'note'             => $reimbursement_data->note,
                    'name'             => $reimbursement_data->name,
                    'type'             => $reimbursement_data->type,
                    'status'           => $reimbursement_data->short,
                    'approve_by'       => $reimbursement_data->approve_by,
                    'approve_time'     => $reimbursement_data->approve_time,
                ];
            }
            return ResponseFormatter::success($ReimbursementResponse, 'Success', 200);
        } catch (Exception $e) {
            $statuscode = 500;
            if ($e->getCode()) $statuscode = $e->getCode();

            $response = [
                'errors' => $e->getMessage(),
            ];

            return ResponseFormatter::error($response, 'Something went wrong', $statuscode);
        }
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

    /**
     * @OA\Post(
     *      path="/api/reimbursements",
     *      operationId="addReimbursement",
     *      tags={"Reimbursement"},
     *      summary="Adding Reimbursement",
     *      description="Reimbursement POST Request",
     *      security={{"bearerAuth":{}}},
     *      @OA\RequestBody(
     *          @OA\MediaType(
     *              mediaType="multipart/form-data",
     *              @OA\Schema(
     *                  required={"employee_id", "start", "end", "images", "note", "name", "type"},
     *                  type="object", 
     *                  @OA\Property(
     *                      property="employee_id",
     *                      type="number",
     *                  ),
     *                  @OA\Property(
     *                      property="start",
     *                      type="date",
     *                  ),
     *                  @OA\Property(
     *                      property="end",
     *                      type="date",
     *                  ),
     *                  @OA\Property(
     *                      property="images",
     *                      type="file",
     *                  ),
     *                  @OA\Property(
     *                      property="note",
     *                      type="string",
     *                  ),
     *                  @OA\Property(
     *                      property="name",
     *                      type="string",
     *                  ),
     *                  @OA\Property(
     *                      property="type",
     *                      type="string",
     *                  ),
     *              ),
     *          )
     *      ),
     *      @OA\Response(
     *          response=201,
     *          description="Success",
     *      ),
     *      @OA\Response(
     *          response=400,
     *          description="Bad Request"
     *      ),
     *      @OA\Response(
     *         response="500",
     *         description="Something went wrong"
     *      )
     * )
     */
    public function store(Request $request)
    {
        try {
            $requestData = $request->all();
            $images = [];


            $validate = Validator::make($requestData, [
                'employee_id' => 'required|numeric',
                'start'       => 'required|date',
                'end'         => 'required|date|after:start',
                'images'      => 'required',
                'images.*'    => 'file|max:10240|mimes:jpg,jpeg,png,pdf',
                'note'        => 'required',
                'name'        => 'required',
                'type'        => 'required'
            ]);

            if ($validate->fails()) {
                $response = [
                    'errors' => $validate->errors()
                ];

                return ResponseFormatter::error($response, 'Bad Request', 400);
            } else {
                if ($request->hasFile('images')) {
                    if ($files = $request->file('images')) {
                        foreach ($files as $file) {
                            $images[] = $file->store('reimbursements');
                        }
                    }
                }

                $requestData['images'] = implode("|", $images);
                $requestData['status'] = 'rpd';

                $return = $this->reimbursementInterface->createReimbursement($requestData);
                return ResponseFormatter::success($return, 'Success', 201);
            }
        } catch (Exception $e) {
            $statuscode = 500;
            if ($e->getCode()) $statuscode = $e->getCode();

            $response = [
                'errors' => $e->getMessage(),
            ];

            return ResponseFormatter::error($response, 'Something went wrong', $statuscode);
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


    public function getAllMedicalReimbursementAppr()
    {
        try {
            $user = Auth::user();
            $employee = Employee::where('user_id', $user->id)->join('department', 'employees.department_id', '=', 'department.department_id')->firstOrFail();
            $env = env('APP_URL');

            $medicalReimbursementList = $this->reimbursementInterface->getAllMedicalReimbursementAppr($employee->branch_id, $employee->employee_id)
                ->where('department.code', '=', $employee->code)
                ->get([
                    'medical_reimbursements.medical_reimbursement_id',
                    'employees.first_name',
                    'employees.last_name',
                    'branches.branch_name',
                    'department.code',
                    'medical_reimbursements.reimbursement_date',
                    'medical_reimbursements.category',
                    'medical_reimbursements.expenses',
                    'medical_reimbursements.total_reimburse',
                    'medical_reimbursements.notes',
                    'medical_reimbursements.attachment as file_path',
                    'medical_reimbursements.status',
                    'status_codes.short',
                ]);

            if (count($medicalReimbursementList) <= 0) return ResponseFormatter::success([], 'No data available at the moment', 204);

            foreach ($medicalReimbursementList as $list) {

                $file_path = [];

                if ($list->file_path !== null) {
                    $exp = explode('/', $list->file_path);

                    $obj = (object)[];
                    $obj->link = $list->file_path;
                    $obj->filename = $exp[count($exp) - 1];

                    array_push($file_path, $obj);
                }
                $list->file_path = $file_path;
            }

            return ResponseFormatter::success($medicalReimbursementList, 'Get Medical Reimbursement Success');
        } catch (Exception $error) {
            return ResponseFormatter::error([
                'error' => $error->getMessage()
            ], 'Something went wrong', 500);
        }
    }

    public function getMedicalReimbursementId($Id)
    {
        try {
            $user = Auth::user();
            $employee = Employee::where('user_id', $user->id)->firstOrFail();
            $medicalReimbursement = $this->reimbursementInterface->getMedicalReimbursementByid($Id, $employee->employee_id);
            $env = env('APP_URL');

            $medicalReimbursementAppr = (object)[];
            $hr = (object)[];
            $manager = (object)[];
            $finance = (object)[];

            if ($medicalReimbursement === null) {
                $response = [
                    'reimbursement_date' => 0,
                    'category' => 0,
                    'amount' => null,
                    'notes' => null,
                    'status' => 0
                ];

                return ResponseFormatter::error($response, 'No data available at the moment', 204);
            }

            if (!is_null($medicalReimbursement->reject_by)) {
                $medicalReimbursementAppr = $this->employeeInterface->getEmployeeById($medicalReimbursement->reject_by)
                    ->join('job_positions', 'employees.job_position_id', '=', 'job_positions.job_position_id')
                    ->first([
                        'employees.user_id',
                        'employees.employee_id',
                        'employees.first_name',
                        'employees.last_name',
                        'employees.image',
                        'job_positions.job_position',
                    ]);

                $medicalReimbursementAppr->image = $this->employeeInterface->getEmployeePicture($medicalReimbursementAppr->user_id);
                Carbon::setLocale('en');
                $medicalReimbursementAppr->action_date = $medicalReimbursement->reject_date;
                $medicalReimbursementAppr->dateDiff = Carbon::createFromFormat('Y-m-d',  $medicalReimbursement->reject_date)->diffForHumans(Carbon::now()->toDateString());
                $medicalReimbursementAppr->reason = $medicalReimbursement->reject_reason;
            }

            if (!is_null($medicalReimbursement->approve_by_human_resource)) {
                $hr = Employee::where('employee_id', $medicalReimbursement->approve_by_human_resources)
                    ->join('job_positions', 'employees.job_position_id', '=', 'job_positions.job_position_id')
                    ->first([
                        'employees.user_id',
                        'employees.employee_id',
                        'employees.first_name',
                        'employees.last_name',
                        'employees.image',
                        'job_positions.job_position',
                    ]);
                $hr->image = $this->employeeInterface->getEmployeePicture($hr->user_id);
                Carbon::setLocale('en');
                $hr->action_date = $medicalReimbursement->approve_human_resources_date;
                $hr->dateDiff = Carbon::createFromFormat('Y-m-d',  $medicalReimbursement->approve_human_resources_date)->diffForHumans(Carbon::now()->toDateString());
            }

            if (!is_null($medicalReimbursement->approve_by_manager)) {
                $manager = Employee::where('employee_id', $medicalReimbursement->approve_by_manager)
                    ->join('job_positions', 'employees.job_position_id', '=', 'job_positions.job_position_id')
                    ->first([
                        'employees.user_id',
                        'employees.employee_id',
                        'employees.first_name',
                        'employees.last_name',
                        'employees.image',
                        'job_positions.job_position',
                    ]);
                $manager->image = $this->employeeInterface->getEmployeePicture($manager->user_id);
                Carbon::setLocale('en');
                $manager->action_date = $medicalReimbursement->approve_manager_date;
                $manager->dateDiff = Carbon::createFromFormat('Y-m-d',  $medicalReimbursement->approve_manager_date)->diffForHumans(Carbon::now()->toDateString());
            }

            if (!is_null($medicalReimbursement->approve_by_finance)) {
                $finance = Employee::where('employee_id', $medicalReimbursement->approve_by_finance)
                    ->join('job_positions', 'employees.job_position_id', '=', 'job_positions.job_position_id')
                    ->first([
                        'employees.user_id',
                        'employees.employee_id',
                        'employees.first_name',
                        'employees.last_name',
                        'employees.image',
                        'job_positions.job_position',
                    ]);
                $finance->image = $this->employeeInterface->getEmployeePicture($finance->user_id);
                Carbon::setLocale('en');
                $finance->action_date = $medicalReimbursement->approve_finance_date;
                $finance->dateDiff = Carbon::createFromFormat('Y-m-d', $medicalReimbursement->approve_finance_date)->diffForHumans(Carbon::now()->toDateString());
            }

            if ($medicalReimbursement->attachment !== null) {

                if ($medicalReimbursement->attachment !== null) {
                    $ext = explode('/', $medicalReimbursement->attachment);
                    $exp = explode('|', $ext[1]);
                    
                    $obj = [];
                    foreach ($exp as $oh) {
                        $obj[] = [
                            'link' => asset('uploads/' . $ext[0] . '/' . $oh),
                            'filename' => $oh
                        ];
                    }
                }
                $medicalReimbursement->attachment = $obj;
            }

            $medicalReimbursement->image = $this->employeeInterface->getEmployeePicture($employee->user_id);

            return ResponseFormatter::success([
                'requester' => [
                    'user_id' => $medicalReimbursement->user_id,
                    'employee_id' => $medicalReimbursement->employee_id,
                    'first_name' => $medicalReimbursement->first_name,
                    'last_name' => $medicalReimbursement->last_name,
                    'image' => $medicalReimbursement->image,
                    'department' => $medicalReimbursement->department_name,
                    'job_position' => $medicalReimbursement->job_position
                ],
                'medical_reimbursement' => [
                    'reimbursement_date' => $medicalReimbursement->reimbursement_date,
                    'category' => $medicalReimbursement->category,
                    'notes' => $medicalReimbursement->notes,
                    'amount' => $medicalReimbursement->amount,
                    'status' => $medicalReimbursement->short,
                    'attachment' => $medicalReimbursement->attachment
                ],
                'approver' => [
                    'manager' => $manager,
                    'human_resource' => $hr,
                    'finance' => $finance,
                    'reject_by' => $medicalReimbursementAppr,
                ]
            ], 'Get Medical Reimbursement Detail Success');
        } catch (Exception $error) {
            return ResponseFormatter::error([
                'error' => $error->getMessage(),
                'line' => $error->getLine()
            ], 'Something went wrong', 500);
        }
    }

    public function cancelReimbursement($id)
    {
        try {
            $user = Auth::user();
            $employee = Employee::where('user_id', $user->id)->firstOrFail();

            $medicalReimbursement = $this->reimbursementInterface->getMedicalReimbursementByid($id);
            if ($medicalReimbursement === null) return ResponseFormatter::error([], 'Data was not found', 204);

            $reimbursement = $this->reimbursementInterface->cancelMedicalReimbursement($id);

            return ResponseFormatter::success([
                "id" => $id
            ], 'Reimbursement has been canceled');
        } catch (Exception $error) {
            return ResponseFormatter::error(['error' => $error->getMessage()], 'Something went wrong', 500);
        }
    }

    public function approveReimbursement(ApproveMedicalReimbursement $request, $id)
    {
        try {
            $user = Auth::user();
            $role = $user->getRoleNames();
            $env = env('APP_URL');

            if (!$user->hasAnyPermission([
                "medical_reimbursement_approval.approve_manager",
                "medical_reimbursement_approval.approve_hr",
                "medical_reimbursement_approval.approve_finance"
            ])) return ResponseFormatter::error([
                'modal-key' => 'authorization-error',
            ], "You account doesn't gain access to approve reimbursement.", 400);

            $medicalReimbursement = $this->reimbursementInterface->getMedicalReimbursementByid($id);
            if ($medicalReimbursement === null) return ResponseFormatter::error([], 'Data was not found', 204);

            if ($medicalReimbursement->approve_by_manager === null) {
                if ($user->can("medical_reimbursement_approval.approve_manager")) {
                    $data = [
                        'status'               => 'rmy',
                        'approve_by_manager'   => Auth::user()->employee->employee_id,
                        'approve_manager_date' => Carbon::now()
                    ];
                    $this->reimbursementInterface->reimbursementUpdate($id, $data);
                    return ResponseFormatter::success($data, 'Reimbursement has been approved by Manager');
                }
                return ResponseFormatter::error([
                    'modal-key' => 'manager-first-approved',
                ], 'Reimbursement should have to approved first by Manager');
            } else if ($medicalReimbursement->approve_by_human_resources === null) {
                if ($user->can("medical_reimbursement_approval.approve_hr")) {
                    $data = [
                        'status'                       => 'rhy',
                        'approve_by_human_resources'   => Auth::user()->employee->employee_id,
                        'approve_human_resources_date' => Carbon::now()
                    ];
                    $this->reimbursementInterface->reimbursementUpdate($id, $data);
                    return ResponseFormatter::success($data, 'Reimbursement has been approved by HR');
                }
                return ResponseFormatter::error([
                    'modal-key' => 'hr-second-approved',
                ], 'Reimbursement should have to approved by HR');
            } else if ($medicalReimbursement->approve_by_finance == null) {
                if ($user->can("medical_reimbursement_approval.approve_finance")) {
                    if (!$request->hasFile('payment_evidence')) {
                        $response = [
                            'modal-key' => 'finance-image-error',
                        ];
                        return ResponseFormatter::error($response, 'File evidence doesn\'t uploaded', 400);
                    }

                    $paymentEvidence = array();
                    $file_path = [];

                    $files = $request->file('payment_evidence');
                    foreach ($files as $file) {
                        $extension = $file->getClientOriginalExtension();
                        $check = in_array($extension, ['pdf', 'jpg', 'png']);

                        if ($check) {
                            $name = Str::random("20") . "-" . $file->getClientOriginalName();
                            $file->move("uploads/medical-reimbursement-approval/", $name);
                            $paymentEvidence[] = $name;

                            $tempStr = "${env}/uploads/medical-reimbursement-approval/{$name}";

                            $obj = (object)[];
                            $obj->link = $tempStr;
                            $obj->filename = $name;

                            array_push($file_path, $obj);
                        } else {
                            $response = [
                                'modal-key' => 'finance-file-format-error',
                            ];
                            return ResponseFormatter::error($response, 'Invalid File Format', 422);
                        }
                    }

                    $paymentEvidence = "medical-reimbursement-approval/" . implode("|", $paymentEvidence);

                    $data = [
                        'status'                       => 'rfy',
                        'approve_by_finance'           => Auth::user()->employee->employee_id,
                        'approve_finance_date'         => Carbon::now(),
                        'payment_evidence'             => $paymentEvidence
                    ];

                    $this->reimbursementInterface->reimbursementUpdate($id, $data);

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


                    $data['payment_evidence'] = $file_path;

                    return ResponseFormatter::success($data, 'Reimbursement has been approved by Finance');
                }
                return ResponseFormatter::error([
                    'modal-key' => 'finance-approve-error',
                ], 'You account doesn\t meet spesification to approve finance');
            }
            return ResponseFormatter::error([
                'modal-key' => 'reimbursement-error',
            ], 'Cannot approve reimbursement anymore');
        } catch (Exception $error) {
            return ResponseFormatter::error(['error' => $error->getMessage()], 'Something went wrong', 500);
        }
    }

    public function rejectReimbursement(RejectMedicalReimbursement $request, $id)
    {
        try {
            $user = Auth::user();
            $employee = Employee::where('user_id', $user->id)->firstOrFail();


            if (!$user->hasAnyPermission([
                "medical_reimbursement_approval.reject_manager",
                "medical_reimbursement_approval.reject_hr",
                "medical_reimbursement_approval.reject_finance"
            ])) return ResponseFormatter::error("", "You account doesn't gain access to reject reimbursement.", 401);

            $medicalReimbursement = $this->reimbursementInterface->getMedicalReimbursementByid($id);
            if ($medicalReimbursement === null) return ResponseFormatter::error([], 'Data was not found', 204);

            $status = $user->can("medical_reimbursement_approval.reject_manager")
                ? "rmr"
                : ($user->can("medical_reimbursement_approval.reject_hr")
                    ? 'fhr'
                    : 'rfr'
                );

            $this->reimbursementInterface->reimbursementUpdate($id, [
                'status'        => $status,
                'reject_by'     => Auth::user()->employee->employee_id,
                'reject_date'   => Carbon::now(),
                'reject_reason' => $request->reject_reason
            ]);

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

            return ResponseFormatter::success([
                "id" => $id,
                "status" => $status
            ], 'Reimbursement has been rejected');
        } catch (Exception $error) {
            return ResponseFormatter::error(['error' => $error->getMessage()], 'Something went wrong', 500);
        }
    }

    public function getAllReimburse($year)
    {
        $user = Auth::user();
        $employee = Employee::where('user_id', $user->id)->firstOrFail();
        $env = env('APP_URL');

        $medicalReimbursements = $this->reimbursementInterface->getReimburseHistory($employee->employee_id, $year)->get();

        if (count($medicalReimbursements) == 0) {
            return ResponseFormatter::error($medicalReimbursements, 'No data available at the momment', 204);
        }

        $balance = $this->reimbursementInterface->calculateEmployeeReimburseBalance($employee->employee_id, $year);

        $ba = $this->reimbursementInterface->balanceApproved($employee->employee_id, $year);
        $br = $this->reimbursementInterface->balanceRejected($employee->employee_id, $year);
        $bp = $this->reimbursementInterface->balancePending($employee->employee_id, $year);

        foreach ($medicalReimbursements as $medicalReimbursement) {
            $statusCode = StatusCode::where('code', $medicalReimbursement->status)->first();

            $file_path = [];
            if ($medicalReimbursement->attachment !== null) {
                $charPos = strpos($medicalReimbursement->attachment, '/') + 1;
                $temp = substr($medicalReimbursement->attachment, $charPos);
                $exp = explode('|', $temp);

                foreach ($exp as $e) {
                    $tempStr = "${env}/uploads/medical-reimbursement/{$e}";

                    $obj = (object)[];
                    $obj->link = $tempStr;
                    $obj->filename = $e;

                    array_push($file_path, $obj);

                    unset($tempStr);
                }
            };

            $reimburseHistory[] =
                [
                    'medical_reimbursement_id' => $medicalReimbursement->medical_reimbursement_id,
                    'employee_id' => $medicalReimbursement->employee_id,
                    'first_name' => $medicalReimbursement->first_name,
                    'last_name' => $medicalReimbursement->last_name,
                    'reimbursement_date' => $medicalReimbursement->reimbursement_date,
                    'category' => ($medicalReimbursement->category == 0) ? "Inpatient" : "Outpatient",
                    'total_amount' => $medicalReimbursement->amount,
                    'total_reimbursement' => $medicalReimbursement->total_reimburse,
                    'notes' => $medicalReimbursement->notes,
                    'status' => $medicalReimbursement->short,
                    'file_path' => $file_path
                ];
        }

        $data = [
            'reimburse' => $year,
            'statistics' => [
                'balance' => $balance,
                'pending' => $bp,
                'rejected' => $br,
                'approved' => $ba
            ],
            'reimburse_history' => $reimburseHistory
        ];

        return ResponseFormatter::success($data, 'Get Reimbursement Success');
    }

    public function requestReimburse(StoreMedicalReimbursement $request)
    {
        try {
            $timeSubmitted = Carbon::now();

            $user = Auth::user();
            $employee = Employee::where('user_id', $user->id)->first();
            $manager_id = Department::where('department_id', $employee->department_id)->first()->manager;
            $employeeManager = Employee::where('employee_id', $manager_id)->first();
            $userManager = User::find($employeeManager->user_id);
            // ->where('user_id', $user->id)->firstOrFail();
            $env = env('APP_URL');


            if (!$request->hasFile('attachment')) {
                return ResponseFormatter::error([], 'File attachments doesn\'t uploaded', 400);
            }

            $balance = $this->reimbursementInterface->calculateEmployeeReimburseBalance($employee->employee_id, date('Y'));

            if ($request->amount > $balance) return ResponseFormatter::error([
                'modal-key' => 'low-employee-balance',
                'balance' => $balance,
                'request-amount' => (int)$request->amount
            ], 'Employee Has Low Balance', 400);

            $attachment = array();
            $file_path = [];

            $files = $request->file('attachment');
            foreach ($files as $file) {
                $extension = $file->getClientOriginalExtension();
                $check = in_array(strtolower($extension), ['pdf', 'jpg', 'png']);
                if ($check) {
                    $name = Str::random("20") . "-" . $file->getClientOriginalName();
                    $file->move("uploads/medical-reimbursement/", $name);
                    $attachment[] = $name;

                    $tempStr = "${env}/uploads/medical-reimbursement/{$name}";

                    $obj = (object)[];
                    $obj->link = $tempStr;
                    $obj->filename = $name;

                    array_push($file_path, $obj);
                } else {
                    return ResponseFormatter::error([], 'Invalid File Format', 422);
                }
            }

            $attachmentString = "medical-reimbursement/" . implode("|", $attachment);

            $arr = [
                'employee_id' => $employee->employee_id,
                'category' => $request->input('category'),
                'outpatient_type' => $request->input('outpatient_type'),
                'reimbursement_date' => $timeSubmitted,
                'amount' => $request->input('amount'),
                'notes' => $request->input('notes'),
                'status' => 'rpd',
                'attachment' => $attachmentString
            ];

            $medicalReimbursement = MedicalReimbursement::create($arr);

            $arr['attachment'] = $file_path;

            $notification = Http::withHeaders([
                'Content-Type' => 'application/json',
                'Authorization' => 'Basic ' . env('ONESIGNAL_REST_API_KEY')
            ])->post(env('ONESIGNAL_URL'), [
                'app_id' => env('ONESIGNAL_APP_ID'),
                'android_channel_id' => env('ONESIGNAL_ANDROID_CHANNEL_ID'),
                'small_icon' => "ic_stat_onesignal_default",
                'include_external_user_ids' => [$userManager->email],
                'channel_for_external_user_ids' => "push",
                'data' => ["detail_id" => $medicalReimbursement->id],
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
                "detail_id" => $medicalReimbursement->id,
                "is_approval" => true,
            ];

            Notification::create($createNotifications);

            return ResponseFormatter::success($arr, 'Added Reimbursement Success', 201);
        } catch (Exception $e) {
            $response = [
                'errors' => $e->getMessage(),
                'line' => $e->getLine()
            ];
            return ResponseFormatter::error($response, 'Something went wrong', 500);
        }
    }
}
